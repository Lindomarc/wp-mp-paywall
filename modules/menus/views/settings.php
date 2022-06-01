<?php
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general_options';
?>
<link href="<?php echo plugins_url('/css/pdi_paywall_custom.css', __FILE__) ?>" rel="stylesheet" />
<script src="<?php echo plugins_url('/js/pdi_paywall_custom.js', __FILE__) ?>"></script>
<script src="<?php echo plugins_url('../../../vendor/js/tinymce/tinymce.min.js', __FILE__)?>"></script>
<div class="wrap">
    <h2>PDI Paywall</h2>

    <h2 class="nav-tab-wrapper">
        <a href="?page=pdi-paywall&tab=general_options" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>">Geral</a>
        <a href="?page=pdi-paywall&tab=restrictions_options" class="nav-tab <?php echo $active_tab == 'restrictions_options' ? 'nav-tab-active' : ''; ?>">Conteúdo</a>
        <a href="?page=pdi-paywall&tab=plans_options" class="nav-tab <?php echo $active_tab == 'plans_options' ? 'nav-tab-active' : ''; ?>">Planos</a>
        <a href="?page=pdi-paywall&tab=payments_options" class="nav-tab <?php echo $active_tab == 'payments_options' ? 'nav-tab-active' : ''; ?>">Pagamentos</a>
        <a href="?page=pdi-paywall&tab=email_options" class="nav-tab <?php echo $active_tab == 'email_options' ? 'nav-tab-active' : ''; ?>">Email</a>
    </h2>
    <div class="panel">
        <form method="post" action="options.php">

            <?php
            switch ($active_tab) {
                case 'plans_options':
                    settings_fields('_pdi_paywall_plans');
                    do_settings_sections('_pdi_paywall_plans');
                    break;

                case 'restrictions_options':
                    settings_fields('_pdi_paywall_restrictions');
                    do_settings_sections('_pdi_paywall_restrictions');
                    break;

                case 'payments_options':
                    settings_fields('_pdi_paywall_payments');
                    do_settings_sections('_pdi_paywall_payments');
                    break;

                case 'email_options':
                    settings_fields('_pdi_paywall_email');
                    do_settings_sections('_pdi_paywall_email');
                    break;

                default:
                    settings_fields('_pdi_paywall_general');
                    do_settings_sections('_pdi_paywall_general');
                    break;
            }

            submit_button();
            ?>
        </form>
    </div>
</div>
<script>
    tinymce.init({
        selector: 'textarea#_pdi_paywall_smtp_message_cancel,textarea#_pdi_paywall_smtp_message_success',
        width: 600,
        height: 400,
        menubar: false,

    });
    tinymce.init({
        selector: 'textarea#_pdi_paywall_plan_description_1,textarea#_pdi_paywall_plan_description_2,textarea#_pdi_paywall_plan_description_3',
        width:350,
        height: 500,
        menubar: false,

    });
</script>
