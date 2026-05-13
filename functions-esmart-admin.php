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
    <div class="wrap">
        <h1 class="wp-heading-inline">eMathSmart API Logs</h1>
        <hr class="wp-header-end">

        <div class="notice notice-info is-dismissible">
            <p>This page displays communication logs for eMathSmart APIs. Only failures and manual resends are logged here for debugging.</p>
        </div>

        <!-- Filters Form -->
        <form method="get" style="margin-bottom: 20px; background: #fff; padding: 15px; border: 1px solid #ccd0d4; border-radius: 4px;">
            <input type="hidden" name="page" value="emathsmart-logs">
            
            <div style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                <div>
                    <label for="order_id">Order ID</label><br>
                    <input type="number" name="order_id" id="order_id" value="<?php echo isset($_GET['order_id']) ? esc_attr($_GET['order_id']) : ''; ?>" placeholder="e.g. 116377">
                </div>
                
                <div>
                    <label for="api_type">API Type</label><br>
                    <select name="api_type" id="api_type">
                        <option value="">All Types</option>
                        <option value="Payment" <?php selected(isset($_GET['api_type']) && $_GET['api_type'] == 'Payment'); ?>>Payment</option>
                        <option value="refund" <?php selected(isset($_GET['api_type']) && $_GET['api_type'] == 'refund'); ?>>Refund</option>
                        <option value="public_exams" <?php selected(isset($_GET['api_type']) && $_GET['api_type'] == 'public_exams'); ?>>Public Exams</option>
                    </select>
                </div>
                
                <div>
                    <label for="date_from">From Date</label><br>
                    <input type="date" name="date_from" id="date_from" value="<?php echo isset($_GET['date_from']) ? esc_attr($_GET['date_from']) : ''; ?>">
                </div>
                
                <div>
                    <label for="date_to">To Date</label><br>
                    <input type="date" name="date_to" id="date_to" value="<?php echo isset($_GET['date_to']) ? esc_attr($_GET['date_to']) : ''; ?>">
                </div>
                
                <div>
                    <button type="submit" class="button button-primary">Filter Logs</button>
                    <a href="admin.php?page=emathsmart-logs" class="button">Clear</a>
                </div>
            </div>
        </form>

        <div class="tablenav top">
            <div class="tablenav-pages">
                <span class="displaying-num"><?php printf(_n('%s item', '%s items', $total_items), number_format_i18n($total_items)); ?></span>
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

        <table class="wp-list-table widefat fixed striped posts">
            <thead>
                <tr>
                    <th scope="col" style="width: 60px;">ID</th>
                    <th scope="col" style="width: 100px;">Order</th>
                    <th scope="col" style="width: 120px;">API Type</th>
                    <th scope="col" style="width: 80px;">Attempt</th>
                    <th scope="col" style="width: 120px;">Code</th>
                    <th scope="col" style="width: 100px;">HTTP</th>
                    <th scope="col">Date (Local)</th>
                    <th scope="col" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php if ($logs): ?>
                    <?php foreach ($logs as $log): 
                        $status_class = '';
                        if ($log->http_status >= 500 || $log->http_status == 0) $status_class = 'log-error-critical';
                        elseif ($log->response_code != 200 && $log->response_code != 40101) $status_class = 'log-error-warning';
                    ?>
                        <tr class="<?php echo $status_class; ?>">
                            <td><?php echo $log->id; ?></td>
                            <td>
                                <a href="<?php echo get_edit_post_link($log->order_id); ?>" target="_blank">
                                    #<?php echo $log->order_id; ?>
                                </a>
                            </td>
                            <td><?php echo esc_html($log->api_type); ?></td>
                            <td><?php echo $log->attempt; ?></td>
                            <td><?php echo $log->response_code; ?></td>
                            <td><?php echo $log->http_status; ?></td>
                            <td><?php echo $log->created_at; ?></td>
                            <td>
                                <button type="button" class="button button-small" onclick="toggleLogDetails(<?php echo $log->id; ?>)">View</button>
                            </td>
                        </tr>
                        <tr id="log-details-<?php echo $log->id; ?>" style="display:none;" class="log-details-row">
                            <td colspan="8">
                                <div style="padding: 15px; background: #f9f9f9; border: 1px solid #ddd;">
                                    <strong>Request Payload:</strong>
                                    <pre style="white-space: pre-wrap; word-break: break-all; background: #fff; padding: 10px; border: 1px solid #eee;"><?php echo esc_html($log->request_payload); ?></pre>
                                    
                                    <strong>Response Body:</strong>
                                    <pre style="white-space: pre-wrap; word-break: break-all; background: #fff; padding: 10px; border: 1px solid #eee;"><?php echo esc_html($log->response_body); ?></pre>
                                    
                                    <?php if ($log->curl_error): ?>
                                        <strong style="color:red;">cURL Error:</strong>
                                        <pre style="white-space: pre-wrap; background: #fff; padding: 10px; border: 1px solid #fee;"><?php echo esc_html($log->curl_error); ?></pre>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8">No logs found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

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

    <style>
        .log-error-critical { background-color: #f8d7da !important; }
        .log-error-warning { background-color: #fff3cd !important; }
        .log-details-row td { padding: 0 !important; }
        .tablenav-pages { float: right; }
        .displaying-num { margin-right: 10px; }
    </style>
    <?php
}

