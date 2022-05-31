const pdiTools = {

    onlyNumber: (value) => {
        return value.replace(/[^\d]/g, "");
    },

    typeDocument: (value) => {
        value = pdiTools.onlyNumber(value);
        switch (value.length){
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
    }

}
