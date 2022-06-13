<?php if (!is_user_logged_in()): ?>
<?php else: ?>
    <?php $price = $plan['auto_recurring']['transaction_amount']; ?>
    <div class="row pdi-paywall-plan ">
        <div class="pdi-paywall-header  panel panel-header panel-info ">
            <h3 class="panel-title"><?php print $plan['reason']; ?></h3>
        </div>
        <div class="col-md-6">
            <div class="panel-body">
                <div class="pdi-paywall-price">
                    <?php list($real, $cent) = explode(',', number_format($price, 2, ',', '')); ?>
                    <p>
                        <span class="signal">R$</span><span class="real"> <?php echo $real ?></span>
                        <span class="cent">,<?php echo $cent; ?></span>
                    </p>
                    <div class="pdi-paywall-allowed-content">
                        <p><?php print nl2br($plan['description']); ?></p>
                    </div>
                </div>
                    <div class="row">
                        <div class="col">

                            <div class="form-group">
                            <input
                                    type="text"
                                    class="form-control"
                                    id="first_name"
                                    placeholder="Nome"
                                    value="<?php echo $first ?? '' ?>"
                                    required="required"
                            />
                        </div>
                        </div>
                        <div class="col">
                            <input
                                    type="text"
                                    class="form-control"
                                    name="last_name"
                                    value="<?php echo $last ?? '' ?>"
                                    placeholder="Sobrenome"
                                    required="required"
                            />
                        </div>
                        <div class="col">

                            <div class="form-group">
                            <input
                                    type="email"
                                    class="form-control"
                                    value="<?php echo $email ?>"
                                    placeholder="Sobrenome"
                                    disabled
                            />

                            <input
                                    type="hidden"
                                    id="pdi_paywall_register_nonce"
                                    value="<?php echo wp_create_nonce('pdi-paywall-register-nonce').'_'.$userID; ?>"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="cardPaymentBrick_container">
            </div>
        </div>
    </div>

    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        const mp = new MercadoPago('<?php echo $public_token?>', {
            locale: 'pt-BR'
        });

        const plan = <?php echo json_encode($plan, true); ?>;

        const amount = plan['auto_recurring']['transaction_amount'];

        const userEmail = "<?php echo $email?>";

        mp.bricks('dark').create('cardPayment', 'cardPaymentBrick_container', {
            initialization: {
                amount: amount,
                payer: {
                    email: userEmail,
                }
            },
            customization: {
                visual: {
                    style: {
                        theme: 'bootstrap' // 'default' |'dark' | 'bootstrap' | 'flat'
                    },
                    texts: {
                        formTitle: 'Informações de pagamento',
                        cardholderName: {
                            placeholder: 'Nome do titula'
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
                    cardData.plan_id = plan['id'];
                    cardData.first_name = document.getElementById('first_name').value;
                    cardData.last_name = document.getElementById('last_name').value;
                    cardData.external_reference = document.getElementById('pdi_paywall_register_nonce').value;
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
                                resolve()
                                const result = response.json()
                                result.then((data) => {
                                    console.log(data)
                                    if ((!!data.status && data.status !== 200) && !!data.message) {
                                        pdiTools._pdi_alert_error(data)
                                    }
                                })
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
    <style>
        .pdi-paywall-plan {
            width: 100% !important;
        }

        .pdi-paywall-plan .pdi-paywall-price p {
            font-size: 5rem;
        }
    </style>
<?php endif; ?>
