<style>
    .pdi_paywall_restriction_wrap {
        position: relative;
        width: 100%;
        margin: -5em auto 2em;
        padding: 6em 0 0;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.5) 0.5em, #fff 5.5em, #fff 100%);
    }

    .pdi_paywall_restriction_message {
        box-shadow: 0 0 0.5em 0.25em rgb(0 0 0 / 13%);
        padding: 1.375em;
        border: .5em solid #FFFFFF;
        -webkit-border-radius: .5em;
        -moz-border-radius: .5em;
        border-radius: .5em;
        background-color: #e3e3e3;
        color: #333333;
        font-size: 1em;
        line-height: 1.625;
    }

    .pdi_paywall_restriction_message a {
        color: inherit;
        font-weight: 600;
    }
</style>

<div class="pdi_paywall_restriction_wrap">
    <div class="pdi_paywall_restriction_message">
        Você precisa <a href="<?php echo $plans ?>">fazer um upgrade na sua conta</a> para ler o restante do conteúdo.
    </div>
</div>
