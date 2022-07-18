<?php
if (!function_exists('pdi_paywall_modules_require')) {
    function pdi_paywall_modules_require()
    {
        require_once(PDI_PAYWALL_MODULES_PATH . 'api/client.php');
        require_once(PDI_PAYWALL_MODULES_PATH . 'email/controller.php');
        require_once(PDI_PAYWALL_MODULES_PATH . 'menus/controller.php');
        require_once(PDI_PAYWALL_MODULES_PATH . 'metabox/controller.php');
        require_once(PDI_PAYWALL_MODULES_PATH . 'restriction/controller.php');
        require_once(PDI_PAYWALL_MODULES_PATH . 'roles/controller.php');
        require_once(PDI_PAYWALL_MODULES_PATH . 'settings/controller.php');
        require_once(PDI_PAYWALL_MODULES_PATH . 'shortcodes/controller.php');
    }

    add_action('init', 'pdi_paywall_modules_require');
}

if (!function_exists('pdi_paywall_settings_link')) {
    function pdi_paywall_settings_link($links, $file)
    {
        if ($file == 'pdi-paywall/main.php') {
            $links['settings'] = sprintf('<a href="3%s">%s</a>', admin_url('options-general.php?page=pdi-paywall'), __('Settings'));
        }

        return $links;
    }

    add_filter('plugin_action_links', 'pdi_paywall_settings_link', 10, 2);
}

if (!function_exists('pdi_paywall_old_form_value')) {
    function pdi_paywall_old_form_value($input, $echo = true)
    {
        $value = '';

        if (isset($_POST[$input]) && $_POST[$input]) {
            $value = esc_attr($_POST[$input]);
        }

        if ($echo) {
            echo $value;
        } else {
            return $value;
        }
    }
}

if (!function_exists('pdi_paywall_get_plans')) {
    function pdi_paywall_get_plans()
    {
        $response = json_decode(pdi_paywall_api_get('plans/datatable'), true);

        //for ($i = 1; $i <= PDI_PAYWALL_PLAN_LIMIT; $i++) {
        foreach ($response['data'] as $key => $plan) {
            // $plan = get_option('_pdi_paywall_plan_name_');
            //$item = pdi_paywall_array_plan($key, $plan);
            if (isset($plan['extern_plan_id']) && !!$plan['extern_plan_id']) {

                $plans[$plan['extern_plan_id']] = $plan;
            } else {

                $plans[$plan['id']] = $plan;
            }
        }
        //}

        return $plans;
    }
}

if (!function_exists('pdi_paywall_get_plan')) {
    function pdi_paywall_get_plan($id)
    {
        return json_decode(pdi_paywall_api_get('plans/' . $id), true);
    }
}

function pdi_paywall_number_format_us($value, $tipo = 'us')
{
    if ($value) {
        if ($tipo == 'us') {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } else {
            $value = number_format($value, 2, ',', '.');
        }
    }
    return $value;
}

function pdi_paywall_array_plan($i, $reason)
{
    $plan = $_POST;
    $price = $plan['_pdi_paywall_plan_price_'];

    return [
        'customer_key' => get_option('_pdi_paywall_payment_pdi_key'),
        'reason' => $reason,
        'description' => $plan['_pdi_paywall_plan_description_'],
        'auto_recurring' => [
            'repetitions' => (int)$plan['_pdi_paywall_plan_repetitions_'],
            'frequency_type' => $plan['_pdi_paywall_plan_frequency_type_'],
            'transaction_amount' => pdi_paywall_number_format_us($price),
            'free_trial' => [
                'frequency' => (int)$plan['_pdi_paywall_plan_free_trial_frequency_'],
                'frequency_type' => $plan['_pdi_paywall_plan_free_trial_frequency_type_']
            ]
        ],
        'free_trial' => $plan['_pdi_paywall_plan_free_trial_'] ?? 0,
        'active' => true,
        'back_url' => $plan['_pdi_paywall_plan_back_url_'],
        'plan_id' => $plan['_pdi_paywall_plan_id_'],
        'extern_plan_id' => $plan['_pdi_paywall_plan_extern_plan_id_']
    ];
}

