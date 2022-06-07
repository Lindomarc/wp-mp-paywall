
<div id="cardPaymentBrick_container">
    <form>
        <input type="text" value="teste" name="teste">
    </form>
</div>

<script src="https://sdk.mercadopago.com/js/v2"></script>

<?php
echo '<script>
    const PUBLIC_KEY = "' . get_option('_pdi_paywall_payment_public_key_test') . '";
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

    mp.bricks().create('cardPayment', 'cardPaymentBrick_container' , {
        initialization: {
            amount: amount
        },
        callbacks: {
            onReady: (cardData) => {
                // handle form ready

            },
            onSubmit: (cardData) => {
                cardData.preapproval_plan_id = plan['extern_plan_id'];
                console.log(cardData)
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
                            console.log(response)
                            resolve();
                        })
                        .catch((error) => {
                            console.log(error)
                            // get payment result error
                            reject();
                        })
                });
            },
            onError: (error) => {
                // handle error
            }
        }
    });
</script>
