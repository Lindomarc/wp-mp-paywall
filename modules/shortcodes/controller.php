<?php

if (!function_exists('pdi_paywall_shortcodes_scripts')) {
    function pdi_paywall_shortcodes_scripts()
    {
        echo '<link href="' . plugins_url('/css/pdi_paywall_card.css', __FILE__) . '" rel="stylesheet" />';
        echo '<link href="' . plugins_url('/css/pdi_paywall_shortcodes.css', __FILE__) . '" rel="stylesheet" />';
        echo '<script src="' . plugins_url('/js/pdi_paywall_card.js', __FILE__) . '"></script>';
        echo '<script src="' . plugins_url('/js/pdi.jquery.mask.min.js', __FILE__) . '"></script>';

        if (!empty(get_option('_pdi_paywall_payment_sandbox'))) {
            echo '<script src="https://sandbox.boletobancario.com/boletofacil/wro/direct-checkout.min.js"></script>';
        } else {
            echo '<script src="https://www.boletobancario.com/boletofacil/wro/direct-checkout.min.js"></script>';
        }
    }
    add_action('wp_head', 'pdi_paywall_shortcodes_scripts');
}

add_shortcode('pdi_paywall_login', 'pdi_paywall_login_shortcode');
function pdi_paywall_login_shortcode($atts, $content = null)
{
    if (!empty(get_option('_pdi_paywall_page_profile'))) {
        $page_link = get_page_link(get_option('_pdi_paywall_page_profile'));
    } else if (!empty(get_option('_pdi_paywall_page_plans'))) {
        $page_link = get_page_link(get_option('_pdi_paywall_page_plans'));
    }

    $content .= '</div><div class="pdi-paywall-form">';

    if (isset($_GET['login']) && $_GET['login'] == 'failed') {
        $content .= '<div class="pdi-paywall-message error"><p>Email ou senha incorretos.</p></div>';
    }

    $args = array(
        'echo' => false,
        'redirect' => $page_link,
    );
    $content .= wp_login_form($args);
    $content .= '</div>';

    return $content;
}

add_shortcode('pdi_paywall_plans', 'pdi_paywall_plans_shortcode');
function pdi_paywall_plans_shortcode($atts, $content = null)
{
    if (isset($_REQUEST['plan'])) {
        return pdi_paywall_register_shortcode($atts);
    }

    $page_register = get_page_link(get_option('_pdi_paywall_page_register'));

    $plans = pdi_paywall_get_plans();

    ob_start();
    require_once(PDI_PAYWALL_MODULES_PATH . 'shortcodes/views/plans.php');
    $content .= ob_get_clean();

    return $content;
}

add_shortcode('pdi_paywall_register', 'pdi_paywall_register_shortcode');
function pdi_paywall_register_shortcode($atts, $content = null)
{
    if (!empty($_POST)) {
        pdi_paywall_register_new_user($_POST);
        $redirect = true;
    }

    $a = shortcode_atts(array(
        'plan' => '',
    ), $atts);

    if (!empty($a['plan'])) {
        $plan = $a['plan'];
    } else if (isset($_GET['plan'])) {
        $plan = $_GET['plan'];
    }

    $users_can_register = get_option('users_can_register');

    if (empty($plan) && $users_can_register === '0') {
        $content .= '<

p>Por favor, <a href="' . get_page_link(get_option('_pdi_paywall_page_plans')) . '">vá para a página de Planos</a> para escolher um, e poder se registrar.</p>';
        return $content;
    }

    if ($users_can_register === '1' && !empty(get_option('_pdi_paywall_page_profile'))) {
        $redirect_to = get_page_link(get_option('_pdi_paywall_page_profile'));
    } else if (!empty(get_option('_pdi_paywall_page_confirmation'))) {
        $redirect_to = get_page_link(get_option('_pdi_paywall_page_confirmation'));
    }

    $userdata = get_userdata(get_current_user_id());
    if (!empty($userdata)) {
        $email = $userdata->user_email;
        $username = $userdata->user_login;
        $first = $userdata->first_name;
        $last = $userdata->last_name;
    } else {
        $email = pdi_paywall_old_form_value('email_address', false);
        $username = pdi_paywall_old_form_value('username', false);
        $first = pdi_paywall_old_form_value('first_name', false);
        $last = pdi_paywall_old_form_value('last_name', false);
    }

    $plans = pdi_paywall_get_plans();
    $plan = $plans[base64_decode($plan)];

    $public_token = get_option('_pdi_paywall_payment_public_token');
    $is_sandbox = get_option('_pdi_paywall_payment_sandbox');

    ob_start();
    require_once(PDI_PAYWALL_MODULES_PATH . 'shortcodes/views/register.php');
    $content .= ob_get_clean();

    return $content;
}

