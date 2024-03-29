<?php

if ($users_can_register) { ?>
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
<?php } else { ?>

    <div class="pdi-paywall-plan-details-container">
        <!--        <h3 class="pdi-paywall-plan-details-title">Resumo da assinatura</h3>-->

        <div class=" panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Seu plano:</h3>
            </div>
            <div class="panel-body">
                <ul class="pdi-list">
                    <li><strong>Seu plano:</strong> <?php print $plan['reason'] ?>
                    </li>
                    <?php $frequency_type = $plan['auto_recurring']['frequency_type']; ?>
                    <li>
                        <strong>Duração:</strong> <?php print pdi_paywall_get_plans_period()[$frequency_type] ?></li>
                    <li><strong>Acesso:</strong>
                        <div><?php print nl2br($plan['description']); ?></div>
                    </li>
                </ul>
            </div>
            <div class="panel-footer aling-end">
                <?php $price = $plan['auto_recurring']['transaction_amount']; ?>
                <h2>
                    <strong>Total:</strong> <?php echo $price > 0 ? 'R$ ' . number_format($price, 2, ',', '') : 'Gratuito' ?>
                </h2>
            </div>
        </div>
    </div>


    <form action="" method="POST" name="payment-form" id="pdi-paywall-payment-form" class="pdi-paywall-payment-form">
        <span class="payment-errors"></span>

        <div id="pdi-paywall-registration-errors"></div>

        <div id="pdi-paywall-step-user" class="pdi-paywall-registration-user-container">

            <div class="pdi-paywall-account-fields panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Informações do Assinante</h3>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="first_name">Nome <i class="required">*</i>
                                    <input
                                            class="form-control"
                                            type="text"
                                            size="20"
                                            placeholder="Nome"
                                            name="first_name" required
                                            value="<?php echo $first; ?>"/>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="input-group">
                                <label for="last_name">Sobrenome <i class="required">*</i>
                                    <input
                                            class="form-control"
                                            type="text"
                                            size="20"
                                            name="last_name"
                                            placeholder="Sobrenome"
                                            value="<?php echo $last; ?>"
                                            required/>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="email_address">E-mail <i class="required">*</i>
                                    <input
                                            class="form-control"
                                            type="email"
                                            placeholder="E-mail"
                                            name="user_email"
                                            id="cardholderEmail"/>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="username">Nome de usuário <i class="required">*</i>
                                    <input
                                            class="form-control"
                                            type="text"
                                            size="20"
                                            name="username"
                                            placeholder="Nome de usuário"
                                            id="username" required
                                            value="<?php echo $username; ?>" <?php echo !empty($username) && !empty($userdata) ? 'disabled="disabled"' : ''; ?>
                                    />
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php if (!is_user_logged_in()) { ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="password">Senha <i class="required">*</i>
                                        <input
                                                class="form-control"
                                                type="password"
                                                size="20"
                                                id="password"
                                                required
                                                placeholder="Senha"
                                                name="password"/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">

                                    <label for="confirm_password">Confirmação de senha <i class="required">*</i>
                                        <input
                                                class="form-control"
                                                type="password"
                                                size="20"
                                                id="confirm_password"
                                                placeholder="Confirmação de senha"
                                                required
                                                name="confirm_password"/>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>


        </div>

        <?php /* if ($price > 0) { ?>
            <div id="pdi-paywall-step-payment" class="pdi-paywall-registration-payment-container">


                <div class="pdi-paywall-info-fields">
                    <h3>Informações pessoais</h3>

                    <p class="form-row document">
                        <label for="document">Documento (CPF/CNPJ) <i class="required">*</i></label>
                        <input type="text" size="20" name="document" id="document" required/>
                    </p>

                    <p class="form-row post-code">
                        <label for="post_code">CEP <i class="required">*</i></label>
                        <input type="text" size="20" name="post_code" id="post_code" required onchange="getAddress()"/>
                    </p>
                    <p class="form-row address">
                        <label for="address">Endereço <i class="required">*</i></label>
                        <input type="text" size="20" name="address" id="address" required/>
                    </p>
                    <p class="form-row complement">
                        <label for="complement">Complemento</label>
                        <input type="text" size="20" name="complement" id="complement"/>
                    </p>
                    <p class="form-row number">
                        <label for="number">Número <i class="required">*</i></label>
                        <input type="text" size="20" name="number" id="number" required/>
                    </p>
                    <p class="form-row neighborhood">
                        <label for="neighborhood">Bairro</label>
                        <input type="text" size="20" name="neighborhood" id="neighborhood"/>
                    </p>
                    <p class="form-row city">
                        <label for="city">Cidade <i class="required">*</i></label>
                        <input type="text" size="20" name="city" id="city" required/>
                    </p>
                    <p class="form-row state">
                        <label for="state">Estado (UF) <i class="required">*</i></label>
                        <input type="text" size="20" name="state" id="state" required/>
                    </p>
                </div>


            </div>
        <?php } */ ?>

        <input type="hidden" name="plan_id" value="<?php echo $plan['plan_id']; ?>"/>
        <input type="hidden" name="extern_plan_id" value="<?php echo $plan['extern_plan_id']; ?>"/>
        <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>"/>
        <input type="hidden" name="card_token_id" id="card_token_id"/>

        <input type="hidden" name="pdi_paywall_register_nonce"
               value="<?php echo wp_create_nonce('pdi-paywall-register-nonce'); ?>"/>

        <div class="pdi-paywall-card-fields panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Dados de Pagamento</h3>
            </div>

            <div class="panel-body">
                <div class="pdi-paywall-card-container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="pdi-paywall-card-wrapper"></div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">

                                        <label>Título no cartão
                                            <input
                                                    class="form-control"
                                                    id="cardholderName"
                                                    type="text"
                                                    placeholder="Título no cartão"
                                                    required/>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label>Número do Cartão
                                            <input class="form-control"
                                                   id="cardNumber"
                                                   type="text"
                                                   placeholder="Número do Cartão"
                                                   required/>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-md-4">
                                    <div class="input-group">
                                        <label style="display: inline-grid;">Expiração
                                            <input
                                                    class="form-control"
                                                    id="expirationDate"
                                                    type="text"
                                                    placeholder="DD/YYYY"
                                                    required
                                                    onchange="verifyYear()">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-4">
                                    <div class="input-group">
                                        <label>Código
                                            <input
                                                    class="form-control"
                                                    id="securityCode"
                                                    type="number"
                                                    placeholder="Cód."
                                                    required/>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display: none">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select id="issuer" class="form-control"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">


                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label>Documento
                                            <input
                                                    class="form-control"
                                                    type="text"
                                                    placeholder="CPF ou CNPJ"
                                                    id="identificationNumber"
                                                    onkeyup="fIdentificationType(this)"
                                                    required/>
                                        </label>
                                        <input type="hidden" class="form-control" id="identificationType">

                                    </div>
                                </div>

                                <div class="col-md-12"  style="display: none">
                                    <div class="input-group">
                                        <select
                                                class="form-control"
                                                id="installments">

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class=" panel-footer">
                <div class="row">
                    <div class="col aling-end">
                        <div class="bottons-group">
                            <progress value="0" class="progress-bar">Assinando...</progress>
                            <button type="submit"  id="wp-submit" class="btn btn-primary btn-lg">Assinar</button>


                        </div>
                    </div>
                </div>
            </div>
        </div>


    </form>
    <?php if ($price > 0) { ?>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://sdk.mercadopago.com/js/v2"></script>

        <script>

            const plan = <?php echo json_encode($plan); ?>;
            const price = "<?php echo $price; ?>";
            var card = new Card({
                form: 'form#pdi-paywall-payment-form',
                container: '.pdi-paywall-card-wrapper',
                formSelectors: {
                    numberInput: '#cardNumber',
                    expiryInput: '#expirationDate',
                    cvcInput: '#securityCode',
                    nameInput: '#cardholderName'
                },
                messages: {
                    monthYear: 'mês/ano',
                },
                placeholders: {
                    number: '•••• •••• •••• ••••',
                    name: 'Nome',
                    expiry: '••/••••',
                    cvc: '•••'
                },
                masks: {
                    cardNumber: '•'
                }
            });


            function getAddress() {
                const cep = document.querySelector('input[name="post_code"]').value.trim();

                fetch(`https://brasilapi.com.br/api/cep/v1/${cep}`)
                    .then(response => response.json())
                    .then(data => {
                        document.querySelector('input[name="address"]').value = data.street;
                        document.querySelector('input[name="neighborhood"]').value = data.neighborhood;
                        document.querySelector('input[name="city"]').value = data.city;
                        document.querySelector('input[name="state"]').value = data.state;
                    });
            }

            function nextStep() {
                document.querySelector('div#pdi-paywall-registration-errors').style.display = 'none';

                const first_name = document.querySelector('input[name="first_name"]').value.trim();
                const last_name = document.querySelector('input[name="last_name"]').value.trim();
                const user_email = document.querySelector('input[name="user_email"]').value.trim();
                const username = document.querySelector('input[name="username"]').value.trim();
                const password = document.querySelector('input[name="password"]').value.trim();
                const confirm_password = document.querySelector('input[name="confirm_password"]').value.trim();

                if (first_name.length === 0 || last_name.length === 0 || user_email.length === 0 || username.length === 0 || password.length === 0 || confirm_password.length === 0) {
                    document.querySelector('div#pdi-paywall-registration-errors').innerText = 'É necessário preencher todos os campos obrigatórios!';
                    document.querySelector('div#pdi-paywall-registration-errors').style.display = 'block';
                    return;
                }

                if (password !== confirm_password) {
                    document.querySelector('div#pdi-paywall-registration-errors').innerText = 'As senhas não coincidem!';
                    document.querySelector('div#pdi-paywall-registration-errors').style.display = 'block';
                    return;
                }

                document.getElementById('pdi-paywall-step-user').style.display = 'none';
                document.getElementById('pdi-paywall-step-payment').style.display = 'block';

                document.getElementById('step-user').classList.remove('active');
                document.getElementById('step-payment').className += ' active';
            }


            jQuery(document).ready(function () {
                jQuery('.pdiDate').mask('00/0000', {
                    // placeholder: "__/____"
                });
            });


            const mercadoPago = new MercadoPago(PDI_PAYWALL_PAYMENT_PUBLIC_KEY, {
                locale: 'pt-BR'
            })


            const cardForm = mercadoPago.cardForm({
                amount: price,
                autoMount: true,
                form: {
                    id: "pdi-paywall-payment-form",
                    cardholderName: {
                        id: "cardholderName",
                        placeholder: "Titular do cartão",
                    },
                    cardholderEmail: {
                        id: "cardholderEmail",
                        placeholder: "E-mail",
                    },
                    cardNumber: {
                        id: "cardNumber",
                        placeholder: "Número do cartão",
                    },
                    expirationDate: {
                        id: "expirationDate",
                        placeholder: "MM/AAAA",
                    },
                    securityCode: {
                        id: "securityCode",
                        placeholder: "CVV",
                    },
                    identificationType: {
                        id: "identificationType",
                        placeholder: "Tipo de documento",
                    },
                    identificationNumber: {
                        id: "identificationNumber",
                        placeholder: "Número do (CPF/CNPJ)",
                    },
                    issuer: {
                        id: "issuer",
                        placeholder: "Banco emissor",
                    },
                    installments: {
                        id: "installments",
                        placeholder: "Parcelas",
                    },
                },

                callbacks: {
                    onFormMounted: error => {
                        if (error) return console.warn("Form Mounted handling error: ", error);
                        console.log("Form mounted");
                    },

                    onSubmit: event => {
                        //let card_token = createCardToken();
                        event.preventDefault();

                        const {
                            paymentMethodId,
                            issuerId,
                            cardholderEmail,
                            amount,
                            token,
                            installments,
                            identificationNumber,
                            identificationType,
                        } = cardForm.getCardFormData();
                        if (!!token) {
                            async function setSubscriber() {
                                const apiUrl = "<?php echo PDI_PAYWALL_API_URI?>subscribers";
                                const form = {
                                    payer_email: cardholderEmail,
                                    username: document.querySelector('input[name="username"]').value.trim(),
                                    password: document.querySelector('input[name="password"]').value.trim(),
                                    first_name: document.querySelector('input[name="first_name"]').value.trim(),
                                    last_name: document.querySelector('input[name="last_name"]').value.trim(),
                                    preapproval_plan_id: "<?php echo $plan['extern_plan_id']; ?>",
                                    card_token_id: token,
                                    identification: {
                                        type: pdiTools.typeDocument(
                                            document.getElementById('identificationNumber').value
                                        ),
                                        number: pdiTools.onlyNumber(
                                            document.getElementById('identificationNumber').value
                                        ),
                                    }
                                };
                                const options = {
                                    path: 'subscribers',
                                    method: 'post',
                                    data: JSON.stringify(form)
                                }


                                try {
                                    const response = await fetch(apiUrl, {
                                        headers: {
                                            "Accept": "application/json",
                                            "Content-Type": "application/json",
                                            "Authorization": "Bearer " + _pdi_paywall_payment_pdi_token,
                                            "x-customer-key": _pdi_paywall_payment_pdi_key,
                                        },
                                        method: "POST",
                                        body: JSON.stringify(form),
                                    })
                                    const data = await response.json();

                                    if ((!!data.status && data.status !== 200) && !!data.message) {
                                        pdiTools._pdi_alert_error(data)
                                    }
                                } catch (error) {
                                    console.log(error);
                                }
                            }

                            return setSubscriber()
                        }

                    },

                    onFetching: (resource) => {
                        console.log("Fetching resource: ", resource);

                        // Animate progress bar
                        const progressBar = document.querySelector(".progress-bar");
                        progressBar.removeAttribute("value");

                        return () => {
                            progressBar.setAttribute("value", "0");
                        };
                    }
                },

            });

            function expirationDate() {
                let item = document.getElementById('expirationDate').value.split("/");
                return Number(item)
            }

            function fIdentificationType(Number) {
                document.getElementById('identificationType').value = pdiTools.typeDocument(Number.value);
            }

            async function newWpUser(cardToken) {
                let identification = JSON.stringify({
                    type: pdiTools.typeDocument(
                        document.getElementById('identificationNumber').value
                    ),
                    number: pdiTools.onlyNumber(
                        document.getElementById('identificationNumber').value
                    ),
                })
                try {

                    let planData = JSON.stringify(plan);
                    let first_name = document.querySelector('input[name="first_name"]').value.trim();
                    let last_name = document.querySelector('input[name="last_name"]').value.trim();
                    const dataUser = {
                        password: document.querySelector('input[name="password"]').value.trim(),
                        username: document.querySelector('input[name="username"]').value.trim(),
                        user_email: document.querySelector('input[name="user_email"]').value.trim(),
                        display_name: `${first_name} ${last_name}`,
                        nickname: document.querySelector('input[name="username"]').value.trim(),
                        first_name: document.querySelector('input[name="first_name"]').value.trim(),
                        last_name: document.querySelector('input[name="last_name"]').value.trim(),
                        document: document.querySelector('input[name="document"]').value.trim(),
                        pdi_paywall_register_nonce: document.querySelector('input[name="pdi_paywall_register_nonce"]').value.trim(),
                        amount: price,
                        planData: `${planData}`,
                        card_token_id: cardToken.id,
                        identification: identification
                    }

                    return sendData('/wp-json/pdi-paywall/v1/new_wp_user', dataUser)
                } catch (e) {
                    console.error('error creating card token: ', e)
                }
            }

            async function sendData(url, data) {
                const formData = new FormData();

                for (const name in data) {
                    formData.append(name, data[name]);
                }

                return await fetch(url, {
                    method: 'POST',
                    body: formData
                });
            }

            function verifyYear() {
                const input_expiry = document.querySelector('#expirationDate');
                const expiry = input_expiry.value.trim();

                let error = document.getElementById('error-year');

                if (error) {
                    document.getElementById('error-year').remove();
                }
                if (expiry.length < 7) {
                    input_expiry.style.border = 'solid 1px red';
                    let span = document.createElement("span");
                    span.setAttribute('id', 'error-year');
                    span.innerText = 'ex: 09/2026';
                    input_expiry.after(span);
                } else {
                    input_expiry.style.border = '';
                }
            }

            const createCardToken = async () => {
                const cardFields = {
                    cardNumber: pdiTools.onlyNumber(document.getElementById('cardNumber').value),
                    email: document.querySelector('input[name="user_email"]').value.trim(),
                    cardholder: {
                        name: document.getElementById('cardholderName').value,
                        identification: {
                            type: pdiTools.typeDocument(
                                document.getElementById('identificationNumber').value
                            ),
                            number: pdiTools.onlyNumber(
                                document.getElementById('identificationNumber').value
                            ),
                        }
                    },
                    security_code: document.getElementById('securityCode').value,
                    cardExpirationMonth: expirationDate()[0],
                    cardExpirationYear: expirationDate()[1]
                }
                try {
                    const response = await fetch('<?php echo PDI_PAYWALL_API_URI . 'cards';?>', {
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                            "Authorization": "Bearer " + _pdi_paywall_payment_pdi_token,
                            "x-customer-key": _pdi_paywall_payment_pdi_key,
                        },
                        method: "POST",
                        body: JSON.stringify(cardFields),
                    })

                    const data = await response.json();
                    if ((!!data.status && data.status !== 200) && !!data.message) {
                        pdiTools._pdi_alert_error(data)
                    }
                    return data;
                } catch (e) {
                    console.error('error creating card token: ', e)
                }
            }

            async function getToken() {
                let ex = document.getElementById('expirationDate').value.split("/")
                console.log(ex);
                const response = await mercadoPago.createCardToken({
                    cardNumber: document.getElementById('cardNumber').value,
                    cardholderName: document.getElementById('cardholderName').value,
                    identificationNumber: pdiTools.onlyNumber(
                        document.getElementById('identificationNumber').value
                    ),
                    identificationType: pdiTools.typeDocument(
                        document.getElementById('identificationNumber').value
                    ),
                    securityCode: document.getElementById('securityCode').value,

                    security_code: document.getElementById('securityCode').value,

                    expirationMonth: expirationDate()[0],
                    expirationYear: expirationDate()[1],
                })

                if ((!!response.status && response.status !== 200) && !!response.message) {
                    pdiTools._pdi_alert_error(response)
                }

                return response;

            }

            async function setSubscriber(event) {
                event.preventDefault();

                let cardToken = await getToken();


                if (!!cardToken.id) {
                    ///let responseUser = await newWpUser(cardToken);
                    const form = {
                        payer_email: document.querySelector('input[name="user_email"]').value.trim(),
                        username: document.querySelector('input[name="username"]').value.trim(),
                        password: document.querySelector('input[name="password"]').value.trim(),
                        first_name: document.querySelector('input[name="first_name"]').value.trim(),
                        last_name: document.querySelector('input[name="last_name"]').value.trim(),
                        preapproval_plan_id: plan['extern_plan_id'],
                        card_token_id: cardToken.id,
                        expirationMonth: expirationDate()[0],
                        expirationYear: expirationDate()[1],

                        identification: {
                            type: pdiTools.typeDocument(
                                document.getElementById('identificationNumber').value
                            ),
                            number: pdiTools.onlyNumber(
                                document.getElementById('identificationNumber').value
                            ),
                        }
                    };
                    try {
                        const response = await fetch("<?php echo PDI_PAYWALL_API_URI?>subscribers", {
                            headers: {
                                "Accept": "application/json",
                                "Content-Type": "application/json",
                                "Authorization": "Bearer " + _pdi_paywall_payment_pdi_token,
                                "x-customer-key": _pdi_paywall_payment_pdi_key,
                            },
                            method: "POST",
                            body: JSON.stringify(form),
                        })
                        const data = await response.json();
                        if ((!!data.status && data.status !== 200) && !!data.message) {
                            pdiTools._pdi_alert_error(data)
                        }

                    } catch (error) {
                        console.log(error);
                    }
                } else {
                    pdiTools._pdi_alert_error({
                        message: "Parece que não foi possivel identificar o seu cartão",
                        status: 400
                    })
                }
            }

            // document.getElementById('wp-submit').addEventListener('click', setSubscriber);

        </script>
    <?php } ?>
<?php } ?>

<?php if (isset($redirect) && $redirect) { ?>
    <script>
        window.location.href = '<?php echo $redirect_to; ?>';
    </script>
<?php } ?>


<style>

    .input-group {
        display: flex !important;
        padding: 2% 0 !important;
    }

    label {
        width: 100%
    }

    .aling-end {
        justify-content: end;
        display: flex;
    }

    .bottons-group {
        padding: 0 2em;
    }

</style>
