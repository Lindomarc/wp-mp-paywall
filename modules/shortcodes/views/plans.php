<div class="pdi-paywall-plans-container panel">

    <?php foreach ($plans as $key => $plan) { ?>


        <?php if (isset($plan['name']) && !!$plan['name']) { ?>
            <div id="option-<?php print $key; ?>" class="pdi-paywall-plan ">
                <div class="pdi-paywall-header  panel panel-header panel-info ">
                    <h3 class="panel-title"><?php print $plan['name']; ?></h3>
                </div>
                <div class="panel-body">
                <div class="pdi-paywall-price">
                    <?php
                    $price = '0.00';
                    if (isset($plan['amount']) && !!$plan['amount']){
                        $price  = $plan['amount'];
                    }

                    list($real, $cent) = explode(',', pdi_paywall_number_format_us($price,'br')); ?>
                    <p><span class="signal">R$</span><span class="real"> <?php echo $real ?></span><span
                                class="cent">,<?php echo $cent; ?></span>
                    </p>

                    <div class="pdi-paywall-allowed-content  ">
                        <p><?php print nl2br($plan['description']); ?></p>
                    </div>
                </div>
                </div>
                <div class="pdi-paywall-payment">
                    <div class="pdi-paywall-payment-button">

                        <?php
                        $link_plan_id= !!$plan['extern_plan_id']?$plan['extern_plan_id']:$key?>
                         <a href="<?php echo $page_register ?>?plan=<?php echo base64_encode($link_plan_id); ?>">
                            Assinar
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<style>

.panel-body {
    padding: 0!important;
    height: calc(100% - 200px)!important;
}
.panel-footer{
    background-color: transparent!important;
}
header.entry-header{
    display: none!important;
}
.pdi-paywall-plan h3{

}

</style>