if (!function_exists('pdi_paywall_get_plans_by_id')) {
    function pdi_paywall_get_plans_by_id()
    {
        $plans = [];

        //for ($i = 1; $i <= PDI_PAYWALL_PLAN_LIMIT; $i++) {
        $plan = get_option('_pdi_paywall_plan_name_');

        if (!empty($plan)) {
            $extern_plan_id = get_option('_pdi_paywall_plan_extern_plan_id_');
            if ($extern_plan_id) {
                $plans[$extern_plan_id] = pdi_paywall_array_plan($i, $plan);
            }
        }
//        }

        return $plans;
    }
}

if (!function_exists('pdi_paywall_get_plans_period')) {
    function pdi_paywall_get_plans_period()
    {
        $periods = [
            'days' => 'Dias',
            'months' => 'Mensal',
            'semesters' => 'Semestral',
            'annual' => 'Anual'
        ];

        return $periods;
    }
}

if (!function_exists('pdi_paywall_delete_user')) {
    function pdi_paywall_delete_user($user_id)
    {
        require_once(ABSPATH . 'wp-admin/includes/user.php');

        wp_delete_user($user_id);
    }
}

if (!function_exists('pdi_paywall_get_restrictions')) {
    function pdi_paywall_get_restrictions()
    {
        $restrictions = [];

        $restrictions_content = get_categories();
        foreach ($restrictions_content as $value) {
            if (get_option('_pdi_paywall_restrictions_content_' . $value->term_id)) {
                $restrictions[] = $value->term_id;
            }
        }

        return $restrictions;
    }
}
if (!function_exists('pdi_paywall_get_free_restrictions')) {
    function pdi_paywall_get_free_restrictions()
    {
        $restrictions = [];

        $restrictions_content = get_categories();
        foreach ($restrictions_content as $value) {
            if (get_option('pdi_paywall_get_free_restrictions_' . $value->term_id)) {
                $restrictions[] = $value->term_id;
            }
        }

        return $restrictions;
    }
}


if (!function_exists('pdi_paywall_admin_notice')) {
    function pdi_paywall_admin_notice($message, $status = 'success')
    {
        echo '<div class="notice notice-' . $status . ' is-dismissible\"><p>' . $message . '</p></div>';
    }
}

/*
 * $options = [
 *  path: url
 *  method: POST, GET, PUT
 *  data: array
 * ]
 */
function pdi_fetch_curl_post()
{
    $api_key = get_option('_pdi_paywall_payment_pdi_token');

    if (isset($_POST['data'])) {
        $_POST['data'] = json_decode(str_replace('\"', '"', $_POST['data']), true);
        return pdi_curl([
            'path' => $_POST['path'],
            'bearer_token' => $api_key,
            'method' => $_POST['method'],
            'data' => $_POST['data']
        ]);
    }
    return pdi_curl([
        'path' => isset($_GET['path']) ? $_GET['path'] : '',
        'bearer_token' => $api_key,
        'method' => 'get',
    ]);

}

function pdi_post_curl_new_subscriber($data)
{
    $subscribers = [
        'preapproval_plan_id' => $data['plan']['extern_plan_id'],
        'external_reference' => $data['pdi_paywall_register_nonce'] . '_' . $data['user_id'],
        'first_name' => trim($data['first_name']),
        'last_name' => trim($data['last_name']),
        'document' => trim($data['document']),
        'payer_email' => trim($data['user_email']),
        'pdi_paywall_register_nonce' => trim($data['pdi_paywall_register_nonce']),
        'identification' => json_decode(str_replace('\"', '"', $data['identification'])),

//            'address_city' => trim($data['city']),
//            'address_number' => trim($data['number']),
//            'address_zipcode' => trim($data['zip_code']),
//            'address_state' => trim($data['state']),
//            'address_street' => trim($data['address']),
//            'address_complement' => trim($data['complement']),
//            'address_neighborhood' => trim($data['neighborhood']),
//            'phone' => '',
//            'birth_date' => null,
//            'secondary_email' => '',
//            'due_day' => date('d'),
        'card_token_id' => trim($data['card_token_id']),
    ];

    $response = json_decode(pdi_paywall_api_post('subscribers', $subscribers));

    if (!isset($response->message)) {

        add_user_meta($data['user_id'], '_pdi_paywall_subscriber_id', $response->id);

        pdi_paywall_subscription_success($subscribers['payer_email']);

    }
    return $response;
}

