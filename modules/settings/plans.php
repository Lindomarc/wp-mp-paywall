<?php
/**
 * Plans
 */


$plan_action = $_GET['plan_action'] ?? '';
$plan_id = $_GET['plan_id'] ?? '';


$plan_item = json_decode(pdi_paywall_api_get('plans/' . $plan_id));
//var_dump($plan_id);
$plan_item = $plan_item->data;

$plan_amount = '0,00';
if (isset($plan_item->pdi->amount)) {
    $plan_amount = pdi_paywall_number_format_us($plan_item->pdi->amount, 'br');
}

$free_time = [];
if (isset($plan_item->pdi->free_time)) {
    $free_time = unserialize($plan_item->pdi->free_time);
}

$plan_title = $plan_id ?? '';
//for ($i = 1; $i <= PDI_PAYWALL_PLAN_LIMIT; $i++) {

add_settings_section('_pdi_paywall_plans_section', 'Plano ' . $plan_title, 'pdi_paywall_section_callback', '_pdi_paywall_plans');
$fields = array(
    array(
        'uid' => '_pdi_paywall_plan_name_',
        'label' => 'Nome',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'text',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $plan_item->mp->reason ?? ''
    ),
    array(
        'uid' => '_pdi_paywall_plan_description_',
        'label' => 'Descrição',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'textarea',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $plan_item->pdi->description ?? ''
    ),

//            array(
//                'uid' => '_pdi_paywall_plan_billing_day_proportional_'.$i,
//                'label' => 'Valor proporcional ao dia',
//                'section' => '_pdi_paywall_plans_'.$i.'_section',
//                'type' => 'checkbox',
//                'options' => false,
//                'placeholder' => null,
//                'helper' => null,
//                'supplemental' => '',
//                'default' => 0
//            ),
    array(
        'uid' => '_pdi_paywall_plan_repetitions_',
        'label' => 'Repetir',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'number',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $plan_item->mp->auto_recurring->repetitions ?? ''
    ),

//        array(
//            'uid' => '_pdi_paywall_plan_frequency_type_',
//            'label' => 'Tipo de Frequência',
//            'section' => '_pdi_paywall_plans_section',
//            'type' => 'number',
//            'options' => false,
//            'placeholder' => null,
//            'helper' => null,
//            'supplemental' => '',
//            'default' => $plan_item->pdi->months??''
//        ),
    array(
        'uid' => '_pdi_paywall_plan_price_',
        'label' => 'Valor da transação',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'price',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $plan_amount ?? ''
    ),
    array(
        'uid' => '_pdi_paywall_plan_frequency_type_',
        'label' => 'Tipo de frequência',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'select',
        'options' => pdi_paywall_get_plans_period(),
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $plan_item->pdi->period ?? 'mounts'
    ),

    array(
        'uid' => '_pdi_paywall_plan_free_trial_',
        'label' => 'Amostra Grátis',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'checkbox',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => !!$free_time ?? 0
    ),
    array(
        'uid' => '_pdi_paywall_plan_free_trial_frequency_',
        'label' => 'Frequência (Amostra Grátis)',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'number',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $free_time['frequency'] ?? ''
    ),
    array(
        'uid' => '_pdi_paywall_plan_free_trial_frequency_type_',
        'label' => 'Tipo de frequência (Amostra Grátis)',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'select',
        'options' => pdi_paywall_get_plans_period(),
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $free_time['frequency_type'] ?? 'days'
    ),
    array(
        'uid' => '_pdi_paywall_plan_back_url_',
        'label' => 'URL de retorno',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'text',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $plan_item->mp->back_url ?? ''
    ),
    array(
        'uid' => '_pdi_paywall_plan_extern_plan_id_',
        'label' => '',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'text',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $plan_item->pdi->extern_plan_id ?? ''
    ),
    array(
        'uid' => '_pdi_paywall_plan_id_',
        'label' => '',
        'section' => '_pdi_paywall_plans_section',
        'type' => 'text',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $plan_item->pdi->id ?? ''
    )
);
foreach ($fields as $field) {
    pdi_paywall_register_setting('_pdi_paywall_plans', $field['uid']);
    add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_plans', $field['section'], $field);
}
//}


//for ($i = 1; $i <= PDI_PAYWALL_PLAN_LIMIT; $i++) {
add_action('update_option__pdi_paywall_plan_name_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_price_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_description_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_repetitions_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_frequency_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_frequency_type_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_billing_day_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_free_trial_frequency_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_free_trial_frequency_type_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_free_trial_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_active_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_back_url_', 'pdi_paywall_update_plans');
//add_action('update_option__pdi_paywall_plan_extern_plan_id_', 'pdi_paywall_update_plans');
add_action('update_option__pdi_paywall_plan_id_', 'pdi_paywall_update_plans');
//}

function pdi_paywall_clear_plan(){
    delete_option('_pdi_paywall_plan_name_');
    delete_option('_pdi_paywall_plan_description_');
    delete_option('_pdi_paywall_plan_repetitions_');
    delete_option('_pdi_paywall_plan_price_');
    delete_option('_pdi_paywall_plan_frequency_type_');
    delete_option('_pdi_paywall_plan_free_trial_');
    delete_option('_pdi_paywall_plan_free_trial_frequency_');
    delete_option('_pdi_paywall_plan_free_trial_frequency_type_');
    delete_option('_pdi_paywall_plan_back_url_');
    delete_option('_pdi_paywall_plan_extern_plan_id_');
    delete_option('_pdi_paywall_plan_id_');
}
