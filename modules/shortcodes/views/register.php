
<div id="cardPaymentBrick_container">

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
                                id="first_name"
                                name="first_name"
                                required
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
                                id="last_name"
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

<script src="https://sdk.mercadopago.com/js/v2"></script>

<?php
echo '<script>
    const PUBLIC_KEY = "' . get_option('_pdi_paywall_payment_public_key') . '";
</script>';
?>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<?php $price = $plan['auto_recurring']['transaction_amount']; ?>

<script>
    const mp = new MercadoPago(PUBLIC_KEY, {
        locale: 'pt-BR'
    })
    const plan = <?php echo json_encode($plan, true); ?>;

    const amount = <?php echo $price; ?>;

    mp.bricks('dark').create('cardPayment', 'cardPaymentBrick_container' , {
        initialization: {
            amount: amount
        },
        customization: {
            visual: {
                style: {
                    theme: 'default' // 'default' |'dark' | 'bootstrap' | 'flat'
                },
                texts: {
                    formTitle: 'Informações de pagamento',
                    cardholderName: {
                        placeholder: ''
                    },
                    formSubmit: 'Assinar'
                }
            },
            paymentMethods: {
                maxInstallments: 1,
                types: {
                    excluded: 'debit_card'
                }
            }
        },
        callbacks: {
            onReady: (cardData) => {
                // handle form ready

            },
            onSubmit: (cardData) => {
                cardData.preapproval_plan_id = plan['extern_plan_id'];
                cardData.first_name = document.getElementById('first_name').value;
                cardData.last_name = document.getElementById('last_name').value;
                return new Promise((resolve, reject) => {
                    fetch("<?php echo PDI_PAYWALL_API_URI?>subscribers", {
                        method: "POST",
                        headers: {
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                            "Authorization": "Bearer " + _pdi_paywall_payment_pdi_token,
                            "x-customer-key": _pdi_paywall_payment_pdi_key,
                        },
                        body: JSON.stringify(cardData)
                    })
                        .then((response) => {
                            resolve();
                            console.log(response)
                        })
                        .catch((error) => {
                            // get payment result error
                            reject();

                            console.log(error)
                        })
                });
            },
            onError: (error) => {
                console.log(error)

                // handle error
            }
        }
    });
</script>
