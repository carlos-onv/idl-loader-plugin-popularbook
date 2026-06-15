<?php

if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

add_action( 'init', 'addtocartlogin' );
function addtocartlogin()
{
    if(isset($_REQUEST['add-to-cart-login']) && is_numeric($_REQUEST['add-to-cart-login']) && $_REQUEST['add-to-cart-login']>0)
    {
        if (!is_user_logged_in())
        {
            header("Location:/parents-club");
            exit;
        }
        else
        {
            WC()->cart->empty_cart();
            WC()->cart->add_to_cart( $_REQUEST['add-to-cart-login'], 1 );
            header("Location:/shop-checkout");
            exit;
            
        }
    }
}










// Fixed hardcoded paths
function wo_before_authorize_function_login_redirect( $request ){
	// file_put_contents("logoauth.txt",date("Y-m-d H:i:s")."\n"."wo_before_authorize_method ::: ".serialize($request)."\nPOST:::".serialize($_POST)."\n\n\n\n",FILE_APPEND);
}
add_action('wo_before_authorize_method', 'wo_before_authorize_function_login_redirect');

function wo_before_authorize_function_login_redirect1( $request ){
	// file_put_contents("logoauth.txt",date("Y-m-d H:i:s")."\n"."wo_before_create_client ::: ".serialize($request)."\nPOST:::".serialize($_POST)."\n\n\n\n",FILE_APPEND);
}
add_action('wo_before_create_client', 'wo_before_authorize_function_login_redirect1');


if(
str_contains(serialize($_POST), 'client_id') ||
str_contains(serialize($_REQUEST), 'client_id') ||
str_contains(serialize(getallheaders()), 'Bearer')
    )
{

if(isset($_POST) && count($_POST)>0)
{
    // file_put_contents("logoauth.txt",date("Y-m-d H:i:s")."\n"."wo_POST ::: "."\nPOST:::".serialize($_POST)."\nHeaders ::: ".serialize(getallheaders())."\n\n\n\n",FILE_APPEND);
}

// file_put_contents("logoauth.txt",date("Y-m-d H:i:s")."\n"."wo_REQUEST ::: "."\nREQUEST:::".serialize($_REQUEST)."\nHeaders ::: ".serialize(getallheaders())."\n\n\n\n",FILE_APPEND);

}











add_action('rest_api_init', function () {
    // API #3: Obtain User Information
    // Documentation says: /api/user-center/user/info
    register_rest_route('wp/v2', '/user-center/user/info', [
        'methods'  => ['GET', 'POST'],
        'callback' => 'restapi_getuserinfo_by_token',
        'permission_callback' => '__return_true'
    ]);

    // Legacy route
    register_rest_route('wp/v2', '/getUserInfo', [
        'methods'  => 'POST',
        'callback' => 'restapi_getuserinfo_by_token',
        'permission_callback' => '__return_true'
    ]);
});

/**
 * Bypass global REST authentication blockers for user info routes.
 * The WP OAuth Server plugin blocks unauthenticated REST access by default.
 */
add_filter('rest_authentication_errors', function($result) {
    if (!empty($result)) {
        if (strpos($_SERVER['REQUEST_URI'], '/wp-json/wp/v2/user-center/user/info') !== false || 
            strpos($_SERVER['REQUEST_URI'], '/wp-json/wp/v2/getUserInfo') !== false ||
            strpos($_SERVER['REQUEST_URI'], '/wp-json/wp/v2/orderpaymentcompensate') !== false ||
            strpos($_SERVER['REQUEST_URI'], '/wp-json/wp/v2/orderrefundcompensate') !== false) {
            return null;
        }
    }
    return $result;
}, 99);

/**
 * Identify the user by their OAuth access_token (Bearer token)
 */
