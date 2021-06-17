let login = function () {

    /*VARS*/
    let token = null;

    /*OBJECTS*/
    let $csrfName;
    let $csrfValue;

    /**
     * Handle buttons events
     */
    let handleButtons = function () {
        $(document).on('click', '#login', function (event) {
            event.preventDefault();
            let $loginModal = $('#login_modal');
            $loginModal.modal('show')
        })
    }

    /**
     * Handle form events
     */
    let handleForm = function () {

        $(document).on('submit', '#form', function (event) {
            event.preventDefault();
            let $form = $(this);
            let $button = $(this).find('.submit')
            $csrfName = $form.find('input[name=csrf_name]').val()
            $csrfValue = $form.find('input[name=csrf_value]').val()
            // $button.attr('disabled', true);
            // if is form, remove class
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            let $ajax = $.ajax({
                url: '/api/v1/authentication/token',
                type: 'post',
                data: $form.serialize(),
                enctype: 'multipart/form-data',
            });

            $ajax.done(function (data, textStatus, xhrLogin) {
                token = data.access_token;
                sendToken(xhrLogin)
            })

            $ajax.fail(function ($response) {
                $button.attr('disabled', false);
                gHandler.handlerErrors($response)
            })
        });
    }

    /*************
     * Functions *
     *************/

    /**
     * Send Token Request
     * @param xhrLogin
     */
    function sendToken(xhrLogin = null) {
        $.ajax(
            {
                type: "POST",
                url: '/api/v1/authentication/authorize',
                data: {
                    code: xhrLogin ? xhrLogin.status : 200,
                    cf_csrf_name: $csrfName,
                    cf_csrf_value: $csrfValue
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token)
                },
                success: function (data) {
                    gHandler.handlerSuccess(data)
                },
                error: function (data) {
                    gHandler.handlerSuccess(data)
                }

            }
        )
    }

    /**
     * Revelation Pattern
     */
    return {
        init: function () {
            handleButtons();
            handleForm();
        }
    }
}();

$(document).ready(function () {
    login.init();
});
