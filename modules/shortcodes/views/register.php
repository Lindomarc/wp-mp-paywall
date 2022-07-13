<?php if (!is_user_logged_in()): ?>
<?php else: ?>
    <?php
    $user = wp_get_current_user();
    $is_subscriber = in_array('subscriber', $user->roles);
    $userID = $user->ID;
    $price = !!$plan['transaction_amount']?$plan['transaction_amount']:'0.00';
    ?>

    <div class="row pdi-paywall-plan ">
        <div class="pdi-paywall-header  panel panel-header panel-info ">
            <h3 class="panel-title"><?php print $plan['reason']; ?></h3>
        </div>
        <div class="col-md-6">
            <div class="panel-body">
                <div class="pdi-paywall-price">
                    <?php list($real, $cent) = explode(',', pdi_paywall_number_format_us($price, 'br')); ?>
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

            <?php
            $subscriber_id = get_user_meta($userID, '_pdi_paywall_subscriber_id', true);
            if ($is_subscriber) {
                if (!$subscriber_id) {
                    $subscriber_txt = 'Você já é assinante por cortesia';
                } elseif ($subscriber_id == $plan['extern_plan_id']) {
                    $subscriber_txt = 'Que bom! Você é assinante deste plano';
                } else {
                    $subscriber_txt = 'Que bom! Você já é assinante de outro plano';
                }
                echo '<p class="subscriber_txt"><b>' . $subscriber_txt . '</b></p>';

            } elseif (!$plan['extern_plan_id']) {
                $subscriber_txt = 'Você possui acesso ao conteúdo gratuito.';

                echo '<p class="subscriber_txt"><b>' . $subscriber_txt . '</b></p>';
                echo '    
                <div id="is_subscriber">
                    <a class="btn btn-md btn-success" href="' . get_page_link(get_option('_pdi_paywall_page_plans')) . '">
                        Quero um upgrade do plano
                    </a>
                </div>';
            }
            ?>

            <?php if (!!$is_subscriber && !!$subscriber_id): ?>
                <div id="is_subscriber">
                    <button class="btn btn-md btn-danger" onclick="unsubscriber('<?php echo $subscriber_id ?>')">
                        Cancelar Assinatura
                    </button>
                </div>
                <script>
                    function unsubscriber(subscriber_id) {
                        swal({
                            title: "Cancelar Assinatura?",
                            text: "Tem certeza que deseja cancelar sua assinaura?",
                            icon: "warning",
                            buttons: ["Não", "Sim, desejo cancelar!"],
                            dangerMode: true,
                        })
                            .then((willDelete) => {
                                if (willDelete) {
                                    return new Promise((resolve, reject) => {
                                        fetch("<?php echo PDI_PAYWALL_API_URI?>subscribers/cancel", {
                                            method: "put",
                                            headers: {
                                                "Accept": "application/json",
                                                "Content-Type": "application/json",
                                                "Authorization": "Bearer " + _pdi_paywall_payment_pdi_token,
                                                "x-customer-key": _pdi_paywall_payment_pdi_key,
                                            },
                                            body: JSON.stringify({subscriber_id: subscriber_id})
                                        })
                                            .then(function (response) {
                                                //console.log(response)
                                                swal({
                                                    title: "Assinatura cancelada.",
                                                    text: "Volte quando desejar.",
                                                    icon: "success",
                                                })
                                                    .then(() => {
                                                        window.location.replace('/planos');
                                                    });
                                            })
                                    })
                                }
                            });
                    }
                </script>

            <?php else: ?>
                <div id="cardPaymentBrick_container"></div>

                <script src="https://sdk.mercadopago.com/js/v2"></script>
                <script>
                    const mp = new MercadoPago('<?php echo $public_token?>', {
                        locale: 'pt-BR'
                    });

                    const plan = <?php echo json_encode($plan, true); ?>;

                    const amount = plan['transaction_amount'];

                    const user_email = "<?php echo $email?>";
                    const first_name = "<?php echo $first?>";
                    const last_name = "<?php echo $last?>";
                    const external_reference = "<?php echo wp_create_nonce('pdi-paywall-register-nonce') . '_' . $userID; ?>";

                    mp.bricks('dark').create('cardPayment', 'cardPaymentBrick_container', {
                        initialization: {
                            amount: amount,
                            payer: {
                                email: user_email,
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
                                                if (!!data.card_token_id) {
                                                    swal({
                                                        title: "Bem Vindo!",
                                                        text: "Agora você é nosso assinante e terá acesso a conteúdo exclusivo",
                                                        icon: "success"
                                                    })
                                                        .then((response) => {
                                                            if (response) {
                                                                //todo melhorar essa url
                                                                window.location.replace(plan['back_url']);
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
                                            error.then((data) => {
                                                if ((!!data.status && data.status !== 200) && !!data.message) {
                                                    pdiTools._pdi_alert_error(data)
                                                }
                                            })
                                        })
                                        .finally((response) => {
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
            <?php endif; ?>
        </div>
    </div>

    <style>
        .pdi-paywall-plan {
            width: 100% !important;
        }

        .pdi-paywall-plan .pdi-paywall-price p {
            font-size: 5rem;
        }
    </style>

<?php endif; ?>
