<?php

add_action('phpmailer_init', 'send_smtp_email');
function send_smtp_email($phpmailer)
{
    $phpmailer->isSMTP();
    $phpmailer->Host = get_option('_pdi_paywall_smtp_host');
    $phpmailer->SMTPAuth = get_option('_pdi_paywall_smtp_auth');
    $phpmailer->Port = get_option('_pdi_paywall_smtp_port');
    $phpmailer->SMTPSecure = get_option('_pdi_paywall_smtp_secure');
    $phpmailer->Username = get_option('_pdi_paywall_smtp_username');
    $phpmailer->Password = get_option('_pdi_paywall_smtp_password');
    $phpmailer->From = get_option('_pdi_paywall_smtp_from');
    $phpmailer->FromName = get_option('_pdi_paywall_smtp_fromname');
}

//function wpdocs_set_html_mail_content_type() {
//    return 'text/html';
//}
//add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

if (!function_exists('pdi_paywall_subscription_success')) {
    function pdi_paywall_subscription_success($user_email)
    {
        return wp_mail($user_email, "Assinatura Confirmada", get_option('_pdi_paywall_smtp_message_success'));
    }
}


if (!function_exists('pdi_paywall_subscription_cancel')) {
    function pdi_paywall_subscription_cancel($user_email)
    {
        return wp_mail($user_email, "Assinatura Cancelada", get_option('_pdi_paywall_smtp_message_cancel'));
    }
}
add_filter("retrieve_password_message", "mapp_custom_password_reset", 99, 4);

function mapp_custom_password_reset($message, $key, $user_login, $user_data)
{

    $url_recover_password = get_page_link(get_option('_pdi_paywall_page_recover_password'));
    $message = "Alguém solicitou uma redefinição de senha para a seguinte conta::" . sprintf(__('%s'), $user_data->user_email) . "

Se isso foi um erro, simplesmente ignore este e-mail e nada acontecerá.

Para redefinir sua senha, acesse o seguinte endereço:

<$url_recover_password?action=rp&key=$key&login=" . rawurlencode($user_login) . ">\r\n" . "

Se você tiver mais problemas, envie um email para %%ADMIN_EMAIL%%

Equipe %%BLOG_NAME%%";
    return $message;
}

add_filter('retrieve_password_title',
    function ($title) {
        $title = __('Redefinição de senha - '.get_option('blogname'));
        return $title;
    }
);

add_filter('new_password_title',
    function ($title) {
        $title = __('Nova senha - '.get_option('blogname'));
        return $title;
    }
);
