<?php

if (!function_exists('pdi_paywall_metabox_visibility')) {
    function pdi_paywall_metabox_visibility()
    {
        $types = ['post', 'page'];
        foreach ($types as $type) {
            add_meta_box(
                'pdi_paywall_metabox_visibility',
                'Visibilidade Paywall',
                'pdi_paywall_metabox_visibility_view',
                $type,
                'side'
            );
        }
    }
    add_action('add_meta_boxes', 'pdi_paywall_metabox_visibility');
}

if (!function_exists('pdi_paywall_metabox_visibility_view')) {
    function pdi_paywall_metabox_visibility_view($post)
    {
        $value = get_post_meta($post->ID, PDI_PAYWALL_META_KEY_VISIBILITY, true);

        require_once(PDI_PAYWALL_MODULES_PATH . 'metabox/view.php');
    }
}

if (!function_exists('pdi_paywall_metabox_visibility_save')) {
    function pdi_paywall_metabox_visibility_save($post_id)
    {
        if (array_key_exists('pdi_paywall_visibility', $_POST)) {
            if (!empty($_POST['pdi_paywall_visibility'])) {
                update_post_meta(
                    $post_id,
                    PDI_PAYWALL_META_KEY_VISIBILITY,
                    $_POST['pdi_paywall_visibility']
                );
            } else {
                delete_post_meta($post_id, PDI_PAYWALL_META_KEY_VISIBILITY);
            }
        }
    }
    add_action('save_post', 'pdi_paywall_metabox_visibility_save');
}
