{
    const spoofLink = el => {
        if(isConfirmed(el)) {
            const { href, data } = getRequestInfo(el);

            if(isAjax(el)) {
                spoofRequestVerbAjax(href, data);
            } else {
                spoofRequestVerb(href, data);
            }
        }
    }

    const spoofLinkAjax = (el, callback) => {
        if(isConfirmed(el)) {
            const { href, data } = getRequestInfo(el);
            spoofRequestVerbAjax(href, data, callback);
        }
    }

    const ajaxForm = (el, callback) => {
        const jqXHR = $.ajax({
            url: $(el).attr('action'),
            type: $(el).attr('method'),
            data: $(el).serialize(),
        }).done(() => {
            if(callback) {
                callback(null, jqXHR);
            }
        })
        .fail(() => {                    
            const message = $.parseJSON(jqXHR.responseText).message;
            alert(`${jqXHR.status} ${jqXHR.statusText}: ${message}`);

            if(callback) {
                callback(new Error(message));
            }
        });
    }

    const isConfirmed = (el) => {
        const confirmMsg = el.getAttribute('data-confirm-msg');
        const confirmed = confirmMsg ? confirm(confirmMsg) : true;

        return confirmed;
    };

    const getRequestInfo = el => {
        const verb = getVerbIfAllowed(el);
        const token = getCsrfToken(el);

        const [ href, params ] = el.href.split('?', 2);

        const data = {
            _method: verb, 
            _token: token, 
        };

        if (params && params.includes('&')) {
            params.split('&').forEach(function(param) {
                if (param.includes('=')) {
                    const [ name, value ] = param.split('=');
                    data[name] = value;
                }
            });
        }

        return { href, data };
    };

    const getVerbIfAllowed = el => {
        const allowed = ['POST', 'PUT', 'PATCH', 'DELETE'];
        const verb = el.getAttribute('data-method').toUpperCase();

        if (!allowed.includes(verb)) {
            throw new Error(`${verb} is not supported. Must be one of ${allowed.join(', ')}`);
        }

        return verb;
    };

    const getCsrfToken = el => el.getAttribute('data-csrf');

    const isAjax = (el) => {
        const ajax = Boolean(el.getAttribute('data-ajax')) 
            && !['false', '0', ''].includes(el.getAttribute('data-ajax'));

        return ajax;
    };

    const spoofRequestVerb = (href = '', inputs = {}) => {
        const form = document.createElement('form');
        const genericId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

        form.id = genericId;
        form.action = href;
        form.method = 'POST';

        Object.entries(inputs).forEach(function(input) {
            const el = document.createElement("input");

            el.type = 'hidden';
            el.name = input[0];
            el.value = input[1];

            form.appendChild(el); 
        });

        document.body.appendChild(form);
        form.submit();
    };

    const spoofRequestVerbAjax = (href = '', inputs = {}, callback = null) => {
        const jqXHR = $.post( href, inputs, callback ).done(() => {
            if(callback) {
                callback(null, jqXHR);
            }
        })
        .fail(() => {                    
            const message = $.parseJSON(jqXHR.responseText).message;
            alert(`${jqXHR.status} ${jqXHR.statusText}: ${message}`);

            if(callback) {
                callback(new Error(message));
            }
        });
    };

    // jQueryBindings

    $(document).ready(function() {
        $(document).on('click', "a.spoofed", function(e) {
            e.stopPropagation();
            e.preventDefault();

            spoofLink(this);
        });

        $(document).on('click', "a.dataTableControl", function(e) {
            e.stopPropagation();
            e.preventDefault();

            const datatable = $(this).closest('.dataTable');

            spoofLinkAjax(this, function(error, jqXHR) {
                if(error === null) {
                    datatable.DataTable().ajax.reload();
                }
            });
        });

        $(document).on('submit', "form.dataTableUpdate", function(e) {
            e.stopPropagation();
            e.preventDefault();

            const datatable = $(this).find('.dataTable');

            const jqXHR = ajaxForm(this, function(error, jqXHR) {
                if(error === null) {
                    datatable.DataTable().ajax.reload();
                }
            });

            return false;
        });
    });
}