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
            if (!empty($plan)) {
                $plans[$i] = pdi_paywall_array_plan($i, $plan);
            }
        }

        return $plans;
    }
}

function pdi_paywall_format_money($value)
{
    return number_format(str_replace(',', '.', str_replace('.', '', $value)), 2);
}

function pdi_paywall_array_plan($i, $plan)
{
    $price = get_option('_pdi_paywall_plan_price_' . $i);
    return [
        'customer_key' => get_option('_pdi_paywall_payment_client_id'),
        'reason' => $plan,
        'description' => get_option('_pdi_paywall_plan_description_' . $i),
        'auto_recurring' => [
            'repetitions' => (int)get_option('_pdi_paywall_plan_repetitions_' . $i),
            'frequency' => (int)get_option('_pdi_paywall_plan_frequency_' . $i),
            'frequency_type' => get_option('_pdi_paywall_plan_frequency_type_' . $i),
            'billing_day_proportional' => (bool)get_option('_pdi_paywall_plan_billing_day_proportional_' . $i),
            'billing_day' => (int)get_option('_pdi_paywall_plan_billing_day_' . $i),
            'transaction_amount' => (float)pdi_paywall_format_money($price),
            'free_trial' => [
                'frequency' => (int)get_option('_pdi_paywall_plan_free_trial_frequency_' . $i),
                'frequency_type' => get_option('_pdi_paywall_plan_free_trial_frequency_type' . $i)
            ],
            'currency_id' => 'BRL'
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
                $plans[get_option('_pdi_paywall_plan_id_' . $i)] = pdi_paywall_array_plan($i, $plan);
            }
        }

        return $plans;
    }
}

if (!function_exists('pdi_paywall_get_plans_period')) {
    function pdi_paywall_get_plans_period()
    {
        $periods = [
            '' => '',
            'days' => 'Dias',
            'months' => 'Mensal',
            'years' => 'Anual',
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
