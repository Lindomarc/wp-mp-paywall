<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>

<div class="container">


    <h1 class="mb-5 mt-5">PDI Paywall - Planos</h1>
    <table id="table_plans" class="table table-bordered display data-table responsive nowrap">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Periodicidade</th>
            <th>Valor</th>
            <th>Situação</th>
            <th>Ativo</th>
            <th></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Nome</th>
            <th>Periodicidade</th>
            <th>Valor</th>
            <th>Situação</th>
            <th>Ativo</th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>

<style>
    #table_subscribers {
        width: 100% !important;
    }

    .container {
        max-width: 100%;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="planForm" tabindex="-1" role="dialog" aria-labelledby="planForm" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Plano <span class="plan-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="inline row" id="form-plan">
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
                                <label for="repetitions" class="col-form-label">Tipo de frequência:</label>
                                <input type="number" class="form-control" id="repetitions"
                                       name="auto_recurring[repetitions]">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="frequency_type" class="col-form-label">Tipo de frequência:</label>
                                <?php
                                $options_markup = '';
                                $options = pdi_paywall_get_plans_period();
                                foreach ($options as $key => $label) {
                                    $options_markup .= sprintf('<option value="%s" >%s</option>', $key, $label);
                                }
                                echo '<select name="auto_recurring[frequency_type]" id="frequency_type" class="form-control select2" style="min-width: 200px">' . $options_markup . '</select>';

                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 hide">
                        <div class="form-group">
                            <label for="frequency" class="col-form-label">Amostra Grátis</label>
                            <input type="checkbox" class="form-control" id="free_trial" name="free_trial">
                        </div>
                        <div class="row" style="background-color: #e4e4e4">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="frequency" class="col-form-label">Repetir:</label>
                                    <input type="number" class="form-control" id="free_trial_frequency"
                                           name="auto_recurring[free_trial][frequency]">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="free_trial-frequency_type" class="col-form-label">Tipo de
                                        frequência:</label>
                                    <?php
                                    $options_markup = '';
                                    $options = pdi_paywall_get_plans_period();
                                    foreach ($options as $key => $label) {
                                        $options_markup .= sprintf('<option value="%s" >%s</option>', $key, $label);
                                    }
                                    echo '<select name="auto_recurring[free_trial][frequency_type]" id="free_trial-frequency_type" class="form-control select2" style="min-width: 200px">' . $options_markup . '</select>';
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
                                   name="auto_recurring[transaction_amount]">
                        </div>
                    </div>
                    <input type="hidden" id="plan_id" name="id">
                    <input type="hidden" id="extern_plan_id" name="extern_plan_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" id="form-save">Salvar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .select2, .select2-container--default .select2-selection--single {
        width: 100% !important;
        height: 37px !important;
    }

    .select2-selection__rendered {
        text-align: center !important;
        padding: 2px !important;
    }

    .hide {
        display: none;
    }
</style>

<script>
    const beforeSend = function (xhr) {
        xhr.setRequestHeader('Authorization', 'Bearer <?php echo get_option('_pdi_paywall_payment_pdi_token'); ?>');
        xhr.setRequestHeader('x-customer-key', '<?php echo get_option('_pdi_paywall_payment_pdi_key'); ?>');
    }

    const functions = {
        form: [
            "reason",
            "description",
            "repetitions",
            "frequency_type",
            "free_trial",
            "free_trial_frequency",
            "free_trial_frequency_type",
            "back_url",
            "transaction_amount",
            "id",
            "extern_plan_id"
        ],
        fill:(id,value)=>{
            jQuery(`#${id}`).val(value)
        },
        formClear: () => {
            functions.form.forEach((value, index) => {
                jQuery(`${value}`).val('')
            })
        },
        planEdit: (plan_id) => {
            console.log(plan_id)
            return new Promise((resolve, reject) => {
                fetch('<?php echo PDI_PAYWALL_API_URI . 'plans/'; ?>' + plan_id, {
                    method: "get",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        "Authorization": "Bearer <?php echo get_option('_pdi_paywall_payment_pdi_token'); ?>",
                        "x-customer-key": "<?php echo get_option('_pdi_paywall_payment_pdi_key'); ?>",
                    }
                })
                    .then((response) => {
                        resolve()
                        const result = response.json()
                        result.then(({data}) => {

                            if ((!!data.status && data.status !== 200) && !!data.message) {
                                pdiTools._pdi_alert_error(data)
                            } else {

                                // "reason",
                                //     "description",
                                //     "repetitions",
                                //     "frequency_type",
                                //     "free_trial",
                                //     "free_trial_frequency",
                                //     "free_trial_frequency_type",
                                //     "back_url",
                                //     "transaction_amount",
                                //     "id",
                                //     "extern_plan_id"
                                console.log(data)
                                functions.fill('reason',data['pdi'].name)
                                functions.fill('description',data['pdi'].description)
                                functions.fill('repetitions',data['pdi'].repetitions)
                                functions.fill('amount',data['pdi'].amount)
                                functions.fill('amount',data['pdi'].amount)

                                jQuery('#planForm').modal('toggle');
                            }
                        })
                    })
                    .catch((error) => {
                        // get payment result error
                        reject();
                        error.then((data) => {
                            if ((!!data.status && data.status !== 200) && !!data.message) {
                                pdiTools._pdi_alert_error(data)
                            }
                        })
                    })
                    .finally((response) => {
                        console.log(response)
                    })
            });
        }
    }

    jQuery(document).ready(function ($) {
        let counter = 1;
        $('#table_plans').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            ajax: {
                "url": '<?php echo PDI_PAYWALL_API_URI . 'plans/datatable'; ?>',
                "type": 'get',
                "beforeSend": beforeSend,
            },
            'responsive': true,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'pageLength': 10,

            columns: [
                {data: 'name'},
                {
                    data: 'period',
                    render: function (data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        let value;
                        switch (data) {
                            case 'days':
                                value = 'Dias'
                                break;
                            case 'semesters':
                                value = 'Semestral'
                                break;
                            case 'months':
                                value = 'Mensal'
                                break;
                            case 'annual':
                                value = 'Anual'
                                break;
                            default:
                                value = data;
                        }
                        return value;
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
                            case 'active':
                                status = 'Ativo'
                                break;
                            case 'inactive':
                                status = 'Inativo'
                                break;
                            default:
                                status = data;
                        }
                        return status
                    }
                },
                {
                    data: 'amount',
                    render: function (data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        let value = parseFloat(data);
                        return value.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})
                    }
                },
                {
                    data: 'active',
                    render: function (data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return data ? 'on' : 'off'
                    }
                },
                {
                    data: 'id',
                    render: function (data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        return `
                        <div class="button-group">
                            <button class="btn btn-xs" onclick="functions.planEdit(${data})">Editar</button>
                            <button class="btn btn-xs" >Editar</button>
                        </div>`
                    }
                },
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Adicionar',
                    action: function (e, dt, node, config) {
                        $('#planForm').modal('toggle');
                    }
                }
            ]
        });


    });
</script>