add_shortcode('pdi_paywall_profile', 'pdi_paywall_profile_shortcode');
function pdi_paywall_profile_shortcode($atts, $content = null)
{
    if (!is_user_logged_in()) {
        return pdi_paywall_login_shortcode(array());
    }

    $user = wp_get_current_user();

    $subscriber_id = get_user_meta($user->ID, '_pdi_paywall_subscriber_id', true);
    $document = get_user_meta($user->ID, '_pdi_paywall_document', true);

    if (!empty($subscriber_id)) {
        $response = pdi_paywall_api_get('subscribers/' . $subscriber_id);
        if (!empty($response)) {
            $subscriber = json_decode($response);
        }
    }

    ob_start();
    require_once(PDI_PAYWALL_MODULES_PATH . 'shortcodes/views/profile.php');
    $content .= ob_get_clean();

    return $content;
}

function pdi_paywall_register_new_user($data)
{
    if ($data['password'] !== $data['confirm_password']) {
        return new WP_Error('password_reset_mismatch', __('The passwords do not match.'));
    }

    $plans = pdi_paywall_get_plans_by_id();
    $plan = $plans[$data['plan']];

    $userdata = [
        'user_pass'             => trim($data['password']),
        'user_login'            => trim($data['username']),
        'user_email'            => trim($data['email_address']),
        'display_name'          => trim($data['first_name']) . ' ' . trim($data['last_name']),
        'nickname'              => trim($data['username']),
        'first_name'            => trim($data['first_name']),
        'last_name'             => trim($data['last_name']),
        'user_registered'       => date('Y-m-d H:i:s'),
        'show_admin_bar_front'  => 'false',
        'role'                  => $plan['amount'] > 0 ? 'subscriber' : 'reader',
    ];

    $user_id = wp_insert_user($userdata);

    if (!is_wp_error($user_id)) {
        add_user_meta($user_id, '_pdi_paywall_plan_id', $data['plan']);
        add_user_meta($user_id, '_pdi_paywall_document', trim($data['document']));

        $user = [
            'plan_id' => $data['plan'],
            'name' => trim($data['first_name']) . ' ' . trim($data['last_name']),
            'document' => trim($data['document']),
            'email' => trim($data['email_address']),
            'address_city' => trim($data['city']),
            'address_number' => trim($data['number']),
            'address_postCode' => trim($data['post_code']),
            'address_state' => trim($data['state']),
            'address_street' => trim($data['address']),
            'address_complement' => trim($data['complement']),
            'address_neighborhood' => trim($data['neighborhood']),
            'phone' => '',
            'birth_date' => null,
            'secondary_email' => '',
            'due_day' => date('d'),
            'credit_card_hash' => trim($data['card_hash']),
        ];

        $response = pdi_paywall_api_post('subscribers', $user);
        if (!empty($response)) {
            $subscriber_res = json_decode($response);

            add_user_meta($user_id, '_pdi_paywall_subscriber_id', $subscriber_res->id);

            pdi_paywall_subscription_success($user['email']);
        }
    }
}
