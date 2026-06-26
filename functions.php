<?php

/**
 * add child styles.
 * 
 * @author CMS
 * @since 1.0.0
 */

require_once("functions-restapi.php");
require_once("functions-esmart.php");
require_once("functions-wpbakery-elements.php");
// require_once("functions-esmart-compensation.php");



function admin_bar(){

  if(is_user_logged_in()){
    add_filter( 'show_admin_bar', '__return_true' , 10000000 );
  }
}
add_action('init', 'admin_bar' );


//add_action( 'wp_print_scripts', 'cyb_list_scripts' );
function cyb_list_scripts()
{
    global $wp_scripts;
    $enqueued_scripts = array();
    foreach ($wp_scripts->queue as $handle) {
        $enqueued_scripts[] = $wp_scripts->registered[$handle]->src;
        print_r($wp_scripts->registered[$handle]);
    }
    exit;
}
//add_action( 'wp_print_styles', 'cyb_list_styles' );
function cyb_list_styles()
{
    global $wp_styles;
    $enqueued_styles = array();
    foreach ($wp_styles->queue as $handle) {
        $enqueued_styles[$handle] = $wp_styles->registered[$handle]->src;
    }
    print_r($enqueued_styles);
    exit;
}

//add_action('wp_enqueue_scripts', 'child_remove_style', 20);
function child_remove_style()
{
    if (!is_admin()) {
        //User-Registration Plugin
        if ($_SERVER['REQUEST_URI'] != "/parents-club-registration") {
            wp_deregister_script('user-registration');
            wp_deregister_script('ur-form-validator');
            wp_deregister_script('ur-common');
            wp_deregister_script('tooltipster');
            wp_deregister_script('user-registration-pro-frontend-script');
            wp_deregister_script('mailcheck');
        }

        // Remove Woocommerce country select from other pages other than cart and checkout
        if ($_SERVER['REQUEST_URI'] != "/shop-cart" && $_SERVER['REQUEST_URI'] != "/shop-checkout") {
            wp_deregister_script('wc-country-select');
            wp_deregister_script('vc_woocommerce-add-to-cart-js');
            wp_deregister_script('wc-add-to-cart');
            wp_deregister_script('js-cookie');
        }
        // Check if WooCommerce is active
        if (function_exists('is_woocommerce')) {

            // Check if we are on a WooCommerce page
            if (! is_woocommerce() && ! is_cart() && ! is_checkout()) {

                ## Dequeue WooCommerce styles
                wp_dequeue_style('woocommerce-layout');
                wp_dequeue_style('woocommerce-general');
                wp_dequeue_style('woocommerce-smallscreen');

                ## Dequeue WooCommerce scripts
                wp_dequeue_script('wc-cart-fragments');
                wp_dequeue_script('woocommerce');
                wp_dequeue_script('wc-add-to-cart');

                wp_deregister_script('jquery-blockui');
                wp_dequeue_script('jquery-blockui');

                wp_deregister_script('js-cookie');
                wp_dequeue_script('js-cookie');

                wp_dequeue_script('woo_discount_pro_script');
            }
            if (! is_cart() && ! is_checkout()) {
                wp_deregister_style('wc-bambora');
                wp_dequeue_script('wc-bambora');
                wp_dequeue_script('bambora-custom-checkout');
                wp_dequeue_script('advanced-flat-rate-shipping-for-woocommerce');
                wp_dequeue_script('sv-wc-payment-gateway-payment-form-v5_11_6');
            }
        }

        // Remove : /wp-content/plugins/wp-redirects-contact-form-7/includes/js/custom.js
        if ($_SERVER['REQUEST_URI'] != "/contact-us" && $_SERVER['REQUEST_URI'] != "/school-ambassador-program-application") {
            wp_dequeue_script('ajax-script');
            wp_dequeue_script('wpcf7-recaptcha');
            wp_dequeue_script('wc-captcha-frontend-script');
            wp_dequeue_script('yspl_cf7r_sweetalert_js');
        }

        if (strpos($_SERVER['REQUEST_URI'], "/product/") === false) {
            wp_dequeue_script('image_zoooom');
            wp_dequeue_script('image_zoooom-init');
        }

        // Revslider
        wp_dequeue_script('tp-tools');
        wp_dequeue_script('revmin');
        //www.popularbook.ca/wp-content/plugins/revslider/sr6/assets/js/rbtools.min.js
        //www.popularbook.ca/wp-content/plugins/revslider/sr6/assets/js/rs6.min.js


        wp_dequeue_style('theme-style');
        ////wp_deregister_script( 'wp-polyfill' );
        wp_deregister_script('regenerator-runtime');

        wp_deregister_script('ultimate-vc-addons-row-bg');
        wp_deregister_script('ultimate-vc-addons-script');

        wp_deregister_script('gtm4wp-ecommerce-generic');
        wp_deregister_script('gtm4wp-woocommerce');

        wp_deregister_script('flex-favorites.js');

        wp_deregister_script('mc4wp-forms-api');

        // WPBakery accordion/tab scripts: skip dequeue on pages that render vc_tta-accordion
        // (parents-club has an FAQ accordion that needs wpb_composer_front_js to toggle).
        if ($_SERVER['REQUEST_URI'] !== '/parents-club' && $_SERVER['REQUEST_URI'] !== '/parents-club/') {
            wp_deregister_script('vc_tta_autoplay_script');
            wp_deregister_script('vc_accordion_script');
            wp_dequeue_script('vc_tta_autoplay_script');
            wp_dequeue_script('vc_accordion_script');

            wp_dequeue_script('wpb_composer_front_js');
        }

        wp_dequeue_script('ultimate-vc-addons-script');
        wp_dequeue_script('ultimate-vc-addons-row-bg');

        wp_dequeue_script('jquery-ui.js');

        wp_dequeue_script('wt-owl-js');

        wp_dequeue_script('yith-wcwtl-frontend');

        wp_dequeue_script('jQAllRangeSliders-min.js');

        wp_dequeue_script('post-favorite');

        wp_dequeue_script('underscore-js');
        wp_dequeue_script('underscore');
        wp_dequeue_script('wp-util');
        wp_dequeue_script('flexslider');
        wp_dequeue_script('owl-carousel-script');
        wp_dequeue_script('owl-carousel');

        wp_dequeue_script('wc-prl-main');

        wp_dequeue_script('bj-handle.js');
        wp_dequeue_script('light-box.js');
        wp_dequeue_script('light-box-js');

        if ($_SERVER['REQUEST_URI'] != "/create-account" && $_SERVER['REQUEST_URI'] != "/login") {
            wp_dequeue_script('um_functions');
            wp_dequeue_script('um_fileupload');
            wp_dequeue_script('um_datetime');
            wp_dequeue_script('um_datetime_date');
            wp_dequeue_script('um_datetime_time');
            wp_dequeue_script('um_crop');
            wp_dequeue_script('um-gdpr');
            wp_dequeue_script('um_profile');
            wp_dequeue_script('um_account');
            wp_dequeue_script('um_modal');
            wp_dequeue_script('um_scrollbar');
            wp_dequeue_script('um_responsive');
        }

        wp_deregister_script('acy_front_messages_js');
        wp_deregister_script('awdr-main');
        wp_deregister_script('awdr-dynamic-price');
        wp_deregister_script('woo_discount_pro_script');
        wp_deregister_script('woo_discount_pro_script-js');
    }
}
//add_action('wp_print_styles', 'wps_deregister_styles', 100);
function wps_deregister_styles()
{
    if (!is_admin()) {
        if ($_SERVER['REQUEST_URI'] != "/contact-us" && $_SERVER['REQUEST_URI'] != "/school-ambassador-program-application") {
            wp_deregister_style('contact-form-7');
            wp_deregister_style('sweetalert2');
            wp_deregister_style('yspl_cf7r_sweetalert_css');
            wp_deregister_style('yspl_cf7r_frontend_css');
        }


        wp_deregister_style('acy_front_messages_css');
        wp_deregister_style('woo_discount_pro_style');

        // Revslider
        wp_deregister_style('revslider-material-icons');
        wp_deregister_style('revslider-basics-css');
        wp_deregister_style('rs-color-picker-css');
        wp_deregister_style('revbuilder-ddTP');
        wp_deregister_style('rs-roboto');
        wp_deregister_style('tp-material-icons');
        //www.popularbook.ca/wp-content/plugins/revslider/sr6/assets/fonts/revicons/revicons.woff?5510888
        //www.popularbook.ca/wp-content/plugins/revslider/sr6/assets/css/rs6.css
        wp_deregister_style('rs-plugin-settings');



        wp_deregister_style('wordfenceAJAXcss');
        wp_deregister_style('wc-captcha-frontend');

        //wp_deregister_style('dashicons');


        //UltimateMember
        wp_deregister_style('um_modal');
        wp_deregister_style('um_profile');
        wp_deregister_style('um_account');
        wp_deregister_style('um_misc');
        wp_deregister_style('um_fileupload');
        wp_deregister_style('um_datetime');
        wp_deregister_style('um_datetime_date');
        wp_deregister_style('um_datetime_time');
        wp_deregister_style('um_scrollbar');
        wp_deregister_style('um_crop');
        wp_deregister_style('um_default_css');
        //wp_deregister_style('um_styles');
        wp_deregister_style('um_responsive');

        //wp_deregister_style('um_tipsy');  // Tipsy/Raty/fonticons together required for design
        //wp_deregister_style('um_raty');  // Tipsy/Raty/fonticons together required for design
        //wp_deregister_style('um_fonticons_ii');  // Tipsy/Raty/fonticons together required for design


        if ($_SERVER['REQUEST_URI'] != "/create-account" && $_SERVER['REQUEST_URI'] != "/login") {
            wp_deregister_style('select2');
        }

        wp_deregister_style('vc_google_fonts_avertaboldregular');

        wp_deregister_style('woo_discount_pro_style');

        wp_deregister_style('wordfenceAJAXcss');

        wp_deregister_style('font-awesome-min');

        wp_deregister_style('bsf-Defaults');

        wp_deregister_style('carousel-css');
        wp_deregister_style('carousel-theme-css');
        wp_deregister_style('owl-carousel-style');

        wp_deregister_style('cms-plugin-stylesheet');

        wp_deregister_style('lex-favorites-style.css');

        wp_deregister_style('advanced-flat-rate-shipping-for-woocommerce');

        wp_deregister_style('wt-woocommerce-related-products');

        wp_deregister_style('wc-prl-css');

        wp_deregister_style('ultimate-vc-addons-style-min');

        wp_deregister_style('flex-favorites-style.css');

        wp_deregister_style('yith-wcwtl-style');

        // Loading twice, load only the second one custom loaded via child theme
        wp_deregister_style('book-junky-static');
        wp_deregister_style('custom-dynamic'); // Is empty
        wp_deregister_style('book-junky'); // Not required



        wp_deregister_style('js_composer_custom_css');

        // Required and loading via custom lazy loader
        wp_deregister_style('js_composer_front');
        wp_deregister_style('bootstrap');
        wp_deregister_style('book-junky-font');
        wp_deregister_style('vc_tta_style');



        //wp_deregister_style('bootstrap'); //breaks styling

        //wp_deregister_style('js_composer_front'); //breaks styling

        wp_deregister_style('iThing.css');

        // Remove Woocommerce country select from other pages other than cart and checkout
        if ($_SERVER['REQUEST_URI'] != "/shop-cart" && $_SERVER['REQUEST_URI'] != "/shop-checkout") {
            wp_deregister_style('wc-country-select');
            wp_deregister_style('vc_woocommerce-add-to-cart-js');
            wp_deregister_style('wc-add-to-cart');
            wp_deregister_style('js-cookie');
        }
        // Check if WooCommerce is active
        if (function_exists('is_woocommerce')) {
            // Check if we are on a WooCommerce page
            if (! is_woocommerce() && ! is_cart() && ! is_checkout()) {
                wp_deregister_style('flexible_shipping_notices');
            }
            if (! is_cart() && ! is_checkout()) {
                wp_deregister_style('wc-bambora');
                wp_deregister_style('advanced-flat-rate-shipping-for-woocommerce');
                wp_deregister_style('sv-wc-payment-gateway-payment-form-v5_11_6');
            }
        }


        //User-Registration Plugin
        if ($_SERVER['REQUEST_URI'] != "/parents-club-registration") {
            wp_deregister_style('sweetalert2');
            wp_deregister_style('user-registration-general');
            wp_deregister_style('user-registration-smallscreen');
            wp_deregister_style('user-registration-my-account-layout');
            wp_deregister_style('tooltipster');
            wp_deregister_style('user-registration-pro-frontend-style');
        }
    }
}
function remove_scripts_styles_footer()
{
    wp_deregister_style('vc_tta_style'); //required for FAQ
    wp_deregister_style('rs-plugin-settings');

    wp_deregister_script('vc_tta_autoplay_script');
    wp_deregister_script('vc_accordion_script');
    wp_dequeue_script('vc_tta_autoplay_script');
    wp_dequeue_script('vc_accordion_script');
}
//add_action('wp_footer', 'remove_scripts_styles_footer');

