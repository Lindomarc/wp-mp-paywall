<?php
//			<input id="hide_toolbar" name="hide_toolbar" type="checkbox" value="yes" <?php checked( $hide_toolbar, 'yes' );  /> <label for="hide_toolbar"> esc_html_e('Hide the Toolbar from all users with the Subscriber role.', 'paid-memberships-pro' );

function pdi_paywall_settings_init()
{
    add_settings_section(
        '_pdi_paywall_read_section',
        'Configurações de leitura',
        'pdi_paywall_section_callback',
        '_pdi_paywall_general'
    );
    $fields = array(
        array(
            'uid' => '_pdi_paywall_block_dashboard',
            'label' => 'Painel do WordPress',
            'section' => '_pdi_paywall_read_section',
            'type' => 'checkbox',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Bloquear painel de controle',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_hide_toolbar',
            'label' => 'Barra de ferramentas do WordPress',
            'section' => '_pdi_paywall_read_section',
            'type' => 'checkbox',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Ocultar a barra de ferramentas',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_page_limit_content',
            'label' => 'Limite de caracaters antes do "ler mais"',
            'section' => '_pdi_paywall_read_section',
            'type' => 'number',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Limite de texto visivel em posts',
            'default' => 200
        ),
        array(
            'uid' => '_pdi_paywall_read_limit_guest',
            'label' => 'Limite de leitura (Anônimo)',
            'section' => '_pdi_paywall_read_section',
            'type' => 'number',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Limite permitido para leituras de conteúdo',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_read_limit_registered',
            'label' => 'Limite de leitura (Leitor)',
            'section' => '_pdi_paywall_read_section',
            'type' => 'number',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Limite permitido para leituras de conteúdo',
            'default' => null
        ),
    );
    foreach ($fields as $field) {
        pdi_paywall_register_setting('_pdi_paywall_general', $field['uid']);
        add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_general', $field['section'], $field);
    }

    add_settings_section('_pdi_paywall_account_section', 'Configurações de conta', 'pdi_paywall_section_callback', '_pdi_paywall_general');
    $fields = array(
        array(
            'uid' => '_pdi_paywall_account_delete',
            'label' => 'Usuário excluir conta',
            'section' => '_pdi_paywall_account_section',
            'type' => 'checkbox',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Permite o usuário excluir sua conta',
            'default' => null
        ),
    );
    foreach ($fields as $field) {
        pdi_paywall_register_setting('_pdi_paywall_general', $field['uid']);
        add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_general', $field['section'], $field);
    }

    add_settings_section('_pdi_paywall_pages_section', 'Configurações de páginas', 'pdi_paywall_section_callback', '_pdi_paywall_general');
    $fields = array(
        array(
            'uid' => '_pdi_paywall_page_login',
            'label' => 'Página para Login',
            'section' => '_pdi_paywall_pages_section',
            'type' => 'pages',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Adicione este shortcode na sua página de Login: [pdi_paywall_login]. Está página não pode ser restrita.',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_page_register',
            'label' => 'Página para Registro',
            'section' => '_pdi_paywall_pages_section',
            'type' => 'pages',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Adicione este shortcode na sua página de Registro: [pdi_paywall_register]. Está página não pode ser restrita.',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_page_confirmation',
            'label' => 'Página para Confirmação',
            'section' => '_pdi_paywall_pages_section',
            'type' => 'pages',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Página de confirmação conta. Está página não pode ser restrita.',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_page_profile',
            'label' => 'Página para Perfil',
            'section' => '_pdi_paywall_pages_section',
            'type' => 'pages',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Adicione este shortcode na sua página de Perfil: [pdi_paywall_profile]. Está página não pode ser restrita.',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_page_plans',
            'label' => 'Página para Planos',
            'section' => '_pdi_paywall_pages_section',
            'type' => 'pages',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Adicione este shortcode na sua página de Planos: [pdi_paywall_plans]. Está página não pode ser restrita.',
            'default' => null
        ),
    );
    foreach ($fields as $field) {
        pdi_paywall_register_setting('_pdi_paywall_general', $field['uid']);
        add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_general', $field['section'], $field);
    }


    $list_categories = get_categories();
    require_once('restrictions.php');

    require_once('restrictions_free.php');


    require_once('plans.php');

    /**
     * Payment settings
     */
    add_settings_section('_pdi_paywall_payments_section', 'Configurações de pagamento', 'pdi_paywall_section_callback', '_pdi_paywall_payments');
    $fields = array(
        array(
            'uid' => '_pdi_paywall_payment_pdi_token',
            'label' => 'PDI Token',
            'section' => '_pdi_paywall_payments_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Token de acesso ao API',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_payment_sandbox',
            'label' => 'Sandbox',
            'section' => '_pdi_paywall_payments_section',
            'type' => 'checkbox',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Utilizando Ambiente de teste',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_payment_pdi_key',
            'label' => 'PDI KEY',
            'section' => '_pdi_paywall_payments_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Chave do cliente PDI',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_payment_public_key',
            'label' => 'Public Key',
            'section' => '_pdi_paywall_payments_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Public Key Mercado Pago',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_payment_access_token',
            'label' => 'Access Token',
            'section' => '_pdi_paywall_payments_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Token privado da Mercado Pago',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_payment_public_key_test',
            'label' => 'Public Key Test',
            'section' => '_pdi_paywall_payments_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Public Key Mercado Pago',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_payment_access_token_test',
            'label' => 'Access Token Test',
            'section' => '_pdi_paywall_payments_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Access Token	 de teste Mercado Pago',
            'default' => null
        ),
    );
    foreach ($fields as $field) {
        pdi_paywall_register_setting('_pdi_paywall_payments', $field['uid']);
        add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_payments', $field['section'], $field);
    }

    /**
     * Email settings
     */
    add_settings_section('_pdi_paywall_email_section', 'Configurações de email', 'pdi_paywall_section_callback', '_pdi_paywall_email');
    $fields = array(
        array(
            'uid' => '_pdi_paywall_smtp_host',
            'label' => 'SMTP Host',
            'section' => '_pdi_paywall_email_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'SMTP Hostname',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_smtp_auth',
            'label' => 'SMTP Auth',
            'section' => '_pdi_paywall_email_section',
            'type' => 'checkbox',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'SMTP Auth',
            'default' => true
        ),
        array(
            'uid' => '_pdi_paywall_smtp_port',
            'label' => 'SMTP Port',
            'section' => '_pdi_paywall_email_section',
            'type' => 'number',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'SMTP Port',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_smtp_secure',
            'label' => 'SMTP Secure',
            'section' => '_pdi_paywall_email_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'SMTP Secure',
            'default' => 'ssl'
        ),
        array(
            'uid' => '_pdi_paywall_smtp_username',
            'label' => 'SMTP Username',
            'section' => '_pdi_paywall_email_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'SMTP Username',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_smtp_password',
            'label' => 'SMTP Password',
            'section' => '_pdi_paywall_email_section',
            'type' => 'password',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'SMTP Password',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_smtp_from',
            'label' => 'SMTP From',
            'section' => '_pdi_paywall_email_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'SMTP From',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_smtp_fromname',
            'label' => 'SMTP From Name',
            'section' => '_pdi_paywall_email_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'SMTP From Name',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_smtp_message_success',
            'label' => 'Mensagem (Inscrição)',
            'section' => '_pdi_paywall_email_section',
            'type' => 'textarea',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Mensagem para o e-mail de sucesso.',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_smtp_message_cancel',
            'label' => 'Mensagem (Cancelamento)',
            'section' => '_pdi_paywall_email_section',
            'type' => 'textarea',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => 'Mensagem para o e-mail de cancelamento.',
            'default' => null
        ),
    );
    foreach ($fields as $field) {
        pdi_paywall_register_setting('_pdi_paywall_email', $field['uid']);
        add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_email', $field['section'], $field);
    }
}

