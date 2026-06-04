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

    // Legacy/Jatin's route
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
            strpos($_SERVER['REQUEST_URI'], '/wp-json/wp/v2/getUserInfo') !== false) {
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

    $startTime = !empty($params['startTime']) ? (int)$params['startTime'] : 0;
    $endTime   = !empty($params['endTime']) ? (int)$params['endTime'] : time();

    $pageNo    = !empty($params['pageNo']) ? (int)$params['pageNo'] : 1;
    $pageSize  = !empty($params['pageSize']) ? (int)$params['pageSize'] : 20;

    $offset = ($pageNo - 1) * $pageSize;
    
    $user = null;

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


    if (!$user) {
        if (empty($params)) {
            $params = $_REQUEST;
        }
        if (!empty($params['email'])) {
            $user = get_user_by(
                'email',
                sanitize_email($params['email'])
            );
        } else if (!empty($params['parentId'])) {
            $user = get_user_by(
                'ID',
                (int)$params['parentId']
            );
        }
    }

    if (!$user) {
        $error_id = uniqid();
        emathsmart_log_api_error(
            1,      // The Order ID involved
            'API #8',     // The API type
            1,       // Which retry attempt this was
            $params,  // The JSON payload we tried to send/receive
            'User not found.',      // The error response from the server
            'InBound Connection Error'.$error_id,    // Any connection errors (optional)
            '200'    // HTTP code, e.g. 500 or 403 (optional)
        );
        return new WP_REST_Response([
            'code' => 404,
            'message' => 'User not found',
            'traceId' => $error_id,
            'data' => null
        ], 200);
    }


    $count_sql = $wpdb->prepare("
        SELECT COUNT(DISTINCT p.ID)

        FROM {$wpdb->posts} p

        INNER JOIN {$wpdb->postmeta} customer_meta
            ON p.ID = customer_meta.post_id
            AND customer_meta.meta_key = '_customer_user'

        WHERE p.post_type = 'shop_subscription'
            AND p.post_date_gmt >= FROM_UNIXTIME(%d)
            AND p.post_date_gmt <= FROM_UNIXTIME(%d)
    ",
        //$user->ID,
        $startTime,
        $endTime
    );
    //AND customer_meta.meta_value = %d

    $totalCount = (int)$wpdb->get_var($count_sql);
    $totalPages = ceil($totalCount / $pageSize);



    $sql = $wpdb->prepare("
        SELECT

            p.ID AS subscription_id,

            COALESCE(
                NULLIF(p.post_parent, 0),
                parent_meta.meta_value,
                ''
            ) AS order_id,

            p.post_status,

            p.post_date_gmt,

            MAX(
                CASE
                    WHEN pm.meta_key = '_order_total'
                    THEN pm.meta_value
                END
            ) AS pay_amount,

            COALESCE(
                oi.order_item_name,
                NULL
            ) AS discount_code

        FROM {$wpdb->posts} p

        LEFT JOIN {$wpdb->postmeta} pm
            ON p.ID = pm.post_id

        LEFT JOIN wp_woocommerce_order_items AS oi
            ON p.ID = oi.order_id
            AND oi.order_item_type = 'coupon'

        LEFT JOIN {$wpdb->postmeta} parent_meta
            ON p.ID = parent_meta.post_id
            AND parent_meta.meta_key = '_order_parent_id'

        INNER JOIN {$wpdb->postmeta} customer_meta
            ON p.ID = customer_meta.post_id
            AND customer_meta.meta_key = '_customer_user'

        WHERE p.post_type = 'shop_subscription'
            AND p.post_date_gmt >= FROM_UNIXTIME(%d)
            AND p.post_date_gmt <= FROM_UNIXTIME(%d)

        GROUP BY p.ID

        ORDER BY p.post_date_gmt DESC

        LIMIT %d OFFSET %d
    ",
        //$user->ID,
        $startTime,
        $endTime,
        $pageSize,
        $offset
    );
    //AND customer_meta.meta_value = %d

    $results = $wpdb->get_results($sql);

    $list = [];

    foreach ($results as $row) {
        $payStatus = 0;
        switch ($row->post_status) {
            case 'wc-active':
                $payStatus = 1;
                break;
            case 'wc-on-hold':
                $payStatus = 2;
                break;
            case 'wc-cancelled':
            case 'wc-failed':
                $payStatus = 0;
                break;
            default:
                $payStatus = 0;
                break;
        }

        $list[] = [

            'appId' => 'ParentClub',
            'type' => 1,
            'orderId' => (string)$row->order_id,
            'parentId' => (string)$user->ID,
            'subscribeId' => (string)$row->subscription_id,
            'payStatus' => $payStatus,
            'payAmount' => (float)$row->pay_amount,
            'payTimestamp' => strtotime($row->post_date_gmt),
            'subscriptionType' => 2,
            'trialType' => null,
            'additionalPackageQuantity' => null,
            'discountCode' => $row->discount_code,
            'activityTag' => null
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
    $startTime = !empty($params['startTime']) ? (int)$params['startTime'] : 0;
    $endTime   = !empty($params['endTime']) ? (int)$params['endTime'] : time();
    $pageNo    = !empty($params['pageNo']) ? (int)$params['pageNo'] : 1;
    $pageSize  = !empty($params['pageSize']) ? (int)$params['pageSize'] : 20;
    $offset = ($pageNo - 1) * $pageSize;

    $user = null;
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

    if (!$user) {
        if (empty($params)) {
            $params = $_REQUEST;
        }
        if (!empty($params['email'])) {
            $user = get_user_by(
                'email',
                sanitize_email($params['email'])
            );
        } else if (!empty($params['parentId'])) {
            $user = get_user_by(
                'ID',
                (int)$params['parentId']
            );
        }
    }

    if (!$user) {
        emathsmart_log_api_error(
            1,      // The Order ID involved
            'API #8',     // The API type
            1,       // Which retry attempt this was
            $params,  // The JSON payload we tried to send/receive
            'User not found.',      // The error response from the server
            'InBound Connection Error'.$error_id,    // Any connection errors (optional)
            '200'    // HTTP code, e.g. 500 or 403 (optional)
        );
        return new WP_REST_Response([
            'code' => 404,
            'message' => 'User not found',
            'traceId' => $error_id,
            'data' => null
        ], 404);
    }

    $count_sql = $wpdb->prepare("
        SELECT COUNT(DISTINCT refund.ID)
        FROM {$wpdb->posts} refund
        INNER JOIN {$wpdb->postmeta} customer_meta
            ON refund.ID = customer_meta.post_id
            AND customer_meta.meta_key = '_customer_user'
        WHERE refund.post_status = 'wc-refunded'
            AND refund.post_date_gmt >= FROM_UNIXTIME(%d)
            AND refund.post_date_gmt <= FROM_UNIXTIME(%d)
    ",
        $startTime,
        $endTime
    );

    $totalCount = (int)$wpdb->get_var($count_sql);

    $totalPages = ceil($totalCount / $pageSize);

    $sql = $wpdb->prepare("
        SELECT
            refund.ID AS order_id,
            refund.post_parent AS order_parent_id,
            refund.post_date_gmt
        FROM {$wpdb->posts} refund
        INNER JOIN {$wpdb->postmeta} customer_meta
            ON refund.ID = customer_meta.post_id
            AND customer_meta.meta_key = '_customer_user'
        WHERE refund.post_status = 'wc-refunded'
            AND refund.post_date_gmt >= FROM_UNIXTIME(%d)
            AND refund.post_date_gmt <= FROM_UNIXTIME(%d)
        ORDER BY refund.post_date_gmt DESC
        LIMIT %d OFFSET %d
    ",
        $startTime,
        $endTime,
        $pageSize,
        $offset
    );

    $results = $wpdb->get_results($sql);
    
    $list = [];

    foreach ($results as $row) {

        $list[] = [
            'appId' => 'ParentClub',
            'orderId' => (string)$row->order_id,
            'parentId' => (string)$user->ID,
            'refundTimestamp' => strtotime($row->post_date_gmt)
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

/**
 * REST API Endpoint for retrieving current user's AI Coin balance
 * Endpoint: GET /wp-json/wp/v2/member/coin-balance
 */
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/member/coin-balance', [
        'methods'             => 'GET',
        'callback'            => 'restapi_get_user_coin_balance',
        'permission_callback' => function () {
            return is_user_logged_in();
        }
    ]);
});

function restapi_get_user_coin_balance($request) {
    $user_id = get_current_user_id();
    if (empty($user_id)) {
        return new WP_REST_Response([
            'success' => 0,
            'message' => 'User not logged in'
        ], 401);
    }

    $bypass_cache = $request->get_param('refresh') === 'true';
    if ($bypass_cache) {
        delete_transient('emathsmart_coin_balance_' . $user_id);
    }

    $balance = emathsmart_get_user_coin_balance($user_id);

    return new WP_REST_Response([
        'success' => 1,
        'coinBalance' => $balance
    ], 200);
}

?>














?>