// Deregister from footer as well
add_action('wp_print_scripts', 'wpdocs_dequeue_script');
function wpdocs_dequeue_script()
{
    if (!is_admin()) {
        wp_deregister_script('woo_discount_pro_script');
        wp_deregister_script('woo_discount_pro_script-js');

        //User-Registration Plugin
        if ($_SERVER['REQUEST_URI'] != "/parents-club-registration" && $_SERVER['REQUEST_URI'] != "/parents-club") {
            wp_deregister_script('user-registration');
            wp_deregister_script('ur-form-validator');
            wp_deregister_script('ur-common');
            wp_deregister_script('tooltipster');
            wp_deregister_script('user-registration-pro-frontend-script');
            wp_deregister_script('mailcheck');
        }


        if ($_SERVER['REQUEST_URI'] != "/shop-cart" && $_SERVER['REQUEST_URI'] != "/shop-checkout") {
            wp_deregister_script('wc-country-select');
        }

        // Revslider
        wp_dequeue_script('tp-tools');
        //wp_dequeue_script('revmin');

        wp_dequeue_script('ultimate-vc-addons-script');
        wp_dequeue_script('ultimate-vc-addons-row-bg');

        wp_dequeue_script('jquery-ui.js');

        wp_dequeue_script('js_composer_front');

        wp_dequeue_script('yith-wcwtl-frontend');

        wp_dequeue_script('jQAllRangeSliders-min.js');

        wp_dequeue_script('post-favorite');

        wp_dequeue_script('underscore-js');
        wp_dequeue_script('underscore');
        wp_dequeue_script('wp-util');
        wp_dequeue_script('flexslider');
        wp_dequeue_script('owl-carousel-script');
        wp_dequeue_script('owl-carousel');

        wp_dequeue_script('wc-prl-main');

        wp_dequeue_script('bj-handle.js');
        wp_dequeue_script('light-box.js');
        wp_dequeue_script('light-box-js');

        if ($_SERVER['REQUEST_URI'] != "/create-account" && $_SERVER['REQUEST_URI'] != "/login") {
            wp_dequeue_script('um_functions');
            wp_dequeue_script('um_fileupload');
            wp_dequeue_script('um_datetime');
            wp_dequeue_script('um_datetime_date');
            wp_dequeue_script('um_datetime_time');
            wp_dequeue_script('um_crop');
            wp_dequeue_script('um-gdpr');
            wp_dequeue_script('um_profile');
            wp_dequeue_script('um_account');
            wp_dequeue_script('um_modal');
            wp_dequeue_script('um_scrollbar');
            wp_dequeue_script('um_responsive');
        }
    }
}


//add_filter('script_loader_tag', 'async_filter', 10, 2);
function async_filter($tag, $handle)
{
    if (!is_admin()) {
        if ($handle == "bootstrap") {
            return str_replace(' src=', ' async src=', $tag);
        }
        if ($handle == "advanced-flat-rate-shipping-for-woocommerce") {
            return str_replace(' src=', ' async src=', $tag);
        }
        if ($handle == "image_zoooom-init") {
            return str_replace(' src=', ' async src=', $tag);
        }
        if ($handle == "ultimate-vc-addons-script") {
            return str_replace(' src=', ' async src=', $tag);
        }
        if ($handle == "wt-owl-js") {
            return str_replace(' src=', ' async src=', $tag);
        }
        if ($handle == "image_zoooom") {
            return str_replace(' src=', ' async src=', $tag);
        }
        if ($handle == "tp-tools") {
            return str_replace(' src=', ' async src=', $tag);
        }
        if ($handle == "revmin") {
            return str_replace(' src=', ' async src=', $tag);
        }
        if ($handle == "ultimate-vc-addons-row-bg") {
            return str_replace(' src=', ' async src=', $tag);
        }
        if ($handle == "jquery-ui.js") {
            return str_replace(' src=', ' async src=', $tag);
        }
    }
    return $tag;
}


if ($_SERVER['REQUEST_URI'] != "/contact-us" && $_SERVER['REQUEST_URI'] != "/school-ambassador-program-application") {
    add_filter('wpcf7_load_js', '__return_false');
    add_filter('wpcf7_load_css', '__return_false');
}




/*
function book_junky_enqueue_styles()
{
    $parent_style = 'book-junky';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array($parent_style));
}
*/

function rs_avada_logout_link_shortcode($atts, $content = '')
{
    $redirect = home_url();
    if (is_user_logged_in()) {
        $link = wp_logout_url($redirect);
    } else {
        $link = wp_login_url($redirect);
    }
    return $link;
}

add_shortcode('rs_logout', 'rs_avada_logout_link_shortcode');

// add_action('wp_enqueue_scripts', 'book_junky_enqueue_styles', PHP_INT_MAX);

/**
 * Replace parent theme's Bootstrap 3.3.6 (incompatible with jQuery 3) and
 * book-junky.js (unguarded rangeSlider call) with patched child-theme copies.
 * (Obsolete in plugin context - commented out to prevent loading errors)
 */
/*
function bjc_replace_parent_scripts()
{
    wp_deregister_script('bootstrap');
    wp_enqueue_script(
        'bootstrap',
        get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js',
        array('jquery'),
        '3.4.1',
        true
    );

    wp_deregister_script('book-junky');
    wp_enqueue_script(
        'book-junky',
        get_stylesheet_directory_uri() . '/assets/js/book-junky.js',
        array('jquery'),
        '1.0.1',
        true
    );
}
add_action('wp_enqueue_scripts', 'bjc_replace_parent_scripts', PHP_INT_MAX);
*/


remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');


remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart');

add_filter('auto_update_plugin', '__return_false');

add_filter('auto_update_theme', '__return_false');



add_action('pre_get_posts', 'wpse_disable_pagination');
function wpse_disable_pagination($query)
{
    if ( function_exists('is_product_category') && is_product_category('clearance')) {
        $query->set('posts_per_page', '-1');
    }
}



function disable_new_posts_for_banners()
{
    // Hide sidebar link
    echo '<style tyle="text/css"> #wp-admin-bar-new-promotional_banners{display:none;}; </style>';
    global $submenu;
    global $pagenow;
    unset($submenu['edit.php?post_type=promotional_banners'][10]);

    // Hide link on listing page
    if ((isset($_GET['post_type']) && $_GET['post_type'] == 'promotional_banners') || (($pagenow == 'post.php') || (get_post_type() == 'promotional_banners'))) {
        echo '<style type="text/css">
        #favorite-actions, .add-new-h2, .tablenav { display:none; }
    	.page-title-action{display:none;}
        </style>';
    }
}
//add_action('admin_menu', 'disable_new_posts_for_banners');


function add_banner_product_search()
{



    if ((strpos($_SERVER["REQUEST_URI"], "/clearance") || strpos($_SERVER["REQUEST_URI"], "paw-patrol")) && function_exists('is_product_category') && (is_product_category())) {

        $posts = get_posts([

            'post_type' => 'promotional_banners',

            'post_status' => 'publish',

            'numberposts' => 1

            // 'order'    => 'ASC'

        ]);



        //echo $posts[0]->ID;



        //print_r($image[0]);
        if (strpos($_SERVER["REQUEST_URI"], "/clearance")) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($posts[0]->ID), 'single-post-thumbnail');
        }
        if (strpos($_SERVER["REQUEST_URI"], "paw-patrol") || strpos($_SERVER["REQUEST_URI"], "visual-mathsmart-series-clearance")) {
            $image = array('https://www.popularbook.ca/wp-content/uploads/Clearance_Banner_80off.jpg');
        }

