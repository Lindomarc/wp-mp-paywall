<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>

<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<div class="container">
    <h1 class="mb-5 mt-5">PDI Paywall - Assinantes</h1>
    <table id="table_subscribers" class="table table-bordered display data-table responsive nowrap">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Plano</th>
            <th>Valor</th>
            <th>Situação</th>
            <th>status</th>
            <th>Próximo pagamento</th>
            <th></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Plano</th>
            <th>Valor</th>
            <th>Situação</th>
            <th>status</th>
            <th>Próximo pagamento</th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="subscriberFormModal" tabindex="-1" role="dialog" aria-labelledby="subscriberFormModal"
     aria-hidden="true">

    <form class="inline row" id="form-subscriber">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assinantes <span class="plan-name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name" class="col-form-label">* Primeiro Nome:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name" class="col-form-label">* Sobre Nome:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email" class="col-form-label">* Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="identification_number" class="col-form-label">* Documento
                                    (cpf/cnpj):</label>
                                <input type="text" class="form-control" id="identification_number"
                                       name="identification_number" required>
                                <input type="hidden" id="identification_type" name="identification_type" value="CPF"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="plan_id" class="col-form-label">* Plano</label>
                                <select id="plan_id" name="plan_id" class="js-plan-data-ajax form-control"></select>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="due_day" class="col-form-label">Dia do pagamento:</label>
                                <input type="number" min="1" max="28" class="form-control" id="due_day"
                                       name="due_day">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="repetitions" class="col-form-label">Repetiçao:</label>
                                <input type="number" class="form-control" id="repetitions" name="repetitions">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_status" class="col-form-label">* Situação:</label>
                                <select name="payment_status" id="payment_status" class="form-control select2" required>
                                    <option value="pending">Pendente</option>
                                    <option value="cancelled">Cancelado</option>
                                    <option value="authorized">Autorizado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label
                                    for="transaction_amount"
                                    class="col-form-label">Valor da transação:
                                </label>
                                <input
                                    type="text"
                                    class="form-control money"
                                    id="transaction_amount"
                                    name="transaction_amount">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="is-handler" value="1">
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
    input.error {
         border-width: 2px !important;
         border-color: red !important;
     }

</style>
<script>
    const formSubscriber = document.getElementById("form-subscriber");
    formSubscriber.addEventListener("submit", handleFormSubmit);
    jQuery(document).ready(function ($) {

        functionsForm.subscriberAdd = () => {
            functionsForm.form_action = `<?php echo PDI_PAYWALL_API_URI . 'subscribers'; ?>`;
            functionsForm.form_method = 'post';
        };

        functionsForm.subscriberEdit = (subscriber_id) => {
            functionsForm.formClear();
            functionsForm.form_action = `<?php echo PDI_PAYWALL_API_URI . 'subscribers'; ?>/${subscriber_id}`;
            functionsForm.form_method = 'put';
            return new Promise((resolve, reject) => {
                fetch(functionsForm.form_action, {
                    method: "get",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        "Authorization": `Bearer ${PDI_PAYWALL_PAYMENT_PDI_TOKEN}`,
                        "x-customer-key": `${PDI_PAYWALL_PAYMENT_PDI_KEY}`,
                    }
                })
                    .then((response) => {
                        resolve()
                        const result = response.json()
                        result.then(({data}) => {

                            if (data) {
                                if ((!!data.status && data.status !== 200) && !!data.message) {
                                    pdiTools._pdi_alert_error(data)
                                } else {
                                    functionsForm.fill('last_name', data.last_name)
                                    functionsForm.fill('first_name', data.first_name)
                                    functionsForm.fill('email', data.payer_email)
                                    functionsForm.fill('identification_number', data.identification_number)
                                    functionsForm.fill('identification_type', data.identification_type)
                                    functionsForm.fill('plan_id', data.plan_id)
                                    functionsForm.fill('due_day', data.due_day)
                                    functionsForm.fill('repetitions', data.repetitions)
                                    jQuery(`#plan_id`).html('').append(`<option value="${data.plan_id}" selected>${data.reason}</option>`);
                                    functionsForm.fill('period', data['plan']['period']);
                                    functionsForm.fill('transaction_amount', data.transaction_amount);
                                    functionsForm.fill('payment_status', data.payment_status);
                                    functionsForm.fill('id', data.id)
                                    jQuery('#subscriberFormModal').modal('toggle');
                                }
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
            });
        }

        $('.js-data-list-plans-ajax').select2({
            ajax: {
                url: '<?php echo PDI_PAYWALL_API_URI . 'plans/select2-list'; ?>',
                dataType: 'json'
            }
        });

        functionsForm.tablePlans = jQuery('#table_subscribers').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            ajax: {
                "url": '<?php echo PDI_PAYWALL_API_URI . 'subscribers/datatable'; ?>',
                "type": 'get',
                "beforeSend": function (xhr) {
                    xhr.setRequestHeader('Authorization', `Bearer ${PDI_PAYWALL_PAYMENT_PDI_TOKEN}`);
                    xhr.setRequestHeader('x-customer-key', `${PDI_PAYWALL_PAYMENT_PDI_KEY}`);
                },
            },
            'responsive': true,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'pageLength': 10,
            columns: [
                {data: 'id'},
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
                    data: 'payment_status',
                    render: function (data, type, row) {
                        if (type === "sort" || type === "type") {
                            return data;
                        }
                        let status;
                        switch (data) {
                            case 'cancelled':
                                status = 'Cancelado'
                                break;
                            case 'rejected':
                                status = 'Rejeitado'
                                break;
                            case 'authorized':
                                status = 'Autorizado'
                                break;
                            case 'pending':
                                status = 'Pendente'
                                break;
                            case 'approved':
                                status = 'Aprovado'
                                break;
                            case 'in_process':
                                status = 'O pagamento está sendo revisado'
                                break;
                            case 'in_mediation':
                                status = 'Rejeitado'
                                break;
                            case 'refunded':
                                status = 'Reembolsado'
                                break;
                            case 'charged_back':
                                status = 'Estornado'
                                break;
                            default:
                                status = data;
                        }
                        return status
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
                            case 'pending':
                                status = 'Pendente'
                                break;
                            case 'paused':
                                status = 'Pausado'
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
                        if (!!data) {
                            let date = new Date(data)
                            return date.toLocaleString('pt-BR', {timeZone: 'UTC'})
                        }
                        return '';
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
                            <button class="btn btn-xs" onclick="functionsForm.subscriberEdit(${data})">Editar</button>
                        </div>`
                    }
                },
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Adicionar',
                    action: function (e, dt, node, config) {
                        functionsForm.formClear();
                        functionsForm.subscriberAdd();
                        $('#subscriberFormModal').modal('toggle');
                    }
                }
            ]
        });

        $('#plan_id').select2({
            ajax: {
                url: '<?php echo PDI_PAYWALL_API_URI . 'plans/select2-list'; ?>',
                processResults: function (data) {

                    return data
                },
                "beforeSend": functionsForm.beforeSend,
            }
        });
    });
</script>
