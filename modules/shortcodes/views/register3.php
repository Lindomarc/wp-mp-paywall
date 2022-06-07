<?php
echo '<script>
    const PUBLIC_KEY = "' . get_option('_pdi_paywall_payment_public_key_test') . '";
</script>';
if (isset($users_can_register) && !!$users_can_register) : ?>
    <div class="pdi-paywall-form">
        <form name="registerform" id="registerform"
              action="<?php echo esc_url(site_url('wp-login.php?action=register', 'login_post')); ?>" method="post"
              novalidate="novalidate">
            <p>
                <label for="user_login"><?php _e('Username'); ?></label>
                <input type="text" name="user_login" id="user_login" class="input"
                       value="<?php echo esc_attr(wp_unslash($user_login)); ?>" size="20" autocapitalize="off"/>
            </p>
            <p>
                <label for="user_email"><?php _e('Email'); ?></label>
                <input type="email" name="user_email" id="user_email" class="input"
                       value="<?php echo esc_attr(wp_unslash($user_email)); ?>" size="25"/>
            </p>
            <?php do_action('register_form'); ?>
            <p id="reg_passmail">
                <?php _e('Registration confirmation will be emailed to you.'); ?>
            </p>
            <br class="clear"/>
            <input type="hidden" name="redirect_to" value="<?php wp_redirect($_REQUEST['redirect_to']) ?>"/>
            <p class="submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large"
                       value="<?php esc_attr_e('Register'); ?>"/>
            </p>
        </form>
    </div>