?><script>
            jQuery(document).ready(function() {

                jQuery('.wrap-archive').prepend('<img id="theImg" src="<?php echo $image[0]; ?>" />');



            });
        </script><?php

                }
            }



            add_action('wp_head', 'add_banner_product_search');





            function hide_update_notice_to_all_but_admin_users()

            {

                if (!current_user_can('update_core')) {

                    remove_action('admin_notices', 'update_nag', 3);
                }
            }

            add_action('admin_head', 'hide_update_notice_to_all_but_admin_users', 1);



            function book_junky_search_subject_handler($q)

            {

                if (!is_array($q)) {

                    $tax_query = array();
                }

                if (isset($_REQUEST['bj_action']) && $q->query['post_type'] == 'product' && isset($_REQUEST['cat_subject']) && $_REQUEST['cat_subject'] != '') {

                    $tax_query = $q->get('tax_query');
                    if (!is_array($q)) {

                        $tax_query = array();
                    }
                    $tax_query['relation'] = "AND";

                    array_push($tax_query, array(

                        'taxonomy' => 'product_cat',

                        'field' => 'id',

                        'terms' => array($_REQUEST['cat_subject'])

                    ));

                    $q->set('tax_query', apply_filters('bj_query_taxonomies', $tax_query));
                }

                if (isset($_REQUEST['bj_action']) && $q->query['post_type'] == 'product' && isset($_REQUEST['product_cat']) && $_REQUEST['product_cat'] != '') {

                    $tax_query = $q->get('tax_query');
                    if (!is_array($q)) {

                        $tax_query = array();
                    }
                    $tax_query['relation'] = "AND";

                    array_push($tax_query, array(

                        'taxonomy' => 'product_cat',

                        'field' => 'slug',

                        'terms' => array($_REQUEST['product_cat'])

                    ));

                    $q->set('tax_query', apply_filters('bj_query_taxonomies', $tax_query));
                }



                if (isset($_REQUEST['bj_action']) && $q->query['post_type'] == 'product') {

                    $meta_query = $q->get('meta_query');

                    //echo "<div style='display: none;'>";print_r($q);echo "</div>";
                    if (!is_array($q)) {
                        $meta_query = array();
                    }
                    $meta_query['relation'] = "OR";

                    array_push($meta_query, array(

                        'relation' => 'OR',

                        array(

                            'key' => '_thumbnail_id',

                            'value' => '',

                            'compare' => 'NOT IN'

                        )

                    ));

                    $q->set('meta_query', apply_filters('bj_filter_by_meta', $meta_query));

                    //$q->set( 'meta_query', $meta_query );

                    //echo "<div style='display: none;'>";print_r($q);echo "</div>";

                }



                /*if(isset($_REQUEST['bj_action']) && $q->query['post_type']=='product' && isset($_REQUEST['s']) && $_REQUEST['s']!=''){

        $meta_query = $q->get( 'meta_query');

        //echo "<div style='display: none;'>";print_r($q);echo "</div>";

        $meta_query['relation'] = "OR";

        array_push($meta_query,array(

            'relation' => 'OR',

            array(

                'key' => '_sku',

                'value' => $_REQUEST['s'],

                'type' => 'numeric',

                'compare' => 'LIKE'

            )

        ));

        //$q->set( 'meta_query', apply_filters('bj_filter_by_meta', $meta_query) );

        $q->set( 'meta_query', $meta_query );

        //echo "<div style='display: none;'>";print_r($q);echo "</div>";

    }*/



                if (isset($_REQUEST['bj_action']) && $q->query['post_type'] == 'product' && isset($_REQUEST['s']) && $_REQUEST['s'] != '') {



                    $meta_query = $q->get('meta_query');
                    if (!is_array($q)) {
                        $meta_query = array();
                    }
                    $meta_query['relation'] = "OR";

                    array_push($meta_query, array(

                        'relation' => 'OR',

                        array(

                            'key' => '_sku',

                            'value' => $_REQUEST['s'],

                            'type' => 'numeric',

                            'compare' => 'LIKE'

                        )

                    ));

                    $q->set('meta_query', apply_filters('bj_filter_by_meta', $meta_query));



                    $title = $_REQUEST['s'];

                    add_filter('get_meta_sql', function ($sql) use ($title) {



                        global $wpdb;



                        //echo "<pre>";print_r($sql);echo "</pre>";



                        // Only run once:

                        static $nr = 0;

                        if (0 != $nr++) return $sql;



                        // Modify WHERE part:

                        /*$sql['where'] = sprintf(

                " OR wp_postmeta.meta_value IN ('".$title."')  AND ( %s ) ",

                mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )

            );*/

                        /*$sql['where'] = sprintf(

                " OR ( wp_postmeta.meta_value LIKE '%%".$title."%%') AND ( %s ) ",

                mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )

            );*/

                        if (is_numeric($title)) {

                            $sql['where'] = sprintf(

                                " OR wp_postmeta.meta_value LIKE '%%" . $title . "%%'  AND ( %s ) ",

                                mb_substr($sql['where'], 5, mb_strlen($sql['where']))

                            );

                            return $sql;
                        }
                    });
                }
            }

            add_action('pre_get_posts', 'book_junky_search_subject_handler', 11, 2);



            function hd_posts_where($where, $wp_query)
            {

                global $wpdb;





                //echo $where;

                //echo "<div style='display: none;'>";print_r($where);echo "</div>";

                return $where;
            }

            add_filter('posts_where', 'hd_posts_where', 11, 2);



            function wpb_woo_my_account_order($menu_links)
            {

                //echo "<pre>";print_r($menu_links);echo "</pre>";

                unset($menu_links['downloads']);

                //unset($menu_links['book-shelf']);

                $menu_links['book-shelf'] = esc_html__('My Wishlist', 'book-junky');

                return $menu_links;
            }

            add_filter('woocommerce_account_menu_items', 'wpb_woo_my_account_order', 11, 2);



            function wpb_woo_my_account_endpoint()
            {

                //add_rewrite_endpoint( 'information', EP_PAGES ); 

                add_rewrite_endpoint("wishlist", EP_ROOT | EP_PAGES);
            }

            add_action('init', 'wpb_woo_my_account_endpoint');

            function wpb_woo_my_account_endpoint_content()
            {

                $current_uid = get_current_user_id();

                $list_book_id = $current_uid !== 0 ? get_user_meta($current_uid, 'fs_favor_ids', true) : "";

                $list_id = explode(',', $list_book_id);

                array_shift($list_id);

                wc_get_template('myaccount/bookshelf.php', array('books' => $list_id));
            }

            add_action('woocommerce_account_information_endpoint', 'wpb_woo_my_account_endpoint_content');



            function add_text_after_order_total()
            {

                echo '<label style="width:100%;text-align:center;margin-top:10px;">';
                _e('Prices are payable in Canadian dollars.<br>Free Shipping $75.00 excludes NWT, Yukon and Nunavut.', 'book-junky');
                echo '</label>';
            };

            add_action('woocommerce_review_order_after_order_total', 'add_text_after_order_total', 11, 0);

            add_action('woocommerce_after_cart', 'add_text_after_order_total', 11, 0);



            function action_woocommerce_before_cart($wccm_before_checkout)
            {

                //echo '<a href="/buy-one-get-one"><img style="display: block; margin-left: auto; margin-right: auto;" src="'.get_stylesheet_directory_uri().'/assets/PBC_August_FreeShipping_Small.jpg" /></a><br/>';

            };

            add_action('woocommerce_before_cart', 'action_woocommerce_before_cart', 9, 1);



            /*function ad_filter_menu($sorted_menu_objects, $args) {

    if ($args->theme_location != 'primary')  

        return $sorted_menu_objects;



    if (!is_user_logged_in()) {

        foreach ($sorted_menu_objects as $key => $menu_object) {

            if ($menu_object->title == 'Download Centre') {

                $menu_object->url = get_permalink(4093);

            }

            if ($menu_object->title == 'Canadian Curriculum SummerSmart') {

                $menu_object->url = get_permalink(4093);

            }

            if ($menu_object->title == 'Curriculum-based ScienceSmart Experiments') {

                $menu_object->url = get_permalink(4093);

            }

        }

    }

    return $sorted_menu_objects;

}

add_filter('wp_nav_menu_objects', 'ad_filter_menu', 10, 2);*/









            add_filter('woocommerce_payment_complete_order_status', 'hd_woocommerce_payment_complete_order_status', 10, 2);

            function hd_woocommerce_payment_complete_order_status($order_status, $order_id)
            {



                $order = wc_get_order($order_id);



                $txt = "";

                $txt .= "==============================================\n";

                $txt .= "Order ID : " . $order_id . ", Order Status : " . $order_status . "\n";

                $txt .= "==============================================\n\n";

                $items = $order->get_items();

                $txt .= "Order Items : " . json_encode($items) . "\n\n";

                file_put_contents('order-logs.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);



                return $order_status;
            }



            add_action('woocommerce_checkout_order_processed', 'hd_order_processed_log',  10, 1);

            function hd_order_processed_log($order_id)
            {

                $order = new WC_Order($order_id);

                $items = $order->get_items();

                $txt = "";

                $txt .= "==============================================\n";

                $txt .= "Order ID : " . $order_id . ", woocommerce_checkout_order_processed \n";

                $txt .= "==============================================\n\n";

                $txt .= "Order Items : " . json_encode($items) . "\n\n";

                file_put_contents('order-logs.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
            }



            add_action('woocommerce_checkout_create_order', 'hd_checkout_create_order_log', 10, 2);

            function hd_checkout_create_order_log($order, $data)
            {

                $items = $order->get_items();

                $txt = "";

                $txt .= "==============================================\n";

                $txt .= "woocommerce_checkout_create_order \n";

                $txt .= "==============================================\n\n";

                $txt .= "Order Items : " . json_encode($items) . "\n\n";

                file_put_contents('order-logs.txt', $txt . PHP_EOL, FILE_APPEND | LOCK_EX);
            }

            /**

             * Change number of products that are displayed per page (shop page)

             */

            add_filter('loop_shop_per_page', 'new_loop_shop_per_page', 20);



            function new_loop_shop_per_page($cols)
            {

                // $cols contains the current number of products per page based on the value stored on Options –> Reading

                // Return the number of products you wanna show per page.

                $cols = 12;

                return $cols;
            }



            //MK June 6, 2022

            add_action('wp_footer', 'checkout_mc_checkbox_pos');

            function checkout_mc_checkbox_pos()
            {

                    ?>

    <script>
        jQuery(document).ready(function() {

            //jQuery('.woocommerce-checkout .mc4wp-checkbox.mc4wp-checkbox-woocommerce').insertBefore('.woocommerce-checkout .checkout.woocommerce-checkout');

        });
    </script>

    <style>
        p.mc4wp-checkbox.mc4wp-checkbox-woocommerce {

            margin-top: 15px;

        }
    </style>

    <?php

            }



            //Custom Sorting by order field

            function add_new_custom_postmeta_orderby($sortby)
            {

                $sortby['default']     = __('Default', 'woocommerce');

                return $sortby;
            }

            add_filter('woocommerce_catalog_orderby', 'add_new_custom_postmeta_orderby');

            function add_custom_postmeta_ordering_args($sort_args)
            {

                $orderby_value = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

                switch ($orderby_value) {

                    case 'default':

                        $sort_args['orderby']  = 'meta_value_num';

                        $sort_args['order']    = 'asc';

                        $sort_args['meta_key'] = 'csort_order';

                        break;

                    case 'title':

                        $sort_args['orderby'] = 'title';

                        $sort_args['order'] = 'ASC';
                }

                return $sort_args;
            }

            add_filter('woocommerce_get_catalog_ordering_args', 'add_custom_postmeta_ordering_args');

            add_filter('woocommerce_default_catalog_orderby', 'mk_default_catalog_orderby');

            function mk_default_catalog_orderby($sort_by)
            {

                $manual_sorting_archives = array('ecomplete-mathsmart', 'complete-mathsmart', 'complete-canadian-curriculum', 'complete-summersmart', 'canadian-curriculum-summersmart', 'complete-englishsmart', 'paw-patrol', 'english-workbooks', 'lets-tackle-series-english', 'integrated-subjects', 'canadian-curriculum-mathsmart', 'lets-tackle-series-math', 'math-workbooks', 'clearance'); //Add category slug , separated 'cat-slug'

                if (is_product_category($manual_sorting_archives)) {

                    return 'default';
                } else {

                    return 'title';
                }
            }



            // Disable cart

            //add_filter( 'woocommerce_is_purchasable','__return_false',10,2);





            //Refresh Rates

            /*function update_woocommerce_shipping_region_change(){

  if ( function_exists('is_checkout') && is_checkout() ) {

    ?>

    <script>

      jQuery( document ).ready(function() {

        jQuery('#billing_country').on('change', function() {

            console.log('country changed');

            

        });

      });

    </script>

    <?php 

  }

}

add_action('wp_print_footer_scripts', 'update_woocommerce_shipping_region_change');*/



            /* code to show Popup - login */

            function login_popup1()
            {

                if (function_exists('cshlg_add_login_form')) : ?>

        <div class="ubtn-ctn-center red-border-btn grey-btn show-login">

            <a class="go_to_login_link ubtn-link ult-adjust-bottom-margin ubtn-center ubtn-small red-border-btn grey-btn show-login" href="https://www.popularbook.ca/wp-login.php">

                <button type="button" id="ubtn-71599" class="ubtn ult-adjust-bottom-margin ult-responsive ubtn-small ubtn-fade-bg  none  ubtn-sep-icon ubtn-sep-icon-at-right  ubtn-center   tooltip-6334629b7b01c"

                    data-border-color="#454545" data-shadow-click="none" data-ultimate-target="#ubtn-7156"

                    style="font-weight:normal;border-radius:12px;border-width:2px;border-color:#454545;border-style:solid;color: #000000;">

                    <span class="ubtn-data ubtn-icon">

                        <i class="Defaults-chevron-right" style="font-size:20px;color:#ffffff;"></i>

                    </span>

                    <span class="ubtn-hover" style="background-color:"></span>

                    <span class="ubtn-data ubtn-text ">Club Login</span>

                </button></a>

        </div>

    <?php else : ?>

        <a href="<?php echo esc_url(home_url('/wp-admin')); ?>">

            <?php esc_html_e('Sign in / Register', 'book-junky'); ?></a>

    <?php endif;
            }

            add_shortcode('login_popup1', 'login_popup1');



            /* code to show Popup - login */

            function login_popup()
            {

                if (function_exists('cshlg_add_login_form')) : ?>

        <?php /*  cshlg_link_to_login(); */ ?>

        <div class="ubtn-ctn-center red-border-btn grey-btn show-login">

            <a class="go_to_login_pop go_to_login_link1 ubtn-link ult-adjust-bottom-margin ubtn-center ubtn-small red-border-btn grey-btn show-login" href="#">

                <button type="button" id="ubtn-71599" class="ubtn ult-adjust-bottom-margin ult-responsive ubtn-small ubtn-fade-bg  none  ubtn-sep-icon ubtn-sep-icon-at-right  ubtn-center   tooltip-6334629b7b01c"

                    data-border-color="#454545" data-shadow-click="none" data-ultimate-target="#ubtn-7156"

                    style="font-weight:normal;border-radius:12px;border-width:2px;border-color:#454545;border-style:solid;color: #000000;">

                    <span class="ubtn-data ubtn-icon">

                        <i class="Defaults-chevron-right" style="font-size:20px;color:#ffffff;"></i>

                    </span>

                    <span class="ubtn-hover" style="background-color:"></span>

                    <span class="ubtn-data ubtn-text ">Club Login</span>

                </button></a>

        </div>

    <?php else : ?>

        <a href="<?php echo esc_url(home_url('/wp-admin')); ?>">

            <?php esc_html_e('Sign in / Register', 'book-junky'); ?></a>

    <?php endif;
            }

            add_shortcode('login_popup', 'login_popup');





//Parent Club Discount
add_action('woocommerce_before_calculate_totals', 'apply_group_discount_coupon', 20, 1);
function apply_group_discount_coupon($cart)
{
    if (is_admin() && ! defined('DOING_AJAX'))
        return;
    global $woocommerce;
    $coupon_name = "parents-club-discount";
    $user_id = get_current_user_id();
    $discount_enabled = get_user_meta($user_id, 'user_registration_check_box_1661192013', true);
    $coupon_code = sanitize_title($coupon_name);

    $has_ai_coins = false;
    if (function_exists('emathsmart_cart_contains_ai_coins') && emathsmart_cart_contains_ai_coins($cart)) {
        $has_ai_coins = true;
    }

    if (!$has_ai_coins && !WC_Subscriptions_Cart::cart_contains_subscription() && $discount_enabled && ! $cart->has_discount($coupon_code)) {
        $cart->apply_coupon($coupon_code);
    } elseif (($has_ai_coins || !$discount_enabled) && $cart->has_discount($coupon_code)) {
        $cart->remove_coupon($coupon_code);
    } else {
        return;
    }
}

            /*

add_action('woocommerce_checkout_process', 'required_checkout_fields');

function required_checkout_fields() {

    // Check if set, if its not set add an error.

    if ( ! $_POST['_mc4wp_subscribe_woocommerce'] )

        wc_add_notice( __( 'Please accept our Terms & Conditions.' ), 'error' );

}

*/



            /* Parents Club Notification on Single Product Page */

            function parents_club_notification()
            {

                $message = "";

                $user_id = get_current_user_id();

                $discount_enabled = get_user_meta($user_id, 'user_registration_check_box_1661192013', true);

                if ($discount_enabled) {

                    $message = "Parent's Club discount for this product will appear at checkout";
                    echo "<p>";
                    esc_html_e($message, 'book-junky');
                    echo "</p>";
                }
            }

            add_shortcode('parents_club_notification', 'parents_club_notification');



            add_filter('body_class', 'pc_member_body_class');

            function pc_member_body_class($classes)
            {

                $user_id = get_current_user_id();

                $pc_member = get_user_meta($user_id, 'user_registration_check_box_1661192013', true);

                if ($pc_member) {

                    $classes[] = "isParentClubMember";
                }

                if (empty($user_id)) {

                    $classes[] = "guestUser";
                }

                return $classes;
            }





            // enable product reviews

            add_action('transition_post_status', 'creating_a_new_product', 10, 3);

            function creating_a_new_product($new_status, $old_status, $post)
            {

                if ($old_status != 'publish' && $new_status == 'publish' && !empty($post->ID)  && in_array($post->post_type, array('product'))) {

                    if ($post->comment_status != 'open') {

                        $product = new WC_Product($post->ID);

                        wp_update_post(array(

                            'ID'    => $post->ID,

                            'comment_status' => 'open',

                        ));
                    }
                }
            }



            // PC tab on My Account page

            function pc_content_shortcode()
            {

    ?>

    <div style="display:none;"><?php echo do_shortcode('[mc4wp_form id="973"]'); ?></div>

    <?php

                $pc_status = "";

                $pc_button = "Join Now";



                $pc_user = wp_get_current_user();

                $user_firstname = $pc_user->user_firstname;

                $user_lastname  = $pc_user->user_lastname;

                $user_email     = $pc_user->user_email;

                if (empty($user_firstname)) {

                    $user_firstname = $pc_user->display_name;
                }

                if (empty($user_lastname)) {

                    $user_lastname = $pc_user->display_name;
                }



                $pc_user_meta = get_user_meta($pc_user->ID, 'user_registration_check_box_1661192013', true);

                if ($pc_user_meta == "parent_club_member") {

                    $pc_status    = "checked";

                    $pc_button    = "Unsubscribe";
                }

                if (isset($_POST['myAccountParentsClub'])) {

                    if (isset($_POST['check_box_1661192013'])) {

                        update_user_meta($pc_user->ID, 'user_registration_mailchimp_1661195342', '1');

                        update_user_meta($pc_user->ID, 'user_registration_check_box_1661192013', 'parent_club_member');

                        update_user_meta($pc_user->ID, 'ur_form_id', '86185');

                        $pc_status    = "checked";

                        $pc_button    = "Unsubscribe";

    ?><script>
                jQuery(document).ready(function() {

                    jQuery(".mc4wp-form input[name='FNAME']").val("<?php echo $user_firstname; ?>");

                    jQuery(".mc4wp-form input[name='LNAME']").val("<?php echo $user_lastname; ?>");

                    jQuery(".mc4wp-form input[name='EMAIL']").val("<?php echo $user_email; ?>");

                    jQuery("#mc4wp-form-1 input[type='submit']").click();

                });
            </script><?php

                    } else {

                        delete_user_meta($current_user->ID, 'user_registration_check_box_1661192013');

                        $pc_status = "";

                        $pc_button = "Join Now";
                    }
                }

                        ?>

    <form method="post" action="" id="myAccountParentsClub" name="myAccountParentsClubForm">

        <label>Parent Club Member: <input type="checkbox" class="input-checkbox ur-frontend-field" name="check_box_1661192013" value="parent_club_member" <?php echo $pc_status; ?>></label>

        <input name="myAccountParentsClub" value="1" type="hidden">

        <button class="button" style="width: 165px;"><?php echo $pc_button; ?></button>

    </form>

    <script>
        jQuery(document).ready(function() {

            jQuery(".mc4wp-form input[name='FNAME']").val("<?php echo $user_firstname; ?>");

            jQuery(".mc4wp-form input[name='LNAME']").val("<?php echo $user_lastname; ?>");

            jQuery(".mc4wp-form input[name='EMAIL']").val("<?php echo $user_email; ?>");

            jQuery("#myAccountParentsClub .button").click(function(event) {

                jQuery("#myAccountParentsClub").submit();

            });

        });
    </script>

<?php }

            add_shortcode('pc_content_shortcode', 'pc_content_shortcode');



            add_action('init', 'register_pc_endpoint', 0);

            function register_pc_endpoint()
            {

                add_rewrite_endpoint('parents-club-settings', EP_ROOT | EP_PAGES);
            }



            add_filter('query_vars', 'pc_query_vars');

            function pc_query_vars($vars)
            {

                $vars[] = 'parents-club-settings';

                return $vars;
            }



            add_filter('woocommerce_account_menu_items', 'add_pc_tab');

            function add_pc_tab($items)
            {

                $items['parents-club-settings'] = 'Parents Club Settings';

                return $items;
            }



            add_action('woocommerce_account_parents-club-settings_endpoint', 'pc_item_content');

            function pc_item_content()
            {

                echo '<div class="wrap-dashboard">';

                echo do_shortcode('[pc_content_shortcode]');

                echo '</div>';
            }







            add_action('um_on_login_before_redirect', 'um_pc_default_page_user_login', 8);

            function um_pc_default_page_user_login()
            {

                $user_id = um_user("ID");

                $pc_member = get_user_meta($user_id, 'user_registration_check_box_1661192013', true);

                if ($pc_member) {

                    wp_redirect("/parents-club");
                    exit;
                }
            }







            if (defined('YITH_WCAF')) {

                function yith_wcaf_dashboard_navigation_menu_custom($dashboard_navigation_menu)
                {

                    if (strpos($_SERVER['REQUEST_URI'], "affiliate-sub-page") !== false) {

                        foreach ($dashboard_navigation_menu as $dashboard_navigation_menu_key => $dashboard_navigation_menu_single) {

                            $dashboard_navigation_menu[$dashboard_navigation_menu_key]['active'] = "";
                        }

                        $dashboard_navigation_menu['access'] = array("label" => "IDL Access", "url" => "/affiliate-dashboard/affiliate-sub-page", "active" => "1");
                    } else {

                        $dashboard_navigation_menu['access'] = array("label" => "IDL Access", "url" => "/affiliate-dashboard/affiliate-sub-page", "active" => "0");
                    }

                    //echo "<pre>";

                    //print_r($dashboard_navigation_menu);

                    return $dashboard_navigation_menu;
                }

                //add_filter( 'yith_wcaf_dashboard_navigation_menu', 'yith_wcaf_dashboard_navigation_menu_custom', 10, 1 );

            }







            //YITH affiliate user emails : Enable by default

            /*

if( ! function_exists( 'yith_wcaf_customization_enable_notifications_by_default' ) ){

    function yith_wcaf_customization_enable_notifications_by_default($data){

        exit;

        $notify_pending_commission = get_user_meta(get_current_user_id(),'_yith_wcaf_notify_pending_commission',true); 

        if(!metadata_exists('user',get_current_user_id(),'_yith_wcaf_notify_pending_commission'))

        {

            return 'yes';

        }

        else

        {

            if($data=="no")

            {

                return 'no';

            }

            else

            {

                return 'yes';

            }

        }

    }

    add_filter( 'yith_wcaf_default_notify_user_pending_commission', 'yith_wcaf_customization_enable_notifications_by_default' );

}

if( ! function_exists( 'yith_wcaf_customization_enable_notifications_by_default1' ) ){

    function yith_wcaf_customization_enable_notifications_by_default1($data){

        $notify_paid_commission = get_user_meta(get_current_user_id(),'_yith_wcaf_notify_paid_commission',true); 

        if(!metadata_exists('user',get_current_user_id(),'_yith_wcaf_notify_paid_commission'))

        {

            return 'yes';

        }

        else

        {

            if($data=="no")

            {

                return 'no';

            }

            else

            {

                return 'yes';

            }

        }

    }

    add_filter( 'yith_wcaf_default_notify_user_paid_commission', 'yith_wcaf_customization_enable_notifications_by_default1' );

}

*/

            //_yith_wcaf_notify_pending_commission

            //_yith_wcaf_notify_paid_commission



            add_action('user_register', 'yith_wcaf_default_notify_user_custom');

            function yith_wcaf_default_notify_user_custom($user_id)
            {

                if (!metadata_exists('user', $user_id, '_yith_wcaf_notify_paid_commission')) {

                    add_user_meta($user_id, '_yith_wcaf_notify_paid_commission', "1");
                }

                if (!metadata_exists('user', $user_id, '_yith_wcaf_notify_pending_commission')) {

                    add_user_meta($user_id, '_yith_wcaf_notify_pending_commission', "1");
                }
            }







            add_filter('wp_is_large_user_count', function () {
                return 100000;
            }, 10, 0);











            add_action('wp_dashboard_setup', 'custom_dashboard_usercount_loader');

            function custom_dashboard_usercount_loader()
            {

                global $wp_meta_boxes;

                wp_add_dashboard_widget('custom_help_widget', 'Website User Count', 'custom_dashboard_usercount');
            }

            function custom_dashboard_usercount()
            {

                global $wp_roles;

                $roles = $wp_roles->roles;

                foreach ($roles as $role_single => $role_single_data) {

                    $role_single;
                }



                $count_users = get_option( 'count_users_stored' );
                
                if(is_array($stored_data) && count($stored_data)>0 && $stored_data['date_updated']!=date('Ymd'))
                {
                    $count_users = count_users();
                    $count_users['date_updated'] = date("Ymd");
                    update_option( 'count_users_stored', $count_users );
                }


                echo 'There are <b>', $count_users['total_users'], '</b> total users on the website' . "<br><br>";



                foreach ($count_users['avail_roles'] as $role => $count) {

                    if ($role == "none") {
                        continue;
                    }

                    if ($role == "administrator") {
                        continue;
                    }

                    if ($role == "shop_manager") {
                        continue;
                    }

                    //if($role=="subscriber") { continue; }

                    echo "<b>" . ucwords(str_replace("_", " ", $role)) . "</b> : " . $count . "<br>";
                }



                global $wpdb;


                $cusers = get_option( 'cusers_stored' );
                
                if(is_array($cusers) && count($cusers)>0 && $cusers['date_updated']==date('Ymd'))
                {
                    
                }
                else
                {
                    $cusers = $wpdb->get_results("SELECT user_id FROM wp_usermeta WHERE meta_key = 'user_registration_check_box_1661192013' AND meta_value = 'parent_club_member' group by user_id",ARRAY_A);
                    $cusers['date_updated'] = date("Ymd");
                    update_option( 'cusers_stored', $cusers );
                }

                
                /*
                foreach ($cusers as $cusers_single) {

                    $cusersc = $wpdb->get_var("SELECT count(*) FROM wp_usermeta WHERE user_id=" . $cusers_single->user_id . " AND meta_key = 'wp_capabilities' AND (meta_value like '%customer%' OR meta_value like '%subscriber%' )");

                    if ($cusersc > 0) {

                        $cusers_array[] = $cusers_single->user_id;
                    }
                }
                */

                echo "<b>Parent Club Members</b> : " . count($cusers) . "<br>";
            }





            function display_current_user_display_name()
            {

                $user = wp_get_current_user();

                $user_firstname = $user->user_firstname;

                if (empty($user_firstname)) {

                    $user_firstname = $user->display_name;
                }

                return $user_firstname;
            }

            add_shortcode('current_user_display_name', 'display_current_user_display_name');



















            if (defined('YITH_WCAF')) {

                function yith_wcaf_dashboard_navigation_menu_deals($dashboard_navigation_menu)
                {

                    if (strpos($_SERVER['REQUEST_URI'], "affiliate-sub-page") !== false) {

                        foreach ($dashboard_navigation_menu as $dashboard_navigation_menu_key => $dashboard_navigation_menu_single) {

                            $dashboard_navigation_menu[$dashboard_navigation_menu_key]['active'] = "";
                        }

                        $dashboard_navigation_menu['bonus'] = array("label" => "Exclusive Deals & Perks", "url" => "/affiliate-dashboard/bonus-program", "active" => "1");
                    } else {

                        $dashboard_navigation_menu['bonus'] = array("label" => "Exclusive Deals & Perks", "url" => "/affiliate-dashboard/bonus-program", "active" => "0");
                    }

                    //echo "<pre>";

                    //print_r($dashboard_navigation_menu);

                    return $dashboard_navigation_menu;
                }

                add_filter('yith_wcaf_dashboard_navigation_menu', 'yith_wcaf_dashboard_navigation_menu_deals', 10, 1);
            }



            function load_bonus_page()
            {

                $content_post_id = 89746;

                $post = get_post($content_post_id);

                $posts = array($post);



                $css = get_post_meta($content_post_id, '_wpb_post_custom_css', true);

                echo "<style> " . $css . " </style>";

                $post = get_post($content_post_id);

                $content = do_shortcode($post->post_content);

                echo $content;
            }

            add_shortcode('load_bonus_page', 'load_bonus_page');

            //----------------------AdLuge tracking------------------------------//

            add_action('wpcf7_mail_sent', 'aurora_wpcf7_mail_sent_function');



            function aurora_wpcf7_mail_sent_function($contact_form)
            {



                $submission = WPCF7_Submission::get_instance();



                if ($submission) {

                    $posted_data = $submission->get_posted_data();
                }



                require_once("clientcenter-api-library.php");

                $lead = new clientcenter();

                $lead->client_code = "flmztem1vx8b8z7"; // Mandatory. Unique identification code.



                $other_fields = array();



                if ($posted_data['your-name']) {

                    $fName = $posted_data['your-name'];
                }

                if ($posted_data['your-email']) {

                    $email = $posted_data['your-email'];
                }



                if ($posted_data['subject']) {

                    $subject = $posted_data['subject'];

                    $other_fields['Subject'] = $subject;
                }



                if ($posted_data['comments']) {

                    $comments = stripslashes($posted_data['message']);
                }

                if ($fName != "" && $email != "") {

                    $lead->fname = $fName;

                    $lead->email = $email;

                    $lead->phone = $phone;

                    $lead->comments = $comments;

                    $lead->other_fields = json_encode($other_fields); //post Value of other_fields 

                }





                $lead->status = 1; // No need to change this

                $lead->useragent = $_SERVER['HTTP_USER_AGENT']; //browser properties

                $lead->remote_ip = $_SERVER['REMOTE_ADDR']; //ip address

                $lead->referrer = $_SERVER['HTTP_REFERER']; // page source

                $lead->contact_date = date("Y-m-d h:i:s");

                $lead->search_engine = $_COOKIE['adl_durl'];

                $lead->keyword = $_COOKIE['adl_key'];

                $lead->source = $_COOKIE['adl_camp'];

                $lead->randomnum = $_COOKIE['adl_rand'];

                $lead->adl_ref = $_COOKIE['adl_ref'];

                $lead->gclid = $_COOKIE['gclid'];



                $lead->send_to_adluge = true; // Set to true If you are sending leads to adluge //default true

                $lead->send();

                //--------------------------------------------------------------------------------------------------

            }
            function registration_track()
            { ?>
    <script type="text/javascript">
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }
        var session_data = sessionStorage.getItem('submitted_data1');
        var session_other_data = sessionStorage.getItem('submitted_other_data1');
        if (session_data) {
            var obj = JSON.parse(session_data);
            var ad_fname = obj.fname;
            var ad_email = obj.email;
            var ad_other_field = obj.other_field;
            if (ad_fname != "" && ad_email != "") {
                jQuery.ajax({
                    url: 'https://www.adluge.com/clientcenter/api/addlead-callback.php',
                    jsonp: "callback",
                    dataType: "jsonp",
                    data: {
                        fname: ad_fname,
                        client_code: "flmztem1vx8b8z7",
                        email: ad_email,
                        referer: window.location.href,
                        lead_sessionid: getCookie("_adl_id.AL_2158"),
                        gclid: getCookie("gclid"),
                        other_fields: session_other_data
                    },
                    success: function(data) {
                        sessionStorage.removeItem('submitted_data1');
                        sessionStorage.removeItem('submitted_other_data1');
                    },
                });
            }
            sessionStorage.removeItem('submitted_data1');
            sessionStorage.removeItem('submitted_other_data1');
        }
    </script>
    <?php }

add_action('login_footer', 'registration_track');

// add_filter( 'woocommerce_available_payment_gateways', 'bbloomer_paypal_enable_manager' );
// function bbloomer_paypal_enable_manager( $available_gateways ) {
//     if ( isset( $available_gateways['cod'] ) && ! wc_current_user_has_role( 'administrator' ) ) {
//       unset( $available_gateways['cod'] );
//     } 
//     return $available_gateways;
//  }
/* Order Adluge Tracking */

// add_action( 'woocommerce_thankyou', 'misha_view_order_and_thankyou_page', 20 );
// function misha_view_order_and_thankyou_page( $order_id ){ 
// 	$order = new WC_Order($order_id);	
//     $order_data = $order->get_data(); 
//     $product_name = '';
//     $count=1;
//     foreach ($order->get_items() as $item_key => $item ){

//     $item_id = $item->get_id();
//     $product      = $item->get_product(); // Get the WC_Product object
//     $product_id   = $item->get_product_id(); // the Product id
//     $variation_id = $item->get_variation_id(); // the Variation id
//     $item_type    = $item->get_type(); // Type of the order item ("line_item")
//     $item_name    = $item->get_name(); // Name of the product
//     $tax_class    = $item->get_tax_class();
//     $line_subtotal     = $item->get_subtotal(); // Line subtotal (non discounted)
//     $line_subtotal_tax = $item->get_subtotal_tax(); // Line subtotal tax (non discounted)
//     $line_total        = $item->get_total(); // Line total (discounted)
//     $line_total_tax    = $item->get_total_tax(); // Line total tax (discounted)
//     $item_data    = $item->get_data();
//     $product_name .= $count.". ".$item_data['name'].",";

//     $count++;  }
//     if($product_name){ 
//         $other_fields['Products'] = $product_name; 
//     }
//     $total = $order_data['total'];
//     $payment_method = $order_data['payment_method_title'];
//     $fname = $order_data['billing']['first_name'];	
//     $lname = $order_data['billing']['last_name'];
//     $email = $order_data['billing']['email'];
//     $phone = $order_data['billing']['phone'];	
//     $address_1 = $order_data['billing']['address_1'];
//     $address_2 = $order_data['billing']['address_2'];
//     $address = $address_1." ".$address_2;
//     $city = $order_data['billing']['city'];
//     $postalcode = $order_data['billing']['postcode'];
//     $country = $order_data['billing']['country'];
//     $state = $order_data['billing']['state'];
//     $company = $order_data['billing']['company'];
//     $other_fields['Order No'] = $order_data['id'];
//     if($order_data['shipping']['first_name']){ 
//         $other_fields['Shipping First Name'] = $order_data['shipping']['first_name']; 
//     }
//     if($order_data['shipping']['last_name']){ 
//         $other_fields['Shipping Last Name'] = $order_data['shipping']['last_name']; 
//     }
//     if($order_data['shipping']['company']){ 
//         $other_fields['Shipping Company Name'] = $order_data['shipping']['company']; 
//     }
//     if($order_data['shipping']['address_1']){ 
//         $other_fields['Shipping Addresss'] = $order_data['shipping']['address_1']." ".$order_data['shipping']['address_2']; 
//     }
//     if($order_data['shipping']['city']){ 
//         $other_fields['Shipping City'] = $order_data['shipping']['city']; 
//     }
//     if($order_data['shipping']['postcode']){ 
//         $other_fields['Shipping Postalcode'] = $order_data['shipping']['postcode']; 
//     }
//     if($order_data['shipping']['country']){ 
//         $other_fields['Shipping Country'] = $order_data['shipping']['country']; 
//     }
//     if($order_data['shipping']['state']){ 
//         $other_fields['Shipping Province'] = $order_data['shipping']['state']; 
//     }
//     $currency = $order->get_currency();
//     if($total){ 
//         $other_fields['Total'] = $total." ".$currency; 
//     }
//     if($payment_method){ 
//         $other_fields['Payment method'] = $payment_method; 
//     }
//     $other_fields['Quantity'] = $order->get_item_count(); 

//     $comments = $order->get_customer_note();

// 		    require_once("clientcenter-api-library.php"); 
// 			$lead = new clientcenter();

// 			$lead->client_code="flmztem1vx8b8z7"; // Mandatory. Unique identification code.

// 			$lead->fname=$fname; //post  Value of first name  
// 			$lead->lname=$lname; //post  Value of first name  
// 			$lead->phone=$phone;//post Value of Phone Number 
// 			$lead->email=$email;//post  Value of Email addess  
// 			$lead->address_1=$address;//post  Value of Email addess  
// 			$lead->state=$state;//post  Value of Email addess  
// 			$lead->country=$country;//post  Value of Email addess  
// 			$lead->city=$city;//post  Value of Email addess  
// 			$lead->postalcode=$postalcode;//post  Value of Email addess  
// 			$lead->company_name=$company;//post Value of Comments
// 			$lead->comments=$comments;//post Value of Comments
// 			$lead->other_fields=json_encode($other_fields);//post Value of other_fields 

// 			$lead->status=1; // No need to change this

// 			$lead->useragent = $_SERVER['HTTP_USER_AGENT']; //browser properties
// 			$lead->remote_ip=$_SERVER['REMOTE_ADDR']; //ip address
// 			$lead->referrer=$_SERVER['HTTP_REFERER'];// page source
// 			$lead->contact_date=date("Y-m-d h:i:s");
// 			$lead->search_engine=$_COOKIE['adl_durl'];
// 			$lead->keyword=$_COOKIE['adl_key'];
// 			$lead->source=$_COOKIE['adl_camp'];
// 			$lead->randomnum=$_COOKIE['adl_rand'];
// 			$lead->adl_ref=$_COOKIE['adl_ref'];
// 			// echo "<pre>"; print_r($other_fields);echo "</pre>";  exit;
// 			$lead->send_to_adluge=true; // Set to true If you are sending leads to adluge //default true
// 			$lead->send();
// }

/* Checkout - How did you hear about us */
add_action('woocommerce_after_order_notes', 'idl_checkout_dropdown');
function idl_checkout_dropdown($checkout)
{
    echo '<div id="custom_checkout_field">';
    woocommerce_form_field(
        'idl_checkout_dropdown',
        array(
            'label'     => __('How did you hear about us?', 'woocommerce'),
            //'placeholder'   => _x('Select', 'placeholder', 'woocommerce'),
            'required'  => false,
            'class'     => array('form-row-wide'),
            'clear'     => true,
            'type'      => 'select',
            'options'     => array(
                ' ' => __('Choose an option', 'woocommerce'),
                'Google/Search Engine' => __('Google/Search Engine', 'woocommerce'),
                'Online Advertisement' => __('Online Advertisement', 'woocommerce'),
                'Instagram' => __('Instagram', 'woocommerce'),
                'Facebook' => __('Facebook', 'woocommerce'),
                'In-store book purchase' => __('In-store book purchase', 'woocommerce'),
                'Friend/Family' => __('Friend/Family', 'woocommerce')
            )
        ),
        $checkout->get_value('idl_checkout_dropdown')
    );
    echo '</div>';
}
add_action('woocommerce_checkout_update_order_meta', 'idl_custom_checkout_field_update_order_meta');
function idl_custom_checkout_field_update_order_meta($order_id)
{
    if (!empty($_POST['idl_checkout_dropdown'])) {
        update_post_meta($order_id, 'idl_how_did_you_hear_about_us', sanitize_text_field($_POST['idl_checkout_dropdown']));
    }
}

add_action('woocommerce_after_order_notes', 'idl_checkout_user_type');
function idl_checkout_user_type($checkout)
{
    echo '<div id="custom_checkout_field2">';
    woocommerce_form_field(
        'idl_user_type_dropdown',
        array(
            'label'     => __('Account Type', 'woocommerce'),
            //'placeholder'   => _x('Select', 'placeholder', 'woocommerce'),
            'required'  => false,
            'class'     => array('form-row-wide'),
            'clear'     => true,
            'type'      => 'select',
            'options'     => array(
                ' ' => __('Choose an option', 'woocommerce'),
                'Parent' => __('Parent', 'woocommerce'),
                'Teacher' => __('Teacher', 'woocommerce'),
                'School' => __('School', 'woocommerce')
            )
        ),
        $checkout->get_value('idl_user_type_dropdown')
    );
    echo '</div>';
}
add_action('woocommerce_checkout_update_user_meta', 'idl_custom_checkout_field_update_user_meta');
function idl_custom_checkout_field_update_user_meta($user_id)
{
    if ($user_id && $_POST['idl_user_type_dropdown']) {
        update_user_meta($user_id, 'idl_user_type', esc_attr($_POST['idl_user_type_dropdown']));
    }
}






























add_action('wp_dashboard_setup', 'custom_dashboard_howdidyourhearaboutus');

function custom_dashboard_howdidyourhearaboutus()
{

    global $wp_meta_boxes;
    wp_add_dashboard_widget('custom_help_widget_howdidyourhearaboutus', 'How did you hear about us', 'custom_dashboard_widget_howdidyourhearaboutus');
}

function custom_dashboard_widget_howdidyourhearaboutus()
{

    global $wpdb;
    $cusers_array = array();
    $cdata = $wpdb->get_results("SELECT meta_value,count(meta_value) as count FROM `wp_postmeta` WHERE `meta_key` LIKE 'idl_how_did_you_hear_about_us' and meta_value!='' group by meta_value");

    foreach ($cdata as $cdata_single) {
        echo "<b>" . $cdata_single->meta_value . "</b> : " . $cdata_single->count . "<br>";
    }
}






add_filter('wp_get_attachment_image_attributes', 'change_attachement_image_attributes', 20, 2);

function change_attachement_image_attributes($attr, $attachment)
{
    // Get post parent
    $parent = get_post_field('post_parent', $attachment);

    // Get post type to check if it's product
    $type = get_post_field('post_type', $parent);
    if ($type != 'product') {
        return $attr;
    }

    /// Get title
    $title = get_post_field('post_title', $parent);

    $attr['alt'] = $title;
    $attr['title'] = $title;

    return $attr;
}

/* HTML Sitemap */
function display_html_sitemap()
{
    $sitemap_menu_id = 488;
    if (is_nav_menu($sitemap_menu_id)) {
        $menu = wp_get_nav_menu_object($sitemap_menu_id);
        $menu_items = wp_get_nav_menu_items($menu->term_id);

        $menu_list   = '<ul class="sitemap-menu">';
        $submenu = false;
        foreach ($menu_items as $menu_item) {
            $link = $menu_item->url;
            $title = $menu_item->title;
            if (!$menu_item->menu_item_parent) {
                $parent_id = $menu_item->ID;
                $menu_list .= '<li>';
                $menu_list .= '<a href="' . $link . '" class="title">' . $title . '</a>';
            }
            if ($parent_id == $menu_item->menu_item_parent) {
                if (!$submenu) {
                    $submenu = true;
                    $menu_list .= '<ul>';
                }
                $menu_list .= '<li>';
                $menu_list .= '<a href="' . $link . '" class="title">' . $title . '</a>';
                $menu_list .= '</li>';
                if ($menu_items[$count + 1]->menu_item_parent != $parent_id && $submenu) {
                    $menu_list .= '</ul>';
                    $submenu = false;
                }
            }
            if ($menu_items[$count + 1]->menu_item_parent != $parent_id) {
                $menu_list .= '</li>';
                $submenu = false;
            }
            $count++;
        }
        $menu_list  .= '</ul>';
        return $menu_list;
    }
}
add_shortcode('display_html_sitemap', 'display_html_sitemap');

/* download ebooks in awaiting-shipment status */
function add_awaiting_shipment_status_to_download_permission($data, $order)
{
    if ($order->has_status('awaiting-shipment')) {
        return true;
    }
    return $data;
}
add_filter('woocommerce_order_is_download_permitted', 'add_awaiting_shipment_status_to_download_permission', 10, 2);
add_action('woocommerce_order_status_awaiting-shipment', 'wc_downloadable_product_permissions');


add_action('custom_woocommerce_archive_description', 'wc_category_description');
function wc_category_description()
{
    if (is_product_category()) {
        global $wp_query;
        $cat_id = $wp_query->get_queried_object_id();
        $cat_desc = '<div class="category-description show">';
        $cat_desc .= '<div class="term-description">';
        $desc = term_description($cat_id, 'product_cat');
        if (strlen($desc) < 1) {
            $term = get_term_by('term_id', $cat_id, 'product_cat');
            $name = $term->name;
            $desc = '<h1>' . $name . '</h1>';
        }
        $cat_desc .= $desc;
        $cat_desc .= '</div>';
        if (strlen($desc) > 250) {
            //$cat_desc .= '<a class="show_hide">Read More</a>';
            //$cat_desc .= '<a class="show_hide">Read Less</a>';
        }
        $cat_desc .= '</div>';
        echo $cat_desc;
    }
}

add_action('custom_woocommerce_archive_after_products_description', 'wc_desc_below_content');
function wc_desc_below_content()
{
    if (is_product_category()) {
        global $wp_query;
        $cat_id = $wp_query->get_queried_object_id();
        $cat_desc = '<div class="category-description show bottom">';
        $cat_desc .= '<div class="term-description">';
        $desc = get_field('content_below_products', 'product_cat_' . $cat_id);
        $cat_desc .= $desc;
        $cat_desc .= '</div>';
        $cat_desc .= '</div>';
        if (strlen($desc) > 1) {
            echo $cat_desc;
        }
    }
}

add_filter('wp_robots', function ($robots) {
    if (strpos($_SERVER['REQUEST_URI'], "/user/") !== false) {
        $robots['noindex']  = true;
        $robots['nofollow'] = true;
    }
    return $robots;
});


//add SVG to allowed file uploads
function add_file_types_to_uploads($file_types)
{

    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg';
    $file_types = array_merge($file_types, $new_filetypes);

    return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');


add_filter('wc_shipment_tracking_get_providers', 'wc_shipment_tracking_add_custom_provider_pgg');
function wc_shipment_tracking_add_custom_provider_pgg($providers)
{
    $providers['Canada']['pgg'] = 'https://www.purolator.com/purolator/ship-track/tracking-summary.page?pin=%1$s'; // add a custom provider
    return $providers;
}

add_action('woocommerce_thankyou', 'fb_track_purchase_event', 10);
function fb_track_purchase_event($order_id)
{
    $order = new WC_Order($order_id);
    $order_data = $order->get_data();
    $total = $order_data['total'];
    echo "<script>fbq('track', 'Purchase', {value:" . $total . " , currency: 'CAD'});</script>";
}

//add_action( 'woocommerce_cart_totals_after_shipping' ,'idl_shipping_delay');
//add_action( 'woocommerce_review_order_after_order_total' ,'idl_shipping_delay');
function idl_shipping_delay()
{
    echo '<div class="shiping-notice" style="width:100%;text-align:left;margin-top:10px;margin-bottom:10px;line-height: 1.4;"><underline>Shipping Delay Notice</underline> :
Kindly note that orders placed between December 23rd and January 2nd will experience delays due to the holiday season. For more information, please contact us at <a href="mailto:ca-info@popularworld.com">ca-info@popularworld.com</a>.</div>';
}


/*----------------- Is_blog starts Here----------------------------*/
function is_blog()
{
    return (is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()) && 'post' == get_post_type();
}
/*----------------- Is_blog ends Here----------------------------*/


function currentYear($atts)
{
    return date('Y');
}
add_shortcode('year', 'currentYear');



add_filter('wpseo_json_ld_output', '__return_false');


/* Best selling product scroller */
function dynamic_top_5_best_seller_scroller_shortcode($atts)
{
    // Get the current category slug dynamically
    $category_slug = '';

    if (is_product_category()) {
        // If on a category archive page
        $category_slug = get_queried_object()->slug;
    } elseif (is_product()) {
        // If on a single product page
        $terms = get_the_terms(get_the_ID(), 'product_cat');
        if ($terms && !is_wp_error($terms)) {
            $category_slug = $terms[0]->slug; // Get the first category
        }
    }

    // Log the detected category for debugging
    error_log('Detected category slug: ' . $category_slug);

    // If no category found, return a message
    if (empty($category_slug)) {
        return '<p>No category detected for this page.</p>';
    }

    // Arguments for querying the top 5 best-selling products in the detected category
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 5, // Display only 5 products
        'meta_key' => 'total_sales', // WooCommerce tracks sales in 'total_sales'
        'orderby' => 'meta_value_num', // Order by number of sales
        'order' => 'DESC', // Show the highest sales first
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug', // Use slug to match the category
                'terms' => $category_slug, // The detected category slug
                'operator' => 'IN',
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start(); ?>

        <!-- Top 5 Best Seller Scroller Wrapper -->
        <div class="top-5-best-seller-scroller">
            <?php while ($query->have_posts()) : $query->the_post();
                        global $product; ?>
                <div class="product">
                    <a href="<?php the_permalink(); ?>">
                        <?php
                        // Display full product image
                        $full_image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
                        echo '<img src="' . $full_image[0] . '" alt="' . get_the_title() . '" />';
                        ?>
                            <p style="font-size:20px; font-family:'averta-bold';"><?php the_title(); ?></p>
                        <span><?php echo $product->get_price_html(); ?></span>
                    </a>
                </div>
            <?php endwhile;
                    wp_reset_postdata(); ?>
        </div>

        <!-- Slick Slider Initialization -->
        <script>
            jQuery(document).ready(function() {
                jQuery('.top-5-best-seller-scroller').slick({
                    slidesToShow: 1, // Show 5 products at once
                    slidesToScroll: 1, // Scroll 1 product at a time
                    autoplay: true, // Enable auto-scrolling
                    autoplaySpeed: 1500, // Speed between auto-scroll (in milliseconds)
                    arrows: false, // Show next/previous arrows
                    dots: true, // Show pagination dots
                });
            });
        </script>

    <?php
        return ob_get_clean();
    } else {
        return '<p>No best-selling products found for this category.</p>';
    }
}
add_shortcode('dynamic_top_5_best_seller_scroller', 'dynamic_top_5_best_seller_scroller_shortcode');

add_filter('wpseo_metadesc', 'do_shortcode');
add_filter('wpseo_opengraph_desc', 'do_shortcode');
function add_missing_desc()
{
    global $wp_query;
    $post_id = $wp_query->get_queried_object_id();
    $content = wp_strip_all_tags(get_the_content());
    //return substr($content, 0, 100);
    return "";
}
//add_shortcode('add_missing_desc', 'add_missing_desc');
function add_custom_hreflang()
{
    global $wp;
    echo '<link rel="alternate" href="' . home_url($wp->request) . '" hreflang="en-CA" />';
}
add_action('wp_head', 'add_custom_hreflang');















add_filter('gettext', 'change_cart_no_shipping_method_text', 10, 3);

function change_cart_no_shipping_method_text($translated_text, $text, $domain)
{
    if ( function_exists('is_cart') &&  (is_cart() || is_checkout()) && ! is_wc_endpoint_url()) {
        $original_text = 'There are no shipping methods available. Please double check your address, or contact us if you need any help.';
        $new_text = 'There are no shipping methods available. We currently ship to Canadian addresses only.<br>For inquiries about US orders, please contact<br>davidmcgee@popularworld.com';
        if ($text === $original_text) {
            $translated_text = $new_text;
        }
    }
    return $translated_text;
}








function idl_woocommerce_checkout_countries($countries)
{
    if (is_cart() || is_checkout()) {
        $found_physical = false;
        if (WC()->cart) {
            foreach (WC()->cart->get_cart() as $cart_item) {
                $product_id = $cart_item['product_id'];
                $product = wc_get_product($product_id);
                if (!$product->is_virtual()) {
                    $found_physical = true;
                }
            }
        }
        if ($found_physical) {
            foreach ($countries as $country_key => $country_value) {
                if ($country_key != "CA") {
                    unset($countries[$country_key]);
                }
            }
        }
    }
    return $countries;
}
add_filter('woocommerce_countries_allowed_countries', 'idl_woocommerce_checkout_countries', 10, 1);



add_action('wp_footer', 'add_apartment_buzzer_js');
function add_apartment_buzzer_js()
{
    ?>
    <script>
        jQuery(document).ready(function($) {
            jQuery('#billing_liveinappt').on('change', function() {
                //alert();
                var checked = jQuery(this).is(':checked') ? 'yes' : 'no';
                jQuery.ajax({
                    url: '/',
                    type: 'POST',
                    data: {
                        action: 'set_appartment_session',
                        value: checked
                    },
                    success: function(response) {
                        setTimeout(
                            function() {
                                window.location.reload();
                            }, 1000);
                        //jQuery('body').trigger('update_checkout');
                        //window.location.reload();
                        //console.log('Session updated:', response);
                    }
                });
            });

            <?php
                if (isset($_SESSION['billing_liveinappt']) && $_SESSION['billing_liveinappt'] == 'yes') {
            ?>
                jQuery('#billing_liveinappt').prop('checked', true);
            <?php
                } else {
            ?>
                jQuery('#billing_liveinappt').prop('checked', false);
            <?php
                }
            ?>

        });
    </script>
<?php
}











add_filter('woocommerce_checkout_fields', 'add_apartment_buzzer');
function add_apartment_buzzer($fields)
{
    $liveinappt = 0;
    if (isset($_SESSION['billing_liveinappt']) && $_SESSION['billing_liveinappt'] == 'yes') {
        $liveinappt = 1;
    }


    $fields['billing']['billing_liveinappt'] = array(
        'type'          => 'checkbox',
        'class'         => array('form-row-wide'),
        'label'       => __('Do you live in a unit, apartment or condominium', 'woocommerce'),
        'required'      => false,
        'priority' => 60,
        'default'     => 0,
    );
    if ($liveinappt == 1) {
        $fields['billing']['billing_liveinappt']['default'] = 1;
    }
    $fields['billing']['billing_apartment_number'] = array(
        'label'       => __('Apartment Number', 'woocommerce'),
        'placeholder' => __('Apartment Number', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority' => 60
    );
    if ($liveinappt == 1) {
        $fields['billing']['billing_apartment_number']['required'] = true;
    }
    $fields['billing']['billing_buzzer_code'] = array(
        'label'       => __('Buzzer Code', 'woocommerce'),
        'placeholder' => __('Buzzer Code', 'woocommerce'),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority' => 60
    );
    if ($liveinappt == 1) {
        $fields['billing']['billing_buzzer_code']['required'] = true;
    }
    //echo "<pre>";
    //print_r($fields);
    //exit;
    return $fields;
}

// Add slug column to pages list in admin
add_filter('manage_pages_columns', 'add_slug_column');
function add_slug_column($columns)
{
    $columns['slug'] = 'Slug';
    return $columns;
}

// Populate the slug column
add_action('manage_pages_custom_column', 'populate_slug_column', 10, 2);
function populate_slug_column($column, $post_id)
{
    if ($column === 'slug') {
        $post = get_post($post_id);
        echo esc_html($post->post_name);
    }
}

// Make slug column sortable (optional)
add_filter('manage_edit-page_sortable_columns', 'make_slug_column_sortable');
function make_slug_column_sortable($columns)
{
    $columns['slug'] = 'slug';
    return $columns;
}

            
            
            
            
add_action('wp_dashboard_setup', 'custom_new_vs_returning');
function custom_new_vs_returning()
{
    global $wp_meta_boxes;
    wp_add_dashboard_widget('custom_new_returning', 'New VS Returning Customers', 'custom_new_returning');
}
function custom_new_returning()
{
    $stored_data = get_option( 'custom_new_returning' );
    
    if(is_array($stored_data) && count($stored_data)>0 && $stored_data['date_updated']==date('Ymd'))
    {
        $customers_with_zero_order = $stored_data['customers_with_zero_order'];
        $customers_with_one_order = $stored_data['customers_with_one_order'];
        $customers_with_1plus_order = $stored_data['customers_with_1plus_order'];
        $customers_with_3plus_order = $stored_data['customers_with_3plus_order'];
        $customers_with_5plus_order = $stored_data['customers_with_5plus_order'];
        $customers_with_10plus_order = $stored_data['customers_with_10plus_order'];
    }
    else
    {
        $customers_with_zero_order = 0;
        $customers_with_one_order = 0;
        $customers_with_1plus_order = 0;
        $customers_with_3plus_order = 0;
        $customers_with_5plus_order = 0;
        $customers_with_10plus_order = 0;
        $customer_ids = get_users( array(
            'role'    => 'customer',
            'fields'  => 'ID',
            'orderby' => 'ID',
            'order'   => 'ASC',
        ) );
        
        $start_date = date('Y-m-d 00:00:00', strtotime('-1 year'));
        $end_date = date('Y-m-d 23:59:59');
    
        if ( ! empty( $customer_ids ) ) {
            
            foreach ( $customer_ids as $customer_id ) {
                $customer_orders = wc_get_orders( array(
                    'customer_id' => $customer_id,
                    'limit'       => -1,
                    'status'      => array_keys( wc_get_order_statuses() ),
                    'date_created' => '>'.$start_date,
                ) );
    
                if ( count( $customer_orders ) == 0 ) {
                    $customers_with_zero_order++;
                }
                if ( count( $customer_orders ) === 1 ) {
                    $customers_with_one_order++;
                }
                if ( count( $customer_orders ) > 1 ) {
                    $customers_with_1plus_order++;
                }
                if ( count( $customer_orders ) > 3 ) {
                    $customers_with_3plus_order++;
                }
                if ( count( $customer_orders ) > 5 ) {
                    $customers_with_5plus_order++;
                }
                if ( count( $customer_orders ) > 10 ) {
                    $customers_with_10plus_order++;
                }
            }
            $store_data = array(
                'date_updated' => date("Ymd"),
                'customers_with_zero_order' => $customers_with_zero_order,
                'customers_with_one_order' => $customers_with_one_order,
                'customers_with_1plus_order' => $customers_with_1plus_order,
                'customers_with_3plus_order' => $customers_with_3plus_order,
                'customers_with_5plus_order' => $customers_with_5plus_order,
                'customers_with_one_order' => $customers_with_10plus_order,
            );
            update_option( 'custom_new_returning', $store_data );
        }
    }
    
    

    echo "<b>Customers with 0 Orders</b> : " . $customers_with_zero_order . "<br>";
    echo "<b>Customers with 1 Order</b> : " . $customers_with_one_order . "<br>";
    echo "<b>Customers with 1+ Order</b> : " . $customers_with_1plus_order . "<br>";
    echo "<b>Customers with 3+ Order</b> : " . $customers_with_3plus_order . "<br>";
    echo "<b>Customers with 5+ Order</b> : " . $customers_with_5plus_order . "<br>";
    echo "<b>Customers with 10+ Order</b> : " . $customers_with_10plus_order . "<br>";
}

   /**
 * Force WooCommerce Product Search Grid (Early Intercept)
 * Fakes the post_type parameter so WooCommerce template routing takes over
 */
add_action( 'init', 'idl_inject_product_search_param' );
function idl_inject_product_search_param() {
    // If this is a Book Junky product search but the post_type is missing/empty
    if ( isset( $_GET['bj_action'] ) && $_GET['bj_action'] === 'bj_product' && empty( $_GET['post_type'] ) ) {
        // Inject the product post type into the global request variables
        $_GET['post_type'] = 'product';
        $_REQUEST['post_type'] = 'product';
    }
}

/**
 * Hide Best Sellers Widget on Search Results
 * Conditionally removes custom_html-6 from the sidebar on search pages
 */
add_filter( 'sidebars_widgets', 'idl_remove_bestsellers_on_search' );
function idl_remove_bestsellers_on_search( $sidebars_widgets ) {
    // Do nothing in the admin backend
    if ( is_admin() ) {
        return $sidebars_widgets;
    }

    // If we are on a search page, find and remove the widget
    if ( is_search() && ! empty( $sidebars_widgets ) ) {
        foreach ( $sidebars_widgets as $sidebar_id => $widgets ) {
            if ( is_array( $widgets ) ) {
                foreach ( $widgets as $key => $widget_id ) {
                    // This is the exact ID you found
                    if ( $widget_id === 'custom_html-6' ) {
                        unset( $sidebars_widgets[$sidebar_id][$key] );
                    }
                }
            }
        }
    }
    return $sidebars_widgets;
}









//add_action( 'woocommerce_checkout_subscription_created', 'custom_action_on_new_subscription', 10, 3 );

function custom_action_on_new_subscription( $subscription, $checkout_order, $recurring_cart ) {
    echo 'New subscription created: ' . $subscription->get_id()."\n";
    echo 'New order created: ' . $checkout_order->get_id()."\n";
    print_r($subscription);
    print_r($checkout_order);
    exit;
}


//add_action('woocommerce_subscription_payment_complete','get_subscription_payment_function');
function get_subscription_payment_function($subscription) {
    echo 'New subscription created: ' . $subscription->get_id()."\n";
    print_r($subscription);
    exit;
}
















/*

add_action('init', 'custom_testcms');
function custom_testcms() {
    if(isset($_REQUEST['testcms']) && $_REQUEST['testcms']=="process")
    {
            $order_id = 116377;
            process_subscription_custom($order_id);

        exit;
    }
}

function process_subscription_custom($order_id,$subscription_type='Payment')
{
    // $subscription_type (Either Payment or refund)
    if(isset($order_id) && is_numeric($order_id) && $order_id>0)
    {
        $order = wc_get_order( $order_id );
        $order_array = array();
        if ( $order ) {
            
            
            $subscriptions = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => 'any' ) );
    
            foreach ( $subscriptions as $subscription_id => $subscription_obj ) {
                $subscription = array();
                $subscription['status'] = $subscription_obj->get_status();
                
                $subscription['sub_id'] = $subscription_obj->get_id();
                
                $subscription['is_in_trial'] = $subscription_obj->get_date('trial_end');
                $subscription['billing_period'] = $subscription_obj->get_billing_period();
                $subscription['billing_interval'] = $subscription_obj->get_billing_interval();
            }
            $order_array['appId']  = "ParentClub";
            $order_array['orderId']  = $order_id;
            $order_array['parentId']  = "PID".$order->get_user_id();
            $order_array['parentClubParentId']  = "PID".$order->get_user_id();
            
            $order_array['subscribeId']  = "SID".$order->get_user_id();

            //$order_array['studentId']  = $order->get_user_id();
            //$order_array['studentName']  = "";
            
            $payStatus = 2;
            if($order->get_status()=="completed") { $payStatus =1; }
            $order_array['payStatus']  = $payStatus;
            
            $date_created = $order->get_date_created();
            $payTimestamp = $date_created->getTimestamp();
            $order_array['payTimestamp']  = $payTimestamp;
            
            $order_array['payAmount']  = $order->get_total();
            // 1 for Subscr4ibe , 2 for Expansion plan
            $order_array['type']  = 1;
            
            if($subscription['is_in_trial']!="")
            {
                $order_array['subscriptionType']  = 1;
            }
            if($subscription['billing_period']=="month")
            {
                $order_array['subscriptionType']  = 2;
            }
            
            $order_array['trialType']  = null;
            
            if($subscription['billing_period']=="year")
            {
                $order_array['subscriptionType']  = 3;
            }
    
            
            $discountCode = "";
            $coupon_codes = $order->get_coupon_codes();
            foreach ( $coupon_codes as $coupon_code ) {
                $discountCode = $coupon_code;
            }
            $order_array['discountCode']  = $discountCode;
            
            $order_array['timestamp']  = time();
            //$order_array['payTimestamp']  = time();
            $order_array['nonce']  = bin2hex(random_bytes(16));
            
            
$appId = $order_array['appId'];
$nonce = $order_array['nonce'];
$timestamp = time();
$type = $order_array['type'];
$secret_key = "yZ.qmUuVYz, h _ = Wzj: 4!naWAoxW.vjLm";

$data = "appId={$appId}&nonce={$nonce}&timestamp={$timestamp}&type={$type}";
$signature = base64_encode(hash_hmac('sha256', $data, $secret_key, true));
           
           
           
$secret = "yZ.qmUuVYz, h _ = Wzj: 4!naWAoxW.vjLm";



$filtered = array_filter($order_array, function($v) {
    return !is_null($v);
});
unset($filtered['signature']);
ksort($filtered);
$sign_string = [];
$sign_string_post = [];
foreach ($filtered as $key => $value) {
    $sign_string_post[$key] = $value;
    $sign_string[] = $key . '=' . $value;
}
$sign_string_format = implode('&', $sign_string);

$hash = hash_hmac('sha256', $sign_string_format, $secret, true);
$base64 = base64_encode($hash);
//$signature = rtrim(strtr($base64, '+/', '-_'), '=');

$order_array['signature'] = $signature;
//$sign_string['signature'] = $signature;
$sign_string_post['signature'] = $signature;

echo "<pre>";
print_r($sign_string);
print_r($sign_string_post);

$ch = curl_init("https://placeholder.emathsmart.ca/api/user-center/order/paymentNotify");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sign_string_post));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo $response;
}
print_r($response);
curl_close($ch);
        
        
        exit;
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
           
           
            
            //$order_array['signature']  = "yZ.qmUuVYz, h _ = Wzj: 4!naWAoxW.vjLm";
            $order_array['signature'] = $signature;
            
            
            echo "<pre>";
            //print_r($order);
            print_r($order_array);
            echo "<br>\n\n";
            //exit;
            $url = "https://placeholder.emathsmart.ca/api/user-center/order/paymentNotify";
            
            
            $payload = json_encode($order_array);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            echo "<br>\n\n";
            print_r($response);    
            echo "<br>\n\n";
            
            
            exit;
            
            
            
            
            
            
            
     echo "<br>  -- <br><br>";       
            
            
$appId = $order_array['appId'];
$nonce = $order_array['nonce'];
$timestamp = time();
$type = $order_array['type'];
$secret_key = "yZ.qmUuVYz, h _ = Wzj: 4!naWAoxW.vjLm";

$data = "appId={$appId}&nonce={$nonce}&&timestamp={$timestamp}&type={$type}";
$signature = base64_encode(hash_hmac('sha256', $data, $secret_key, true));


$url = "https://placeholder.emathsmart.ca/api/user-center/order/paymentNotify";

// POST fields (if required)
$postFields = $order_array;

// Init curl
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
$headers = [
    "Content-Type: application/json",
    "appId: $appId",
    "nonce: $nonce",
    "timestamp: $timestamp",
    "type: $type",
    "signature: $signature"
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo $response;
}
print_r($response);
curl_close($ch);
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            exit;
            
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($order_array));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/x-www-form-urlencoded"
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);
            echo $response;
            echo "<br>\n\n";
            print_r($response);
            
            
            
        }
            
            
            
}
*/

// -----------------------------------------------------------------------------
// PARENTS CLUB FULL-WIDTH TEMPLATE LOADER
// -----------------------------------------------------------------------------

/**
 * Register the custom page template programmatically in the theme templates dropdown.
 */
add_filter( 'theme_page_templates', 'idl_loader_register_parents_club_template' );
function idl_loader_register_parents_club_template( $templates ) {
    $templates['parents-club-template.php'] = 'Parents Club Landing (No Sidebar, Full Width)';
    return $templates;
}

/**
 * Intercept the template selection and load the custom full-width file from the plugin templates.
 */
add_filter( 'template_include', 'idl_loader_load_parents_club_template' );
function idl_loader_load_parents_club_template( $template ) {
    // 1. Check if the page is using our custom page template
    $page_template = get_page_template_slug( get_queried_object_id() );
    if ( 'parents-club-template.php' === $page_template ) {
        $plugin_template = plugin_dir_path( __FILE__ ) . 'templates/parents-club-template.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    }
    
    // 2. Fallback: Force load if it's the exact 'parents-club' slug page
    if ( is_page( 'parents-club' ) ) {
        $plugin_template = plugin_dir_path( __FILE__ ) . 'templates/parents-club-template.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    }
    return $template;
}/**
 * Force Porto Theme to strip out sidebars and render in full-width.
 */
add_filter( 'porto_meta_layout', 'idl_loader_parents_club_layout_filter' );
function idl_loader_parents_club_layout_filter( $layout ) {
    if ( is_page( 'parents-club' ) || 'parents-club-template.php' === get_page_template_slug( get_queried_object_id() ) ) {
        return array( 'fullwidth', '', '' );
    }
    return $layout;
}

add_filter( 'porto_meta_default_layout', 'idl_loader_parents_club_default_layout_filter' );
function idl_loader_parents_club_default_layout_filter( $layout ) {
    if ( is_page( 'parents-club' ) || 'parents-club-template.php' === get_page_template_slug( get_queried_object_id() ) ) {
        return 'fullwidth';
    }
    return $layout;
}

/**
 * Guarantee the proper page template body classes are always output,
 * even when the template file is intercepted programmatically by slug fallbacks.
 */
add_filter( 'body_class', 'idl_loader_parents_club_body_classes' );
function idl_loader_parents_club_body_classes( $classes ) {
    if ( is_page( 'parents-club' ) || 'parents-club-template.php' === get_page_template_slug( get_queried_object_id() ) ) {
        $classes[] = 'page-template-parents-club-template';
        $classes[] = 'parents-club-landing-page';
    }
    return $classes;
}

/**
 * Enqueue all Parents Club redesigned component stylesheets natively.
 * Utilizes highly robust fallback checks (page ID, slug, and template file name checks).
 */
add_action( 'wp_enqueue_scripts', 'idl_loader_parents_club_template_styles' );
function idl_loader_parents_club_template_styles() {
    global $post;
    $is_parents_page = false;
    
    if ( is_page( 'parents-club' ) ) {
        $is_parents_page = true;
    } elseif ( is_page_template( 'parents-club-template.php' ) ) {
        $is_parents_page = true;
    } elseif ( isset( $post->post_name ) && 'parents-club' === $post->post_name ) {
        $is_parents_page = true;
    } elseif ( is_singular() && 'parents-club-template.php' === get_page_template_slug( get_the_ID() ) ) {
        $is_parents_page = true;
    }
    
    if ( $is_parents_page ) {
        wp_enqueue_style( 'parents-club-template-layout', plugins_url( 'templates/css/parents-club-template.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-hero-brand', plugins_url( 'templates/css/parents-club-hero-brand.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-hero-signup', plugins_url( 'templates/css/parents-club-hero-signup.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-hero-benefits', plugins_url( 'templates/css/parents-club-hero-benefits.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-section-1', plugins_url( 'templates/css/section-1.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-section-2', plugins_url( 'templates/css/section-2.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-plans-section', plugins_url( 'templates/css/parents-club-plans-section.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-plans-base', plugins_url( 'templates/css/parents-club-plans-base.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-plan-digital', plugins_url( 'templates/css/parents-club-plan-digital.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-plan-monthly', plugins_url( 'templates/css/parents-club-plan-monthly.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-plan-annual', plugins_url( 'templates/css/parents-club-plan-annual.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-plan-not-ready', plugins_url( 'templates/css/parents-club-plan-not-ready.css', __FILE__ ) );
    }
}

/**
 * Inject custom CSS into admin head to style the WPBakery element visibility parameter tag.
 */
add_action( 'admin_head', 'idl_loader_add_wpbakery_visibility_tag_css' );
function idl_loader_add_wpbakery_visibility_tag_css() {
    ?>
    <style>
        .vc_admin_label.admin_label_pc_visibility {
            background-color: #af0128 !important;
            color: #ffffff !important;
            padding: 3px 8px !important;
            border-radius: 4px !important;
            font-size: 11px !important;
            font-weight: bold !important;
            display: inline-block !important;
            margin: 4px 0 !important;
            border: 1px solid #900120 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            transition: all 0.3s ease !important;
        }
        .vc_admin_label.admin_label_pc_visibility label {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: normal !important;
            margin-right: 3px !important;
            text-transform: none !important;
        }
        /* Custom colors based on visibility values */
        .vc_admin_label.admin_label_pc_visibility[data-vis-type="guests"] {
            background-color: #e67e22 !important; /* Amber Orange */
            border-color: #d35400 !important;
        }
        .vc_admin_label.admin_label_pc_visibility[data-vis-type="non-members"] {
            background-color: #7f8c8d !important; /* Asbestos Gray */
            border-color: #95a5a6 !important;
        }
        .vc_admin_label.admin_label_pc_visibility[data-vis-type="members"] {
            background-color: #2980b9 !important; /* Belize Hole Blue */
            border-color: #3498db !important;
        }
        .vc_admin_label.admin_label_pc_visibility[data-vis-type="members-no-sub"] {
            background-color: #16a085 !important; /* Turquoise Greenish Teal */
            border-color: #1abc9c !important;
        }
        .vc_admin_label.admin_label_pc_visibility[data-vis-type="active-subscribers"] {
            background-color: #27ae60 !important; /* Nephrite Green */
            border-color: #2ecc71 !important;
        }
        .vc_admin_label.admin_label_pc_visibility[data-vis-type="exclude-subscribers"] {
            background-color: #8e44ad !important; /* Wisteria Purple */
            border-color: #9b59b6 !important;
        }
    </style>
    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        function updateVisibilityTags() {
            var labels = document.querySelectorAll('.admin_label_pc_visibility');
            labels.forEach(function(span) {
                var text = span.textContent || span.innerText;
                var valText = text.replace(/Visibility\s*:\s*/i, '').trim().toLowerCase();
                
                var slug = 'all';
                if (valText.indexOf('guests') !== -1) {
                    slug = 'guests';
                } else if (valText.indexOf('non parents') !== -1) {
                    slug = 'non-members';
                } else if (valText.indexOf('no active subscription') !== -1) {
                    slug = 'members-no-sub';
                } else if (valText.indexOf('active subscription') !== -1) {
                    slug = 'active-subscribers';
                } else if (valText.indexOf('except active') !== -1) {
                    slug = 'exclude-subscribers';
                } else if (valText.indexOf('members only') !== -1) {
                    slug = 'members';
                }
                
                if (span.getAttribute('data-vis-type') !== slug) {
                    span.setAttribute('data-vis-type', slug);
                }
            });
        }

        // Run initially
        updateVisibilityTags();

        // Observe changes inside Visual Composer editor
        var target = document.getElementById('visual_composer_content') || document.body;
        var observer = new MutationObserver(function(mutations) {
            updateVisibilityTags();
        });
        observer.observe(target, { childList: true, subtree: true });
    });
    </script>
    <?php
}

/**
 * Process Contact Form 7 Parents Club upgrade form submissions.
 */
add_action( 'wpcf7_mail_sent', 'idl_loader_process_parents_club_cf7_upgrade' );
function idl_loader_process_parents_club_cf7_upgrade( $contact_form ) {
    $submission = WPCF7_Submission::get_instance();
    if ( ! $submission ) {
        return;
    }

    $posted_data = $submission->get_posted_data();
    $user_id = get_current_user_id();

    // Only run for logged-in users
    if ( ! $user_id ) {
        return;
    }

    // Identify if it's the upgrade form by checking for the presence of count and grades fields
    if ( isset( $posted_data['children-count'] ) || isset( $posted_data['children-grades'] ) ) {
        // 1. Mark user as Parents Club member
        update_user_meta( $user_id, 'user_registration_check_box_1661192013', 'parent_club_member' );

        // 2. Opt-in to Mailchimp (if required by site flow)
        update_user_meta( $user_id, 'user_registration_mailchimp_1661195342', '1' );

        // 3. Save children count
        if ( isset( $posted_data['children-count'] ) ) {
            $count = is_array( $posted_data['children-count'] ) ? implode( '', $posted_data['children-count'] ) : $posted_data['children-count'];
            update_user_meta( $user_id, 'parents_club_children_count', sanitize_text_field( $count ) );
        }

        // 4. Save children grades
        if ( isset( $posted_data['children-grades'] ) ) {
            $grades = $posted_data['children-grades'];
            if ( is_array( $grades ) ) {
                $grades = array_map( 'sanitize_text_field', $grades );
            } else {
                $grades = sanitize_text_field( $grades );
            }
            update_user_meta( $user_id, 'parents_club_children_grades', $grades );
        }
    }
}

