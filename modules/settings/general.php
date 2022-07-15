<?php
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
        'supplemental' => 'Adicione este shortcode na sua página de Login: [pdi_paywall_login]',
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
        'supplemental' => 'Adicione este shortcode na sua página de Registro: [pdi_paywall_register]',
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
        'supplemental' => 'Página de confirmação conta',
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
        'supplemental' => 'Adicione este shortcode na sua página de Perfil: [pdi_paywall_profile]',
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
        'supplemental' => 'Adicione este shortcode na sua página de Planos: [pdi_paywall_plans]',
        'default' => null
    ),
    array(
        'uid' => '_pdi_paywall_page_recover_password',
        'label' => 'Página para recuperar senha',
        'section' => '_pdi_paywall_pages_section',
        'type' => 'pages',
        'options' => false,
        'placeholder' => null,
        'helper' => null,
        'supplemental' => 'Adicione este shortcode na sua página de Recuperar Senha: [pdi_paywall_recover_password]',
        'default' => null
    ),
);
foreach ($fields as $field) {
    pdi_paywall_register_setting('_pdi_paywall_general', $field['uid']);
    add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_general', $field['section'], $field);
}

//add_settings_section('_pdi_paywall_html_restriction', 'Layout de restrição', 'pdi_paywall_section_callback', '_pdi_paywall_general');
$field = [
    'uid' => '_pdi_paywall_html_restriction',
    'label' => 'Bloco de bloqueio',
    'section' => '_pdi_paywall_pages_section',
    'type' => 'textarea',
    'options' => false,
    'placeholder' => null,
    'helper' => null,
    'supplemental' => 'Customizar html do bloco de restrição (vázio para exibir o padrão)',
    'default' => null
];
pdi_paywall_register_setting('_pdi_paywall_general', $field['uid']);
add_settings_field($field['uid'], $field['label'], 'pdi_paywall_field_callback', '_pdi_paywall_general', $field['section'], $field);

//add_action('update_option__pdi_paywall_html_restriction', 'pdi_paywall_update_plans');
