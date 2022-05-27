<div class="pdi-paywall-plans-container">
    <?php foreach ($plans as $key => $plan) { ?>
        <div id="option-<?php print $key; ?>" class="pdi-paywall-plan">
            <div class="pdi-paywall-header">
                <h3><?php print $plan['reason']; ?></h3>
            </div>
            <div class="pdi-paywall-price">
                <?php list($real, $cent) = explode(',',number_format($plan['auto_recurring']['transaction_amount'], 2, ',', '')); ?>
                <p><span class="signal">R$</span><span class="real"> <?php  echo $real ?></span><span class="cent">,<?php echo $cent; ?></span></p>
            </div>
            <div class="pdi-paywall-allowed-content">
                <p><?php print nl2br($plan['description']); ?></p>
            </div>
            <div class="pdi-paywall-payment">
                <div class="pdi-paywall-payment-button">
                    <a href="<?php echo $page_register ?>?plan=<?php echo base64_encode($plan['extern_plan_id']); ?>">
                        Assinar
                    </a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
