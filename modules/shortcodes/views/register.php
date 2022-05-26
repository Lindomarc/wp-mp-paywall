<?php if ($users_can_register) { ?>
    <div class="pdi-paywall-form">
        <form name="registerform" id="registerform" action="<?php echo esc_url(site_url('wp-login.php?action=register', 'login_post')); ?>" method="post" novalidate="novalidate">
            <p>
                <label for="user_login"><?php _e('Username'); ?></label>
                <input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr(wp_unslash($user_login)); ?>" size="20" autocapitalize="off" />
            </p>
            <p>
                <label for="user_email"><?php _e('Email'); ?></label>
                <input type="email" name="user_email" id="user_email" class="input" value="<?php echo esc_attr(wp_unslash($user_email)); ?>" size="25" />
            </p>
            <?php do_action('register_form'); ?>
            <p id="reg_passmail">
                <?php _e('Registration confirmation will be emailed to you.'); ?>
            </p>
            <br class="clear" />
            <input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />
            <p class="submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Register'); ?>" />
            </p>
        </form>
    </div>
<?php } else { ?>
    <div class="pdi-paywall-plan-details-container">
        <h3 class="pdi-paywall-plan-details-title">Resumo da assinatura</h3>
        <ul class="pdi-paywall-plan-details">
            <li class="pdi-paywall-plan-details-name"><strong>Seu plano:</strong> <?php print $plan['name'] ?></li>
            <li class="pdi-paywall-plan-details-duration"><strong>Duração:</strong> <?php print pdi_paywall_get_plans_period()[$plan['period']] ?></li>
            <li class="pdi-paywall-plan-details-content-access"><strong>Acesso:</strong><br />
                <?php print nl2br($plan['description']); ?>
            </li>
        </ul>
        <p class="pdi-paywall-plan-total">
            <strong>Total:</strong> <?php print $plan['amount'] > 0 ? 'R$ ' . number_format($plan['amount'], 2, ',', '') : 'Gratuito' ?>
        </p>
    </div>

    <?php if ($plan['amount'] > 0) { ?>
        <div class="pdi-paywall-form-steps">
            <div id="step-user" class="pdi-paywall-form-account-setup-step pdi-paywall-form-step active">
                <span class="step-number">1</span>
                <span class="step-title">Dados da conta</span>
            </div>
            <div id="step-payment" class="pdi-paywall-form-payment-setup-step pdi-paywall-form-step">
                <span class="step-number">2</span>
                <span class="step-title">Pagamento</span>
            </div>
        </div>
    <?php } ?>

    <form action="" method="POST" name="payment-form" id="pdi-paywall-payment-form" class="pdi-paywall-payment-form">
        <span class="payment-errors"></span>

        <div id="pdi-paywall-registration-errors"></div>

        <div id="pdi-paywall-step-user" class="pdi-paywall-registration-user-container">
            <div class="pdi-paywall-user-fields">
                <h3>Informações pessoais</h3>

                <p class="form-row first-name">
                    <label for="first_name">Nome <i class="required">*</i></label>
                    <input type="text" size="20" name="first_name" required value="<?php echo $first; ?>" />
                </p>

                <p class="form-row last-name">
                    <label for="last_name">Sobrenome <i class="required">*</i></label>
                    <input type="text" size="20" name="last_name" required value="<?php echo $last; ?>" />
                </p>

                <p class="form-row email-address">
                    <label for="email_address">E-mail <i class="required">*</i></label>
                    <input type="email" size="20" id="email_address" name="email_address" required value="<?php echo $email; ?>" <?php echo !empty($email) && !empty($userdata) ? 'disabled="disabled"' : ''; ?> />
                </p>
            </div>

            <div class="pdi-paywall-account-fields">
                <h3>Informações de conta</h3>

                <p class="form-row username">
                    <label for="username">Nome de usuário <i class="required">*</i></label>
                    <input type="text" size="20" name="username" id="username" required value="<?php echo $username; ?>" <?php echo !empty($username) && !empty($userdata) ? 'disabled="disabled"' : ''; ?> />
                </p>

                <?php if (!is_user_logged_in()) { ?>
                    <p class="form-row password">
                        <label for="password">Senha <i class="required">*</i></label>
                        <input type="password" size="20" id="password" required name="password" />
                    </p>
                    <p class="form-row confirm-password">
                        <label for="confirm_password">Confirmação de senha <i class="required">*</i></label>
                        <input type="password" size="20" id="confirm_password" required name="confirm_password" />
                    </p>
                <?php } ?>
            </div>

            <p>
                <?php if ($plan['amount'] > 0) { ?>
                    <button id="pdi-paywall-registration-next" type="button" onclick="nextStep()">Próximo</button>
                <?php } else { ?>
                    <button id="pdi-paywall-registration-submit" type="submit">Cadastrar-se</button>
                <?php } ?>
            </p>
        </div>

        <?php if ($plan['amount'] > 0) { ?>
            <div id="pdi-paywall-step-payment" class="pdi-paywall-registration-payment-container">
                <div class="pdi-paywall-card-fields">
                    <h3>Dados do cartão</h3>

                    <div class="pdi-paywall-card-container">
                        <div class="pdi-paywall-card-wrapper">
                        </div>

                        <div class="pdi-paywall-card-inputs">
                            <input placeholder="Número do cartão *" type="tel" name="payment_card_number" required>
                            <input placeholder="Nome completo *" type="text" name="payment_card_name" required>
                            <input placeholder="MM/YY *" type="tel" name="payment_card_expiry" class="pdiDate" required onchange="verifyYear()">
                            <input placeholder="CVC *" type="number" name="payment_card_cvc" required onchange="generateHash()">
                        </div>
                    </div>
                </div>

                <div class="pdi-paywall-info-fields">
                    <h3>Informações pessoais</h3>

                    <p class="form-row document">
                        <label for="document">Documento (CPF/CNPJ) <i class="required">*</i></label>
                        <input type="text" size="20" name="document" id="document" required />
                    </p>

                    <p class="form-row post-code">
                        <label for="post_code">CEP <i class="required">*</i></label>
                        <input type="text" size="20" name="post_code" id="post_code" required onchange="getAddress()" />
                    </p>
                    <p class="form-row address">
                        <label for="address">Endereço <i class="required">*</i></label>
                        <input type="text" size="20" name="address" id="address" required />
                    </p>
                    <p class="form-row complement">
                        <label for="complement">Complemento</label>
                        <input type="text" size="20" name="complement" id="complement" />
                    </p>
                    <p class="form-row number">
                        <label for="number">Número <i class="required">*</i></label>
                        <input type="text" size="20" name="number" id="number" required />
                    </p>
                    <p class="form-row neighborhood">
                        <label for="neighborhood">Bairro</label>
                        <input type="text" size="20" name="neighborhood" id="neighborhood" />
                    </p>
                    <p class="form-row city">
                        <label for="city">Cidade <i class="required">*</i></label>
                        <input type="text" size="20" name="city" id="city" required />
                    </p>
                    <p class="form-row state">
                        <label for="state">Estado (UF) <i class="required">*</i></label>
                        <input type="text" size="20" name="state" id="state" required />
                    </p>
                </div>

                <p>
                    <button id="pdi-paywall-registration-submit" type="submit">Assinar</button>
                </p>
            </div>
        <?php } ?>

        <input type="hidden" name="plan" value="<?php echo $plan['plan_id']; ?>" />
        <input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" />
        <input type="hidden" name="card_hash" />

        <input type="hidden" name="pdi_paywall_register_nonce" value="<?php echo wp_create_nonce('pdi-paywall-register-nonce'); ?>" />
    </form>

    <?php if ($plan['amount'] > 0) { ?>
        <script>
            var card = new Card({
                form: 'form#pdi-paywall-payment-form',
                container: '.pdi-paywall-card-wrapper',
                formSelectors: {
                    numberInput: 'input[name="payment_card_number"]',
                    expiryInput: 'input[name="payment_card_expiry"]',
                    cvcInput: 'input[name="payment_card_cvc"]',
                    nameInput: 'input[name="payment_card_name"]'
                },
                messages: {
                    monthYear: 'mês/ano',
                },
                placeholders: {
                    number: '•••• •••• •••• ••••',
                    name: 'Nome Completo',
                    expiry: '••/••••',
                    cvc: '•••'
                },
                masks: {
                    cardNumber: '•'
                }
            });

            <?php if (!empty($is_sandbox)) { ?>
                const checkout = new DirectCheckout('<?php echo $public_token ?>', false);
            <?php } else { ?>
                const checkout = new DirectCheckout('<?php echo $public_token ?>');
            <?php } ?>

            function generateHash() {
                const cardData = {
                    cardNumber: document.querySelector('input[name="payment_card_number"]').value.replaceAll(' ', ''),
                    holderName: document.querySelector('input[name="payment_card_name"]').value,
                    securityCode: document.querySelector('input[name="payment_card_cvc"]').value,
                    expirationMonth: parseInt(document.querySelector('input[name="payment_card_expiry"]').value.split("/")[0].trim()),
                    expirationYear: parseInt(document.querySelector('input[name="payment_card_expiry"]').value.split("/")[1].trim())
                };

                checkout.getCardHash(cardData, function(cardHash) {
                    document.querySelector('input[name="card_hash"]').value = cardHash;
                }, function(error) {
                    console.log(error);
                });
            }

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
                const email_address = document.querySelector('input[name="email_address"]').value.trim();
                const username = document.querySelector('input[name="username"]').value.trim();
                const password = document.querySelector('input[name="password"]').value.trim();
                const confirm_password = document.querySelector('input[name="confirm_password"]').value.trim();

                if (first_name.length === 0 || last_name.length === 0 || email_address.length === 0 || username.length === 0 || password.length === 0 || confirm_password.length === 0) {
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

            function verifyYear() {
                const input_expiry = document.querySelector('input[name="payment_card_expiry"]');
                const expiry = input_expiry.value.trim();

                if (expiry.length < 7) {
                    document.getElementById('error-year').remove();
                    input_expiry.style.border = 'solid 1px red';

                    let span = document.createElement("span");
                    span.setAttribute('id', 'error-year');
                    span.innerText = 'Formato inválido! o Ano deve conter 4 digitos. Ex.: 09/2026';
                    input_expiry.after(span);
                } else {
                    input_expiry.style.border = '';
                    document.getElementById('error-year').remove();
                }
            }

            jQuery(document).ready(function() {
                jQuery('.pdiDate').mask('00/0000', {
                    // placeholder: "__/____"
                });
            });
        </script>
    <?php } ?>
<?php } ?>

<?php if (isset($redirect) && $redirect) { ?>
    <script>
        window.location.href = '<?php echo $redirect_to; ?>';
    </script>
<?php } ?>