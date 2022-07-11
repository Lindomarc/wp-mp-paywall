<p class="pdi-paywall-logout-link"><?php printf('Bem vindo %s, você está logado. <a href="%s">Clique aqui, para sair.</a>', $user->user_login, wp_logout_url(get_page_link(get_option('_pdi_paywall_page_login')))); ?></p>

<?php

//add_user_meta($user->ID, 'subscriber_id','2c93808481b537eb0181b6b948cb00c2');

if (pdi_paywall_is_subscriber()) { ?>
    <h2 class="pdi-paywall-profile-subscription-title">Sua assinatura</h2>
    <div class="table-responsive">

        <table class="table pdi-paywall-profile-subscription-details">
            <thead>
            <tr>
                <th>Tem acesso</th>
                <th>Status</th>
                <th>Plano</th>
                <th>Método pag.</th>
                <th>Valor</th>
                <th>Prox. cobrança</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($subscriber->data->status)): ?>
                <tr>
                    <td><?php echo($subscriber->data->status === 'authorized' ? 'Sim' : 'Não') ?></td>
                    <td><?php echo($subscriber->data->status === 'authorized' ? 'Ativo' : 'Inativo') ?></td>
                    <td><?php echo $subscriber->data->reason ?></td>
                    <td><?php echo(($subscriber->data->transaction_amount > 0) ? 'Cartão de crédito' : 'Registro gratuito') ?></td>
                    <td><?php echo(($subscriber->data->transaction_amount > 0) ? 'R$ ' . number_format($subscriber->data->transaction_amount, 2, ',', '') : 'Gratuito') ?></td>
                    <td><?php echo(($subscriber->data->transaction_amount > 0 && $subscriber->data->status === 'authorized' && !empty($subscriber->data->next_retry_date)) ? date('d/m/Y', strtotime($subscriber->data->next_retry_date)) : '--') ?></td>
                    <td>
                        <button class="btn btn-md btn-danger" onclick="unsubscriber('<?php echo $subscriber_id ?>')">
                            Cancelar Assinatura
                        </button>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td>Sim</td>
                    <td>Ativo</td>
                    <td>Cortesia</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <hr/>
<?php } ?>

<h2 class="pdi-paywall-your-profile-header">Seu perfil</h2>
<?php
if (!empty($_POST['pdi-paywall-profile-nonce'])) {
    if (wp_verify_nonce($_POST['pdi-paywall-profile-nonce'], 'pdi-paywall-profile')) {
        try {
            $userdata = get_userdata($user->ID);
            $args = array(
                'ID' => $user->ID,
                'user_login' => $userdata->user_login,
                'display_name' => $userdata->display_name,
                'user_email' => $userdata->user_email,
            );

            if (!empty($_POST['username'])) {
                $args['user_login'] = sanitize_text_field($_POST['username']);
            }

            if (!empty($_POST['displayname'])) {
                $args['display_name'] = sanitize_text_field($_POST['displayname']);
            }

            if (!empty($_POST['email'])) {
                if (is_email($_POST['email'])) {
                    $args['user_email'] = sanitize_text_field($_POST['email']);
                } else {
                    throw new Exception(__('Invalid email address.', 'pdi-paywall'));
                }
            }

            if (!empty($_POST['password1']) && !empty($_POST['password2'])) {
                if ($_POST['password1'] === $_POST['password2']) {
                    wp_set_password(sanitize_text_field($_POST['password1']), $user->ID);
                } else {
                    throw new Exception(__('Passwords do not match.', 'pdi-paywall'));
                }
            }

            $user_id = wp_update_user($args);

            if (is_wp_error($user_id)) {
                throw new Exception($user_id->get_error_message());
            } else {
                $user = get_userdata($user_id); //Refresh the user object
                echo '<div class="pdi_paywall_message success"><p>' . __('Profile Changes Saved.', 'pdi-paywall') . '</p></div>';
                do_action('pdi_paywall_after_profile_changes_saved', $user_id, $args, $userdata);
            }
        } catch (Exception $e) {
            echo '<div class="pdi_paywall_message error"><p class="error">' . $e->getMessage() . '</p></div>';
        }
    }
} else if (!empty($_POST['pdi-paywall-delete-account-nonce'])) {
    if (wp_verify_nonce($_POST['pdi-paywall-delete-account-nonce'], 'pdi-paywall-delete-account')) {
        if (!empty($subscriber_id)) {
            $response = pdi_paywall_api_put('subscribers/cancel', ['subscriber_id' => $subscriber_id]);
        }

        pdi_paywall_subscription_cancel($user->user_email);

        pdi_paywall_delete_user($user->ID);

        wp_logout();
        ?>
        <script>
            window.location.href = '<?php echo site_url(); ?>';
        </script>
        <?php
    }

}
?>

