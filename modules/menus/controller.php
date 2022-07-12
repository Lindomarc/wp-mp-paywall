<?php

function pdi_paywall_settings_view()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    require_once(PDI_PAYWALL_MODULES_PATH . 'menus/views/settings.php');
}

function pdi_paywall_admin_menu()
{
    add_menu_page(
        __( 'PDI Paywall - Configurações', 'textdomain' ),
        __( 'PDI Paywall', 'textdomain' ),
        'read',
        'pdi-paywall',
        'pdi_paywall_settings_view'
    );
   // add_menu_page('PDI Paywall', 'PDI Paywall', 'manage_options', 'pdi-paywall', 'mainMenu','dashicons-calendar-alt');
  //  add_submenu_page('pdi-paywall', 'Configurações', 'Configurações', 'manage_options', 'pdi_paywall_settings_view', 'pdi_paywall_settings_view');
    add_submenu_page('pdi-paywall', 'Assinantes', 'Assinantes', 'manage_options', 'pdi_paywall_subscriptions_view', 'pdi_paywall_subscriptions_view');
}
add_action('admin_menu', 'pdi_paywall_admin_menu');

function mainMenu() {
    echo "<h2>My WordPress Plugin</h2>Hello world! I'm the main menu.";
}
function subMenu() {
    echo "<h2>My WordPress Plugin</h2>Hello world! I'm the submenu.";
}
function pdi_paywall_subscriptions_view()
{
    if (!current_user_can('manage_categories')) {
        return;
    }

    $subscribers = [];

    $response = pdi_paywall_api_get('subscribers');

    if (!empty($response)) {
        $subscribers = json_decode($response);
    }

    require_once(PDI_PAYWALL_MODULES_PATH . 'menus/views/subscriptions.php');
}
