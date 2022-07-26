<?php
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general_options';
?>
<link href="<?php echo PDI_PAYWALL_URL.'css/custom.css' ?>" rel="stylesheet" />
<script src="<?php echo PDI_PAYWALL_URL.'js/custom.js' ?>"></script>
<script src="<?php echo plugins_url('../../../vendor/js/tinymce/tinymce.min.js', __FILE__)?>"></script>
<div class="wrap">
    <h2>PDI Paywall</h2>

    <h2 class="nav-tab-wrapper">
        <a href="?page=pdi-paywall&tab=general_options" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>">Geral</a>
        <a href="?page=pdi-paywall&tab=restrictions_options" class="nav-tab <?php echo $active_tab == 'restrictions_options' ? 'nav-tab-active' : ''; ?>">Restrição Parcial</a>
        <a href="?page=pdi-paywall&tab=restrictions_no_content_options" class="nav-tab <?php echo $active_tab == 'restrictions_no_content_options' ? 'nav-tab-active' : ''; ?>">Restrição Total</a>
        <a href="?page=pdi-paywall&tab=restrictions_free_options" class="nav-tab <?php echo $active_tab == 'restrictions_free_options' ? 'nav-tab-active' : ''; ?>">Liberação</a>
        <a href="?page=pdi_paywall_plans_view" class="nav-tab <?php echo $active_tab == 'plans_options' ? 'nav-tab-active' : ''; ?>">Planos</a>
        <a href="?page=pdi-paywall&tab=payments_options" class="nav-tab <?php echo $active_tab == 'payments_options' ? 'nav-tab-active' : ''; ?>">Pagamentos</a>
        <a href="?page=pdi-paywall&tab=email_options" class="nav-tab <?php echo $active_tab == 'email_options' ? 'nav-tab-active' : ''; ?>">Email</a>
    </h2>
    <div class="panel">
        <form method="post" action="options.php">

            <?php
            switch ($active_tab) {
//                case 'plans_options':
//                    settings_fields('_pdi_paywall_plans');
//                    do_settings_sections('_pdi_paywall_plans');
//                    break;

                case 'restrictions_options':
                    settings_fields('_pdi_paywall_restrictions');
                    do_settings_sections('_pdi_paywall_restrictions');
                    break;

                case 'restrictions_no_content_options':
                    settings_fields('_pdi_paywall_no_content_restrictions');
                    do_settings_sections('_pdi_paywall_no_content_restrictions');
                    break;


                case 'restrictions_free_options':
                    settings_fields('_pdi_paywall_free_restrictions');
                    do_settings_sections('_pdi_paywall_free_restrictions');
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
    //     tinymce.init({
    //     selector: 'textarea#_pdi_paywall_html_restriction',
    //     plugins: [
    //     'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
    //     'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
    //     'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
    //     ],
    //         menubar: false,
    //     toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
    //     'alignleft aligncenter alignright alignjustify | ' +
    //     'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
    // });
</script>
