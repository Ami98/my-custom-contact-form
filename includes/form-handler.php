<?php
// Enqueue optional styles
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('mccf-style', plugins_url('../assets/style.css', __FILE__));
    wp_enqueue_script(
        'mccf-form-js',
        plugins_url('../assets/form.js', __FILE__),
        ['jquery'],
        null,
        true
    );

    // Pass AJAX URL and nonce to JS
    wp_localize_script('mccf-form-js', 'mccf_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mccf_nonce')
    ]);
});

// Shortcode: [mccf_form]
add_shortcode('mccf_form', 'mccf_render_form');

function mccf_render_form()
{
    ob_start();

    if (isset($_POST['mccf_submit'])) {
        mccf_handle_form_submission();
    }

?>
    <form method="post">
        <p><label>Name:</label><br>
            <input type="text" name="mccf_name" required>
        </p>
        <p><label>Email:</label><br>
            <input type="email" name="mccf_email" required>
        </p>
        <p><label>Message:</label><br>
            <textarea name="mccf_message" required></textarea>
        </p>
        <p><input type="submit" name="mccf_submit" value="Send"></p>
    </form>
<?php

    return ob_get_clean();
}

function mccf_handle_form_submission()
{
    $name = sanitize_text_field($_POST['mccf_name']);
    $email = sanitize_email($_POST['mccf_email']);
    $message = sanitize_textarea_field($_POST['mccf_message']);

    global $wpdb;
    $table = $wpdb->prefix . 'mccf_messages';

    $wpdb->insert($table, [
        'name' => $name,
        'email' => $email,
        'message' => $message,
    ]);
}
