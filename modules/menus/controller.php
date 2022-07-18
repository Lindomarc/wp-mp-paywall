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

    add_submenu_page('pdi-paywall', 'Assinantes', 'Assinantes', 'manage_options', 'pdi_paywall_subscriptions_view', 'pdi_paywall_subscriptions_view');
    add_submenu_page('pdi-paywall', 'Planos', 'Planos', 'manage_options', 'pdi_paywall_plans_view', 'pdi_paywall_plans_view');
    $link = get_customizer_link();
    add_submenu_page( 'pdi-paywall', 'Email Templates', 'Email Templates', apply_filters( 'mailtpl/roles', 'edit_theme_options'), $link , null );

}
add_action('admin_menu', 'pdi_paywall_admin_menu');

function get_customizer_link() {
    $link = add_query_arg(
        array(
            'url'             => urlencode( site_url( '/?mailtpl_display=true' ) ),
            'return'          => urlencode( admin_url() ),
            'mailtpl_display' => 'true'
        ),
        'customize.php'
    );

    return $link;
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

function pdi_paywall_plans_view()
{
    if (!current_user_can('manage_categories')) {
        return;
    }
    $plans = [];
    $response = pdi_paywall_api_get('plans/database');
    if (!empty($response)) {
        $plans = json_decode($response);
    }
    require_once(PDI_PAYWALL_MODULES_PATH . 'menus/views/plans.php');
}

add_action('admin_head', 'pdi_paywall_const_keys', PHP_INT_MAX);
