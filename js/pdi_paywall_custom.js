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
            swal(`Ops! ${data.status}`, `${data.message}`, "error");
        }
    }

}

const functionsForm = {
    form_action: '',
    form_method: '',
    form: {},
    tablePlans: null,
    reloadTable: (element_id) => {
        jQuery(`#${element_id}`).modal('toggle');
        functionsForm.tablePlans.ajax.reload()
    },
    fill: (id, value) => {
        jQuery(`#${id}`).val(value).trigger('change');
        functionsForm.form[id] = value
        jQuery(`select${id}`).change('change',function(){
            functionsForm.form[id] = jQuery(this).val();
        })
    },
    formClear: () => {
        jQuery('form input,form textarea,form select').val('').removeClass('error').trigger('change')
    },
    checkErrors: (errorJson) => {
        console.log(errorJson)
        if (!!errorJson.errors) {
            Object.entries(errorJson.errors).forEach(([key, value]) => {
                jQuery(`#${key}`).addClass('error').focus();
            })
        }
    },
    clearError: (id) => {
        jQuery(`#${id}`).removeClass('error');
    },
    clearErrors: () => {
        jQuery('form input,form textarea,form select').removeClass('error').trigger('change')
    },

    beforeSend: (xhr) => {
        xhr.setRequestHeader('Authorization', `Bearer ${PDI_PAYWALL_PAYMENT_PDI_TOKEN}` )
        xhr.setRequestHeader('x-customer-key', `${PDI_PAYWALL_PAYMENT_PDI_KEY}`)
    }
};

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
    jQuery('#form-save').prop('disabled',true);

    /**
     * This gets the element which the event handler was attached to.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/API/Event/currentTarget
     */
    const form = event.currentTarget;

    /**
     * This takes the API URL from the form's `action` attribute.
     */
    const url = functionsForm.form_action;

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
        jQuery('#modalForm').modal('toggle');
        jQuery('#form-save').prop('disabled',false);

        return responseData;

    } catch (error) {
        jQuery('#form-save').prop('disabled',false);

        const errorJson = JSON.parse(error.message);
        errorJson.status = 'Error';
        pdiTools._pdi_alert_error(errorJson)
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
        method: functionsForm.form_method,
        /**
         * These headers will be added to the request and tell
         * the API that the request body is JSON and that we can
         * accept JSON responses.
         */
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "Authorization": `Bearer ${PDI_PAYWALL_PAYMENT_PDI_TOKEN}`,
            "x-customer-key": `${PDI_PAYWALL_PAYMENT_PDI_KEY}`,
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
            functionsForm.clearErrors();
            functionsForm.checkErrors(errorJson);
            pdiTools._pdi_alert_error({status: "Erros", message: errorJson['message']})
        }
        throw new Error(errorMessage);
    }
    functionsForm.reloadTable();
    return response.json();
}
