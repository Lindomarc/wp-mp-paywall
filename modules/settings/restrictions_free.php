<?php

/**
 * Liberação de conteúdo
 */
add_settings_section('_pdi_paywall_restrictions_free_section', 'Configurações de liberação', 'pdi_paywall_section_callback', '_pdi_paywall_restrictions');
$fields = array(
    array(
        'uid' => '_pdi_paywall_restrictions_free_content',
        'label' => 'Liberação de conteúdo',
        'section' => '_pdi_paywall_restrictions_free_section',
        'type' => 'categories',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => 'Conteúdo sempre livre',
        'default' => null
    ),
    array(
        'uid' => '_pdi_paywall_restrictions_free_content_2',
        'label' => 'Liberação de conteúdo (2)',
        'section' => '_pdi_paywall_restrictions_free_section',
        'type' => 'categories',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => 'Conteúdo sempre livre',
        'default' => null
    ),
    array(
        'uid' => '_pdi_paywall_restrictions_free_content_3',
        'label' => 'Liberação de conteúdo (3)',
        'section' => '_pdi_paywall_restrictions_free_section',
        'type' => 'categories',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => 'Conteúdo sempre livre',
        'default' => null
    ),
    array(
        'uid' => '_pdi_paywall_restrictions_free_content_4',
        'label' => 'Liberação de conteúdo (4)',
        'section' => '_pdi_paywall_restrictions_free_section',
        'type' => 'categories',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => 'Conteúdo sempre livre',
        'default' => null
    ),
    array(
        'uid' => '_pdi_paywall_restrictions_free_content_5',
        'label' => 'Liberação de conteúdo (5)',
        'section' => '_pdi_paywall_restrictions_free_section',
        'type' => 'categories',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => 'Conteúdo sempre livre',
        'default' => null
    ),
);
foreach ($fields as $field) {
    pdi_paywall_register_setting('_pdi_paywall_restrictions', $field['uid']);
    add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_restrictions', $field['section'], $field);
}
