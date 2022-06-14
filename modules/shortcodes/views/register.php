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
        const first_name = "<?php echo $first?>";
        const last_name = "<?php echo $last?>";
        const external_reference = "<?php echo wp_create_nonce('pdi-paywall-register-nonce') . '_' . $userID; ?>";

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
                    cardData.first_name = first_name;
                    cardData.last_name = last_name;
                    cardData.external_reference = external_reference;
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
                                    if (!!data.card_token_id){
                                        swal({
                                            title:"Bem Vindo!",
                                            text: "Agora você é nosso assinante e terá acesso a conteúdo exclusivo"
                                            icon: "success"
                                        })
                                            .then((response)=>{
                                                if(response){
                                                    Response.redirect(plan['back_url']);
                                                }
                                            })
                                    }

                                    if ((!!data.status && data.status !== 200) && !!data.message) {
                                        pdiTools._pdi_alert_error(data)
                                    }
                                })
                            })
                            .catch((error) => {
                                // get payment result error
                                reject();
                                console.log(error)

                                error.then((data) => {
                                    console.log(data)

                                    if ((!!data.status && data.status !== 200) && !!data.message) {
                                        pdiTools._pdi_alert_error(data)
                                    }
                                })
                            })
                            .finally((response)=>{
                                console.log(response)
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
