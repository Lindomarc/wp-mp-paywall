<?php
/**
 * Restrições
 */
add_settings_section('_pdi_paywall_restrictions_section', 'Configurações de restrições', 'pdi_paywall_section_callback', '_pdi_paywall_restrictions');

foreach ($list_categories as $value) {
    $default = get_option('_pdi_paywall_restrictions_content_' . $value->term_id);
    $name = $value->name;
    $field = [
        'uid' => '_pdi_paywall_restrictions_content_' . $value->term_id,
        'label' => $name,
        'section' => '_pdi_paywall_restrictions_section',
        'type' => 'checkbox',
        'options' => true,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => '',
        'default' => $default
    ];

    pdi_paywall_register_setting('_pdi_paywall_restrictions', $field['uid']);
    add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_restrictions', $field['section'], $field);

}



