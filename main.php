<?php
/**
 * Plugin Name:       PDI Paywall (MP+login-ajax)
 * Plugin URI:        https://publicadordigital.com.br/
 * Description:       PDI Paywall
 * Version:           0.2.9
 * Requires PHP:      7.4
 * Author:            MSWi Soluções Web Inteligentes
 * Author URI:        https://mswi.com.br/
 * Text Domain:       pdi-paywall
 * Domain Path:       /i18n
 */

//function pdi_paywall_number_format_us($value, $tipo = 'us')
//{
//    	if ($value) {
//            if ($tipo == 'us') {
//                $value = str_replace('.', '', $value);
//                $value = str_replace(',', '.', $value);
//            } else {
//                $value = number_format($value, 2, ',', '.');
//            }
//        }
//		return $value;
//}
//
//echo pdi_paywall_number_format_us('2,29');
//exit();
if (!function_exists('get_plugin_data')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}
$plugin_data = get_plugin_data(__FILE__);

define('PDI_PAIWALL_VERSION', $plugin_data['Version']);
define('PDI_PAYWALL_URL', plugin_dir_url(__FILE__));
define('PDI_PAYWALL_PATH', plugin_dir_path(__FILE__));
define('PDI_PAYWALL_MODULES_PATH', PDI_PAYWALL_PATH . 'modules' . DIRECTORY_SEPARATOR);

define('PDI_PAYWALL_META_KEY_VISIBILITY', '_pdi_paywall_visibility');
define('PDI_PAYWALL_VISIBILITY_REGISTERED', 'registered');
define('PDI_PAYWALL_VISIBILITY_SUBSCRIBER', 'subscriber');
define('PDI_PAYWALL_VISIBILITY_PUBLIC', 'Publico');

define('PDI_PAYWALL_COOKIE', '_ppcrl');

//define('PDI_PAYWALL_API_URI', 'https://pag-api.herokuapp.com/');
define('PDI_PAYWALL_API_URI', 'https://planosdev.applicanda.com.br/api/');
//define('PDI_PAYWALL_API_URI', 'https://planos.test/api/');
define('PDI_PAYWALL_PLAN_LIMIT', 3);

function pdi_paywall_activate()
{
    require_once(PDI_PAYWALL_PATH . 'functions.php');
    require_once(PDI_PAYWALL_PATH . 'login-registration-modal-pro.php');
    require_once(PDI_PAYWALL_PATH . 'wp-last-login.php');

    /**
     * Sets a default meta value for all users.
     *
     * Allows sorting users by last login to work, even though some might not have
     * recorded login time.
     *
     * @see https://wordpress.org/support/topic/wp-40-sorting-by-date-doesnt-work
     */
    $user_ids = get_users(array(
        'blog_id' => '',
        'fields' => 'ID',
    ));

    foreach ($user_ids as $user_id) {
        update_user_meta($user_id, 'wp-last-login', 0);
    }

    register_activation_hook(__FILE__, 'pdi_paywall_activate');

}

add_action('plugins_loaded', 'pdi_paywall_activate');
