let login = function () {

    let handleform = function () {
        $(document).on('click', '#login, .close-modal', function (event) {
            event.preventDefault();
            toggleModal('my_modal')
        })

        $(document).on('submit', '#form_login', function (e) {
            e.preventDefault()
            let $form = $(this);
            getToken($form)
        });

    }

    /**
     *
     * @param $form
     */
    function getToken($form) {

        $.ajax({
            type: "POST",
            url: "/api/v1/auth/token",
            data: $form.serialize(), // serializes the form's elements.
            success: function (response,  textStatus, xhrLogin) {
                console.info(response)
                let data = JSON.parse(response)
                login(data.access_token, xhrLogin)
            }
        });

    }

    function login(accessToken, xhrLogin) {
        $.ajax(
            {
                type: "POST",
                url: "/api/v1/auth/login",
                data: {
                    code: xhrLogin ? xhrLogin.status : 200
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer " + accessToken)
                },
                success: function (res) {
                    console.info("")
                }

            }
        )
    }

    function toggleModal(id) {
        let $modal = $('#' + id);
        let $modalBackdrop = $('#' + id + '_backdrop')
        $modal.toggleClass("hidden")
        $modal.toggleClass('flex')
        $modalBackdrop.toggleClass("hidden")
        $modalBackdrop.toggleClass('flex')
    }

    return {
        init: function () {
            handleform();
        }
    }
}();

$(document).ready(function () {
    login.init()
    $('#login').trigger("click")
})
