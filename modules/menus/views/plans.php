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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php echo '<script src="' . PDI_PAYWALL_URL . 'js/pdi_paywall_custom.js?v=' . PDI_PAIWALL_VERSION . '"></script>'; ?>
<div class="container">


    <h1 class="mb-5 mt-5">PDI Paywall - Planos</h1>
    <table id="table_plans" class="table table-bordered display data-table responsive nowrap">
        <thead>
        <tr>
            <th>#</th>
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
            <th>#</th>
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

    input.error {
        border-width: 2px;
        border-color: red;
    }
</style>

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

    const formPlan = document.getElementById("form-plan");

    /**
     * We'll define the `handleFormSubmit()` event handler function in the next step.
     */
    formPlan.addEventListener("submit", handleFormSubmit);

    /**
     * Event handler for a form submit event.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/API/HTMLFormElement/submit_event
     *
     * @param {SubmitEvent} event
     */
    async function handleFormSubmit(event) {
        /**
         * This prevents the default behaviour of the browser submitting
         * the form so that we can handle things instead.
         */
        event.preventDefault();

        /**
         * This gets the element which the event handler was attached to.
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/API/Event/currentTarget
         */
        const form = event.currentTarget;

        /**
         * This takes the API URL from the form's `action` attribute.
         */
        const url = functions.form_action;

        try {
            /**
             * This takes all the fields in the form and makes their values
             * available through a `FormData` instance.
             *
             * @see https://developer.mozilla.org/en-US/docs/Web/API/FormData
             */
            const formData = new FormData(form);

            /**
             * We'll define the `postFormDataAsJson()` function in the next step.
             */
            const responseData = await postFormDataAsJson({url, formData});

            /**
             * Normally you'd want to do something with the response data,
             * but for this example we'll just log it to the console.
             */
            console.log({responseData});

        } catch (error) {
            console.error(error);
        }
    }


    async function postFormDataAsJson({url, formData}) {
        /**
         * We can't pass the `FormData` instance directly to `fetch`
         * as that will cause it to automatically format the request
         * body as "multipart" and set the `Content-Type` request header
         * to `multipart/form-data`. We want to send the request body
         * as JSON, so we're converting it to a plain object and then
         * into a JSON string.
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods/POST
         * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/fromEntries
         * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/JSON/stringify
         */
        const plainFormData = Object.fromEntries(formData.entries());
        const formDataJsonString = JSON.stringify(plainFormData);

        const fetchOptions = {
            /**
             * The default method for a request with fetch is GET,
             * so we must tell it to use the POST HTTP method.
             */
            method: functions.form_method,
            /**
             * These headers will be added to the request and tell
             * the API that the request body is JSON and that we can
             * accept JSON responses.
             */
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Bearer <?php echo get_option('_pdi_paywall_payment_pdi_token'); ?>",
                "x-customer-key": "<?php echo get_option('_pdi_paywall_payment_pdi_key'); ?>",
            },
            /**
             * The body of our POST request is the JSON string that
             * we created above.
             */
            body: formDataJsonString,
        };

        const response = await fetch(url, fetchOptions);

        if (!response.ok) {
            const errorMessage = await response.text();
            const errorJson = JSON.parse(errorMessage);
            if (!!errorJson.errors) {
                functions.checkErrors(errorJson);
                pdiTools._pdi_alert_error({status: "Erros", message: errorJson['message']})
            }
            throw new Error(errorMessage);
        }
        functions.reloadTable();
        return response.json();
    }

    const functions = {
        form_action: '',
        form_method: '',
        form: {},
        tablePlans: null,
        reloadTable: () => {
            jQuery('#planForm').modal('toggle');
            functions.tablePlans.ajax.reload()
        },
        fill: (id, value) => {
            jQuery(`#${id}`).val(value).trigger('change');
            functions.form[id] = value
        },
        formClear: () => {
            jQuery('form input,form textarea,form select').val('').removeClass('error').trigger('change')
        },
        checkErrors: (errorJson) => {
            if (!!errorJson.errors) {
                Object.entries(errorJson.errors).forEach(([key, value]) => {
                    jQuery(`#${key}`).addClass('error');
                })
            }
        },
        clearError: (id) => {
            jQuery(`#${id}`).removeClass('error');
        },
        planAdd: () => {
            functions.form_action = `<?php echo PDI_PAYWALL_API_URI . 'plans'; ?>/`;
            functions.form_method = 'post';
        },
        planEdit: (plan_id) => {
            functions.formClear();
            functions.form_action = `<?php echo PDI_PAYWALL_API_URI . 'plans'; ?>/${plan_id}`;
            functions.form_method = 'put';
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

                                functions.fill('reason', data['pdi'].reason)
                                functions.fill('description', data['pdi'].description)
                                functions.fill('repetitions', data['pdi'].repetitions)
                                functions.fill('transaction_amount', data['pdi'].transaction_amount)
                                functions.fill('back_url', data['pdi'].back_url)
                                functions.fill('period', data['pdi'].period)
                                functions.fill('plan_id', data['pdi'].id)
                                functions.fill('extern_plan_id', data['pdi'].extern_plan_id)
                                functions.fill('free_trial', data['pdi'].free_trial)
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
            });
        },
        planSave: (event) => {
            event.preventDefault();
            const form = event.currentTarget;
            const formData = new FormData(form);

            console.log(form)
            let method = 'post'
            let plan_id = jQuery('#plan_id').val();
            let id = ''
            if (!plan_id) {
                method = 'put'
                id = '/' + plan_id
            }

            return new Promise((resolve, reject) => {

                const data = new URLSearchParams();
                for (const pair of new FormData(formElement)) {
                    data.append(pair[0], pair[1]);
                }

                fetch('<?php echo PDI_PAYWALL_API_URI . 'plans'; ?>' + id, {
                    method: method,
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        "Authorization": "Bearer <?php echo get_option('_pdi_paywall_payment_pdi_token'); ?>",
                        "x-customer-key": "<?php echo get_option('_pdi_paywall_payment_pdi_key'); ?>",
                    },
                    body: formData
                })
                    .then((response) => {
                        const result = response.json()
                        result.then((data) => {
                            console.log(data)
                            if ((!!data.status && data.status !== 200) && !!data.message) {
                                pdiTools._pdi_alert_error(data)
                            }
                        })
                    })
                    .catch((error) => {
                        // get payment result error
                        console.log(error)
                        reject();
                        error.then((data) => {
                            if ((!!data.status && data.status !== 200) && !!data.message) {
                                pdiTools._pdi_alert_error(data)
                            }
                        })
                    })
            });
        }
    }

    jQuery(document).ready(function ($) {
        let counter = 1;
        functions.tablePlans = $('#table_plans').DataTable({
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
                {data: 'id'},
                {data: 'reason'},
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
                        </div>`
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
