class Handlers {

    /**
     * Handler success
     * @param $response
     */
    handlerSuccess($response) {
        let $redirect = $response.data.redirect;
        let $alert = $('.alert');
        // clean fields with mask
        $('.masked').trigger('change');
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

        $alert.removeClass('d-none')
        $alert.addClass('alert-success')
        $alert.find('.message').text($response.data.message)
        if ($redirect == '#') {
            setTimeout(function () {
                location.reload();
            }, 3000);
        } else if ($redirect) {
            window.location = $redirect;
        }
    }

    /**
     * Handler error
     * @param $response
     */
    handlerErrors($response) {

        let $alert = $('.alert');
        $alert.addClass('alert-danger')
        // if is form, remove class
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // if response is not json, show the text message
        if (!$response.responseJSON) {
            $alert.removeClass('d-none')

            $alert.find('.message').text($response.responseJSON.data.message)
            return;
        }

        /**
         *
         */
        if ($response.statusCode === 401) {
            // fazer algo quando a sessão acabar
        }

        // try fill with the invalid class the invalid inputs
        try {
            let response = $.parseJSON($response.responseJSON.data.message);
            $.each(response, function (keyItem, valueItem) {
                let $input = $($('input[name=' + keyItem + '], select[name=' + keyItem + ']')[0]);
                $input.addClass('is-invalid');
                let $node = $input.parent();
                let $message = valueItem;
                if (Array.isArray(valueItem)) {
                    $message = valueItem.join("<br/>")
                } else {
                    $message = $.map($message, function (value, index) {
                        return [value];
                    }).join("");
                }
                $node.append('<div class="invalid-feedback">' + $message + '</div>');
            });
            // return mask

        } catch (e) {
            $alert.removeClass('d-none')
            $alert.find('.message').text($response.responseJSON.data.message)
        }

        // clean fields with mask
        $('.masked').trigger('keydown');
    };
}

// global var for handlers
let gHandler = new Handlers();