function pdiStatusSubscription()
{
    $userID = $_REQUEST['user_id'];
    $subscriberId = $_REQUEST['subscriber_id'];
    $planId = $_REQUEST['plan_id'];
    $document = $_REQUEST['document'];
    $payerEmail = $_REQUEST['payer_email'];
    $status = $_REQUEST['status'];

    $response = [];
    switch ($status) {
        case 'authorized':
            add_user_meta($userID, '_pdi_paywall_subscriber_id', $subscriberId);
            add_user_meta($userID, '_pdi_paywall_plan_id', $planId);
            add_user_meta($userID, '_pdi_paywall_document', $document);
            $response = pdi_paywall_subscription_success($payerEmail);
            break;
//        case 'paused':
//            update_user_meta($userID, '_pdi_paywall_subscriber_id', $subscriberId);
//            update_user_meta($userID, '_pdi_paywall_plan_id', $planId);
//            update_user_meta($userID, '_pdi_paywall_document', $document);
//            //$response = pdi_paywall_subscription_success($payerEmail);
//            break;
        case 'cancelled':
            delete_user_meta($userID, '_pdi_paywall_subscriber_id');
            delete_user_meta($userID, '_pdi_paywall_plan_id');
            delete_user_meta($userID, '_pdi_paywall_document');
            $response = pdi_paywall_subscription_cancel($payerEmail);
            break;
    }
    header('Content-Type: application/json');
    return json_encode($response, true);
}

function pdi_callback_null()
{

    return [];
}

add_action('rest_api_init', function () {
    register_rest_route('pdi-paywall/v1', '/subscription/status', array(
        'methods' => 'post,get,put',
        'callback' => 'pdiStatusSubscription',
        'body' => $_POST,
        'permission_callback' => 'pdi_callback_null'
    ));
});


function get_before_login_url()
{
    if (!is_user_logged_in() && $_SERVER['REQUEST_URI'] != '/login/'):
        $_REQUEST['referer_url'] = get_the_permalink();
    endif;
}

add_action('wp', 'get_before_login_url');

function json_basic_auth_handler($user)
{
    global $wp_json_basic_auth_error;

    $wp_json_basic_auth_error = null;

    // Don't authenticate twice
    if (!empty($user)) {
        return $user;
    }

    // Check that we're trying to authenticate
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        return $user;
    }

    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    /**
     * In multi-site, wp_authenticate_spam_check filter is run on authentication. This filter calls
     * get_currentuserinfo which in turn calls the determine_current_user filter. This leads to infinite
     * recursion and a stack overflow unless the current function is removed from the determine_current_user
     * filter during authentication.
     */
    remove_filter('determine_current_user', 'json_basic_auth_handler', 20);

    $user = wp_authenticate($username, $password);

    add_filter('determine_current_user', 'json_basic_auth_handler', 20);

    if (is_wp_error($user)) {
        $wp_json_basic_auth_error = $user;
        return null;
    }

    $wp_json_basic_auth_error = true;

    return $user->ID;
}

add_filter('determine_current_user', 'json_basic_auth_handler', 20);

function json_basic_auth_error($error)
{
    // Passthrough other errors
    if (!empty($error)) {
        return $error;
    }

    global $wp_json_basic_auth_error;

    return $wp_json_basic_auth_error;
}

add_filter('rest_authentication_errors', 'json_basic_auth_error');


/**
 * Hide the WordPress Toolbar from Subscribers.
 *
 * @since 2.3
 */
