<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script>
    // jQuery(document).ready( function () {
    //     jQuery('#table_subscribers').DataTable();
    // } );
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
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'pageLength': 25,
            columns: [
                {data: 'first_name'},
                {data: 'payer_email'},
                {data: 'reason'},
                {
                    data: 'transaction_amount',
                    render: function(data, type, row){
                        if(type === "sort" || type === "type"){
                            return data;
                        }
                        let value = parseFloat(data);
                        return value.toLocaleString('pt-BR',{style: 'currency', currency: 'BRL'})
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row){
                        if(type === "sort" || type === "type"){
                            return data;
                        }
                        let status;
                        switch (data) {
                            case 'cancelled':
                                status =  'Cancelado'
                                break;
                            case 'authorized':
                                status = 'Autorizado'
                                break;
                            default:
                                status =  data;
                        }
                        return status
                    }
                },
                {
                    data: 'next_retry_date',
                    render: function(data, type, row){
                            if(type === "sort" || type === "type"){
                                return data;
                            }
                        let date = new Date(data)
                        return   date.toLocaleString('pt-BR',{timeZone:'UTC'})
                    }
                },
            ],
        });
    });
</script>
<div class="wrap">
    <div class="container">
        <h2>PDI Paywall - Assinantes</h2>
        <table id="table_subscribers" class="table table-bordered data-table wp-list-table widefat fixed striped table-view-list posts">
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
</div>
