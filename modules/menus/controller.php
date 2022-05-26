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
    add_options_page(
        'PDI Paywall',
        'PDI Paywall',
        'manage_options',
        'pdi-paywall',
        'pdi_paywall_settings_view'
    );

    add_submenu_page(
        'users.php',
        'Assinantes',
        'Assinantes',
        'manage_categories',
        'pdi-paywall-subscriptions',
        'pdi_paywall_subscriptions_view'
    );
}
add_action('admin_menu', 'pdi_paywall_admin_menu');

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
