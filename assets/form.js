jQuery(document).ready(function ($) {
    $('#mccf-form').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let formData = {
            action: 'mccf_submit_form',
            nonce: mccf_ajax_obj.nonce,
            name: form.find('input[name="name"]').val(),
            email: form.find('input[name="email"]').val(),
            message: form.find('textarea[name="message"]').val()
        };

        $.post(mccf_ajax_obj.ajax_url, formData, function (response) {
            const msgBox = $('#mccf-message');
            if (response.success) {
                msgBox.html('<p style="color:green;">' + response.data.message + '</p>');
                form[0].reset();
            } else {
                msgBox.html('<p style="color:red;">' + response.data.message + '</p>');
            }
        });
    });
});
