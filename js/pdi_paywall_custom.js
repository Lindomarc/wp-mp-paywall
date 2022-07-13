const pdiTools = {

    onlyNumber: (value) => {
        return value.replace(/[^\d]/g, "");
    },

    typeDocument: (value) => {
        value = pdiTools.onlyNumber(value);
        switch (value.length) {
            case 11:
                value = 'CPF'
                break;
            case 14:
                value = 'CNPJ';
                break;
            default:
                value = '';

        }
        return value;
    },
    _pdi_alert_error: (data) => {
        if ((!!data.status && data.status !== 200) && !!data.message) {
            swal(`Ops! erro: ${data.status}`, `${data.message}`, "error");
        }
    }

}
