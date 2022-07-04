<div class="wrap">
    <h2>PDI Paywall - Assinantes</h2>

    <p></p>


    <table class="widefat fixed">
        <thead>
        <tr>
            <!-- <th id="cb" class="manage-column column-cb check-column" scope="col"></th> this column contains checkboxes -->
            <th id="number" class="manage-column column-number num" scope="col">#</th>
            <th id="name" class="manage-column column-name" scope="col">Nome</th>
            <th id="email" class="manage-column column-email" scope="col">E-mail</th>
            <th id="plan" class="manage-column column-plan" scope="col">Plano</th>
            <th id="plan" class="manage-column column-plan" scope="col">Tipo</th>
            <th id="payment" class="manage-column column-payment" scope="col">Método de pagamento</th>
            <th id="next_retry_date" class="manage-column column-next_retry_date" scope="col">Próximo pagamento</th>
            <th id="status" class="manage-column column-status" scope="col">Situação</th>
        </tr>
        </thead>
        <!-- <tfoot>
            <tr>
                <th class="manage-column column-number num" scope="col">#</th>
                <th class="manage-column column-name" scope="col">Nome</th>
                <th class="manage-column column-email" scope="col">E-mail</th>
                <th class="manage-column column-plan" scope="col">Plano</th>
                <th class="manage-column column-payment" scope="col">Método de pagamento</th>
                <th class="manage-column column-next_retry_date" scope="col">Próximo pagamento</th>
                <th class="manage-column column-status" scope="col">Situação</th>
            </tr>
        </tfoot> -->
        <tbody>
        <?php if (isset($subscribers->results)) { ?>
            <?php foreach ($subscribers->results as $key => $subscriber) {?>
                <tr class="alternate">
                    <td class="column-name">
                        <?php echo $subscriber->id ?>
                    </td>
                    <td class="column-name">
                        <?php echo $subscriber->payer_first_name ?>
                    </td>
                    <td class="column-email">
                        <?php echo $subscriber->payer_last_name ?>
                    </td>
                    <td class="column-plan">
                        <?php echo $subscriber->reason ?>
                    </td>
                    <td class="column-frequency_type">
                        <?php echo $subscriber->auto_recurring->frequency_type ?>
                    </td>
                    <?php
                        $price = 0;
                        if (isset($subscriber->auto_recurring->transaction_amount)){
                            $price = $subscriber->auto_recurring->transaction_amount;
                        }
                    ?>
                    <td class="column-payment">
                        <?php echo $price > 0 ? 'Cartão de crédito' : 'Registro gratuito' ?>
                    </td>
                    <td class="column-next_retry_date">
                        <?php echo $subscriber->next_payment_date
                            ? date('d/m/Y', strtotime($subscriber->next_payment_date))
                            : '--';
                        ?>
                    </td>
                    <td class="column-status">
                        <?php echo $subscriber->status ?>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>
