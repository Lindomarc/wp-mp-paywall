<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<div class="container">
    <h1 class="mb-5 mt-5">PDI Paywall - Assinantes</h1>
    <table id="table_subscribers" class="table table-bordered display data-table responsive nowrap">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Plano</th>
            <th>Valor</th>
            <th>Situação</th>
            <th>Próximo pagamento</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Plano</th>
            <th>Valor</th>
            <th>Situação</th>
            <th>Próximo pagamento</th>
        </tr>
        </tfoot>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="planForm" tabindex="-1" role="dialog" aria-labelledby="planForm" aria-hidden="true">

    <form class="inline row" id="form-plan" action="<?php echo PDI_PAYWALL_API_URI . 'plans'; ?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Plano <span class="plan-name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col">
                        <div class="form-group">
                            <label for="reason" class="col-form-label">Nome:</label>
                            <input type="text" class="form-control" id="reason" name="reason" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description" class="col-form-label">Descrição:</label>
                            <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row col-12">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="repetitions" class="col-form-label">Repetiçao:</label>
                                <input type="number" class="form-control" id="repetitions"
                                       name="repetitions">
                                <span class="info small text-blue">Vázio irá repetir infinitamente.</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="period" class="col-form-label">Tipo de frequência:</label>
                                <?php
                                $options_markup = '';
                                $options = pdi_paywall_get_plans_period();
                                foreach ($options as $key => $label) {
                                    $options_markup .= sprintf('<option value="%s" >%s</option>', $key, $label);
                                }
                                echo '<select name="period" id="period" class="form-control select2">' . $options_markup . '</select>';

                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 hide">
                        <div class="form-group">
                            <label for="frequency" class="col-form-label">Amostra Grátis</label>
                            <input type="checkbox" class="form-control" id="free_trial" name="free_trial" value="0">
                        </div>
                        <div class="row" style="background-color: #e4e4e4">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="frequency" class="col-form-label">Repetir:</label>
                                    <input type="number" class="form-control" id="free_trial_frequency"
                                           name="free_trial_frequency" value="1">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="free_trial_frequency_type" class="col-form-label">Tipo de
                                        frequência:</label>
                                    <?php
                                    $options_markup = '';
                                    $options = pdi_paywall_get_plans_period();
                                    foreach ($options as $key => $label) {
                                        $options_markup .= sprintf('<option value="%s" >%s</option>', $key, $label);
                                    }
                                    echo '<select name="free_trial_frequency_type" id="free_trial_frequency_type" class="form-control select2" style="min-width: 200px">' . $options_markup . '</select>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="back_url" class="col-form-label">URL de retorno:</label>
                            <input type="text" class="form-control" id="back_url" name="back_url" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="transaction_amount" class="col-form-label">Valor da transação:</label>
                            <input type="text" class="form-control money" id="transaction_amount"
                                   name="transaction_amount">
                        </div>
                    </div>
                    <input type="hidden" id="plan_id" name="id">
                    <input type="hidden" id="extern_plan_id" name="extern_plan_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="form-save">Salvar
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
<style>
    #table_subscribers {
        width: 100% !important;
    }
    .container {
        max-width: 100%;
    }
</style>

<script>
    jQuery(document).ready(function () {
        jQuery('#table_subscribers').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            ajax: {
                "url": '<?php echo PDI_PAYWALL_API_URI . 'subscribers/datatable'; ?>',
                "type": 'get',
                "beforeSend": function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer <?php echo get_option('_pdi_paywall_payment_pdi_token'); ?>');
                    xhr.setRequestHeader('x-customer-key', '<?php echo get_option('_pdi_paywall_payment_pdi_key'); ?>');
                },
            },
            'responsive': true,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'pageLength': 10,
            columns: [
                {data: 'first_name'},
                {data: 'payer_email'},
                {data: 'reason'},
                {
                    data: 'transaction_amount',
                    render: function (data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        let value = parseFloat(data);
                        return value.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})
                    }
                },
                {
                    data: 'status',
                    render: function (data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        let status;
                        switch (data) {
                            case 'cancelled':
                                status = 'Cancelado'
                                break;
                            case 'authorized':
                                status = 'Autorizado'
                                break;
                            default:
                                status = data;
                        }
                        return status
                    }
                },
                {
                    data: 'next_retry_date',
                    render: function (data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        if (!!data){
                            let date = new Date(data)
                            return date.toLocaleString('pt-BR', {timeZone: 'UTC'})
                        }
                        return '';
                    }
                },
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Adicionar',
                    action: function (e, dt, node, config) {
                        functions.formClear();
                        functions.planAdd();
                        $('#planForm').modal('toggle');
                    }
                }
            ]
        });
    });
</script>
