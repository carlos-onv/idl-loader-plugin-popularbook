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
    add_submenu_page(
        'woocommerce',
        'eMathSmart Settings',
        'eMathSmart Settings',
        'manage_options',
        'emathsmart-settings',
        'emathsmart_render_settings_page'
    );
}

/**
 * eMathSmart Settings Page — API Secret Key Manager
 * Allows updating the HMAC secret key from WP admin without file changes.
 * Navigate to: WooCommerce → eMathSmart Settings
 */
function emathsmart_render_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions.'));
    }

    $saved  = false;
    $error  = '';
    $notice = '';

    // Handle save
    if (isset($_POST['emathsmart_save_settings']) && check_admin_referer('emathsmart_settings_nonce')) {
        $new_key = isset($_POST['emathsmart_api_secret']) ? trim(sanitize_text_field($_POST['emathsmart_api_secret'])) : '';
        $new_url = isset($_POST['emathsmart_api_url']) ? trim(sanitize_text_field($_POST['emathsmart_api_url'])) : '';

        $has_error = false;
        if (strlen($new_key) < 8) {
            $error = 'Secret key is too short (minimum 8 characters).';
            $has_error = true;
        }

        if (!$has_error) {
            update_option('emathsmart_api_secret', $new_key);
            update_option('wc_emathsmart_url', $new_url);
            $saved = true;
            $notice = 'eMathSmart integration settings updated successfully.';
        }
    }

    // Handle reset to default
    if (isset($_POST['emathsmart_reset_secret']) && check_admin_referer('emathsmart_settings_nonce')) {
        delete_option('emathsmart_api_secret');
        delete_option('wc_emathsmart_url');
        $saved  = true;
        $notice = 'eMathSmart settings reset to built-in defaults.';
    }

    $current_key  = get_option('emathsmart_api_secret', 'yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm');
    $default_key  = 'yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm';
    $current_url  = get_option('wc_emathsmart_url', 'https://test.emathsmart.ca/');
    $default_url  = 'https://test.emathsmart.ca/';

    $active_key = defined('EMATHSMART_API_SECRET') && EMATHSMART_API_SECRET !== '' ? EMATHSMART_API_SECRET : $current_key;
    $active_url = defined('EMATHSMART_API_URL') && EMATHSMART_API_URL !== '' ? EMATHSMART_API_URL : $current_url;

    $is_default = ($active_key === $default_key);
    $is_url_default = (rtrim($active_url, '/') === rtrim($default_url, '/'));

    $key_hash   = substr(hash('sha256', $active_key), 0, 16);
    $has_override = (defined('EMATHSMART_API_SECRET') && EMATHSMART_API_SECRET !== '');
    $has_url_override = (defined('EMATHSMART_API_URL') && EMATHSMART_API_URL !== '');
    ?>
    <style>
        .esmart-settings-wrap { max-width: 700px; margin: 30px 0; }
        .esmart-settings-card { background: #fff; border: 1px solid #ccd0d4; border-radius: 4px; padding: 28px 32px; margin-bottom: 20px; }
        .esmart-settings-card h2 { margin: 0 0 6px; font-size: 18px; }
        .esmart-settings-card p.desc { color: #666; margin: 0 0 24px; font-size: 13px; }
        .esmart-field-row { margin-bottom: 24px; }
        .esmart-field-row label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 13px; }
        .esmart-field-row input[type=text] { width: 100%; font-family: monospace; font-size: 13px; padding: 8px 10px; border: 1px solid #8c8f94; border-radius: 3px; }
        .esmart-key-meta { background: #f8f9fa; border: 1px solid #e1e1e1; border-radius: 4px; padding: 10px 14px; font-size: 11px; font-family: monospace; margin-top: 8px; }
        .esmart-key-meta span { display: block; margin-bottom: 4px; color: #555; }
        .esmart-key-meta strong { color: #23282d; }
        .esmart-badge-default { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700; }
        .esmart-badge-custom  { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700; }
        .esmart-badge-error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700; }
        .esmart-alert { background: #d4edda; border-left: 4px solid #28a745; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 4px 4px 0; font-size: 13px; }
        .esmart-alert.error { background: #f8d7da; border-left-color: #dc3545; }
        .esmart-action-row { display: flex; gap: 12px; align-items: center; }
    </style>

    <div class="wrap esmart-settings-wrap">
        <h1 style="margin-bottom:4px;">⚙️ eMathSmart Settings</h1>
        <p style="color:#666; margin-bottom:24px;">Manage integration configuration for the eMathSmart API.</p>

        <?php if ($saved && $notice): ?>
            <div class="esmart-alert"><?php echo esc_html($notice); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="esmart-alert error"><?php echo esc_html($error); ?></div>
        <?php endif; ?>

        <div class="esmart-settings-card">
            <h2>🔑 API Integration Configuration</h2>
            <p class="desc">
                Configure the API Base URL and Secret Key used for outbound webhook payloads (paymentNotify, refundNotify, publicExams).
            </p>

            <form method="post">
                <?php wp_nonce_field('emathsmart_settings_nonce'); ?>

                <div class="esmart-field-row">
                    <label for="esmart_url_input">API Base URL</label>
                    <input type="text"
                           id="esmart_url_input"
                           name="emathsmart_api_url"
                           value="<?php echo esc_attr($current_url); ?>"
                           autocomplete="off"
                           spellcheck="false"
                           placeholder="https://test.emathsmart.ca/">
                    <div class="esmart-key-meta">
                        <span>Status: <?php 
                            if ($has_url_override) {
                                echo '<span class="esmart-badge-custom" style="background:#d1ecf1; color:#0c5460; border-color:#bee5eb;">✓ Overridden by wp-config.php</span>';
                            } elseif ($is_url_default) {
                                echo '<span class="esmart-badge-default">✓ Using default staging URL</span>';
                            } else {
                                echo '<span class="esmart-badge-custom">★ Custom URL active</span>';
                            }
                        ?></span>
                        <?php if ($has_url_override): ?>
                            <span style="color:#721c24; margin-top:4px; display:block;">⚠️ Note: The API Base URL is currently overridden by the <code>EMATHSMART_API_URL</code> constant in <code>wp-config.php</code>.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="esmart-field-row">
                    <label for="esmart_secret_input">API Secret Key</label>
                    <input type="text"
                           id="esmart_secret_input"
                           name="emathsmart_api_secret"
                           value="<?php echo esc_attr($current_key); ?>"
                           autocomplete="off"
                           spellcheck="false"
                           placeholder="Paste new secret key from eMathSmart here">
                    <div class="esmart-key-meta">
                        <span>Status: <?php 
                            if ($has_override) {
                                echo '<span class="esmart-badge-custom" style="background:#d1ecf1; color:#0c5460; border-color:#bee5eb;">✓ Overridden by wp-config.php</span>';
                            } elseif ($is_default) {
                                echo '<span class="esmart-badge-default">✓ Using built-in default key</span>';
                            } else {
                                echo '<span class="esmart-badge-custom">★ Custom key active</span>';
                            }
                        ?></span>
                        <span>SHA-256 fingerprint (first 16 chars): <strong><?php echo esc_html($key_hash); ?>…</strong></span>
                        <span>Key length: <strong><?php echo strlen($active_key); ?> characters</strong></span>
                        <?php if ($has_override): ?>
                            <span style="color:#721c24; margin-top:8px; display:block;">⚠️ Note: The API Secret Key is currently overridden by the <code>EMATHSMART_API_SECRET</code> constant in <code>wp-config.php</code>.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="esmart-action-row">
                    <button type="submit" name="emathsmart_save_settings" class="button button-primary">Save Settings</button>
                    <?php if ((!$is_default || !$is_url_default) && !$has_override && !$has_url_override): ?>
                        <button type="submit" name="emathsmart_reset_secret"
                                class="button button-link-delete"
                                onclick="return confirm('Reset settings to defaults?')"
                                style="color:#d63638;">
                            Reset to Defaults
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    <?php
}


/**
 * Schedule daily cleanup of old logs (30 days)
 */
if (!wp_next_scheduled('emathsmart_daily_log_cleanup')) {
    wp_schedule_event(time(), 'daily', 'emathsmart_daily_log_cleanup');
}
add_action('emathsmart_daily_log_cleanup', 'emathsmart_cleanup_old_logs');

function emathsmart_cleanup_old_logs() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'emathsmart_log';
    $days_to_keep = 30;
    $wpdb->query($wpdb->prepare(
        "DELETE FROM $table_name WHERE created_at < DATE_SUB(NOW(), INTERVAL %d DAY)",
        $days_to_keep
    ));
}

/**
 * Render the logs page
 */
function emathsmart_render_logs_page() {
    if (!current_user_can('manage_woocommerce')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'emathsmart_log';

    // Handle Manual Clear All
    if (isset($_POST['emathsmart_clear_logs']) && check_admin_referer('emathsmart_clear_logs_action')) {
        $wpdb->query("TRUNCATE TABLE $table_name");
        echo '<div class="notice notice-success is-dismissible"><p>All logs have been cleared.</p></div>';
    }

    // Guard: ensure the log table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
        echo '<div class="wrap"><h1>eMathSmart API Logs</h1>';
        echo '<div class="notice notice-warning"><p>The log table <code>' . esc_html($table_name) . '</code> does not exist yet. Logs will appear here once the first API call is made.</p></div></div>';
        return;
    }

    // Pagination Logic
    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;

    // Filters
    $where = "1=1";
    $params = [];

    if (!empty($_GET['order_id'])) {
        $where .= " AND order_id = %d";
        $params[] = intval($_GET['order_id']);
    }

    if (!empty($_GET['api_type'])) {
        $where .= " AND api_type = %s";
        $params[] = sanitize_text_field($_GET['api_type']);
    }

    if (!empty($_GET['date_from'])) {
        $where .= " AND created_at >= %s";
        $params[] = sanitize_text_field($_GET['date_from']) . ' 00:00:00';
    }

    if (!empty($_GET['date_to'])) {
        $where .= " AND created_at <= %s";
        $params[] = sanitize_text_field($_GET['date_to']) . ' 23:59:59';
    }

    // Build count query — only use prepare() when we have placeholders
    $count_sql = "SELECT COUNT(*) FROM $table_name WHERE $where";
    $total_items = !empty($params)
        ? (int) $wpdb->get_var($wpdb->prepare($count_sql, $params))
        : (int) $wpdb->get_var($count_sql);
    $num_pages = ceil($total_items / $per_page);

    // Build data query
    $data_sql = "SELECT * FROM $table_name WHERE $where ORDER BY id DESC LIMIT %d OFFSET %d";
    $query_params = array_merge($params, [$per_page, $offset]);
    $logs = $wpdb->get_results($wpdb->prepare($data_sql, $query_params));
    ?>
    <style>
        :root {
            --pb-red: #d63638; /* Brand Red */
            --pb-dark: #23282d;
            --pb-success: #46b450;
            --pb-warning: #ffb900;
            --pb-error: #dc3232;
        }

        .wrap.emathsmart-admin {
            margin: 20px 20px 0 0;
            max-width: 1400px;
        }

        .emathsmart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            padding: 20px 30px;
            border-bottom: 3px solid var(--pb-red);
            border-radius: 4px 4px 0 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .emathsmart-header h1 {
            margin: 0;
            color: var(--pb-dark);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            font-size: 24px;
        }

        .emathsmart-filter-card {
            background: #fff;
            padding: 25px;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .emathsmart-filter-card h2 {
            margin: 0 0 20px 0;
            font-size: 16px;
            font-weight: 600;
            color: #555;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .emathsmart-filter-card h2:before {
            content: "\f111";
            font-family: dashicons;
            font-size: 14px;
            color: var(--pb-red);
        }

        .emathsmart-filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .emathsmart-filter-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 13px;
        }

        .emathsmart-filter-group input, 
        .emathsmart-filter-group select {
            width: 100%;
            height: 36px;
            border: 1px solid #8c8f94;
            border-radius: 3px;
            padding: 0 10px;
        }

        .emathsmart-btn-filter {
            background: var(--pb-red) !important;
            border-color: var(--pb-red) !important;
            color: #fff !important;
            height: 36px !important;
            line-height: 34px !important;
            padding: 0 25px !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
        }

        .emathsmart-btn-filter:hover {
            background: #b32d2e !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15) !important;
        }

        .emathsmart-table-wrap {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .wp-list-table.emathsmart-table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #e1e1e1;
            padding: 15px 10px;
            font-weight: 700;
            color: #333;
        }

        .log-error-critical { background-color: #fff8f8 !important; }
        .log-error-warning { background-color: #fffcf5 !important; }

        .log-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-success { background: #e7f4e9; color: #1e7e34; border: 1px solid #c3e6cb; }
        .badge-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .badge-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .badge-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

        .log-details-row td {
            padding: 0 !important;
            border-top: none !important;
        }

        .log-details-grid {
            display: flex;
            gap: 20px;
        }

        .log-details-col {
            flex: 1;
            min-width: 0;
        }

        .log-details-content {
            padding: 25px;
            background: #fcfcfc;
            border-top: 1px solid #eee;
        }

        .log-details-content pre {
            white-space: pre-wrap;
            word-break: break-all;
            background: #fff;
            border: 1px solid #e1e1e1;
            padding: 15px;
            border-radius: 5px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 12px;
            line-height: 1.6;
            max-height: 500px;
            overflow-y: auto;
        }

        .log-details-col strong {
            display: block;
            margin-bottom: 10px;
            color: #444;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Header column layout for stacked title + meta */
        .emathsmart-header.emathsmart-header--column {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    </style>

    <div class="wrap emathsmart-admin">
        <div class="emathsmart-notices">
            <div class="notice notice-info is-dismissible">
                <p>This page displays communication logs for eMathSmart APIs. Only failures and manual resends are logged here for debugging.</p>
            </div>
        </div>

        <div class="emathsmart-header emathsmart-header--column">
            <h1>eMathSmart API Logs</h1>
            <div class="emathsmart-meta">
                <span class="displaying-num"><?php printf(_n('%s Entry Found', '%s Entries Found', $total_items), number_format_i18n($total_items)); ?></span>
            </div>
        </div>

        <!-- Filters Form -->
        <div class="emathsmart-filter-card">
            <h2>Filter & Search</h2>
            <form method="get">
                <input type="hidden" name="page" value="emathsmart-logs">
                <div class="emathsmart-filter-grid">
                    <div class="emathsmart-filter-group">
                        <label for="order_id">Order ID</label>
                        <input type="number" name="order_id" id="order_id" value="<?php echo isset($_GET['order_id']) ? esc_attr($_GET['order_id']) : ''; ?>" placeholder="e.g. 116377">
                    </div>
                    
                    <div class="emathsmart-filter-group">
                        <label for="api_type">API Type</label>
                        <select name="api_type" id="api_type">
                            <option value="">All Types</option>
                            <option value="Payment" <?php selected(isset($_GET['api_type']) && $_GET['api_type'] == 'Payment'); ?>>Payment</option>
                            <option value="refund" <?php selected(isset($_GET['api_type']) && $_GET['api_type'] == 'refund'); ?>>Refund</option>
                            <option value="public_exams" <?php selected(isset($_GET['api_type']) && $_GET['api_type'] == 'public_exams'); ?>>Public Exams</option>
                        </select>
                    </div>
                    
                    <div class="emathsmart-filter-group">
                        <label for="date_from">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="<?php echo isset($_GET['date_from']) ? esc_attr($_GET['date_from']) : ''; ?>">
                    </div>
                    
                    <div class="emathsmart-filter-group">
                        <label for="date_to">To Date</label>
                        <input type="date" name="date_to" id="date_to" value="<?php echo isset($_GET['date_to']) ? esc_attr($_GET['date_to']) : ''; ?>">
                    </div>
                    
                    <div class="emathsmart-filter-actions">
                        <button type="submit" class="button button-primary emathsmart-btn-filter">Apply Filters</button>
                        <a href="admin.php?page=emathsmart-logs" class="button">Clear</a>
                    </div>
                </div>
            </form>
            
            <form method="post" onsubmit="return confirm('Are you sure you want to delete ALL logs? This cannot be undone.');" style="margin-top:15px; padding-top:15px; border-top:1px dashed #eee;">
                <?php wp_nonce_field('emathsmart_clear_logs_action'); ?>
                <button type="submit" name="emathsmart_clear_logs" class="button button-link-delete" style="color:#d63638; text-decoration:none;">Delete All Logs Permanently</button>
            </form>
        </div>

        <div class="tablenav top">
            <div class="tablenav-pages">
                <?php
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'total' => $num_pages,
                    'current' => $current_page
                ));
                ?>
            </div>
            <br class="clear">
        </div>

        <div class="emathsmart-table-wrap">
            <table class="wp-list-table widefat fixed striped posts emathsmart-table">
                <thead>
                    <tr>
                        <th scope="col" style="width: 70px;">ID</th>
                        <th scope="col" style="width: 100px;">Order</th>
                        <th scope="col" style="width: 150px;">API Type</th>
                        <th scope="col" style="width: 90px;">Attempt</th>
                        <th scope="col" style="width: 120px;">Response Code</th>
                        <th scope="col" style="width: 100px;">HTTP Status</th>
                        <th scope="col">Timestamp</th>
                        <th scope="col" style="width: 120px; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="the-list">
                    <?php if ($logs): ?>
                        <?php foreach ($logs as $log): 
                            $status_class = '';
                            $badge_class = 'badge-success';
                            $status_text = 'Success';

                            if ($log->http_status >= 500 || $log->http_status == 0) {
                                $status_class = 'log-error-critical';
                                $badge_class = 'badge-error';
                                $status_text = 'Critical Error';
                            } elseif ($log->response_code != 200 && !in_array($log->response_code, [40101, 40201, 40202])) {
                                $status_class = 'log-error-warning';
                                $badge_class = 'badge-warning';
                                $status_text = 'API Error';
                            } elseif (in_array($log->response_code, [40101, 40201, 40202])) {
                                $badge_class = 'badge-info';
                                $status_text = 'Info';
                            }

                            // Try to pretty print JSON
                            $pretty_payload = $log->request_payload;
                            $json_payload = json_decode($log->request_payload);
                            if ($json_payload) $pretty_payload = json_encode($json_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                            $pretty_response = $log->response_body;
                            $json_response = json_decode($log->response_body);
                            if ($json_response) $pretty_response = json_encode($json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        ?>
                            <tr class="<?php echo $status_class; ?>">
                                <td style="color:#888;">#<?php echo $log->id; ?></td>
                                <td>
                                    <a href="<?php echo get_edit_post_link($log->order_id); ?>" target="_blank" style="font-weight:600; color:var(--pb-red); text-decoration:none;">
                                        #<?php echo $log->order_id; ?>
                                    </a>
                                </td>
                                <td>
                                    <code style="background:#f0f0f0; padding:3px 6px; border-radius:4px; font-size:11px;"><?php echo esc_html($log->api_type); ?></code>
                                    <?php if (!empty($log->api_url)): ?>
                                        <div style="font-size: 10px; color: #666; margin-top: 5px; word-break: break-all; max-width: 250px; line-height: 1.2;">
                                            <?php echo esc_html($log->api_url); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><span style="background:#eee; padding:2px 6px; border-radius:10px; font-size:11px;"><?php echo $log->attempt; ?> / 3</span></td>
                                <td>
                                    <span class="log-badge <?php echo $badge_class; ?>">
                                        <?php echo $log->response_code ?: 'N/A'; ?>
                                    </span>
                                </td>
                                <td><strong><?php echo $log->http_status; ?></strong></td>
                                <td style="color:#666;"><?php echo date('M j, Y — H:i:s', strtotime($log->created_at)); ?></td>
                                <td style="text-align:right;">
                                    <button type="button" class="button button-small" onclick="toggleLogDetails(<?php echo $log->id; ?>)">View Details</button>
                                </td>
                            </tr>
                            <tr id="log-details-<?php echo $log->id; ?>" style="display:none;" class="log-details-row">
                                <td colspan="8">
                                    <div class="log-details-content">
                                        <?php if (!empty($log->api_url)): ?>
                                            <div style="margin-bottom: 20px; padding: 12px; background: #fff; border: 1px solid #ccd0d4; border-left: 4px solid var(--pb-red); border-radius: 3px;">
                                                <strong style="display: block; margin-bottom: 4px; color: #333; font-size: 13px;">Endpoint URL:</strong>
                                                <code style="font-family: monospace; font-size: 12px; color: #333; word-break: break-all;"><?php echo esc_html($log->api_url); ?></code>
                                            </div>
                                        <?php endif; ?>
                                        <div class="log-details-grid">
                                            <div class="log-details-col">
                                                <strong>Request Payload:</strong>
                                                <pre><?php echo esc_html($pretty_payload); ?></pre>
                                            </div>
                                            <div class="log-details-col">
                                                <strong>Response Body:</strong>
                                                <pre><?php echo esc_html($pretty_response); ?></pre>
                                            </div>
                                        </div>
                                        
                                        <?php if ($log->curl_error): ?>
                                            <div style="margin-top:20px; padding-top:20px; border-top:1px dashed #ddd;">
                                                <strong style="color:var(--pb-error);">System cURL Error:</strong>
                                                <pre style="border-left: 5px solid var(--pb-error); background: #fffafb;"><?php echo esc_html($log->curl_error); ?></pre>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" style="padding:40px; text-align:center; color:#999;">No logs found matching your criteria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'total' => $num_pages,
                    'current' => $current_page
                ));
                ?>
            </div>
            <br class="clear">
        </div>
    </div>

    <script>
    function toggleLogDetails(id) {
        var el = document.getElementById('log-details-' + id);
        if (el.style.display === 'none') {
            el.style.display = 'table-row';
        } else {
            el.style.display = 'none';
        }
    }
    </script>
    <?php
}
