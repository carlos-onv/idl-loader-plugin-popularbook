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
 * Render the logs page
 */
function emathsmart_render_logs_page() {
    if (!current_user_can('manage_woocommerce')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'emathsmart_log';

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

    $total_items = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE $where", $params));
    $num_pages = ceil($total_items / $per_page);

    $sql = "SELECT * FROM $table_name WHERE $where ORDER BY id DESC LIMIT %d OFFSET %d";
    $query_params = array_merge($params, [$per_page, $offset]);
    $logs = $wpdb->get_results($wpdb->prepare($sql, $query_params));
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

        .log-details-content {
            padding: 25px;
            background: #fcfcfc;
            border-top: 1px solid #eee;
        }

        .log-details-content pre {
            background: #fff;
            border: 1px solid #e1e1e1;
            padding: 15px;
            border-radius: 5px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 12px;
            line-height: 1.6;
            max-height: 500px;
            overflow: auto;
        }

        .log-details-col strong {
            display: block;
            margin-bottom: 10px;
            color: #444;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>

    <div class="wrap emathsmart-admin">
        <div class="emathsmart-notices">
            <div class="notice notice-info is-dismissible">
                <p>This page displays communication logs for eMathSmart APIs. Only failures and manual resends are logged here for debugging.</p>
            </div>
        </div>

        <div class="emathsmart-header" style="flex-direction: column; align-items: flex-start; gap: 10px;">
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
                                <td><code style="background:#f0f0f0; padding:3px 6px; border-radius:4px; font-size:11px;"><?php echo esc_html($log->api_type); ?></code></td>
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