<form id="pdi-paywall-profile" action="" method="post" autocomplete="off">
    <p>
        <label class="pdi-paywall-field-label" for="pdi-paywall-username">Nome de usuário</label>
        <input type="text" class="pdi-paywall-field-input" id="pdi-paywall-username" name="username"
               value="<?php echo $user->user_login; ?>" disabled="disabled" readonly="readonly"/>
    </p>

    <p>
        <label class="pdi-paywall-field-label" for="pdi-paywall-display-name">Nome de exibição</label>
        <input type="text" class="pdi-paywall-field-input" id="pdi-paywall-display-name" name="displayname"
               value="<?php echo $user->display_name; ?>"/>
    </p>

    <p>
    <p>
        <label class="pdi-paywall-field-label" for="pdi-paywall-display-name">Nome</label>
        <input type="text" class="pdi-paywall-field-input" id="pdi-paywall-display-name" name="firs_name"
               value="<?php echo $user->firs_name; ?>"/>
    </p>

    <p>
    <p>
        <label class="pdi-paywall-field-label" for="pdi-paywall-display-name">Sobrenome</label>
        <input type="text" class="pdi-paywall-field-input" id="pdi-paywall-display-name" name="last_name"
               value="<?php echo $user->last_name; ?>"/>
    </p>

    <p>
        <label class="pdi-paywall-field-label" for="pdi-paywall-email">E-mail</label>
        <input type="text" class="pdi-paywall-field-input" id="pdi-paywall-email" name="email"
               value="<?php echo $user->user_email; ?>"/>
    </p>

    <p>
        <label class="pdi-paywall-field-label" for="pdi-paywall-password1">Nova senha</label>
        <input type="password" class="pdi-paywall-field-input" id="pdi-paywall-password1" name="password1" value=""/>
    </p>

    <p>
        <label class="pdi-paywall-field-label" for="pdi-paywall-gift-subscription-password2">Confirmação de nova
            senha</label>
        <input type="password" class="pdi-paywall-field-input" id="pdi-paywall-gift-subscription-password2"
               name="password2" value=""/>
    </p>

    <?php echo wp_nonce_field('pdi-paywall-profile', 'pdi-paywall-profile-nonce', true, false); ?>

    <p class="submit">
        <input type="submit" id="submit" class="button button-primary" value="Salvar alterações"/>
    </p>
</form>

<?php if (get_option('_pdi_paywall_account_delete') == '1') { ?>
    <hr/>
    <form id="pdi-paywall-delete-account" action="" method="post" autocomplete="off">
        <p>
            <button type="submit"
                    onclick="return confirm('Deseja cancelar sua conta e inscrição? Esta ação é irreversível!')">
                Excluir conta
            </button>
        </p>
        <?php echo wp_nonce_field('pdi-paywall-delete-account', 'pdi-paywall-delete-account-nonce', true, false); ?>
    </form>
<?php }

if (isset($subscriber_id) && !!$subscriber_id):
?>
    <script>
        function unsubscriber(subscriber_id) {
            swal({
                title: "Cancelar Assinatura?",
                text: `Tem certeza que deseja cancelar sua assinaura?`,
                icon: "warning",
                buttons: ["Não", "Sim, desejo cancelar!"],
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        return new Promise((resolve, reject) => {
                            fetch("<?php echo PDI_PAYWALL_API_URI?>subscribers/cancel", {
                                method: "put",
                                headers: {
                                    "Accept": "application/json",
                                    "Content-Type": "application/json",
                                    "Authorization": "Bearer " + _pdi_paywall_payment_pdi_token,
                                    "x-customer-key": _pdi_paywall_payment_pdi_key,
                                },
                                body: JSON.stringify({subscriber_id: subscriber_id})
                            })
                                .then(function (response) {
                                    //console.log(response)
                                    swal({
                                        title: "Assinatura cancelada.",
                                        text: "Volte quando desejar.",
                                        icon: "success",
                                    })
                                        .then(() => {
                                            window.location.replace('/planos');
                                        });
                                })
                        })
                    }
                });
        }
    </script>
<?php endif; ?>
