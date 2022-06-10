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
            $links['settings'] = sprintf('<a href="%s">%s</a>', admin_url('options-general.php?page=pdi-paywall'), __('Settings'));
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
        $plans = [];
        for ($i = 1; $i <= PDI_PAYWALL_PLAN_LIMIT; $i++) {
            $plan = get_option('_pdi_paywall_plan_name_' . $i);
            $item = pdi_paywall_array_plan($i, $plan);
            if (isset($item['extern_plan_id']) && !!$item['extern_plan_id']) {

                $plans[$item['extern_plan_id']] = $item;
            } else {

                $plans[$i] = $item;
            }
        }
        return $plans;
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
    $price = get_option('_pdi_paywall_plan_price_' . $i);
    return [
        'customer_key' => get_option('_pdi_paywall_payment_pdi_key'),
        'reason' => $reason,
        //'description' => get_option('_pdi_paywall_plan_description_' . $i),
        'auto_recurring' => [
            'repetitions' => (int)get_option('_pdi_paywall_plan_repetitions_' . $i),
            'frequency_type' => get_option('_pdi_paywall_plan_frequency_type_' . $i),
            'transaction_amount' => pdi_paywall_number_format_us($price),
            'free_trial' => [
                'frequency' => (int)get_option('_pdi_paywall_plan_free_trial_frequency_' . $i),
                'frequency_type' => get_option('_pdi_paywall_plan_free_trial_frequency_type' . $i)
            ]
        ],
        'free_trial' => (bool)get_option('_pdi_paywall_plan_free_trial_' . $i),
        'active' => (bool)get_option('_pdi_paywall_plan_active_' . $i),
        'back_url' => get_option('_pdi_paywall_plan_back_url_' . $i),
        'plan_id' => get_option('_pdi_paywall_plan_id_' . $i),
        'extern_plan_id' => get_option('_pdi_paywall_plan_extern_plan_id_' . $i),
    ];

}

if (!function_exists('pdi_paywall_get_plans_by_id')) {
    function pdi_paywall_get_plans_by_id()
    {
        $plans = [];

        for ($i = 1; $i <= PDI_PAYWALL_PLAN_LIMIT; $i++) {
            $plan = get_option('_pdi_paywall_plan_name_' . $i);
            if (!empty($plan)) {
                $extern_plan_id = get_option('_pdi_paywall_plan_extern_plan_id_' . $i);
                if ($extern_plan_id) {
                    $plans[$extern_plan_id] = pdi_paywall_array_plan($i, $plan);
                }
            }
        }

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

        $restrictions[0] = get_option('_pdi_paywall_restrictions_content');
        $restrictions[1] = get_option('_pdi_paywall_restrictions_content_2');
        $restrictions[2] = get_option('_pdi_paywall_restrictions_content_3');
        $restrictions[3] = get_option('_pdi_paywall_restrictions_content_4');
        $restrictions[4] = get_option('_pdi_paywall_restrictions_content_5');

        return $restrictions;
    }
}

if (!function_exists('pdi_paywall_get_free_restrictions')) {
    function pdi_paywall_get_free_restrictions()
    {
        $restrictions = [];

        $restrictions[0] = get_option('_pdi_paywall_restrictions_free_content');
        $restrictions[1] = get_option('_pdi_paywall_restrictions_free_content_2');
        $restrictions[2] = get_option('_pdi_paywall_restrictions_free_content_3');
        $restrictions[3] = get_option('_pdi_paywall_restrictions_free_content_4');
        $restrictions[4] = get_option('_pdi_paywall_restrictions_free_content_5');

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
        'identification' => json_decode(str_replace('\"','"',$data['identification'])),

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

function pdi_fetch_curl_new_wp_user()
{
    $userdata = [];
     if ($_POST) {
        $data = $_POST;
         $plan_data =  str_replace('\"','"',$data['planData']);
         $plan_data =  json_decode(str_replace('\\"','\\\"',$plan_data),true);

        $userdata = [
            'user_pass' => trim($data['password']),
            'user_login' => trim($data['username']),
            'user_email' => trim($data['user_email']),
            'display_name' => trim($data['first_name']) . ' ' . trim($data['last_name']),
            'nickname' => trim($data['username']),
            'first_name' => trim($data['first_name']),
            'last_name' => trim($data['last_name']),
            'user_registered' => date('Y-m-d H:i:s'),
            'show_admin_bar_front' => 'false',
            'role' => $data['amount'] > 0 ? 'subscriber' : 'reader',
            'plan_data' => $plan_data
        ];
    }
    $result = wp_insert_user($userdata);

     if (is_numeric($result)) {
        add_user_meta($result, '_pdi_paywall_plan_id', $plan_data['plan_id']);
        add_user_meta($result, '_pdi_paywall_document', trim($data['document']));
        $_REQUEST['redirec_to']=$plan_data['back_url'];
        $data['user_id'] = $result;
        return pdi_post_curl_new_subscriber($data);
    } else {
        $return = [
            'status' => 400,
            'data' => $result
        ];
    }
    return $return;

}

function pdi_fetch_curl_callback_null()
{
    return [];
}

add_action('rest_api_init', function () {
    register_rest_route('pdi-paywall/v1', '/curl', array(
        'headers' => ['Content-Type' => 'application/json'],
        'methods' => 'post,get',
        'callback' => 'pdi_fetch_curl_post',
        'permission_callback' => 'pdi_fetch_curl_callback_null'
    ));
    register_rest_route('pdi-paywall/v1', '/new_wp_user', array(
        'headers' => ['Content-Type' => 'application/json'],
        'body' => $_POST,
        'methods' => 'post,get',
        'callback' => 'pdi_fetch_curl_new_wp_user',
        'permission_callback' => 'pdi_fetch_curl_callback_null'
    ));
});



add_action( 'admin_menu', 'register_newpage' );

function register_newpage(){
    add_menu_page('custom_page', 'custom', 'administrator','custom', 'custompage');
    remove_menu_page('custom');
}
