jQuery(document).ready(function($) {
    $('#my-ajax-button').click(function() {
        $.ajax({
            url: ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'my_custom_ajax_action',
                security: security_nonce
            },
            success: function(response) {
                alert(response.data.message);
            },
            error: function() {
                alert('Something went wrong!');
            }
        });
    });
});
