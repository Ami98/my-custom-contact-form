<?php
// Hook into admin menu
add_action('admin_menu', 'mccf_admin_menu');
function mccf_admin_menu()
{
    // Main menu: View Submissions
    add_menu_page(
        'Contact Submissions',
        'Contact Form',
        'manage_options',
        'mccf-submissions',
        'mccf_render_submissions_page',
        'dashicons-email',
        25
    );

    // Submenu: Settings page
    add_submenu_page(
        'mccf-submissions', // parent slug
        'Form Settings',
        'Settings',
        'manage_options',
        'mccf-settings',
        'mccf_render_settings_page'
    );
}

// Render submissions page
function mccf_render_submissions_page()
{
    global $wpdb;
    $table = $wpdb->prefix . 'mccf_messages';
    $results = $wpdb->get_results("SELECT * FROM $table ORDER BY submitted_at DESC");

    echo '<div class="wrap"><h1>Contact Form Submissions</h1>';

    if ($results) {
        echo '<table class="widefat fixed striped">';
        echo '<thead><tr><th>Name</th><th>Email</th><th>Message</th><th>Date</th></tr></thead><tbody>';

        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . esc_html($row->name) . '</td>';
            echo '<td>' . esc_html($row->email) . '</td>';
            echo '<td>' . esc_html($row->message) . '</td>';
            echo '<td>' . esc_html($row->submitted_at) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p>No messages found.</p>';
    }

    echo '</div>';
}

// Register the setting
add_action('admin_init', function () {
    register_setting('mccf_settings_group', 'mccf_thankyou_msg', [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
});

// Render settings page
function mccf_render_settings_page()
{
?>
    <div class="wrap">
        <h1>Contact Form Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('mccf_settings_group');
            do_settings_sections('mccf_settings_group');

            $msg = get_option('mccf_thankyou_msg', 'Thank you for your message!');
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="mccf_thankyou_msg">Thank You Message</label></th>
                    <td>
                        <input type="text" id="mccf_thankyou_msg" name="mccf_thankyou_msg" value="<?php echo esc_attr($msg); ?>" style="width: 400px;">
                        <p class="description">This message will be shown to users after successful form submission.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
<?php
}