function _pdi_paywall_hide_toolbar()
{
    global $current_user;

    $hide = !is_user_logged_in();
    if ((in_array('reader', (array)$current_user->roles)
        || in_array('subscriber', (array)$current_user->roles)
    )
    ) {
        $hide = get_option('_pdi_paywall_hide_toolbar');
    }
    show_admin_bar(!$hide);
}

add_action('init', '_pdi_paywall_hide_toolbar', 9);

/**
 * Block Subscibers from accessing the WordPress Dashboard.
 *
 * @since 2.3.4
 */
function _pdi_paywall_block_dashboard_redirect()
{
    if (_pdi_paywall_block_dashboard()) {
        $redirect_to = get_page_link(get_option('_pdi_paywall_page_profile'));
        wp_redirect($redirect_to);
        exit;
    }
}

add_action('admin_init', '_pdi_paywall_block_dashboard_redirect', 9);


/**
 * Is the current user blocked from the dashboard
 * per the advanced setting.
 *
 * @since 2.3
 */
function _pdi_paywall_block_dashboard()
{
    global $current_user, $pagenow;

    $block_dashboard = get_option('_pdi_paywall_block_dashboard');

    if (
        !wp_doing_ajax()
        && 'admin-post.php' !== $pagenow
        && !empty($block_dashboard)
        && !current_user_can('manage_options')
        && !current_user_can('edit_users')
        && !current_user_can('edit_posts')
        && (
            in_array('subscriber', (array)$current_user->roles)
            || in_array('reader', (array)$current_user->roles)
        )
    ) {
        $block = true;
    } else {
        $block = false;
    }
    $block = apply_filters('_pdi_paywall_block_dashboard', $block);

    /**
     * Allow filtering whether to block Dashboard access.
     *
     * @param bool $block Whether to block Dashboard access.
     */
    return apply_filters('_pdi_paywall_block_dashboard', $block);


    function prefix_pdi_paywall_visibility($bool)
    {
        return current_user_can('manage_options'); // Only for Admins
    }

    add_filter('pdi_paywall_current_user_can', 'prefix_pdi_paywall_visibility');
    return true;
}

function admin_bar_remove_logo()
{
    global $wp_admin_bar;
    global $current_user;
    if (in_array('reader', (array)$current_user->roles) || in_array('subscriber', (array)$current_user->roles)) {
        $wp_admin_bar->remove_menu('wp-logo');
        $wp_admin_bar->remove_menu('site-name');
        $wp_admin_bar->remove_menu('search');
    }
}

add_action('wp_before_admin_bar_render', 'admin_bar_remove_logo', 0);


function pdi_paywall_const_keys()
{

    echo '
        <script>
            const PDI_PAYWALL_PAYMENT_PDI_TOKEN = "' . get_option('_pdi_paywall_payment_pdi_token') . '"; 
            const PDI_PAYWALL_PAYMENT_PDI_KEY = "' . get_option('_pdi_paywall_payment_pdi_key') . '"; 
        </script>
    ';
    if (get_option('_pdi_paywall_payment_sandbox')) :
        echo '
        <script>
                const PDI_PAYWALL_PAYMENT_ACCESS_TOKEN = "' . get_option('_pdi_paywall_payment_access_token') . '";
                const PDI_PAYWALL_PAYMENT_PUBLIC_KEY = "' . get_option('_pdi_paywall_payment_public_key_test') . '";
        </script>';
    else:
        echo '
        <script>
            const PDI_PAYWALL_PAYMENT_ACCESS_TOKEN = "' . get_option('_pdi_paywall_payment_access_token_test') . '";
            const PDI_PAYWALL_PAYMENT_PUBLIC_KEY = "' . get_option('_pdi_paywall_payment_public_key') . '";
        </script>';
    endif;

    echo '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>';
    echo '<script src="' . PDI_PAYWALL_URL . 'js/pdi_paywall_custom.js?v=' . PDI_PAIWALL_VERSION . '"></script>';

}