function restapi_getuserinfo_by_token($request) {
    $user = null;

    // 1. Try to identify user via Access Token (OAuth Bearer)
    $auth_header = $request->get_header('Authorization');
    if ($auth_header && strpos($auth_header, 'Bearer ') === 0) {
        $access_token = str_replace('Bearer ', '', $auth_header);
        if (function_exists('wo_public_get_access_token')) {
            $token_data = wo_public_get_access_token($access_token);
            if ($token_data && isset($token_data['user_id'])) {
                $user = get_user_by('ID', $token_data['user_id']);
            }
        }
    }

    // 2. Fallback to params (Legacy/Search mode)
    /*
    if (!$user) {
        $params = $request->get_json_params();
        if (empty($params)) {
             $params = $_REQUEST;
        }

        if (!empty($params['email'])) {
            $user = get_user_by('email', sanitize_email($params['email']));
        } else if (!empty($params['parentId'])) {
            $user = get_user_by('ID', (int)$params['parentId']);
        }
    }
    */

    if (!$user) {
        $error_id = uniqid();
        emathsmart_log_api_error(
            1,      // The Order ID involved
            'API #3',     // The API type
            1,       // Which retry attempt this was
            $params,  // The JSON payload we tried to send/receive
            'User not found. Please provide a valid Authorization: Bearer {token} header.',      // The error response from the server
            'InBound Connection Error',    // Any connection errors (optional)
            '200'    // HTTP code, e.g. 500 or 403 (optional)
        );
        
        return new WP_REST_Response([
            'success' => 0,
            'traceId' => $error_id,
            'message' => 'User not found. Please provide a valid Authorization: Bearer {token} header.'
        ], 200);
    }

    return new WP_REST_Response([
        'success' => 1,
        'data' => [
            'parentId'       => $user->ID,
            'email'    => $user->user_email,
            'userName'    => $user->user_login,
            'firstName'     => $user->first_name,
            'lastName'     => $user->last_name,
        ]
    ], 200);
}













add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/pay', [
        'methods'  => 'GET',
        'callback' => 'restapi_pay',
        'permission_callback' => '__return_true'
    ]);
});

function restapi_pay($request) {
    $params = $request->get_json_params();
    
    /*
    appId:eMathSmart
    type:1/Subscribe / 2/Additional Package
    subscribeId :  Parent Club subscription ID
    */
    if(isset($_GET['appId']) && $_GET['appId']=="eMathSmart")
    {
        $type=1;
        if(isset($_GET['type']) && is_numeric($_GET['type']) && $_GET['type']>0 && $_GET['type']<3)
        {
            $type = $_GET['type'];
        }
        $duration=1;
        if(isset($_GET['duration']) && is_numeric($_GET['duration']) && $_GET['duration']>0 && $_GET['duration']<3)
        {
            $duration = $_GET['duration'];
        }
        if(isset($_GET['subscribeId']) && is_numeric($_GET['subscribeId']) && $_GET['subscribeId']>0)
        {
            
        }
        if ($type == 2) {
            return new WP_REST_Response([
                'success' => 1,
                'data' => [
                    'type' => 'redirect',
                    'uri'  => home_url('/product/ai-coins'),
                ]
            ], 200);
        }
        if($duration==1)
        return new WP_REST_Response([
            'success' => 1,
            'data' => [
                'type'       => 'redirect',
                'uri'     => home_url('/product/monthly-subscription'),
            ]
        ], 200);
        if($duration==2)
        return new WP_REST_Response([
            'success' => 1,
            'data' => [
                'type'       => 'redirect',
                'uri'     => home_url('/product/yearly-subscription'),
            ]
        ], 200);
    }
    else
    {
        return new WP_REST_Response([
            'success' => 0,
            'message' => 'appId is required.'
        ], 200);
    }

}















add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/orderpaymentcompensate', [
        'methods'  => 'POST',
        'callback' => 'restapi_orderPaymentCompensate',
        'permission_callback' => '__return_true'
    ]);
});



