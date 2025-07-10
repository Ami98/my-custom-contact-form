<?php
add_action('admin_menu', 'mccf_admin_menu');

function mccf_admin_menu()
{
    add_menu_page(
        'Contact Submissions',
        'Contact Form',
        'manage_options',
        'mccf-submissions',
        'mccf_render_admin_page',
        'dashicons-email',
        25
    );
}

function mccf_render_admin_page()
{
    global $wpdb;
    $table = $wpdb->prefix . 'mccf_messages';
    $results = $wpdb->get_results("SELECT * FROM $table ORDER BY submitted_at DESC");

    echo '<div class="wrap"><h1>Contact Form Submissions</h1>';

    if ($results) {
        echo '<table class="widefat"><thead><tr>
              <th>Name</th><th>Email</th><th>Message</th><th>Date</th>
              </tr></thead><tbody>';
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
