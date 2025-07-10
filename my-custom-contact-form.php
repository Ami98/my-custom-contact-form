<?php
/*
Plugin Name: My Custom Contact Form
Description: A contact form plugin with frontend form and admin submission viewer.
Version: 1.0
Author: Ami Dalwadi
*/

// Activation: create table
register_activation_hook(__FILE__, 'mccf_activate_plugin');
function mccf_activate_plugin()
{
    global $wpdb;
    $table = $wpdb->prefix . 'mccf_messages';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(255),
        email VARCHAR(255),
        message TEXT,
        submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Deactivation: optional log
register_deactivation_hook(__FILE__, function () {
    error_log('My Custom Contact Form plugin deactivated.');
});

// Load frontend and admin code
require_once plugin_dir_path(__FILE__) . 'includes/form-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-messages.php';
