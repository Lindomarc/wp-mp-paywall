<?php

add_action('phpmailer_init', 'send_smtp_email');
function send_smtp_email($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host       = get_option('_pdi_paywall_smtp_host');
    $phpmailer->SMTPAuth   = get_option('_pdi_paywall_smtp_auth');
    $phpmailer->Port       = get_option('_pdi_paywall_smtp_port');
    $phpmailer->SMTPSecure = get_option('_pdi_paywall_smtp_secure');
    $phpmailer->Username   = get_option('_pdi_paywall_smtp_username');
    $phpmailer->Password   = get_option('_pdi_paywall_smtp_password');
    $phpmailer->From       = get_option('_pdi_paywall_smtp_from');
    $phpmailer->FromName   = get_option('_pdi_paywall_smtp_fromname');
}

function wpdocs_set_html_mail_content_type() {
    return 'text/html';
}
add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

if (!function_exists('pdi_paywall_subscription_success')) {
    function pdi_paywall_subscription_success($user_email)    {
        return wp_mail($user_email, "Assinatura Confirmada", get_option('_pdi_paywall_smtp_message_success'));
    }
}


if (!function_exists('pdi_paywall_subscription_cancel')) {
    function pdi_paywall_subscription_cancel($user_email)
    {
        return wp_mail($user_email, "Assinatura Cancelada", get_option('_pdi_paywall_smtp_message_cancel'));
    }
}