function restapi_orderPaymentCompensate($request) {
    global $wpdb;
    $params = $request->get_json_params();

    // Verify signature
    if (!restapi_verify_emathsmart_signature($params)) {
        return new WP_REST_Response([
            'code' => 401,
            'message' => 'Invalid or expired signature',
            'traceId' => uniqid(),
            'data' => null
        ], 401);
    }

    $startTime = !empty($params['startTime']) ? (int)$params['startTime'] : 0;
    $endTime   = !empty($params['endTime']) ? (int)$params['endTime'] : time();

    $pageNo    = !empty($params['pageNo']) ? (int)$params['pageNo'] : 1;
    $pageSize  = !empty($params['pageSize']) ? (int)$params['pageSize'] : 20;

    $offset = ($pageNo - 1) * $pageSize;

    $category_slug = emathsmart_get_product_category_slug();

    $count_sql = $wpdb->prepare("
        SELECT COUNT(DISTINCT p.ID)
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->prefix}woocommerce_order_items oi 
            ON p.ID = oi.order_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim 
            ON oi.order_item_id = oim.order_item_id
        INNER JOIN {$wpdb->term_relationships} tr 
            ON tr.object_id = oim.meta_value
        INNER JOIN {$wpdb->term_taxonomy} tt 
            ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t 
            ON tt.term_id = t.term_id
        WHERE p.post_type = 'shop_order'
          AND oim.meta_key = '_product_id'
          AND tt.taxonomy = 'product_cat'
          AND t.slug = %s
          AND p.post_date_gmt >= FROM_UNIXTIME(%d)
          AND p.post_date_gmt <= FROM_UNIXTIME(%d)
    ",
        $category_slug,
        $startTime,
        $endTime
    );

    $totalCount = (int)$wpdb->get_var($count_sql);
    $totalPages = ceil($totalCount / $pageSize);

    $sql = $wpdb->prepare("
        SELECT DISTINCT p.ID, p.post_date_gmt
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->prefix}woocommerce_order_items oi 
            ON p.ID = oi.order_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim 
            ON oi.order_item_id = oim.order_item_id
        INNER JOIN {$wpdb->term_relationships} tr 
            ON tr.object_id = oim.meta_value
        INNER JOIN {$wpdb->term_taxonomy} tt 
            ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t 
            ON tt.term_id = t.term_id
        WHERE p.post_type = 'shop_order'
          AND oim.meta_key = '_product_id'
          AND tt.taxonomy = 'product_cat'
          AND t.slug = %s
          AND p.post_date_gmt >= FROM_UNIXTIME(%d)
          AND p.post_date_gmt <= FROM_UNIXTIME(%d)
        ORDER BY p.post_date_gmt DESC
        LIMIT %d OFFSET %d
    ",
        $category_slug,
        $startTime,
        $endTime,
        $pageSize,
        $offset
    );

    $results = $wpdb->get_results($sql);
    $list = [];

    foreach ($results as $row) {
        $order = wc_get_order($row->ID);
        if (!$order) {
            continue;
        }

        $order_status = $order->get_status();
        $payStatus = 0;
        if (in_array($order_status, ['completed', 'processing', 'refunded'])) {
            $payStatus = 1;
        } elseif (in_array($order_status, ['on-hold', 'pending'])) {
            $payStatus = 2;
        } else {
            $payStatus = 0;
        }

        $user_id = $order->get_user_id();
        $coupons = $order->get_coupon_codes();
        $discount_code = !empty($coupons) ? $coupons[0] : null;

        $type = 1;
        $subscribeId = null;
        $subscriptionType = 2; // Default Month
        $trialType = null;
        $additionalPackageQuantity = null;

        $expireTimestamp = strtotime($row->post_date_gmt) + (365 * 86400);

        $additional_packages = (int) emathsmart_order_has_additional_packages($order);
        if ($additional_packages > 0) {
            // Type 2: AI Coins
            $type = 2;
            $additionalPackageQuantity = $additional_packages;
            $subscriptionType = null;
        } else {
            // Type 1: Subscription
            $type = 1;
            if (function_exists('wcs_get_subscriptions_for_order')) {
                $subscriptions = wcs_get_subscriptions_for_order($order->get_id(), array('order_type' => 'any'));
                foreach ($subscriptions as $sub_obj) {
                    $subscribeId = (string) $sub_obj->get_id();
                    $billing_period = $sub_obj->get_billing_period();
                    $trial_end = $sub_obj->get_date('trial_end');
                    $start_date = $sub_obj->get_date('date_created');
                    $next_payment = $sub_obj->get_date('next_payment');

                    if (!empty($next_payment)) {
                        $expireTimestamp = strtotime($next_payment);
                    }

                    if (!empty($trial_end)) {
                        $subscriptionType = 1; // Trial
                        $trial_end_ts = strtotime($trial_end);
                        $start_date_ts = !empty($start_date) ? strtotime($start_date) : strtotime($row->post_date_gmt);
                        $diff_days = round(($trial_end_ts - $start_date_ts) / 86400);
                        if ($diff_days > 10) {
                            $trialType = 2; // 14 days
                        } else {
                            $trialType = 1; // 7 days
                        }
                    } else if ($billing_period === 'year') {
                        $subscriptionType = 3;
                    } else {
                        $subscriptionType = 2;
                    }
                    break; // One-by-one subscription model
                }
            }
        }

        $list[] = [
            'appId'                     => 'ParentClub',
            'type'                      => $type,
            'orderId'                   => (string)$order->get_id(),
            'parentId'                  => (string)$user_id,
            'subscribeId'               => $subscribeId,
            'payStatus'                 => $payStatus,
            'payAmount'                 => round((float)$order->get_total(), 2),
            'payTimestamp'              => strtotime($row->post_date_gmt),
            'expireTimestamp'           => (int)$expireTimestamp,
            'subscriptionType'          => $subscriptionType,
            'trialType'                 => $trialType,
            'additionalPackageQuantity' => $additionalPackageQuantity,
            'discountCode'              => $discount_code,
            'activityTag'               => null
        ];
    }

    return new WP_REST_Response([
        'code' => 200,
        'message' => 'success',
        'traceId' => uniqid(),
        'data' => [
            'pageNo' => $pageNo,
            'pageSize' => $pageSize,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'list' => $list
        ]
    ], 200);
}
















