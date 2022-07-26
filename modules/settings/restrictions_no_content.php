<?php

/**
 * Liberação de conteúdo
 */
add_settings_section('_pdi_paywall_restrictions_no_content_section', 'Bloqueio total do contúdo', 'pdi_paywall_section_callback', '_pdi_paywall_no_content_restrictions');
foreach ($list_categories as $value) {

    $default = get_option('_pdi_paywall_restrictions_no_content_' . $value->term_id);
    $name = $value->name;
    $field = array(
        'uid' => '_pdi_paywall_restrictions_no_content_'.$value->term_id,
        'label' => $name,
        'section' => '_pdi_paywall_restrictions_no_content_section',
        'type' => 'checkbox',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => null,
        'default' => $default
    );
    pdi_paywall_register_setting('_pdi_paywall_no_content_restrictions', $field['uid']);
    add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_no_content_restrictions', $field['section'], $field);
}
