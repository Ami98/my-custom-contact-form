<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
$table = $wpdb->prefix . 'mccf_messages';
$wpdb->query("DROP TABLE IF EXISTS $table");
