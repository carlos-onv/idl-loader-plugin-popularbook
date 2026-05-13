<?php
/**
 * eMathSmart Admin Interface - Error Logs
 */

if (!defined('ABSPATH')) exit;

/**
 * Register the submenu under WooCommerce
 */
add_action('admin_menu', 'emathsmart_add_logs_page', 99);
function emathsmart_add_logs_page() {
    add_submenu_page(
        'woocommerce',
        'eMathSmart API Logs',
        'eMathSmart Logs',
        'manage_woocommerce',
        'emathsmart-logs',
        'emathsmart_render_logs_page'
    );
}

/**
 * Render the logs page skeleton
 */
function emathsmart_render_logs_page() {
    if (!current_user_can('manage_woocommerce')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">eMathSmart API Logs</h1>
        <hr class="wp-header-end">

        <div class="notice notice-info is-dismissible">
            <p>This page displays a log of all eMathSmart API communication failures and retries.</p>
        </div>

        <div id="emathsmart-logs-container">
            <p>Loading table... (Feature 2 Implementation Pending)</p>
        </div>
    </div>
    <style>
        #emathsmart-logs-container {
            margin-top: 20px;
            background: #fff;
            border: 1px solid #ccd0d4;
            padding: 20px;
            border-radius: 4px;
        }
    </style>
    <?php
}
