<?php

// If this file is called directly, abort.
if (!class_exists('WP')) {
	die();
}

define("LRM_PRO_PATH", plugin_dir_path(__FILE__));
define("LRM_PRO_URL", plugin_dir_url(__FILE__));
define("LRM_PRO_BASENAME", plugin_basename(__FILE__));
define("LRM_PRO_VERSION", '2.09');

if ( ! defined("LRM_URL") ) {
    define("LRM_URL", LRM_PRO_URL . 'free/');
    define("LRM_ASSETS_URL", LRM_URL . 'assets/');

    define("LRM_PATH", LRM_PRO_PATH . 'free/');
    define("LRM_BASENAME", plugin_basename(LRM_PATH));
    define("LRM_IN_BUILD_FREE", true);
    require "free/ajax-login-registration-modal-popup.php";

    if (!SHORTINIT) {
        /**
         * The code that runs during plugin deactivation.
         */
        register_deactivation_hook( __FILE__, array( 'LRM_Deactivator', 'deactivate' ) );
    }
}

//define("LRM_FORM_PATH", LRM_PRO_PATH . 'includes/formbuilder/fields/');
define("LRM_DS", '/');

require LRM_PRO_PATH . 'vendor/autoload.php';
require LRM_PRO_PATH . 'class-lrm-pro.php';

// CustomMod->add
update_option( 'lrm_api_manager_activated', 'Activated' );

add_action('plugins_loaded', array('LRM_Pro', 'get'), 12);
