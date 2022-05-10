/*
 * This JS file is loaded for the custom registration form shortcode screen
 */

(function($, wpcustomregform) {

    $(document).ready(function() {
        init();
    });

    // For submit data of custom registration form.
    function initCustomRegDataSubmit() {
        // For submit the data of custom registration form.
        $('body').on('click', '.wp_custom_reg_form_submit', function(e) {
            e.preventDefault();

            var enable_btn = jQuery(this);
            jQuery('.wp_custom_reg_form_error').hide();

            // Check validation of all the fields
            var validated = initFormFieldsValidation();

            if (validated) {
                var action = $(this).data('action');
                var nonce = $(this).data('nonce');
                var username = jQuery('#wp_custom_username').val();
                var email = jQuery('#wp_custom_email').val();
                var password = jQuery('#wp_custom_password').val();

                jQuery(".wp_custom_reg_form_wait_msg").show();
                jQuery(this).attr('disabled', 'disabled');

                $.ajax({
                    url: wpcustomregform.admin_ajax,
                    method: 'POST',
                    data: {
                        action: action,
                        nonce: nonce,
                        username: username,
                        email: email,
                        password: password,
                    },
                    success: function(data) {
                        alert(data.data.msg);
                        jQuery(".wp_custom_reg_form_wait_msg").hide();

                        if (data.data.redirection_url != '' && data.data.redirection_url != undefined) {
                            window.location.href = data.data.redirection_url;
                        } else {
                            location.reload();
                        }
                    },
                    error: function(error) {
                        alert(error);
                        jQuery(".wp_custom_reg_form_wait_msg").hide();
                        location.reload();
                    }
                });
            }

        });
    }

    // For validating the fields on the custom registration form.
    function initFormFieldsValidation() {

        // Get fields to check for validation
        var wp_username = $('#wp_custom_username').val();
        var wp_email = $('#wp_custom_email').val();
        var wp_password = $('#wp_custom_password').val();

        var valid = true;

        // Invalid - If username is empty.
        if (wp_username == '') {
            valid = false;
            $('#wp_custom_username').parent().find('.wp_custom_reg_form_error').show();
        }

        // Invalid - If email is empty.
        if (wp_email == '') {
            valid = false;
            $('#wp_custom_email').parent().find('.wp_custom_reg_form_error').show();
        }

        // Invalid - If password is empty.
        if (wp_password == '') {
            valid = false;
            $('#wp_custom_password').parent().find('.wp_custom_reg_form_error').show();
        }

        return valid;
    }

    /**
     * Instantiate JS functions
     */
    function init() {
        initCustomRegDataSubmit();
    }

})(jQuery, wpcustomregform);