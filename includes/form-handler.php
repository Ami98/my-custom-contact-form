<?php
// Enqueue JS and CSS
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('mccf-style', plugins_url('../assets/style.css', __FILE__));
    wp_enqueue_script(
        'mccf-form-js',
        plugins_url('../assets/form.js', __FILE__),
        ['jquery'],
        null,
        true
    );

    wp_localize_script('mccf-form-js', 'mccf_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('mccf_nonce')
    ]);
});

// Shortcode: [mccf_form]
add_shortcode('mccf_form', function () {
    ob_start();
?>
    <form id="mccf-form">
        <p><label>Name:</label><br><input type="text" name="name" required></p>
        <p><label>Email:</label><br><input type="email" name="email" required></p>
        <p><label>Message:</label><br><textarea name="message" required></textarea></p>
        <p><button type="submit">Send</button></p>
        <div id="mccf-message"></div>
    </form>
<?php
    return ob_get_clean();
});

// AJAX handler
add_action('wp_ajax_mccf_submit_form', 'mccf_handle_ajax');
add_action('wp_ajax_nopriv_mccf_submit_form', 'mccf_handle_ajax');

function mccf_handle_ajax()
{
    check_ajax_referer('mccf_nonce', 'nonce');

    $name    = sanitize_text_field($_POST['name'] ?? '');
    $email   = sanitize_email($_POST['email'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(['message' => 'All fields are required.']);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'mccf_messages';

    $wpdb->insert($table, [
        'name' => $name,
        'email' => $email,
        'message' => $message,
    ]);

    wp_send_json_success(['message' => 'Thank you for contacting us!']);
}