add_action('admin_init', 'pdi_paywall_settings_init');

function pdi_paywall_section_callback()
{
}

function pdi_paywall_register_setting($tab, $name)
{
    register_setting(
        $tab,
        $name,
    );
}

function pdi_paywall_field_callback($arguments)
{
    $value = get_option($arguments['uid']);
    if (!$value) {
        $value = $arguments['default'];
    }

    switch ($arguments['type']) {
        case 'hidden':
            printf('<input name="%1$s" id="%1$s" type="%2$s"  value="%3$s" />', $arguments['uid'], $arguments['type'], $value);
            break;
        case 'text':
        case 'password':
            printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
            break;
        case 'number':
            printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" class="small-text" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
            break;
        case 'price':
            printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" step="0.01" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
            break;
        case 'checkbox':
            printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="1" ' . ($value ? 'checked' : null) . ' />', $arguments['uid'], $arguments['type'], $arguments['placeholder']);
            break;
        case 'textarea':
            printf('<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value);
            break;
        case 'select':
            if (!empty($arguments['options']) && is_array($arguments['options'])) {
                $options_markup = '';
                foreach ($arguments['options'] as $key => $label) {
                    $options_markup .= sprintf('<option value="%s" %s>%s</option>', $key, selected($value, $key, false), $label);
                }
                printf('<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup);
            }
            break;
        case 'pages':
            print wp_dropdown_pages([
                'name' => $arguments['uid'],
                'echo' => 0,
                'show_option_none' => __('&mdash; Select &mdash;'),
                'selected' => $value,
            ]);
            break;
        case 'categories':
            $options = get_categories();
            if (!empty($options) && is_array($options)) {
                $options_markup = '<option value="">' . __('&mdash; Select &mdash;') . '</option>';
                foreach ($options as $option) {
                    $options_markup .= sprintf('<option value="%s" %s>%s</option>', $option->term_id, selected($value, $option->term_id, false), $option->name);
                }
                printf('<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup);
            }
            break;
        case 'select2':
            $options = get_categories();
            if (!empty($options) && is_array($options)) {
                $options_markup = '';
                foreach ($options as $option) {
                    if (is_array($value)) {
                        $selected = in_array($option->term_id, $value) ? 'selected' : '';
                    } else {
                        $selected = selected($value, $option->term_id, false);
                    }
                    $options_markup .= sprintf('<option value="%s" %s>%s</option>', $option->term_id, $selected, $option->name);
                }
                printf('<select name="%1$s" id="%1$s" class="form-control %1$s"   multiple="multiple" >%2$s</select>', $arguments['uid'], $options_markup);

            }
            break;
    }

    if ($helper = $arguments['helper']) {
        printf('<span class="helper"> %s</span>', $helper);
    }

    if ($supplimental = $arguments['supplemental']) {
        printf('<p class="description">%s</p>', $supplimental);
    }
}

function pdi_paywall_update_options($option_name)
{
    $api_key = get_option('_pdi_paywall_payment_pdi_token');
    if (!empty($api_key)) {
        $data = [];

        if ($option_name = '_pdi_paywall_payment_sandbox') {
            $option = get_option($option_name);
            $data['sandbox'] = $option;
        }

        if ($option_name = '_pdi_paywall_payment_pdi_key') {
            $option = get_option($option_name);
            $data['client_id'] = $option;
        }

        if ($option_name = '_pdi_paywall_payment_client_secret') {
            $option = get_option($option_name);
            $data['client_secret'] = $option;
        }

        if ($option_name = '_pdi_paywall_payment_public_key') {
            $option = get_option($option_name);
            $data['public_token'] = $option;
        }

        if ($option_name = '_pdi_paywall_payment_access_token') {
            $option = get_option($option_name);
            $data['private_token'] = $option;
        }


        if ($option_name = '_pdi_paywall_payment_public_key_test') {
            $option = get_option($option_name);
            $data['public_token_test'] = $option;
        }

        if ($option_name = '_pdi_paywall_payment_access_token_test') {
            $option = get_option($option_name);
            $data['private_token_test'] = $option;
        }

        if (!empty($data)) {
            //TODO: $response = pdi_paywall_api_post('settings', $data);
        }
    }
}

add_action('update_option__pdi_paywall_payment_sandbox', 'pdi_paywall_update_options');
add_action('update_option__pdi_paywall_payment_pdi_key', 'pdi_paywall_update_options');
add_action('update_option__pdi_paywall_payment_client_secret', 'pdi_paywall_update_options');
add_action('update_option__pdi_paywall_payment_public_key', 'pdi_paywall_update_options');
add_action('update_option__pdi_paywall_payment_access_token', 'pdi_paywall_update_options');
add_action('update_option__pdi_paywall_payment_public_key_test', 'pdi_paywall_update_options');
add_action('update_option__pdi_paywall_payment_access_token_test', 'pdi_paywall_update_options');

function pdi_paywall_update_plans()
{
    $api_key = get_option('_pdi_paywall_payment_pdi_token');

    if (!empty($api_key)) {
        $plans = pdi_paywall_get_plans();
        $response = [];
        if (!empty($plans)) {
//            for ($i = 1; $i <= PDI_PAYWALL_PLAN_LIMIT; $i++) {
            $i = 0;
            foreach ($plans as $key => $plan) {
                $i++;

                // enviar somente se valor positivo
                if ($plan['auto_recurring']['transaction_amount']) {
                    if (isset($plan['extern_plan_id']) && $plan['extern_plan_id']) {
                        $response = pdi_paywall_api_put('plans/' . $plan['plan_id'], $plan);
                    } else {
                        if (isset($plan['reason']) && !!$plan['reason']) {
                            $response = pdi_paywall_api_post('plans', $plan);
                            if (!empty($response)) {
                                $plan_res = json_decode($response, true);
                                if (isset($plan_res['data']['id'])) {
                                    $id_save = '_pdi_paywall_plan_id_' . $i;
                                    update_option($id_save, $plan_res['data']['id']);
                                    $extern_plan_id_save = '_pdi_paywall_plan_extern_plan_id_' . $i;
                                    update_option($extern_plan_id_save, $plan_res['data']['extern_plan_id']);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }
}

for ($i = 1; $i <= PDI_PAYWALL_PLAN_LIMIT; $i++) {
    add_action('update_option__pdi_paywall_plan_name_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_price_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_description_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_repetitions_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_frequency_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_frequency_type_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_billing_day_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_free_trial_frequency_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_free_trial_frequency_type_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_free_trial_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_active_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_back_url_' . $i, 'pdi_paywall_update_plans');
    //add_action('update_option__pdi_paywall_plan_extern_plan_id_' . $i, 'pdi_paywall_update_plans');
    add_action('update_option__pdi_paywall_plan_id_' . $i, 'pdi_paywall_update_plans');
}

//add_action('update_option__pdi_paywall_restrictions_content', 'pdi_paywall_set_list_restrition');
//add_action('get_option__pdi_paywall_restrictions_content', 'pdi_paywall_get_list_restrition');

function pdi_paywall_set_list_restrition($value)
{
    update_option('_pdi_paywall_restrictions_content', []);
    $restrictions = get_option('_pdi_paywall_restrictions_content');
    if (isset($_POST['_pdi_paywall_restrictions_content'])) {
        $i = array_push($restrictions, $value);
        update_option('_pdi_paywall_restrictions_content', $restrictions);
    }
}


add_action('admin_enqueue_scripts', 'rudr_select2_enqueue');
function rudr_select2_enqueue()
{

    wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');
    wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery'));


    // please create also an empty JS file in your theme directory and include it too
    wp_enqueue_script('custom', PDI_PAYWALL_URL . '/js/custom.js', array('jquery', 'select2'));

}
