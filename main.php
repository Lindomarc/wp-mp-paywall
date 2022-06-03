<?php

/**
 * Plugin Name:       PDI Paywall (MP)
 * Plugin URI:        https://publicadordigital.com.br/
 * Description:       PDI Paywall
 * Version:           0.2.4
 * Requires PHP:      7.4
 * Author:            MSWi Soluções Web Inteligentes
 * Author URI:        https://mswi.com.br/
 * Text Domain:       pdi-paywall
 * Domain Path:       /i18n
 */
if( ! function_exists( 'get_plugin_data' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$plugin_data = get_plugin_data( __FILE__ );

define('PDI_PAIWALL_VERSION', $plugin_data['Version']);
define('PDI_PAYWALL_URL', plugin_dir_url(__FILE__));
define('PDI_PAYWALL_PATH', plugin_dir_path(__FILE__));
define('PDI_PAYWALL_MODULES_PATH', PDI_PAYWALL_PATH . 'modules' . DIRECTORY_SEPARATOR);

define('PDI_PAYWALL_META_KEY_VISIBILITY', '_pdi_paywall_visibility');
define('PDI_PAYWALL_VISIBILITY_REGISTERED', 'registered');
define('PDI_PAYWALL_VISIBILITY_SUBSCRIBER', 'subscriber');
define('PDI_PAYWALL_VISIBILITY_EXCLUSIVE', 'exclusive');

define('PDI_PAYWALL_COOKIE', '_ppcrl');

//define('PDI_PAYWALL_API_URI', 'https://pag-api.herokuapp.com/');
 define('PDI_PAYWALL_API_URI', 'https://planos.applicanda.com.br/api/');
//define('PDI_PAYWALL_API_URI', 'https://planos.test/api/');
define('PDI_PAYWALL_PLAN_LIMIT', 3);

function pdi_paywall_activate()
{
    require_once(PDI_PAYWALL_PATH . 'functions.php');
}

add_action('plugins_loaded', 'pdi_paywall_activate');
