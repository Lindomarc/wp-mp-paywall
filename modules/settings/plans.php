<?php
/**
 * Plans
 */
for ($i = 1; $i <= PDI_PAYWALL_PLAN_LIMIT; $i++) {
    add_settings_section('_pdi_paywall_plans_' . $i . '_section', 'Plano ' . $i, 'pdi_paywall_section_callback', '_pdi_paywall_plans');
    $fields = array(
        array(
            'uid' => '_pdi_paywall_plan_name_' . $i,
            'label' => 'Nome',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_plan_description_' . $i,
            'label' => 'Descrição',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'textarea',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => null
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
            'uid' => '_pdi_paywall_plan_repetitions_' . $i,
            'label' => 'Repetir',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'number',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => 12
        ),

        array(
            'uid' => '_pdi_paywall_plan_frequency_type_' . $i,
            'label' => 'Tipo de Frequência',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'number',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => 10
        ),
        array(
            'uid' => '_pdi_paywall_plan_price_' . $i,
            'label' => 'Valor da transação',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'price',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_plan_frequency_type_' . $i,
            'label' => 'Tipo de frequência',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'select',
            'options' => pdi_paywall_get_plans_period(),
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => 'mounts'
        ),

        array(
            'uid' => '_pdi_paywall_plan_free_trial_' . $i,
            'label' => 'Amostra Grátis',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'checkbox',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => 0
        ),
        array(
            'uid' => '_pdi_paywall_plan_free_trial_frequency_' . $i,
            'label' => 'Frequência (Amostra Grátis)',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'number',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => 1
        ),
        array(
            'uid' => '_pdi_paywall_plan_free_trial_frequency_type_' . $i,
            'label' => 'Tipo de frequência (Amostra Grátis)',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'select',
            'options' => pdi_paywall_get_plans_period(),
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => 'mounts'
        ),
        array(
            'uid' => '_pdi_paywall_plan_back_url_' . $i,
            'label' => 'URL de retorno',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => null
        ),
        array(
            'uid' => '_pdi_paywall_plan_extern_plan_id_' . $i,
            'label' => '',
            'section' => '_pdi_paywall_plans_' . $i . '_section',
            'type' => 'text',
            'options' => false,
            'placeholder' => null,
            'helper' => null,
            'supplemental' => '',
            'default' => null
        ),
    );
    foreach ($fields as $field) {
        pdi_paywall_register_setting('_pdi_paywall_plans', $field['uid']);
        add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_plans', $field['section'], $field);
    }
}