add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/orderrefundcompensate', [
        'methods'  => 'POST',
        'callback' => 'restapi_orderRefundCompensate',
        'permission_callback' => '__return_true'
    ]);
});
function restapi_orderRefundCompensate($request) {
    global $wpdb;
    $error_id = uniqid();
    $params = $request->get_json_params();

    // Verify signature
    if (!restapi_verify_emathsmart_signature($params)) {
        return new WP_REST_Response([
            'code' => 401,
            'message' => 'Invalid or expired signature',
            'traceId' => uniqid(),
            'data' => null
        ], 401);
    }

    $startTime = !empty($params['startTime']) ? (int)$params['startTime'] : 0;
    $endTime   = !empty($params['endTime']) ? (int)$params['endTime'] : time();
    $pageNo    = !empty($params['pageNo']) ? (int)$params['pageNo'] : 1;
    $pageSize  = !empty($params['pageSize']) ? (int)$params['pageSize'] : 20;
    $offset = ($pageNo - 1) * $pageSize;

    $category_slug = emathsmart_get_product_category_slug();

    $count_sql = $wpdb->prepare("
        SELECT COUNT(DISTINCT p.ID)
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->prefix}woocommerce_order_items oi 
            ON p.ID = oi.order_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim 
            ON oi.order_item_id = oim.order_item_id
        INNER JOIN {$wpdb->term_relationships} tr 
            ON tr.object_id = oim.meta_value
        INNER JOIN {$wpdb->term_taxonomy} tt 
            ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t 
            ON tt.term_id = t.term_id
        WHERE p.post_type = 'shop_order'
          AND p.post_status = 'wc-refunded'
          AND oim.meta_key = '_product_id'
          AND tt.taxonomy = 'product_cat'
          AND t.slug = %s
          AND p.post_date_gmt >= FROM_UNIXTIME(%d)
          AND p.post_date_gmt <= FROM_UNIXTIME(%d)
    ",
        $category_slug,
        $startTime,
        $endTime
    );

    $totalCount = (int)$wpdb->get_var($count_sql);
    $totalPages = ceil($totalCount / $pageSize);

    $sql = $wpdb->prepare("
        SELECT DISTINCT p.ID, p.post_date_gmt
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->prefix}woocommerce_order_items oi 
            ON p.ID = oi.order_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim 
            ON oi.order_item_id = oim.order_item_id
        INNER JOIN {$wpdb->term_relationships} tr 
            ON tr.object_id = oim.meta_value
        INNER JOIN {$wpdb->term_taxonomy} tt 
            ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN {$wpdb->terms} t 
            ON tt.term_id = t.term_id
        WHERE p.post_type = 'shop_order'
          AND p.post_status = 'wc-refunded'
          AND oim.meta_key = '_product_id'
          AND tt.taxonomy = 'product_cat'
          AND t.slug = %s
          AND p.post_date_gmt >= FROM_UNIXTIME(%d)
          AND p.post_date_gmt <= FROM_UNIXTIME(%d)
        ORDER BY p.post_date_gmt DESC
        LIMIT %d OFFSET %d
    ",
        $category_slug,
        $startTime,
        $endTime,
        $pageSize,
        $offset
    );

    $results = $wpdb->get_results($sql);
    $list = [];

    foreach ($results as $row) {
        $order = wc_get_order($row->ID);
        if (!$order) {
            continue;
        }
        $user_id = $order->get_user_id();
        $date_modified = $order->get_date_modified();
        $refund_ts = $date_modified ? $date_modified->getTimestamp() : strtotime($row->post_date_gmt);

        $list[] = [
            'appId' => 'ParentClub',
            'orderId' => (string)$order->get_id(),
            'parentId' => (string)$user_id,
            'refundTimestamp' => $refund_ts
        ];
    }

    emathsmart_log_api_error(
        1,      // The Order ID involved
        'API #8',     // The API type
        1,       // Which retry attempt this was
        $list,  // The JSON payload we tried to send/receive
        'success',      // The error response from the server
        'Request Successful - '.$error_id,    // Any connection errors (optional)
        '200'    // HTTP code, e.g. 500 or 403 (optional)
    );

    return new WP_REST_Response([
        'code' => 200,
        'message' => 'success',
        'traceId' => $error_id,
        'data' => [
            'pageNo' => $pageNo,
            'pageSize' => $pageSize,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'list' => $list
        ]
    ], 200);
}






