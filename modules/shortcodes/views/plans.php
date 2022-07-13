<div class="pdi-paywall-plans-container panel" id="displayPlan">
</div>
<style>

    .panel-body {
        padding: 0 !important;
        height: calc(100% - 200px) !important;
    }

    .panel-footer {
        background-color: transparent !important;
    }

    header.entry-header {
        display: none !important;
    }

    .pdi-paywall-plan h3 {

    }

</style>
<script>
    const PAGE_REGISTER = "<?php echo $page_register ?>";
    const getPlans = () => {
        return new Promise((resolve, reject) => {

            fetch('<?php echo PDI_PAYWALL_API_URI . 'plans'; ?>', {
                method: 'get',
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "Authorization": "Bearer <?php echo get_option('_pdi_paywall_payment_pdi_token'); ?>",
                    "x-customer-key": "<?php echo get_option('_pdi_paywall_payment_pdi_key'); ?>",
                },
            })
                .then((response) => {
                    const result = response.json()
                    console.log(response)
                    result.then(({data}) => {
                        let reason;
                        let link_plan;
                        let template;

                        Object.entries(data).forEach(([key, value]) => {
                            console.log(key,value)

                            link_plan = btoa(value['extern_plan_id']??value['id'])
                            reason = value['reason']

                            let transaction_amount = parseFloat(value['transaction_amount']);
                            let amount = transaction_amount.toLocaleString('pt-BR', {decimal:2}).split(',')
                            let priceInt = amount[0]??0;
                            let priceDecimal = amount[1]??'00';
                            let description = value['description']

                            templatePlan= `
                                <div id="option-${link_plan}" class="pdi-paywall-plan ">
                                    <div class="pdi-paywall-header  panel panel-header panel-info ">
                                        <h3 class="panel-title">${reason}</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="pdi-paywall-price">
                                            <p>
                                                <span class="signal">R$</span><span class="real"> ${priceInt}</span><span class="cent">,${priceDecimal}</span>
                                            </p>

                                            <div class="pdi-paywall-allowed-content  ">${description}</div>
                                        </div>
                                    </div>
                                    <div class="pdi-paywall-payment">
                                        <div class="pdi-paywall-payment-button">
                                            <a href="${PAGE_REGISTER}/?plan=${link_plan}">
                                                Assinar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;





                            jQuery("#displayPlan").append(templatePlan);
                        })
                    })
                })
                .catch((error) => {
                    // get payment result error
                    console.log(error)
                    reject();
                    error.then((data) => {
                        if ((!!data.status && data.status !== 200) && !!data.message) {
                            pdiTools._pdi_alert_error(data)
                        }
                    })
                })
        });
    }
    jQuery(document).ready(()=>{
        getPlans();
    })
</script>
