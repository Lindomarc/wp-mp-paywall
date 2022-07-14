<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

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
        });
    });
</script>