add_action('wo_before_authorize_method', 'enforce_pkce_for_specific_url');
function enforce_pkce_for_specific_url() {
    // Commented out as eMathSmart APIs #7 and #8 now use Client Credentials, and getuserinfo PKCE is disabled.
    /*
    if (isset($_GET['response_type']) && $_GET['response_type'] === 'code') {
        if (strpos($_SERVER['REQUEST_URI'], 'getuserinfo') !== false) {
            if (!isset($_GET['code_challenge']) || empty($_GET['code_challenge'])) {
                wp_die(
                    'Authorization failed: Missing required PKCE parameters for this endpoint.', 
                    'OAuth 2.0 Error', 
                    array('response' => 400)
                );
            }
        }
        if (strpos($_SERVER['REQUEST_URI'], 'orderpaymentcompensate') !== false) {
            if (!isset($_GET['code_challenge']) || empty($_GET['code_challenge'])) {
                wp_die(
                    'Authorization failed: Missing required PKCE parameters for this endpoint.', 
                    'OAuth 2.0 Error', 
                    array('response' => 400)
                );
            }
        }
        if (strpos($_SERVER['REQUEST_URI'], 'orderrefundcompensate') !== false) {
            if (!isset($_GET['code_challenge']) || empty($_GET['code_challenge'])) {
                wp_die(
                    'Authorization failed: Missing required PKCE parameters for this endpoint.', 
                    'OAuth 2.0 Error', 
                    array('response' => 400)
                );
            }
        }
    }
    */
}

/**
 * Verifies the HMAC-SHA256 signature for incoming REST API requests (APIs #7 and #8)
 */
function restapi_verify_emathsmart_signature($params) {
    if (empty($params['signature']) || empty($params['timestamp']) || empty($params['nonce'])) {
        return false;
    }

    // 1. Replay attack protection (check if timestamp is within 5 minutes)
    $time_diff = abs(time() - (int)$params['timestamp']);
    if ($time_diff > 300) {
        return false; // Request expired
    }

    // 2. Retrieve the shared secret from WP options
    $secret = emathsmart_get_api_secret();

    // 3. Filter out signature and nulls, cast values to string
    $filtered = [];
    foreach ($params as $key => $value) {
        if ($key === 'signature' || $value === null) {
            continue;
        }
        $filtered[$key] = is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
    }

    // 4. Sort keys alphabetically (ASCII ascending)
    ksort($filtered, SORT_STRING);

    // 5. Build signing content string
    $pairs = [];
    foreach ($filtered as $key => $value) {
        $pairs[] = $key . '=' . $value;
    }
    $content = implode('&', $pairs);

    // 6. Calculate signature using base64url format
    $raw = hash_hmac('sha256', $content, $secret, true);
    $calculated_signature = rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');

    // 7. Strict timing-attack-safe comparison
    return hash_equals($calculated_signature, $params['signature']);
}

?>