<?php ELSE: ?>
            <form id="form-checkout" method="POST" action="<?php echo PDI_PAYWALL_API_URI?>process_payment">
                <input type="text" id="form-checkout__cardNumber" placeholder="Número do cartão"/>
                <input type="text" id="form-checkout__expirationMonth" placeholder="Mês de vencimiento (MM)"/>
                <input type="text" id="form-checkout__expirationYear" placeholder="Ano de vencimiento (YY o YYYY)"/>
                <input type="text" name="cardholderName" id="form-checkout__cardholderName" placeholder="Titular do cartão"/>
                <input type="email" name="cardholderEmail" id="form-checkout__cardholderEmail" placeholder="E-mail"/>
                <input type="text" id="form-checkout__securityCode" placeholder="Código de segurança"/>
                <select name="issuer" id="form-checkout__issuer">
                    <option value="" disabled selected>Selecione o emissor</option>
                </select>
                <select name="identificationType" id="form-checkout__identificationType">
                    <option value="" disabled selected>Tipo de documento</option>
                </select>
                <input type="text" name="identificationNumber" id="form-checkout__identificationNumber"
                       placeholder="N​ú​mero do documento​"/>
                <select name="installments" id="form-checkout__installments">
                    <option value="" disabled selected>Escolha a quantidade de parcelas</option>
                </select>
                <input id="token" name="token" type="hidden"/>
                <input id="paymentMethodId" name="paymentMethodId" type="hidden"/>
                <input id="transactionAmount" name="transactionAmount" type="hidden" value="2"/>
                <input id="description" name="description" type="hidden" value="product description"/>
                <button type="submit" id="form-checkout__submit">Pagar</button>
            </form>
    <script src="https://sdk.mercadopago.com/js/v2"></script>

            <script>
                const mp = new MercadoPago(PDI_PAYWALL_PAYMENT_PUBLIC_KEY, {
                    locale: 'pt-BR'
                })
                // Step #getIdentificationTypes
                // Helper function to append option elements to a select input
                function createSelectOptions(elem, options, labelsAndKeys = {label: "name", value: "id"}) {
                    const {label, value} = labelsAndKeys;

                    elem.options.length = 0;

                    const tempOptions = document.createDocumentFragment();

                    options.forEach(option => {
                        const optValue = option[value];
                        const optLabel = option[label];

                        const opt = document.createElement('option');
                        opt.value = optValue;
                        opt.textContent = optLabel;

                        tempOptions.appendChild(opt);
                    });

                    elem.appendChild(tempOptions);
                }
                // Get Identification Types
                (async function getIdentificationTypes() {
                    try {
                        const identificationTypes = await mp.getIdentificationTypes();
                        const identificationTypeElement = document.getElementById('form-checkout__identificationType');

                        createSelectOptions(identificationTypeElement, identificationTypes)
                    } catch (e) {
                        return console.error('Error getting identificationTypes: ', e);
                    }
                })()

                // Step #getPaymentMethods
                const cardNumberElement = document.getElementById('form-checkout__cardNumber');
                function clearHTMLSelectChildrenFrom(element) {
                    const currOptions = [...element.children];
                    currOptions.forEach(child => child.remove());
                }
                cardNumberElement.addEventListener('keyup', async () => {
                    try {
                        const paymentMethodElement = document.getElementById('paymentMethodId');
                        const issuerElement = document.getElementById('form-checkout__issuer');
                        const installmentsElement = document.getElementById('form-checkout__installments');
                        let cardNumber = cardNumberElement.value;

                        if (cardNumber.length < 8 && paymentMethodElement.value) {
                            clearHTMLSelectChildrenFrom(issuerElement);
                            clearHTMLSelectChildrenFrom(installmentsElement);
                            paymentMethodElement.value = "";
                            return
                        }

                        if (cardNumber.length >= 8 && !paymentMethodElement.value) {
                            let bin = cardNumber.substring(0,8);
                            const paymentMethods = await mp.getPaymentMethods({'bin': bin});

                            const { id: paymentMethodId, additional_info_needed, issuer } = paymentMethods.results[0];

                            // Assign payment method ID to a hidden input.
                            paymentMethodElement.value = paymentMethodId;

                            // If 'issuer_id' is needed, we fetch all issuers (getIssuers()) from bin.
                            // Otherwise we just create an option with the unique issuer and call getInstallments().
                            additional_info_needed.includes('issuer_id') ? getIssuers() : (() => {
                                const issuerElement = document.getElementById('form-checkout__issuer');
                                createSelectOptions(issuerElement, [issuer]);

                                getInstallments();
                            })()
                        }
                    }catch(e) {
                        console.error('error getting payment methods: ', e)
                    }
                });

                // Step #getIssuers
                const getIssuers = async () => {
                    try {
                        const cardNumber = document.getElementById('form-checkout__cardNumber').value;
                        const paymentMethodId = document.getElementById('paymentMethodId').value;
                        const issuerElement = document.getElementById('form-checkout__issuer');

                        const issuers = await mp.getIssuers({paymentMethodId, bin: cardNumber.slice(0,8)});

                        createSelectOptions(issuerElement, issuers);

                        getInstallments();
                    }catch(e) {
                        pdiTools._pdi_alert_error({'message': `error getting issuers: ${e}`})
                        console.error('error getting issuers: ', e)
                    }
                };

                // Step #getInstallments
                const getInstallments = async () => {
                    try {
                        const installmentsElement = document.getElementById('form-checkout__installments')
                        const cardNumber = document.getElementById('form-checkout__cardNumber').value;
                        const installments = await mp.getInstallments({
                            amount: document.getElementById('transactionAmount').value,
                            bin: cardNumber.slice(0,8),
                            paymentTypeId: 'credit_card'
                        });
                        createSelectOptions(installmentsElement, installments[0].payer_costs, {label: 'recommended_message', value: 'installments'})
                    }catch(e) {
                        pdiTools._pdi_alert_error({'message': `error getting installments: ${e}`})
                        console.error('error getting installments: ', e)
                    }
                }

                // Step #createCardToken
                const formElement = document.getElementById('form-checkout');
                formElement.addEventListener('submit', e => createCardToken(e));

                const createCardToken = async (event) => {
                    try {
                        const tokenElement = document.getElementById('token');
                        if (!tokenElement.value) {
                            event.preventDefault();

                            const token = await mp.createCardToken({
                                cardNumber: document.getElementById('form-checkout__cardNumber').value,
                                cardholderName: document.getElementById('form-checkout__cardholderName').value,
                                identificationType: document.getElementById('form-checkout__identificationType').value,
                                identificationNumber: document.getElementById('form-checkout__identificationNumber').value,
                                securityCode: document.getElementById('form-checkout__securityCode').value,
                                cardExpirationMonth: document.getElementById('form-checkout__expirationMonth').value,
                                cardExpirationYear: document.getElementById('form-checkout__expirationYear').value
                            });

                            tokenElement.value = token.id;

                            formElement.requestSubmit();
                        }
                    }catch(e) {
                        pdiTools._pdi_alert_error({'message': `error creating card token: ${e}`})
                        console.error('error creating card token: ', e)
                    }
                }
            </script>
<?php ENDIF;
