<?php
/**
 * Custom WPBakery Elements for eMathSmart Integration
 * File: functions-wpbakery-elements.php
 * 
 * Defines and registers custom page builder elements strictly within the idl-loader plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Safe guard
}

// -----------------------------------------------------------------------------
/**
 * Query and return WooCommerce subscription products and variations in a key-value dropdown format.
 */
function idl_loader_get_subscription_products() {
    if ( ! function_exists( 'wc_get_products' ) ) {
        return array();
    }
    
    $products = wc_get_products( array(
        'type'   => array( 'subscription', 'variable-subscription' ),
        'limit'  => -1,
        'status' => 'publish',
    ) );
    
    $options = array();
    $options[ esc_html__( 'Select a subscription product...', 'book-junky' ) ] = '';
    
    foreach ( $products as $product ) {
        $options[ $product->get_name() . ' (#' . $product->get_id() . ')' ] = $product->get_id();
        
        if ( $product->is_type( 'variable-subscription' ) ) {
            $variations = $product->get_children();
            foreach ( $variations as $var_id ) {
                $variation = wc_get_product( $var_id );
                if ( $variation ) {
                    $options[ '  - ' . $variation->get_name() . ' (#' . $variation->get_id() . ')' ] = $variation->get_id();
                }
            }
        }
    }
    return $options;
}

/**
 * Query and return WooCommerce products within the eMathSmart product category in a key-value dropdown format.
 */
function idl_loader_get_emathsmart_coins_products() {
    if ( ! taxonomy_exists( 'product_cat' ) ) {
        return array();
    }
    
    $slug = function_exists('emathsmart_get_product_category_slug') ? emathsmart_get_product_category_slug() : 'emathsmart-woo';
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $slug,
            ),
        ),
    );
    
    $products = get_posts( $args );
    $options = array();
    $options[ esc_html__( 'Select AI Coins Product...', 'book-junky' ) ] = '';
    
    foreach ( $products as $post ) {
        $product = wc_get_product( $post->ID );
        if ( $product && $product->is_type( 'variable' ) ) {
            $options[ $product->get_name() . ' (#' . $product->get_id() . ')' ] = $product->get_id();
        }
    }
    return $options;
}

/**
 * Query and return User Registration forms.
 */
function idl_loader_get_user_registration_forms() {
    $forms = get_posts( array(
        'post_type'      => 'user_registration',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ) );
    
    $options = array();
    $options[ esc_html__( 'Select a registration form...', 'book-junky' ) ] = '';
    
    if ( ! empty( $forms ) ) {
        foreach ( $forms as $form ) {
            $options[ $form->post_title . ' (#' . $form->ID . ')' ] = $form->ID;
        }
    }
    return $options;
}

add_action( 'vc_before_init', 'idl_loader_register_parents_club_elements' );


function idl_loader_register_parents_club_elements() {
    if ( ! function_exists( 'vc_map' ) ) {
        return; // Visual Composer is not active, bail out safely
    }

    // Register [parents_club_hero] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Hero", "book-junky" ),
        "base"        => "parents_club_hero",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Redesigned visual hero section for Parents Club page.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Hero Title", "book-junky" ),
                "param_name"  => "title",
                "value"       => esc_html__( "Welcome to the Parents Club", "book-junky" ),
                "admin_label" => true,
                "description" => esc_html__( "Enter the hero main heading.", "book-junky" ),
            ),
            array(
                "type"        => "textarea",
                "heading"     => esc_html__( "Hero Subtitle", "book-junky" ),
                "param_name"  => "subtitle",
                "value"       => esc_html__( "Unlock active personalized tutor portal links, custom student worksheets, educational video guides, and premium resources.", "book-junky" ),
                "admin_label" => true,
                "description" => esc_html__( "Enter the detailed secondary paragraph.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "CTA Button Text", "book-junky" ),
                "param_name"  => "cta_text",
                "value"       => esc_html__( "Get Started", "book-junky" ),
                "admin_label" => true,
                "description" => esc_html__( "Enter the call-to-action button label.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "CTA Button Link", "book-junky" ),
                "param_name"  => "cta_link",
                "value"       => "#parents-club-login",
                "description" => esc_html__( "Enter a target URL or scroll anchor (e.g. #parents-club-login or #pricing).", "book-junky" ),
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Background Image Overlay", "book-junky" ),
                "param_name"  => "bg_image",
                "description" => esc_html__( "Select an optional custom background image override.", "book-junky" ),
            ),
        )
    ) );

    // Register [parents_club_hero_intro] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Hero Intro", "book-junky" ),
        "base"        => "parents_club_hero_intro",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Branding columns, attribute checklist, and action buttons for Hero.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Custom Logo Image", "book-junky" ),
                "param_name"  => "logo_image",
                "description" => esc_html__( "Upload an optional custom logo. If left blank, the default Parents Club logo is loaded.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Attribute 1 Bold Heading", "book-junky" ),
                "param_name"  => "attr1_heading",
                "value"       => esc_html__( "40,000+", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Attribute 1 Subtext", "book-junky" ),
                "param_name"  => "attr1_subheading",
                "value"       => esc_html__( "Canadian Parents", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Attribute 2 Bold Heading", "book-junky" ),
                "param_name"  => "attr2_heading",
                "value"       => esc_html__( "Curriculum-", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Attribute 2 Subtext", "book-junky" ),
                "param_name"  => "attr2_subheading",
                "value"       => esc_html__( "Aligned", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Attribute 3 Bold Heading", "book-junky" ),
                "param_name"  => "attr3_heading",
                "value"       => esc_html__( "Trusted", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Attribute 3 Subtext", "book-junky" ),
                "param_name"  => "attr3_subheading",
                "value"       => esc_html__( "Since 1994", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Solid Crimson Button Text", "book-junky" ),
                "param_name"  => "btn1_text",
                "value"       => esc_html__( "Join Parents' Club - It's Free!", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Solid Crimson Button Link", "book-junky" ),
                "param_name"  => "btn1_link",
                "value"       => "#signup",
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Outline Button Text", "book-junky" ),
                "param_name"  => "btn2_text",
                "value"       => esc_html__( "Member Login", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Outline Button Link", "book-junky" ),
                "param_name"  => "btn2_link",
                "value"       => "#login",
            ),
        )
    ) );

    // Register [parents_club_benefits_glance] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Benefits Glance", "book-junky" ),
        "base"        => "parents_club_benefits_glance",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Overlapping crimson benefits glance card and top study banner illustration.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Show Banner Image", "book-junky" ),
                "param_name"  => "show_banner_image",
                "value"       => array(
                    esc_html__( "Yes", "book-junky" ) => "yes",
                    esc_html__( "No (No Image)", "book-junky" ) => "no",
                ),
                "std"         => "yes",
                "description" => esc_html__( "Choose whether to display the top banner image.", "book-junky" ),
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Top Banner Image", "book-junky" ),
                "param_name"  => "banner_image",
                "dependency"  => array(
                    "element" => "show_banner_image",
                    "value"   => array( "yes" ),
                ),
                "description" => esc_html__( "Upload an optional custom banner image. If left blank, the default study banner is loaded.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Card Heading Title", "book-junky" ),
                "param_name"  => "card_title",
                "value"       => esc_html__( "Benefits at a Glance", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Benefit 1 Text", "book-junky" ),
                "param_name"  => "benefit1_text",
                "value"       => esc_html__( "50% OFF Complete Canadian Curriculum Series", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 1 Icon Source", "book-junky" ),
                "param_name"  => "benefit1_icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 1 Brand Outline Icon", "book-junky" ),
                "param_name"  => "benefit1_brand_icon_type",
                "value"       => array(
                    esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                    esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                    esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                    esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                    esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                    esc_html__( "Book Icon", "book-junky" )                 => "book",
                    esc_html__( "Star Icon", "book-junky" )                 => "star",
                    esc_html__( "Shield Icon", "book-junky" )               => "shield",
                ),
                "std"         => "tag",
                "dependency"  => array(
                    "element" => "benefit1_icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 1 Icon Library", "book-junky" ),
                "param_name"  => "benefit1_icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "benefit1_icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 1 Font Awesome Icon", "book-junky" ),
                "param_name"  => "benefit1_icon_fontawesome",
                "value"       => "fa fa-tag",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit1_icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 1 Linecons Icon", "book-junky" ),
                "param_name"  => "benefit1_icon_linecons",
                "value"       => "vc_li vc_li-tag",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit1_icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Benefit 1 Custom Icon", "book-junky" ),
                "param_name"  => "benefit1_custom_icon",
                "description" => esc_html__( "Upload an SVG or image to use as a custom icon for Benefit 1.", "book-junky" ),
                "dependency"  => array(
                    "element" => "benefit1_icon_source",
                    "value"   => array( "custom" )
                )
            ),

            // Benefit 2
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Benefit 2 Text", "book-junky" ),
                "param_name"  => "benefit2_text",
                "value"       => esc_html__( "Learning Tips & News Helpful parenting advice", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 2 Icon Source", "book-junky" ),
                "param_name"  => "benefit2_icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 2 Brand Outline Icon", "book-junky" ),
                "param_name"  => "benefit2_brand_icon_type",
                "value"       => array(
                    esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                    esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                    esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                    esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                    esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                    esc_html__( "Book Icon", "book-junky" )                 => "book",
                    esc_html__( "Star Icon", "book-junky" )                 => "star",
                    esc_html__( "Shield Icon", "book-junky" )               => "shield",
                ),
                "std"         => "idea",
                "dependency"  => array(
                    "element" => "benefit2_icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 2 Icon Library", "book-junky" ),
                "param_name"  => "benefit2_icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "benefit2_icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 2 Font Awesome Icon", "book-junky" ),
                "param_name"  => "benefit2_icon_fontawesome",
                "value"       => "fa fa-lightbulb-o",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit2_icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 2 Linecons Icon", "book-junky" ),
                "param_name"  => "benefit2_icon_linecons",
                "value"       => "vc_li vc_li-bulb",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit2_icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Benefit 2 Custom Icon", "book-junky" ),
                "param_name"  => "benefit2_custom_icon",
                "description" => esc_html__( "Upload an SVG or image to use as a custom icon for Benefit 2.", "book-junky" ),
                "dependency"  => array(
                    "element" => "benefit2_icon_source",
                    "value"   => array( "custom" )
                )
            ),

            // Benefit 3
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Benefit 3 Text", "book-junky" ),
                "param_name"  => "benefit3_text",
                "value"       => esc_html__( "30% OFF All Other Workbooks", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 3 Icon Source", "book-junky" ),
                "param_name"  => "benefit3_icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 3 Brand Outline Icon", "book-junky" ),
                "param_name"  => "benefit3_brand_icon_type",
                "value"       => array(
                    esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                    esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                    esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                    esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                    esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                    esc_html__( "Book Icon", "book-junky" )                 => "book",
                    esc_html__( "Star Icon", "book-junky" )                 => "star",
                    esc_html__( "Shield Icon", "book-junky" )               => "shield",
                ),
                "std"         => "tag",
                "dependency"  => array(
                    "element" => "benefit3_icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 3 Icon Library", "book-junky" ),
                "param_name"  => "benefit3_icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "benefit3_icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 3 Font Awesome Icon", "book-junky" ),
                "param_name"  => "benefit3_icon_fontawesome",
                "value"       => "fa fa-tag",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit3_icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 3 Linecons Icon", "book-junky" ),
                "param_name"  => "benefit3_icon_linecons",
                "value"       => "vc_li vc_li-tag",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit3_icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Benefit 3 Custom Icon", "book-junky" ),
                "param_name"  => "benefit3_custom_icon",
                "description" => esc_html__( "Upload an SVG or image to use as a custom icon for Benefit 3.", "book-junky" ),
                "dependency"  => array(
                    "element" => "benefit3_icon_source",
                    "value"   => array( "custom" )
                )
            ),

            // Benefit 4
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Benefit 4 Text", "book-junky" ),
                "param_name"  => "benefit4_text",
                "value"       => esc_html__( "Special Promotions & Offers Exclusive member campaigns", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 4 Icon Source", "book-junky" ),
                "param_name"  => "benefit4_icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 4 Brand Outline Icon", "book-junky" ),
                "param_name"  => "benefit4_brand_icon_type",
                "value"       => array(
                    esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                    esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                    esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                    esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                    esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                    esc_html__( "Book Icon", "book-junky" )                 => "book",
                    esc_html__( "Star Icon", "book-junky" )                 => "star",
                    esc_html__( "Shield Icon", "book-junky" )               => "shield",
                ),
                "std"         => "gift",
                "dependency"  => array(
                    "element" => "benefit4_icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 4 Icon Library", "book-junky" ),
                "param_name"  => "benefit4_icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "benefit4_icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 4 Font Awesome Icon", "book-junky" ),
                "param_name"  => "benefit4_icon_fontawesome",
                "value"       => "fa fa-gift",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit4_icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 4 Linecons Icon", "book-junky" ),
                "param_name"  => "benefit4_icon_linecons",
                "value"       => "vc_li vc_li-params",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit4_icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Benefit 4 Custom Icon", "book-junky" ),
                "param_name"  => "benefit4_custom_icon",
                "description" => esc_html__( "Upload an SVG or image to use as a custom icon for Benefit 4.", "book-junky" ),
                "dependency"  => array(
                    "element" => "benefit4_icon_source",
                    "value"   => array( "custom" )
                )
            ),

            // Benefit 5
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Benefit 5 Text", "book-junky" ),
                "param_name"  => "benefit5_text",
                "value"       => esc_html__( "Free Worksheets & Resources Immediate print downloads", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 5 Icon Source", "book-junky" ),
                "param_name"  => "benefit5_icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 5 Brand Outline Icon", "book-junky" ),
                "param_name"  => "benefit5_brand_icon_type",
                "value"       => array(
                    esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                    esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                    esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                    esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                    esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                    esc_html__( "Book Icon", "book-junky" )                 => "book",
                    esc_html__( "Star Icon", "book-junky" )                 => "star",
                    esc_html__( "Shield Icon", "book-junky" )               => "shield",
                ),
                "std"         => "file",
                "dependency"  => array(
                    "element" => "benefit5_icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 5 Icon Library", "book-junky" ),
                "param_name"  => "benefit5_icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "benefit5_icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 5 Font Awesome Icon", "book-junky" ),
                "param_name"  => "benefit5_icon_fontawesome",
                "value"       => "fa fa-file-text-o",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit5_icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 5 Linecons Icon", "book-junky" ),
                "param_name"  => "benefit5_icon_linecons",
                "value"       => "vc_li vc_li-paperplane",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit5_icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Benefit 5 Custom Icon", "book-junky" ),
                "param_name"  => "benefit5_custom_icon",
                "description" => esc_html__( "Upload an SVG or image to use as a custom icon for Benefit 5.", "book-junky" ),
                "dependency"  => array(
                    "element" => "benefit5_icon_source",
                    "value"   => array( "custom" )
                )
            ),

            // Benefit 6
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Benefit 6 Text", "book-junky" ),
                "param_name"  => "benefit6_text",
                "value"       => esc_html__( "Access to eMathSmart Optional interactive portal", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 6 Icon Source", "book-junky" ),
                "param_name"  => "benefit6_icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 6 Brand Outline Icon", "book-junky" ),
                "param_name"  => "benefit6_brand_icon_type",
                "value"       => array(
                    esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                    esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                    esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                    esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                    esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                    esc_html__( "Book Icon", "book-junky" )                 => "book",
                    esc_html__( "Star Icon", "book-junky" )                 => "star",
                    esc_html__( "Shield Icon", "book-junky" )               => "shield",
                ),
                "std"         => "computer",
                "dependency"  => array(
                    "element" => "benefit6_icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 6 Icon Library", "book-junky" ),
                "param_name"  => "benefit6_icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "benefit6_icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 6 Font Awesome Icon", "book-junky" ),
                "param_name"  => "benefit6_icon_fontawesome",
                "value"       => "fa fa-desktop",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit6_icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 6 Linecons Icon", "book-junky" ),
                "param_name"  => "benefit6_icon_linecons",
                "value"       => "vc_li vc_li-tv",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit6_icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Benefit 6 Custom Icon", "book-junky" ),
                "param_name"  => "benefit6_custom_icon",
                "description" => esc_html__( "Upload an SVG or image to use as a custom icon for Benefit 6.", "book-junky" ),
                "dependency"  => array(
                    "element" => "benefit6_icon_source",
                    "value"   => array( "custom" )
                )
            ),

            // Benefit 7
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Benefit 7 Text", "book-junky" ),
                "param_name"  => "benefit7_text",
                "value"       => "",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 7 Icon Source", "book-junky" ),
                "param_name"  => "benefit7_icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 7 Brand Outline Icon", "book-junky" ),
                "param_name"  => "benefit7_brand_icon_type",
                "value"       => array(
                    esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                    esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                    esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                    esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                    esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                    esc_html__( "Book Icon", "book-junky" )                 => "book",
                    esc_html__( "Star Icon", "book-junky" )                 => "star",
                    esc_html__( "Shield Icon", "book-junky" )               => "shield",
                ),
                "std"         => "star",
                "dependency"  => array(
                    "element" => "benefit7_icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 7 Icon Library", "book-junky" ),
                "param_name"  => "benefit7_icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "benefit7_icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 7 Font Awesome Icon", "book-junky" ),
                "param_name"  => "benefit7_icon_fontawesome",
                "value"       => "fa fa-star",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit7_icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 7 Linecons Icon", "book-junky" ),
                "param_name"  => "benefit7_icon_linecons",
                "value"       => "vc_li vc_li-star",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit7_icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Benefit 7 Custom Icon", "book-junky" ),
                "param_name"  => "benefit7_custom_icon",
                "description" => esc_html__( "Upload an SVG or image to use as a custom icon for Benefit 7.", "book-junky" ),
                "dependency"  => array(
                    "element" => "benefit7_icon_source",
                    "value"   => array( "custom" )
                )
            ),

            // Benefit 8
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Benefit 8 Text", "book-junky" ),
                "param_name"  => "benefit8_text",
                "value"       => "",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 8 Icon Source", "book-junky" ),
                "param_name"  => "benefit8_icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 8 Brand Outline Icon", "book-junky" ),
                "param_name"  => "benefit8_brand_icon_type",
                "value"       => array(
                    esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                    esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                    esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                    esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                    esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                    esc_html__( "Book Icon", "book-junky" )                 => "book",
                    esc_html__( "Star Icon", "book-junky" )                 => "star",
                    esc_html__( "Shield Icon", "book-junky" )               => "shield",
                ),
                "std"         => "shield",
                "dependency"  => array(
                    "element" => "benefit8_icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 8 Icon Library", "book-junky" ),
                "param_name"  => "benefit8_icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "benefit8_icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 8 Font Awesome Icon", "book-junky" ),
                "param_name"  => "benefit8_icon_fontawesome",
                "value"       => "fa fa-shield",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit8_icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 8 Linecons Icon", "book-junky" ),
                "param_name"  => "benefit8_icon_linecons",
                "value"       => "vc_li vc_li-key",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit8_icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Benefit 8 Custom Icon", "book-junky" ),
                "param_name"  => "benefit8_custom_icon",
                "description" => esc_html__( "Upload an SVG or image to use as a custom icon for Benefit 8.", "book-junky" ),
                "dependency"  => array(
                    "element" => "benefit8_icon_source",
                    "value"   => array( "custom" )
                )
            ),

            // Benefit 9
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Benefit 9 Text", "book-junky" ),
                "param_name"  => "benefit9_text",
                "value"       => "",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 9 Icon Source", "book-junky" ),
                "param_name"  => "benefit9_icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 9 Brand Outline Icon", "book-junky" ),
                "param_name"  => "benefit9_brand_icon_type",
                "value"       => array(
                    esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                    esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                    esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                    esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                    esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                    esc_html__( "Book Icon", "book-junky" )                 => "book",
                    esc_html__( "Star Icon", "book-junky" )                 => "star",
                    esc_html__( "Shield Icon", "book-junky" )               => "shield",
                ),
                "std"         => "tag",
                "dependency"  => array(
                    "element" => "benefit9_icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 9 Icon Library", "book-junky" ),
                "param_name"  => "benefit9_icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "benefit9_icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 9 Font Awesome Icon", "book-junky" ),
                "param_name"  => "benefit9_icon_fontawesome",
                "value"       => "fa fa-tag",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit9_icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 9 Linecons Icon", "book-junky" ),
                "param_name"  => "benefit9_icon_linecons",
                "value"       => "vc_li vc_li-tag",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit9_icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Benefit 9 Custom Icon", "book-junky" ),
                "param_name"  => "benefit9_custom_icon",
                "description" => esc_html__( "Upload an SVG or image to use as a custom icon for Benefit 9.", "book-junky" ),
                "dependency"  => array(
                    "element" => "benefit9_icon_source",
                    "value"   => array( "custom" )
                )
            ),

            // Benefit 10
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Benefit 10 Text", "book-junky" ),
                "param_name"  => "benefit10_text",
                "value"       => "",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 10 Icon Source", "book-junky" ),
                "param_name"  => "benefit10_icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 10 Brand Outline Icon", "book-junky" ),
                "param_name"  => "benefit10_brand_icon_type",
                "value"       => array(
                    esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                    esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                    esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                    esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                    esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                    esc_html__( "Book Icon", "book-junky" )                 => "book",
                    esc_html__( "Star Icon", "book-junky" )                 => "star",
                    esc_html__( "Shield Icon", "book-junky" )               => "shield",
                ),
                "std"         => "idea",
                "dependency"  => array(
                    "element" => "benefit10_icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Benefit 10 Icon Library", "book-junky" ),
                "param_name"  => "benefit10_icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "benefit10_icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 10 Font Awesome Icon", "book-junky" ),
                "param_name"  => "benefit10_icon_fontawesome",
                "value"       => "fa fa-lightbulb-o",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit10_icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Benefit 10 Linecons Icon", "book-junky" ),
                "param_name"  => "benefit10_icon_linecons",
                "value"       => "vc_li vc_li-bulb",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "benefit10_icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Benefit 10 Custom Icon", "book-junky" ),
                "param_name"  => "benefit10_custom_icon",
                "description" => esc_html__( "Upload an SVG or image to use as a custom icon for Benefit 10.", "book-junky" ),
                "dependency"  => array(
                    "element" => "benefit10_icon_source",
                    "value"   => array( "custom" )
                )
            ),
        )
    ) );

    // Register [parents_club_why_join] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Why Join Bar", "book-junky" ),
        "base"        => "parents_club_why_join",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Horizontal feature highlights bar for Parents Club landing page.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Section Title", "book-junky" ),
                "param_name"  => "section_title",
                "value"       => esc_html__( "Why Join Parents' Club?", "book-junky" ),
                "admin_label" => true,
                "description" => esc_html__( "Use <br> to stack the heading.", "book-junky" ),
            ),
            // Feature 1
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 1 Title", "book-junky" ),
                "param_name"  => "feat1_title",
                "value"       => esc_html__( "Save More", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 1 Description", "book-junky" ),
                "param_name"  => "feat1_desc",
                "value"       => esc_html__( "Workbook discounts all year long", "book-junky" ),
            ),
            // Feature 2
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 2 Title", "book-junky" ),
                "param_name"  => "feat2_title",
                "value"       => esc_html__( "Free Resources", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 2 Description", "book-junky" ),
                "param_name"  => "feat2_desc",
                "value"       => esc_html__( "Worksheets, printables & learning tools", "book-junky" ),
            ),
            // Feature 3
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 3 Title", "book-junky" ),
                "param_name"  => "feat3_title",
                "value"       => esc_html__( "Learning Support", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 3 Description", "book-junky" ),
                "param_name"  => "feat3_desc",
                "value"       => esc_html__( "Helpful tips for learning at home", "book-junky" ),
            ),
            // Feature 4
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 4 Title", "book-junky" ),
                "param_name"  => "feat4_title",
                "value"       => esc_html__( "Special Perks", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 4 Description", "book-junky" ),
                "param_name"  => "feat4_desc",
                "value"       => esc_html__( "Exclusive promotions & giveaways", "book-junky" ),
            ),
            // Feature 5
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 5 Title", "book-junky" ),
                "param_name"  => "feat5_title",
                "value"       => esc_html__( "Trusted Quality", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 5 Description", "book-junky" ),
                "param_name"  => "feat5_desc",
                "value"       => esc_html__( "Curriculum-aligned Canadian resources", "book-junky" ),
            ),
            // Feature 6
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 6 Title", "book-junky" ),
                "param_name"  => "feat6_title",
                "value"       => esc_html__( "eMathSmart Access", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Feature 6 Description", "book-junky" ),
                "param_name"  => "feat6_desc",
                "value"       => esc_html__( "Optional digital learning upgrade", "book-junky" ),
            ),
        )
    ) );

    // Register [parents_club_how_works] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club How Works Steps", "book-junky" ),
        "base"        => "parents_club_how_works",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Dynamic step-by-step onboarding card with customizable numbers, texts, and icons.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Card Title", "book-junky" ),
                "param_name"  => "card_title",
                "value"       => esc_html__( "How Parents' Club Works", "book-junky" ),
                "admin_label" => true,
            ),
            // Dynamic repeatable steps
            array(
                "type"        => "param_group",
                "heading"     => esc_html__( "Steps", "book-junky" ),
                "param_name"  => "steps",
                "description" => esc_html__( "Add, remove, and reorder steps visually.", "book-junky" ),
                "value"       => urlencode( json_encode( array(
                    array(
                        'num'             => '1',
                        'title'           => 'Create Your Free Account',
                        'desc'            => 'Sign up in minutes and start enjoying member benefits right away.',
                        'icon_source'     => 'brand',
                        'brand_icon_type' => 'user_add',
                    ),
                    array(
                        'num'             => '2',
                        'title'           => 'Enjoy Member Savings & Resources',
                        'desc'            => 'Unlock discounts on workbooks and access free learning resources, tips, and more.',
                        'icon_source'     => 'brand',
                        'brand_icon_type' => 'tag',
                    ),
                    array(
                        'num'             => '3',
                        'title'           => '(Optional) Access eMathSmart',
                        'desc'            => 'Subscribe to eMathSmart for interactive digital learning and track progress.',
                        'icon_source'     => 'brand',
                        'brand_icon_type' => 'users',
                    ),
                ) ) ),
                "params"      => array(
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Step Number Badge", "book-junky" ),
                        "param_name"  => "num",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Step Title", "book-junky" ),
                        "param_name"  => "title",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Step Description", "book-junky" ),
                        "param_name"  => "desc",
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Step Icon Source", "book-junky" ),
                        "param_name"  => "icon_source",
                        "value"       => array(
                            esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                            esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                            esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                        ),
                        "std"         => "brand",
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Step Brand Outline Icon", "book-junky" ),
                        "param_name"  => "brand_icon_type",
                        "value"       => array(
                            esc_html__( "User Add Icon", "book-junky" )             => "user_add",
                            esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                            esc_html__( "Users Icon", "book-junky" )                => "users",
                            esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                            esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                            esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                            esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                            esc_html__( "Star Icon", "book-junky" )                 => "star",
                            esc_html__( "Shield Icon", "book-junky" )               => "shield",
                        ),
                        "std"         => "user_add",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "brand" )
                        )
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Step Icon Library", "book-junky" ),
                        "param_name"  => "icon_library",
                        "value"       => array(
                            esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                            esc_html__( "Linecons", "book-junky" )     => "linecons",
                        ),
                        "std"         => "fontawesome",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "library" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Step Font Awesome Icon", "book-junky" ),
                        "param_name"  => "icon_fontawesome",
                        "value"       => "fa fa-user-plus",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "fontawesome" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Step Linecons Icon", "book-junky" ),
                        "param_name"  => "icon_linecons",
                        "value"       => "vc_li vc_li-user",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "type"         => "linecons",
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "linecons" )
                        )
                    ),
                    array(
                        "type"        => "attach_image",
                        "heading"     => esc_html__( "Step Custom Icon", "book-junky" ),
                        "param_name"  => "custom_icon",
                        "description" => esc_html__( "Upload an SVG or image to use as a custom icon for this step.", "book-junky" ),
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "custom" )
                        )
                    ),
                ),
            ),
        )
    ) );

    // Register [esmart_login_card] Element
    vc_map( array(
        "name"        => esc_html__( "eMathSmart Login Card", "book-junky" ),
        "base"        => "esmart_login_card",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Gateway login card for eMathSmart — shows brand logo, login CTA, and redirect link.", "book-junky" ),
        "params"      => array(

            // --- Brand ---
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Card Brand Type", "book-junky" ),
                "param_name"  => "brand_type",
                "value"       => array(
                    esc_html__( "Plugin eMathSmart Logo (default)", "book-junky" ) => "plugin_logo",
                    esc_html__( "Custom Brand Image Upload", "book-junky" )         => "custom_image",
                ),
                "std"         => "plugin_logo",
                "admin_label" => true,
                "description" => esc_html__( "Choose how the brand identity is displayed at the top of the card.", "book-junky" ),
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Custom Brand Image", "book-junky" ),
                "param_name"  => "brand_image",
                "description" => esc_html__( "Upload a custom logo image or SVG. Shown only when 'Custom Brand Image Upload' is selected above.", "book-junky" ),
                "dependency"  => array(
                    "element" => "brand_type",
                    "value"   => array( "custom_image" ),
                ),
            ),

            // --- Content ---
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Lead Title Text", "book-junky" ),
                "param_name"  => "lead_title",
                "value"       => esc_html__( "Already have an eMathSmart account?", "book-junky" ),
                "admin_label" => true,
                "description" => esc_html__( "Main question / prompt shown above the login button.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Description Paragraph", "book-junky" ),
                "param_name"  => "description",
                "value"       => esc_html__( "Login here to access the program.", "book-junky" ),
                "description" => esc_html__( "Short instruction text shown below the lead title.", "book-junky" ),
            ),

            // --- CTA Button ---
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "CTA Button Label", "book-junky" ),
                "param_name"  => "cta_label",
                "value"       => esc_html__( "Login to eMathSmart", "book-junky" ),
                "admin_label" => true,
                "description" => esc_html__( "Text displayed on the main login button.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "CTA Button Link", "book-junky" ),
                "param_name"  => "cta_link",
                "value"       => "#emathsmart-login",
                "description" => esc_html__( "Destination URL or scroll anchor for the login button (e.g. #emathsmart-login).", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "CTA Button Icon", "book-junky" ),
                "param_name"  => "cta_icon_library",
                "value"       => array(
                    esc_html__( "Arrow (default)", "book-junky" ) => "arrow",
                    esc_html__( "Font Awesome", "book-junky" )    => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )        => "linecons",
                ),
                "std"         => "arrow",
                "description" => esc_html__( "Hover indicator icon library for the CTA button.", "book-junky" ),
            ),
            array(
                "type"       => "iconpicker",
                "heading"    => esc_html__( "Font Awesome Button Icon", "book-junky" ),
                "param_name" => "cta_icon_fontawesome",
                "value"      => "fa fa-arrow-right",
                "settings"   => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency" => array(
                    "element" => "cta_icon_library",
                    "value"   => array( "fontawesome" ),
                ),
            ),
            array(
                "type"       => "iconpicker",
                "heading"    => esc_html__( "Linecons Button Icon", "book-junky" ),
                "param_name" => "cta_icon_linecons",
                "value"      => "vc_li vc_li-arrow-right",
                "settings"   => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency" => array(
                    "element" => "cta_icon_library",
                    "value"   => array( "linecons" ),
                ),
            ),

            // --- Redirect / Bottom Link ---
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Redirect Lead Text", "book-junky" ),
                "param_name"  => "redirect_lead",
                "value"       => esc_html__( "New to eMathSmart?", "book-junky" ),
                "description" => esc_html__( "Introductory text before the bottom redirect link.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Redirect Link Label", "book-junky" ),
                "param_name"  => "redirect_label",
                "value"       => esc_html__( "Learn more", "book-junky" ),
                "description" => esc_html__( "Clickable anchor text for the redirect link.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Redirect Link URL", "book-junky" ),
                "param_name"  => "redirect_link",
                "value"       => "#emathsmart-learn",
                "description" => esc_html__( "Destination URL or anchor for the redirect link (e.g. #emathsmart-learn).", "book-junky" ),
            ),
        ),
    ) );

    // Register [parents_club_member_perks] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Member Perks Bar", "book-junky" ),
        "base"        => "parents_club_member_perks",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Horizontal 5-perk icon bar — 'What You Get as a Parents' Club Member'.", "book-junky" ),
        "params"      => array(

            // Section title
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Section Title", "book-junky" ),
                "param_name"  => "section_title",
                "value"       => esc_html__( "What You Get as a Parents' Club Member", "book-junky" ),
                "admin_label" => true,
                "description" => esc_html__( "Heading displayed above the perks row.", "book-junky" ),
            ),

            // ── Perk 1 ──
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Perk 1 — Line 1", "book-junky" ),
                "param_name"  => "perk1_line1",
                "value"       => esc_html__( "Free Worksheets", "book-junky" ),
                "admin_label" => true,
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Perk 1 — Line 2", "book-junky" ),
                "param_name"  => "perk1_line2",
                "value"       => esc_html__( "& Resources", "book-junky" ),
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Perk 1 — Brand Icon", "book-junky" ),
                "param_name"  => "perk1_icon",
                "value"       => array(
                    esc_html__( "File / Worksheets", "book-junky" )         => "file",
                    esc_html__( "Lightbulb / Learning Tips", "book-junky" ) => "idea",
                    esc_html__( "Discount Tag / Promotions", "book-junky" ) => "discount",
                    esc_html__( "Package Box / Early Access", "book-junky" ) => "box",
                    esc_html__( "Gift / Contests & Giveaways", "book-junky" ) => "gift",
                    esc_html__( "Star", "book-junky" )                      => "star",
                    esc_html__( "Shield", "book-junky" )                    => "shield",
                    esc_html__( "Tag", "book-junky" )                       => "tag",
                ),
                "std"         => "file",
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),

            // ── Perk 2 ──
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Perk 2 — Line 1", "book-junky" ),
                "param_name"  => "perk2_line1",
                "value"       => esc_html__( "Learning Tips,", "book-junky" ),
                "admin_label" => true,
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Perk 2 — Line 2", "book-junky" ),
                "param_name"  => "perk2_line2",
                "value"       => esc_html__( "News & Articles", "book-junky" ),
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Perk 2 — Brand Icon", "book-junky" ),
                "param_name"  => "perk2_icon",
                "value"       => array(
                    esc_html__( "File / Worksheets", "book-junky" )          => "file",
                    esc_html__( "Lightbulb / Learning Tips", "book-junky" )  => "idea",
                    esc_html__( "Discount Tag / Promotions", "book-junky" )  => "discount",
                    esc_html__( "Package Box / Early Access", "book-junky" ) => "box",
                    esc_html__( "Gift / Contests & Giveaways", "book-junky" ) => "gift",
                    esc_html__( "Star", "book-junky" )                       => "star",
                    esc_html__( "Shield", "book-junky" )                     => "shield",
                    esc_html__( "Tag", "book-junky" )                        => "tag",
                ),
                "std"         => "idea",
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),

            // ── Perk 3 ──
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Perk 3 — Line 1", "book-junky" ),
                "param_name"  => "perk3_line1",
                "value"       => esc_html__( "Special Promotions", "book-junky" ),
                "admin_label" => true,
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Perk 3 — Line 2", "book-junky" ),
                "param_name"  => "perk3_line2",
                "value"       => esc_html__( "& Discounts", "book-junky" ),
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Perk 3 — Brand Icon", "book-junky" ),
                "param_name"  => "perk3_icon",
                "value"       => array(
                    esc_html__( "File / Worksheets", "book-junky" )          => "file",
                    esc_html__( "Lightbulb / Learning Tips", "book-junky" )  => "idea",
                    esc_html__( "Discount Tag / Promotions", "book-junky" )  => "discount",
                    esc_html__( "Package Box / Early Access", "book-junky" ) => "box",
                    esc_html__( "Gift / Contests & Giveaways", "book-junky" ) => "gift",
                    esc_html__( "Star", "book-junky" )                       => "star",
                    esc_html__( "Shield", "book-junky" )                     => "shield",
                    esc_html__( "Tag", "book-junky" )                        => "tag",
                ),
                "std"         => "discount",
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),

            // ── Perk 4 ──
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Perk 4 — Line 1", "book-junky" ),
                "param_name"  => "perk4_line1",
                "value"       => esc_html__( "Early Access to", "book-junky" ),
                "admin_label" => true,
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Perk 4 — Line 2", "book-junky" ),
                "param_name"  => "perk4_line2",
                "value"       => esc_html__( "New Products", "book-junky" ),
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Perk 4 — Brand Icon", "book-junky" ),
                "param_name"  => "perk4_icon",
                "value"       => array(
                    esc_html__( "File / Worksheets", "book-junky" )          => "file",
                    esc_html__( "Lightbulb / Learning Tips", "book-junky" )  => "idea",
                    esc_html__( "Discount Tag / Promotions", "book-junky" )  => "discount",
                    esc_html__( "Package Box / Early Access", "book-junky" ) => "box",
                    esc_html__( "Gift / Contests & Giveaways", "book-junky" ) => "gift",
                    esc_html__( "Star", "book-junky" )                       => "star",
                    esc_html__( "Shield", "book-junky" )                     => "shield",
                    esc_html__( "Tag", "book-junky" )                        => "tag",
                ),
                "std"         => "box",
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),

            // ── Perk 5 ──
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Perk 5 — Line 1", "book-junky" ),
                "param_name"  => "perk5_line1",
                "value"       => esc_html__( "Contests, Giveaways", "book-junky" ),
                "admin_label" => true,
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Perk 5 — Line 2", "book-junky" ),
                "param_name"  => "perk5_line2",
                "value"       => esc_html__( "& More!", "book-junky" ),
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Perk 5 — Brand Icon", "book-junky" ),
                "param_name"  => "perk5_icon",
                "value"       => array(
                    esc_html__( "File / Worksheets", "book-junky" )          => "file",
                    esc_html__( "Lightbulb / Learning Tips", "book-junky" )  => "idea",
                    esc_html__( "Discount Tag / Promotions", "book-junky" )  => "discount",
                    esc_html__( "Package Box / Early Access", "book-junky" ) => "box",
                    esc_html__( "Gift / Contests & Giveaways", "book-junky" ) => "gift",
                    esc_html__( "Star", "book-junky" )                       => "star",
                    esc_html__( "Shield", "book-junky" )                     => "shield",
                    esc_html__( "Tag", "book-junky" )                        => "tag",
                ),
                "std"         => "gift",
                "group"       => esc_html__( "Perk Items", "book-junky" ),
            ),

            // ── Appearance ──
            array(
                "type"        => "colorpicker",
                "heading"     => esc_html__( "Card Background Colour", "book-junky" ),
                "param_name"  => "bg_color",
                "value"       => "#fdf9fb",
                "description" => esc_html__( "Fill colour of the contained perks card. Default is the warm cream from the design.", "book-junky" ),
                "group"       => esc_html__( "Appearance", "book-junky" ),
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => esc_html__( "Icon Colour", "book-junky" ),
                "param_name"  => "icon_color",
                "value"       => "#af0128",
                "description" => esc_html__( "Brand red applied to all perk icons. Adjust only if you need a colour override.", "book-junky" ),
                "group"       => esc_html__( "Appearance", "book-junky" ),
            ),
        )
    ) );

    // Register [emathsmart_plan_card] Unified Element
    vc_map( array(
        "name"        => esc_html__( "eMathSmart Plan Card", "book-junky" ),
        "base"        => "emathsmart_plan_card",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Unified modular plan card supporting digital, pricing, and alternative not-ready layouts.", "book-junky" ),
        "params"      => array(
            // 1. Layout & High-Level Scoping
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Card Layout Type", "book-junky" ),
                "param_name"  => "card_layout",
                "value"       => array(
                    esc_html__( "Digital Learning Layout", "book-junky" ) => "digital",
                    esc_html__( "Pricing Plan Layout", "book-junky" )    => "pricing",
                    esc_html__( "Alternative Info / Not Ready Layout", "book-junky" ) => "not_ready",
                ),
                "std"         => "digital",
                "admin_label" => true,
                "description" => esc_html__( "Select the card layout style. Scoped custom CSS will load conditionally.", "book-junky" ),
            ),
            array(
                "type"        => "checkbox",
                "heading"     => esc_html__( "Highlight Border", "book-junky" ),
                "param_name"  => "highlighted_border",
                "value"       => array( esc_html__( "Yes, apply highlighted border (Annual theme)", "book-junky" ) => "yes" ),
                "dependency"  => array(
                    "element" => "card_layout",
                    "value"   => array( "pricing" )
                ),
                "description" => esc_html__( "Check this to style the border with the highlighted brand color.", "book-junky" ),
            ),
            array(
                "type"        => "checkbox",
                "heading"     => esc_html__( "Show 'Best Value' Badge", "book-junky" ),
                "param_name"  => "best_value",
                "value"       => array( esc_html__( "Yes, display the Best Value badge", "book-junky" ) => "yes" ),
                "dependency"  => array(
                    "element" => "card_layout",
                    "value"   => array( "pricing" )
                ),
                "description" => esc_html__( "Renders the absolute centered badge ribbon above the card header.", "book-junky" ),
            ),

            // 2. Branding (Conditional on Digital Layout)
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Branding Type", "book-junky" ),
                "param_name"  => "brand_type",
                "value"       => array(
                    esc_html__( "eMathSmart Logo Image", "book-junky" ) => "image",
                    esc_html__( "Styled Text Brand", "book-junky" )      => "text",
                ),
                "std"         => "image",
                "dependency"  => array(
                    "element" => "card_layout",
                    "value"   => array( "digital" )
                ),
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Custom Brand Logo Image", "book-junky" ),
                "param_name"  => "logo_image",
                "dependency"  => array(
                    "element" => "brand_type",
                    "value"   => array( "image" )
                ),
                "description" => esc_html__( "Upload an SVG or image brand logo. Defaults to the pre-packaged eMathSmart logo.", "book-junky" ),
            ),

            // 3. Card Typography & Text Blocks
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Card Title Heading", "book-junky" ),
                "param_name"  => "title",
                "value"       => esc_html__( "Digital Learning Program", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Price Label", "book-junky" ),
                "param_name"  => "price",
                "value"       => esc_html__( "$9.95", "book-junky" ),
                "dependency"  => array(
                    "element" => "card_layout",
                    "value"   => array( "pricing" )
                ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Billing Period", "book-junky" ),
                "param_name"  => "period",
                "value"       => esc_html__( "/month", "book-junky" ),
                "dependency"  => array(
                    "element" => "card_layout",
                    "value"   => array( "pricing" )
                ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Badge text Capsule", "book-junky" ),
                "param_name"  => "badge_text",
                "value"       => esc_html__( "For Grades 3 & 4", "book-junky" ),
                "dependency"  => array(
                    "element" => "card_layout",
                    "value"   => array( "digital", "pricing" )
                ),
            ),
            array(
                "type"        => "textarea",
                "heading"     => esc_html__( "Card Description Content", "book-junky" ),
                "param_name"  => "description",
                "value"       => esc_html__( "No problem! Enjoy all Parents’ Club benefits for free, and upgrade to eMathSmart anytime.", "book-junky" ),
                "dependency"  => array(
                    "element" => "card_layout",
                    "value"   => array( "not_ready" )
                ),
            ),

            // 4. Feature Checklist Repeatable Param Group
            array(
                "type"        => "param_group",
                "heading"     => esc_html__( "Feature Items Checklist", "book-junky" ),
                "param_name"  => "list_items",
                "description" => esc_html__( "Dynamically add, edit, reorder, and delete features checklist items.", "book-junky" ),
                "dependency"  => array(
                    "element" => "card_layout",
                    "value"   => array( "digital", "pricing" )
                ),
                "params"      => array(
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Feature Item Text", "book-junky" ),
                        "param_name"  => "item_text",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Source Type", "book-junky" ),
                        "param_name"  => "icon_source",
                        "value"       => array(
                            esc_html__( "Default Checkmark", "book-junky" )            => "default",
                            esc_html__( "Predefined Brand Outline Icon", "book-junky" ) => "brand",
                            esc_html__( "WPBakery Icon Picker Library", "book-junky" )   => "library",
                            esc_html__( "Custom Image / SVG Upload", "book-junky" )    => "custom",
                        ),
                        "std"         => "default",
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Predefined Brand Outline Icon", "book-junky" ),
                        "param_name"  => "brand_icon_type",
                        "value"       => array(
                            esc_html__( "Gamepad / Plus (Interactive)", "book-junky" )  => "gamepad",
                            esc_html__( "Lightbulb (Instant Feedback)", "book-junky" ) => "idea",
                            esc_html__( "File / Worksheet (Printables)", "book-junky" ) => "file",
                            esc_html__( "Bar Chart (Progress tracking)", "book-junky" ) => "chart",
                            esc_html__( "Star (AI learning tools)", "book-junky" )      => "star",
                            esc_html__( "Shield (Curriculum aligned)", "book-junky" )   => "shield",
                            esc_html__( "Checkmark Outline", "book-junky" )            => "check",
                        ),
                        "std"         => "gamepad",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "brand" )
                        )
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Library", "book-junky" ),
                        "param_name"  => "icon_library",
                        "value"       => array(
                            esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                            esc_html__( "Linecons", "book-junky" )     => "linecons",
                        ),
                        "std"         => "fontawesome",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "library" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Font Awesome Icon Selection", "book-junky" ),
                        "param_name"  => "icon_fontawesome",
                        "value"       => "fa fa-check",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "fontawesome" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Linecons Icon Selection", "book-junky" ),
                        "param_name"  => "icon_linecons",
                        "value"       => "vc_li vc_li-check",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "type"         => "linecons",
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "linecons" )
                        )
                    ),
                    array(
                        "type"        => "attach_image",
                        "heading"     => esc_html__( "Custom Icon SVG/Image", "book-junky" ),
                        "param_name"  => "custom_icon",
                        "description" => esc_html__( "Upload an SVG file or pixel graphic as checklist icon.", "book-junky" ),
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "custom" )
                        )
                    ),
                ),
            ),

            // 5. Footer Content (Conditional on Card Layout & Admin Preference)
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Footer Style", "book-junky" ),
                "param_name"  => "footer_type",
                "value"       => array(
                    esc_html__( "None / Empty Footer", "book-junky" ) => "none",
                    esc_html__( "CTA Button", "book-junky" )          => "button",
                    esc_html__( "Graphic Illustration", "book-junky" ) => "graphic",
                ),
                "std"         => "button",
                "description" => esc_html__( "Select what is displayed inside the card footer bottom.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Button Label", "book-junky" ),
                "param_name"  => "button_text",
                "value"       => esc_html__( "Start 7-Day Free Trial", "book-junky" ),
                "dependency"  => array(
                    "element" => "footer_type",
                    "value"   => array( "button" )
                ),
            ),
            array(
                "type"        => "vc_link",
                "heading"     => esc_html__( "Button Target Action", "book-junky" ),
                "param_name"  => "button_link",
                "dependency"  => array(
                    "element" => "footer_type",
                    "value"   => array( "button" )
                ),
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Footer Graphic Illustration", "book-junky" ),
                "param_name"  => "footer_image",
                "dependency"  => array(
                    "element" => "footer_type",
                    "value"   => array( "graphic" )
                ),
                "description" => esc_html__( "Upload custom illustration graphic. Defaults to high-fidelity theme templates graphics.", "book-junky" ),
            ),
        )
    ) );

    // Register [emathsmart_subscription_product_card] Dynamic WooCommerce Element
    vc_map( array(
        "name"        => esc_html__( "eMathSmart Subscription Product Card", "book-junky" ),
        "base"        => "emathsmart_subscription_product_card",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Dynamic subscription product pricing card matching the premium Pricing Plan layout.", "book-junky" ),
        "params"      => array(
            // WooCommerce product selection
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Select Subscription Product/Variation", "book-junky" ),
                "param_name"  => "product_id",
                "value"       => idl_loader_get_subscription_products(),
                "description" => esc_html__( "Select the WooCommerce subscription product or specific variation to load dynamically.", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "OR Enter Custom Product/Variation ID", "book-junky" ),
                "param_name"  => "product_id_override",
                "description" => esc_html__( "Optional. If your subscription is not in the dropdown, manually type the WooCommerce Product/Variation ID here. This takes precedence over the dropdown selection.", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "checkbox",
                "heading"     => esc_html__( "Highlight Border", "book-junky" ),
                "param_name"  => "highlighted_border",
                "value"       => array( esc_html__( "Yes, apply highlighted border (Annual theme)", "book-junky" ) => "yes" ),
                "description" => esc_html__( "Check this to style the border with the highlighted brand color.", "book-junky" ),
            ),
            array(
                "type"        => "checkbox",
                "heading"     => esc_html__( "Show 'Best Value' Badge", "book-junky" ),
                "param_name"  => "best_value",
                "value"       => array( esc_html__( "Yes, display the Best Value badge", "book-junky" ) => "yes" ),
                "description" => esc_html__( "Renders the absolute centered badge ribbon above the card header.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Card Title Override", "book-junky" ),
                "param_name"  => "title",
                "description" => esc_html__( "Optional. Leave blank to load the WooCommerce product title dynamically.", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Price Override", "book-junky" ),
                "param_name"  => "price",
                "description" => esc_html__( "Optional. Leave blank to load WooCommerce product price dynamically (e.g. $9.95).", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Billing Period Override", "book-junky" ),
                "param_name"  => "period",
                "description" => esc_html__( "Optional. Leave blank to load WooCommerce product billing period dynamically (e.g. /month).", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Badge text Override", "book-junky" ),
                "param_name"  => "badge_text",
                "description" => esc_html__( "Optional. Leave blank to automatically show free trial duration (e.g. 7-Day Free Trial or 14-Day Free Trial based on user eligibility).", "book-junky" ),
            ),
            // Repeatable Checklist
            array(
                "type"        => "param_group",
                "heading"     => esc_html__( "Feature Items Checklist", "book-junky" ),
                "param_name"  => "list_items",
                "description" => esc_html__( "Dynamically add, edit, reorder, and delete features checklist items.", "book-junky" ),
                "params"      => array(
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Feature Item Text", "book-junky" ),
                        "param_name"  => "item_text",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Source Type", "book-junky" ),
                        "param_name"  => "icon_source",
                        "value"       => array(
                            esc_html__( "Default Checkmark", "book-junky" )            => "default",
                            esc_html__( "Predefined Brand Outline Icon", "book-junky" ) => "brand",
                            esc_html__( "WPBakery Icon Picker Library", "book-junky" )   => "library",
                            esc_html__( "Custom Image / SVG Upload", "book-junky" )    => "custom",
                        ),
                        "std"         => "default",
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Predefined Brand Outline Icon", "book-junky" ),
                        "param_name"  => "brand_icon_type",
                        "value"       => array(
                            esc_html__( "Gamepad / Plus (Interactive)", "book-junky" )  => "gamepad",
                            esc_html__( "Lightbulb (Instant Feedback)", "book-junky" ) => "idea",
                            esc_html__( "File / Worksheet (Printables)", "book-junky" ) => "file",
                            esc_html__( "Bar Chart (Progress tracking)", "book-junky" ) => "chart",
                            esc_html__( "Star (AI learning tools)", "book-junky" )      => "star",
                            esc_html__( "Shield (Curriculum aligned)", "book-junky" )   => "shield",
                            esc_html__( "Checkmark Outline", "book-junky" )            => "check",
                        ),
                        "std"         => "gamepad",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "brand" )
                        )
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Library", "book-junky" ),
                        "param_name"  => "icon_library",
                        "value"       => array(
                            esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                            esc_html__( "Linecons", "book-junky" )     => "linecons",
                        ),
                        "std"         => "fontawesome",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "library" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Font Awesome Icon Selection", "book-junky" ),
                        "param_name"  => "icon_fontawesome",
                        "value"       => "fa fa-check",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "fontawesome" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Linecons Icon Selection", "book-junky" ),
                        "param_name"  => "icon_linecons",
                        "value"       => "vc_li vc_li-check",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "type"         => "linecons",
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "linecons" )
                        )
                    ),
                    array(
                        "type"        => "attach_image",
                        "heading"     => esc_html__( "Custom Icon SVG/Image", "book-junky" ),
                        "param_name"  => "custom_icon",
                        "description" => esc_html__( "Upload an SVG file or pixel graphic as checklist icon.", "book-junky" ),
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "custom" )
                        )
                    ),
                ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Button Label Override", "book-junky" ),
                "param_name"  => "button_text",
                "description" => esc_html__( "Optional. Leave blank to automatically show 'Start [TRIAL]-Day Free Trial' based on trial days.", "book-junky" ),
            ),
            array(
                "type"        => "vc_link",
                "heading"     => esc_html__( "Button Custom Link Override", "book-junky" ),
                "param_name"  => "button_link",
                "description" => esc_html__( "Optional. Leave blank to automatically route user to empty-cart redirect checkout: /subscription/?add-to-cart-login=PRODUCT_ID", "book-junky" ),
            ),
            array(
                "type"        => "checkbox",
                "heading"     => esc_html__( "Show to Active Subscribers", "book-junky" ),
                "param_name"  => "show_to_subscribers",
                "value"       => array( esc_html__( "Yes, display this card even to logged-in users with an active subscription", "book-junky" ) => "yes" ),
                "description" => esc_html__( "Check this box to bypass the default visibility constraint that hides pricing/trial cards from existing subscribers.", "book-junky" ),
            ),
        )
    ) );

    // Register [parents_club_need_help] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Need Help", "book-junky" ),
        "base"        => "parents_club_need_help",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Need Help contact panel with custom modular icons and list items.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Panel Title", "book-junky" ),
                "param_name"  => "title",
                "value"       => esc_html__( "Need Help?", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Panel Subtitle", "book-junky" ),
                "param_name"  => "subtitle",
                "value"       => esc_html__( "Our team is here to support you!", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Panel Top Illustration", "book-junky" ),
                "param_name"  => "illustration",
                "description" => esc_html__( "Select an image for the top illustration. Defaults to books-pencils.png if left blank.", "book-junky" ),
            ),
            // Repeatable Contacts param_group
            array(
                "type"        => "param_group",
                "heading"     => esc_html__( "Contacts List", "book-junky" ),
                "param_name"  => "contacts",
                "description" => esc_html__( "Dynamically add, edit, reorder, and delete contact rows.", "book-junky" ),
                "value"       => urlencode( json_encode( array(
                    array(
                        'label'           => 'Email Us',
                        'value'           => 'e-info@popularworld.com',
                        'link'            => 'mailto:e-info@popularworld.com',
                        'icon_source'     => 'brand',
                        'brand_icon_type' => 'email',
                    ),
                    array(
                        'label'           => 'Call Us',
                        'value'           => '905-731-9827',
                        'link'            => 'tel:9057319827',
                        'icon_source'     => 'brand',
                        'brand_icon_type' => 'phone',
                    ),
                    array(
                        'label'           => '',
                        'value'           => 'Mon–Fri: 9am – 4:30pm EST',
                        'link'            => '',
                        'icon_source'     => 'brand',
                        'brand_icon_type' => 'clock',
                    ),
                ) ) ),
                "params"      => array(
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Contact Label", "book-junky" ),
                        "param_name"  => "label",
                        "admin_label" => true,
                        "description" => esc_html__( "e.g. Email Us or Call Us (can be left blank for hours).", "book-junky" ),
                    ),
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Contact Value", "book-junky" ),
                        "param_name"  => "value",
                        "admin_label" => true,
                        "description" => esc_html__( "e.g. e-info@popularworld.com or Mon-Fri: 9am - 4:30pm EST.", "book-junky" ),
                    ),
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Contact Link / Action", "book-junky" ),
                        "param_name"  => "link",
                        "description" => esc_html__( "Optional URL or action link (e.g. mailto:e-info@popularworld.com or tel:9057319827).", "book-junky" ),
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Source Type", "book-junky" ),
                        "param_name"  => "icon_source",
                        "value"       => array(
                            esc_html__( "Predefined Brand Outline SVG", "book-junky" ) => "brand",
                            esc_html__( "WPBakery Icon Picker Library", "book-junky" )  => "library",
                            esc_html__( "Custom Image / SVG Upload", "book-junky" )   => "custom",
                        ),
                        "std"         => "brand",
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Predefined Brand Outline SVG", "book-junky" ),
                        "param_name"  => "brand_icon_type",
                        "value"       => array(
                            esc_html__( "Email Icon", "book-junky" ) => "email",
                            esc_html__( "Phone Icon", "book-junky" ) => "phone",
                            esc_html__( "Clock / Hours Icon", "book-junky" ) => "clock",
                        ),
                        "std"         => "email",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "brand" )
                        )
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Library", "book-junky" ),
                        "param_name"  => "icon_library",
                        "value"       => array(
                            esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                            esc_html__( "Linecons", "book-junky" )     => "linecons",
                        ),
                        "std"         => "fontawesome",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "library" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Font Awesome Icon Selection", "book-junky" ),
                        "param_name"  => "icon_fontawesome",
                        "value"       => "fa fa-envelope-o",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "fontawesome" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Linecons Icon Selection", "book-junky" ),
                        "param_name"  => "icon_linecons",
                        "value"       => "vc_li vc_li-mail",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "type"         => "linecons",
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "linecons" )
                        )
                    ),
                    array(
                        "type"        => "attach_image",
                        "heading"     => esc_html__( "Custom Icon SVG/Image", "book-junky" ),
                        "param_name"  => "custom_icon",
                        "description" => esc_html__( "Upload an SVG file or image to use as custom contact icon.", "book-junky" ),
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "custom" )
                        )
                    ),
                )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => esc_html__( "Card Background Colour", "book-junky" ),
                "param_name"  => "bg_color",
                "value"       => "#fdf9fb",
                "description" => esc_html__( "Fill colour of the contained Need Help card. Default is #fdf9fb.", "book-junky" ),
                "group"       => esc_html__( "Appearance", "book-junky" ),
            ),
        )
    ) );

    // Register [parents_club_cta_banner] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Support CTA Banner", "book-junky" ),
        "base"        => "parents_club_cta_banner",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Full-width promotional CTA banner with mother and child image, text, and two action buttons.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Left Banner Image", "book-junky" ),
                "param_name"  => "banner_image",
                "description" => esc_html__( "Select an image for the left side of the banner. Defaults to parents-club-cta-tablet.png if left blank.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Banner Title", "book-junky" ),
                "param_name"  => "title",
                "value"       => esc_html__( "Ready to Support Your Child's Success?", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "textarea",
                "heading"     => esc_html__( "Banner Description", "book-junky" ),
                "param_name"  => "description",
                "value"       => "Join 40,000+ Canadian parents and get trusted resources,\nworkbook savings, and learning support — for free!",
                "description" => esc_html__( "Enter the text description.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Button 1 Label (White)", "book-junky" ),
                "param_name"  => "btn1_text",
                "value"       => esc_html__( "Join Parents' Club – It's Free!", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "vc_link",
                "heading"     => esc_html__( "Button 1 Link (White)", "book-junky" ),
                "param_name"  => "btn1_link",
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Button 2 Label (Orange)", "book-junky" ),
                "param_name"  => "btn2_text",
                "value"       => esc_html__( "Explore eMathSmart", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "vc_link",
                "heading"     => esc_html__( "Button 2 Link (Orange)", "book-junky" ),
                "param_name"  => "btn2_link",
            ),
        )
    ) );

    // Register [parents_club_member_welcome] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Member Welcome", "book-junky" ),
        "base"        => "parents_club_member_welcome",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Welcome card showing logged-in user name, subtitle, custom background, and checklist attributes.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Title Template", "book-junky" ),
                "param_name"  => "title_template",
                "value"       => "Hi, {user_name}! [wave]\nWelcome back!",
                "description" => esc_html__( "Use {user_name} to insert the user's name, and {wave} or [wave] for the waving hand emoji.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Subtitle", "book-junky" ),
                "param_name"  => "subtitle",
                "value"       => esc_html__( "We’re here to support your child’s learning journey.", "book-junky" ),
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Background Image", "book-junky" ),
                "param_name"  => "bg_image",
                "description" => esc_html__( "Upload/select a custom background image override. If left blank, the default ParentClub2.jpg is loaded.", "book-junky" ),
            ),
            array(
                "type"        => "param_group",
                "heading"     => esc_html__( "Checklist Attributes", "book-junky" ),
                "param_name"  => "attributes",
                "description" => esc_html__( "Add, remove, and reorder welcome attributes visually.", "book-junky" ),
                "value"       => urlencode( json_encode( array(
                    array(
                        'bold_text'       => '40,000+',
                        'subtext'         => 'Canadian Parents',
                        'icon_source'     => 'brand',
                        'brand_icon_type' => 'users',
                    ),
                    array(
                        'bold_text'       => 'Curriculum-',
                        'subtext'         => 'Aligned',
                        'icon_source'     => 'brand',
                        'brand_icon_type' => 'maple_star',
                    ),
                    array(
                        'bold_text'       => 'Trusted',
                        'subtext'         => 'Since 1994',
                        'icon_source'     => 'brand',
                        'brand_icon_type' => 'shield',
                    ),
                ) ) ),
                "params"      => array(
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Bold Text", "book-junky" ),
                        "param_name"  => "bold_text",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Subtext", "book-junky" ),
                        "param_name"  => "subtext",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Source", "book-junky" ),
                        "param_name"  => "icon_source",
                        "value"       => array(
                            esc_html__( "Predefined Brand Outline", "book-junky" )  => "brand",
                            esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                            esc_html__( "Custom Image Upload", "book-junky" )        => "custom",
                        ),
                        "std"         => "brand",
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Predefined Brand Outline Icon", "book-junky" ),
                        "param_name"  => "brand_icon_type",
                        "value"       => array(
                            esc_html__( "Users Icon", "book-junky" )                => "users",
                            esc_html__( "Curriculum Star (Maple)", "book-junky" )    => "maple_star",
                            esc_html__( "Shield Icon", "book-junky" )               => "shield",
                            esc_html__( "Tag Icon (Price Tag)", "book-junky" )      => "tag",
                            esc_html__( "parenting Tips (Lightbulb)", "book-junky" ) => "idea",
                            esc_html__( "Offers (Gift Box)", "book-junky" )         => "gift",
                            esc_html__( "Worksheets (File)", "book-junky" )         => "file",
                            esc_html__( "eMathSmart portal (Computer)", "book-junky" ) => "computer",
                        ),
                        "std"         => "users",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "brand" )
                        )
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Library", "book-junky" ),
                        "param_name"  => "icon_library",
                        "value"       => array(
                            esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                            esc_html__( "Linecons", "book-junky" )     => "linecons",
                        ),
                        "std"         => "fontawesome",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "library" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Font Awesome Icon", "book-junky" ),
                        "param_name"  => "icon_fontawesome",
                        "value"       => "fa fa-user",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "fontawesome" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Linecons Icon", "book-junky" ),
                        "param_name"  => "icon_linecons",
                        "value"       => "vc_li vc_li-user",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "type"         => "linecons",
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "linecons" )
                        )
                    ),
                    array(
                        "type"        => "attach_image",
                        "heading"     => esc_html__( "Custom Icon Image", "book-junky" ),
                        "param_name"  => "custom_icon",
                        "description" => esc_html__( "Upload an SVG or image to use as a custom icon.", "book-junky" ),
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "custom" )
                        )
                    ),
                )
            ),
        )
    ) );

    // Register [parents_club_member_quick_links] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Member Quick Links", "book-junky" ),
        "base"        => "parents_club_member_quick_links",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Quick links horizontal bar section showing title and a list of customizable quick link buttons.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Section Title", "book-junky" ),
                "param_name"  => "title",
                "value"       => esc_html__( "Quick Links", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "param_group",
                "heading"     => esc_html__( "Quick Link Buttons", "book-junky" ),
                "param_name"  => "buttons",
                "description" => esc_html__( "Add, remove, and reorder quick link buttons visually.", "book-junky" ),
                "value"       => urlencode( json_encode( array(
                    array(
                        'btn_text'    => 'Our Canadian Teachers',
                        'btn_link'    => '/nominate-a-teacher',
                        'icon_source' => 'default',
                    ),
                    array(
                        'btn_text'    => 'Learning Tips',
                        'btn_link'    => '/blog',
                        'icon_source' => 'default',
                    ),
                    array(
                        'btn_text'    => 'Free Worksheets',
                        'btn_link'    => '/download-centre',
                        'icon_source' => 'default',
                    ),
                ) ) ),
                "params"      => array(
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Button Text", "book-junky" ),
                        "param_name"  => "btn_text",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "vc_link",
                        "heading"     => esc_html__( "Button Link", "book-junky" ),
                        "param_name"  => "btn_link",
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Source", "book-junky" ),
                        "param_name"  => "icon_source",
                        "value"       => array(
                            esc_html__( "Predefined Chevron Right SVG", "book-junky" ) => "default",
                            esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                            esc_html__( "Custom Image/SVG Upload", "book-junky" )      => "custom",
                            esc_html__( "No Icon", "book-junky" )                      => "none",
                        ),
                        "std"         => "default",
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Library", "book-junky" ),
                        "param_name"  => "icon_library",
                        "value"       => array(
                            esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                            esc_html__( "Linecons", "book-junky" )     => "linecons",
                        ),
                        "std"         => "fontawesome",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "library" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Font Awesome Icon", "book-junky" ),
                        "param_name"  => "icon_fontawesome",
                        "value"       => "fa fa-angle-right",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "fontawesome" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Linecons Icon", "book-junky" ),
                        "param_name"  => "icon_linecons",
                        "value"       => "vc_li vc_li-chevron-right",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "type"         => "linecons",
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "linecons" )
                        )
                    ),
                    array(
                        "type"        => "attach_image",
                        "heading"     => esc_html__( "Custom Icon Image", "book-junky" ),
                        "param_name"  => "custom_icon",
                        "description" => esc_html__( "Upload an SVG or image to use as a custom icon inside the button.", "book-junky" ),
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "custom" )
                        )
                    ),
                )
            ),
        )
    ) );

    // Register [parents_club_member_subscription] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Member Subscription Card (Active Subscribers Only)", "book-junky" ),
        "base"        => "parents_club_member_subscription",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Active Subscribers Only. Subscription card showing logo, active status pill, portal button, details list, what's included list, and bottom actions.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Custom Logo Image", "book-junky" ),
                "param_name"  => "logo_image",
                "description" => esc_html__( "Select a custom logo image. Defaults to eMathSmart_logo_FINAL .png if left blank.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Portal Button Text", "book-junky" ),
                "param_name"  => "portal_btn_text",
                "value"       => esc_html__( "Go to eMathSmart", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "vc_link",
                "heading"     => esc_html__( "Portal Button Link", "book-junky" ),
                "param_name"  => "portal_btn_link",
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Card Title", "book-junky" ),
                "param_name"  => "title",
                "value"       => esc_html__( "Your eMathSmart Subscription", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Tablet Illustration Image", "book-junky" ),
                "param_name"  => "tablet_image",
                "description" => esc_html__( "Select a tablet illustration image. Defaults to subscription-active.png if left blank.", "book-junky" ),
            ),
            // Repeatable Included Checklist
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "What's Included Header", "book-junky" ),
                "param_name"  => "included_title",
                "value"       => esc_html__( "What's Included", "book-junky" ),
            ),
            array(
                "type"        => "param_group",
                "heading"     => esc_html__( "What's Included Items", "book-junky" ),
                "param_name"  => "included_list",
                "description" => esc_html__( "List the features or items included in this plan.", "book-junky" ),
                "value"       => urlencode( json_encode( array(
                    array( 'item_text' => 'Full access to eMathSmart' ),
                    array( 'item_text' => 'Interactive exercises & tools' ),
                    array( 'item_text' => 'Printable worksheets' ),
                    array( 'item_text' => 'Progress tracking & reports' ),
                    array( 'item_text' => 'AI credits included every year' ),
                ) ) ),
                "params"      => array(
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Item Text", "book-junky" ),
                        "param_name"  => "item_text",
                        "admin_label" => true,
                    )
                )
            ),

        )
    ) );

    // Register [parents_club_member_account_overview] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Account Overview Panel (Active Subscribers Only)", "book-junky" ),
        "base"        => "parents_club_member_account_overview",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Active Subscribers Only. Account overview section showing dynamic grids of student management, progress reports, and settings.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Section Title", "book-junky" ),
                "param_name"  => "title",
                "value"       => esc_html__( "Account Overview", "book-junky" ),
                "admin_label" => true,
            ),
            // Repeatable items grid
            array(
                "type"        => "param_group",
                "heading"     => esc_html__( "Overview Items", "book-junky" ),
                "param_name"  => "items_list",
                "description" => esc_html__( "Configure each overview panel card.", "book-junky" ),
                "value"       => urlencode( json_encode( array(
                    array(
                        'item_title'           => 'Students',
                        'desc_line1'           => '1 Student',
                        'desc_line2'           => 'Grade 3',
                        'action_text'          => 'Manage Students',
                        'action_link'          => '#students',
                        'icon_source'          => 'brand',
                        'predefined_icon_type' => 'graduation',
                    ),
                    array(
                        'item_title'           => 'Progress Reports',
                        'desc_line1'           => "View your child's",
                        'desc_line2'           => 'learning progress',
                        'action_text'          => 'View Reports',
                        'action_link'          => '#reports',
                        'icon_source'          => 'brand',
                        'predefined_icon_type' => 'chart',
                    ),
                    array(
                        'item_title'           => 'Account Settings',
                        'desc_line1'           => 'Update your profile,',
                        'desc_line2'           => 'password & preferences',
                        'action_text'          => 'Manage Settings',
                        'action_link'          => '#settings',
                        'icon_source'          => 'brand',
                        'predefined_icon_type' => 'gear',
                    ),
                ) ) ),
                "params"      => array(
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Item Title", "book-junky" ),
                        "param_name"  => "item_title",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Description Line 1", "book-junky" ),
                        "param_name"  => "desc_line1",
                    ),
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Description Line 2", "book-junky" ),
                        "param_name"  => "desc_line2",
                    ),
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Action Link Text", "book-junky" ),
                        "param_name"  => "action_text",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "vc_link",
                        "heading"     => esc_html__( "Action Link URL", "book-junky" ),
                        "param_name"  => "action_link",
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Source", "book-junky" ),
                        "param_name"  => "icon_source",
                        "value"       => array(
                            esc_html__( "Predefined Brand SVG", "book-junky" )       => "brand",
                            esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                            esc_html__( "Custom Image Upload", "book-junky" )         => "custom",
                        ),
                        "std"         => "brand",
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Predefined Icon", "book-junky" ),
                        "param_name"  => "predefined_icon_type",
                        "value"       => array(
                            esc_html__( "Graduation Cap", "book-junky" )  => "graduation",
                            esc_html__( "Progress Chart", "book-junky" )   => "chart",
                            esc_html__( "Gear / Settings", "book-junky" )  => "gear",
                        ),
                        "std"         => "graduation",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "brand" )
                        )
                    ),
                    array(
                        "type"        => "dropdown",
                        "heading"     => esc_html__( "Icon Library", "book-junky" ),
                        "param_name"  => "icon_library",
                        "value"       => array(
                            esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                            esc_html__( "Linecons", "book-junky" )     => "linecons",
                        ),
                        "std"         => "fontawesome",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "library" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Font Awesome Icon", "book-junky" ),
                        "param_name"  => "icon_fontawesome",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "fontawesome" )
                        )
                    ),
                    array(
                        "type"        => "iconpicker",
                        "heading"     => esc_html__( "Linecons Icon", "book-junky" ),
                        "param_name"  => "icon_linecons",
                        "settings"    => array(
                            "emptyIcon"    => false,
                            "type"         => "linecons",
                            "iconsPerPage" => 4000,
                        ),
                        "dependency"  => array(
                            "element" => "icon_library",
                            "value"   => array( "linecons" )
                        )
                    ),
                    array(
                        "type"        => "attach_image",
                        "heading"     => esc_html__( "Custom Icon", "book-junky" ),
                        "param_name"  => "custom_icon",
                        "dependency"  => array(
                            "element" => "icon_source",
                            "value"   => array( "custom" )
                        )
                    ),
                )
            ),
        )
    ) );

    // Register [parents_club_member_coins] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club AI Coins Card (Active Subscribers Only)", "book-junky" ),
        "base"        => "parents_club_member_coins",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Active Subscribers Only. AI Coins balance card showing parent's coins and coin package purchase grids.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Card Title", "book-junky" ),
                "param_name"  => "title",
                "value"       => esc_html__( "AI Coins Balance", "book-junky" ),
                "admin_label" => true,
            ),

            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Coin Image", "book-junky" ),
                "param_name"  => "coin_image",
                "description" => esc_html__( "Upload or select coin visual icon.", "book-junky" ),
            ),
            array(
                "type"        => "textarea",
                "heading"     => esc_html__( "Card Description", "book-junky" ),
                "param_name"  => "description",
                "value"       => esc_html__( "Use AI coins to chat with your AI helper, and mark worksheets.", "book-junky" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Purchase Header", "book-junky" ),
                "param_name"  => "purchase_title",
                "value"       => esc_html__( "Purchase AI Coins", "book-junky" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "AI Coins Product Source", "book-junky" ),
                "param_name"  => "product_id",
                "value"       => idl_loader_get_emathsmart_coins_products(),
                "description" => esc_html__( "Select the variable AI Coins product from the eMathSmart category. The card will dynamically render its variations.", "book-junky" ),
                "admin_label" => true,
            ),
            // Repeatable packages grid
            array(
                "type"        => "param_group",
                "heading"     => esc_html__( "Coin Packages", "book-junky" ),
                "param_name"  => "packages_list",
                "description" => esc_html__( "Configure coin purchase options.", "book-junky" ),
                "value"       => urlencode( json_encode( array(
                    array(
                        'package_qty'   => '100 Coins',
                        'package_price' => '$5.99',
                        'package_link'  => '',
                    ),
                    array(
                        'package_qty'   => '250 Coins',
                        'package_price' => '$9.99',
                        'package_link'  => '',
                    ),
                ) ) ),
                "params"      => array(
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Quantity (e.g. 100 Coins)", "book-junky" ),
                        "param_name"  => "package_qty",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "textfield",
                        "heading"     => esc_html__( "Price (e.g. $5.99)", "book-junky" ),
                        "param_name"  => "package_price",
                        "admin_label" => true,
                    ),
                    array(
                        "type"        => "vc_link",
                        "heading"     => esc_html__( "Buy Button Link", "book-junky" ),
                        "param_name"  => "package_link",
                        "description" => esc_html__( "Link to checkout or product page variation.", "book-junky" ),
                    ),
                )
            ),
        )
    ) );

    // Register [parents_club_member_billing] Element
    vc_map( array(
        "name"        => esc_html__( "Parents Club Billing History Card (Active Subscribers Only)", "book-junky" ),
        "base"        => "parents_club_member_billing",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Active Subscribers Only. Billing history card showing past payments, details, and download options.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Card Title", "book-junky" ),
                "param_name"  => "title",
                "value"       => esc_html__( "Billing History", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "View All Text", "book-junky" ),
                "param_name"  => "view_all_text",
                "value"       => esc_html__( "View all", "book-junky" ),
            ),
            array(
                "type"        => "vc_link",
                "heading"     => esc_html__( "View All Link", "book-junky" ),
                "param_name"  => "view_all_link",
            ),

            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Download Button Text", "book-junky" ),
                "param_name"  => "download_btn_text",
                "value"       => esc_html__( "DOWNLOAD RECEIPT HISTORY", "book-junky" ),
                "admin_label" => true,
            ),
            array(
                "type"        => "vc_link",
                "heading"     => esc_html__( "Download Button Link", "book-junky" ),
                "param_name"  => "download_btn_link",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Icon Source", "book-junky" ),
                "param_name"  => "icon_source",
                "value"       => array(
                    esc_html__( "Predefined Brand SVG", "book-junky" )       => "brand",
                    esc_html__( "WPBakery Icon Picker Library", "book-junky" ) => "library",
                    esc_html__( "Custom Image Upload", "book-junky" )         => "custom",
                    esc_html__( "No Icon", "book-junky" )                     => "none",
                ),
                "std"         => "brand",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Predefined Icon", "book-junky" ),
                "param_name"  => "predefined_icon_type",
                "value"       => array(
                    esc_html__( "Download Arrow", "book-junky" ) => "download",
                ),
                "std"         => "download",
                "dependency"  => array(
                    "element" => "icon_source",
                    "value"   => array( "brand" )
                )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Icon Library", "book-junky" ),
                "param_name"  => "icon_library",
                "value"       => array(
                    esc_html__( "Font Awesome", "book-junky" ) => "fontawesome",
                    esc_html__( "Linecons", "book-junky" )     => "linecons",
                ),
                "std"         => "fontawesome",
                "dependency"  => array(
                    "element" => "icon_source",
                    "value"   => array( "library" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Font Awesome Icon", "book-junky" ),
                "param_name"  => "icon_fontawesome",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "icon_library",
                    "value"   => array( "fontawesome" )
                )
            ),
            array(
                "type"        => "iconpicker",
                "heading"     => esc_html__( "Linecons Icon", "book-junky" ),
                "param_name"  => "icon_linecons",
                "settings"    => array(
                    "emptyIcon"    => false,
                    "type"         => "linecons",
                    "iconsPerPage" => 4000,
                ),
                "dependency"  => array(
                    "element" => "icon_library",
                    "value"   => array( "linecons" )
                )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => esc_html__( "Custom Icon", "book-junky" ),
                "param_name"  => "custom_icon",
                "dependency"  => array(
                    "element" => "icon_source",
                    "value"   => array( "custom" )
                )
            ),
        )
    ) );

    // Register [parents_club_user_registration_form] Element
    vc_map( array(
        "name"        => esc_html__( "User Registration Form", "book-junky" ),
        "base"        => "parents_club_user_registration_form",
        "icon"        => "cs_icon_for_vc",
        "category"    => esc_html__( "eMathSmart Elements", "book-junky" ),
        "description" => esc_html__( "Embeds a custom styled User Registration form with Login Switcher.", "book-junky" ),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Form Title", "book-junky" ),
                "param_name"  => "form_title",
                "admin_label" => true,
            ),
            array(
                "type"        => "textarea",
                "heading"     => esc_html__( "Form Description", "book-junky" ),
                "param_name"  => "form_description",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Select Form", "book-junky" ),
                "param_name"  => "form_id",
                "value"       => idl_loader_get_user_registration_forms(),
                "admin_label" => true,
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Login Form Title", "book-junky" ),
                "param_name"  => "login_title",
                "value"       => "Welcome Back",
            ),
            array(
                "type"        => "textarea",
                "heading"     => esc_html__( "Login Form Description", "book-junky" ),
                "param_name"  => "login_description",
                "value"       => "Login to your Parents Club account",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Default View", "book-junky" ),
                "param_name"  => "default_view",
                "value"       => array(
                    esc_html__( "Registration Form", "book-junky" ) => "register",
                    esc_html__( "Login Form", "book-junky" )        => "login",
                ),
                "std"         => "register",
            ),
            array(
                "type"        => "dropdown",
                "heading"     => esc_html__( "Enable Switcher Toggle", "book-junky" ),
                "param_name"  => "enable_toggle",
                "value"       => array(
                    esc_html__( "Yes", "book-junky" ) => "yes",
                    esc_html__( "No", "book-junky" )  => "no",
                ),
                "std"         => "yes",
            ),
            array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Footer Text", "book-junky" ),
                "param_name"  => "footer_text",
                "value"       => "Already a member?",
            ),
            array(
                "type"        => "vc_link",
                "heading"     => esc_html__( "Footer Link", "book-junky" ),
                "param_name"  => "footer_link",
            ),
        )
    ) );

    // Add Visibility Parameter to all custom Parents Club elements
    if ( function_exists( 'vc_add_param' ) ) {
        $custom_tags = array(
            'parents_club_hero',
            'parents_club_hero_intro',
            'parents_club_benefits_glance',
            'parents_club_why_join',
            'parents_club_how_works',
            'esmart_login_card',
            'emathsmart_plan_card',
            'parents_club_member_perks',
            'parents_club_need_help',
            'emathsmart_subscription_product_card',
            'parents_club_cta_banner',
            'parents_club_member_welcome',
            'parents_club_member_quick_links',
            'parents_club_member_subscription',
            'parents_club_member_account_overview',
            'parents_club_member_coins',
            'parents_club_member_billing',
            'parents_club_user_registration_form'
        );
        $visibility_param = idl_loader_get_visibility_param();
        foreach ( $custom_tags as $tag ) {
            vc_add_param( $tag, $visibility_param );
        }
    }
}


// -----------------------------------------------------------------------------
// SECTION 2: Shortcode Renderers
// -----------------------------------------------------------------------------

add_shortcode( 'parents_club_hero', 'idl_loader_parents_club_hero_shortcode' );

function idl_loader_parents_club_hero_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'title'     => 'Welcome to the Parents Club',
        'subtitle'  => 'Unlock active personalized tutor portal links, custom student worksheets, educational video guides, and premium resources.',
        'cta_text'  => 'Get Started',
        'cta_link'  => '#parents-club-login',
        'bg_image'  => '',
    ), $atts );

    $title     = esc_html( $attributes['title'] );
    $subtitle  = esc_html( $attributes['subtitle'] );
    $cta_text  = esc_html( $attributes['cta_text'] );
    $cta_link  = esc_attr( $attributes['cta_link'] );
    $bg_image  = $attributes['bg_image'];

    // Resolve dynamic background image if ID is supplied
    $bg_url = '';
    if ( ! empty( $bg_image ) && is_numeric( $bg_image ) ) {
        $img_src = wp_get_attachment_image_src( $bg_image, 'full' );
        if ( $img_src ) {
            $bg_url = esc_url( $img_src[0] );
        }
    } elseif ( ! empty( $bg_image ) ) {
        $bg_url = esc_url( $bg_image );
    }

    // Dynamic unique ID to scope css variables
    $wrapper_id = 'pc-hero-' . uniqid();

    ob_start();
    ?>
    <!-- Premium Google Fonts Loader -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        #<?php echo $wrapper_id; ?> {
            --title-font: 'Outfit', sans-serif;
            --body-font: 'Inter', sans-serif;
            --hero-bg-color: #fde4ba;
            --hero-bg-gradient: linear-gradient(135deg, #fde4ba 0%, #fedb9b 100%);
            --text-dark: #5a3e2b;
            --text-muted: #8a6f59;
            --btn-orange: #f28538;
            --btn-orange-hover: #e07427;
            --btn-gradient: linear-gradient(135deg, #f59652 0%, #eb731f 100%);
            --btn-gradient-hover: linear-gradient(135deg, #eb731f 0%, #d85c07 100%);
            --border-warm: #ffe9d2;

            background-color: var(--hero-bg-color);
            background-image: var(--hero-bg-gradient);
            <?php if ( ! empty( $bg_url ) ) : ?>
            background-image: 
                linear-gradient(to right, rgba(253, 228, 186, 0.85) 0%, rgba(254, 219, 155, 0.6) 100%), 
                url('<?php echo $bg_url; ?>');
            <?php endif; ?>
            background-size: cover;
            background-position: center;
            border-radius: 28px;
            border: 2px solid var(--border-warm);
            padding: 70px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(90, 62, 43, 0.06);
            margin-bottom: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Ambient subtle vertical lines pattern to align with eMathSmart branding */
        #<?php echo $wrapper_id; ?>::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.16) 1px, transparent 1px);
            background-size: 40px 100%;
            pointer-events: none;
            z-index: 1;
        }

        #<?php echo $wrapper_id; ?> .hero-content-inner {
            position: relative;
            z-index: 2;
            max-width: 820px;
            margin: 0 auto;
        }

        #<?php echo $wrapper_id; ?> .hero-badge {
            display: inline-block;
            padding: 6px 18px;
            background: #ffffff;
            border: 1px solid #ffe2ca;
            border-radius: 100px;
            font-family: var(--title-font);
            font-size: 13px;
            font-weight: 700;
            color: var(--btn-orange);
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 10px rgba(90, 62, 43, 0.03);
        }

        #<?php echo $wrapper_id; ?> h1.hero-title {
            font-family: var(--title-font);
            font-size: 44px;
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-dark);
            margin-top: 0;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        #<?php echo $wrapper_id; ?> p.hero-subtitle {
            font-family: var(--body-font);
            font-size: 17px;
            line-height: 1.6;
            color: var(--text-muted);
            margin-bottom: 30px;
            font-weight: 400;
        }

        #<?php echo $wrapper_id; ?> .hero-action-row {
            margin-top: 10px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        #<?php echo $wrapper_id; ?> a.hero-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--btn-orange);
            background-image: var(--btn-gradient);
            color: #ffffff !important;
            padding: 16px 44px;
            border-radius: 100px;
            font-family: var(--title-font);
            font-size: 17px;
            font-weight: 700;
            text-decoration: none !important;
            box-shadow: 0 6px 20px rgba(242, 133, 55, 0.25);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border: none;
            cursor: pointer;
            z-index: 5;
        }

        #<?php echo $wrapper_id; ?> a.hero-btn:hover {
            background-color: var(--btn-orange-hover);
            background-image: var(--btn-gradient-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(242, 133, 55, 0.35);
        }

        #<?php echo $wrapper_id; ?> a.hero-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 767px) {
            #<?php echo $wrapper_id; ?> {
                padding: 50px 20px;
                border-radius: 20px;
            }
            #<?php echo $wrapper_id; ?> h1.hero-title {
                font-size: 32px;
            }
            #<?php echo $wrapper_id; ?> p.hero-subtitle {
                font-size: 15px;
            }
            #<?php echo $wrapper_id; ?> a.hero-btn {
                padding: 14px 36px;
                font-size: 16px;
                width: 100%;
                max-width: 280px;
            }
        }
    </style>

    <div id="<?php echo $wrapper_id; ?>">
        <div class="hero-content-inner">
            <span class="hero-badge">eMathSmart Parents Club</span>
            <h1 class="hero-title"><?php echo $title; ?></h1>
            <p class="hero-subtitle"><?php echo $subtitle; ?></p>
            <?php if ( ! empty( $cta_text ) ) : ?>
                <div class="hero-action-row">
                    <a href="<?php echo $cta_link; ?>" class="hero-btn"><?php echo $cta_text; ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Register [parents_club_hero_intro] Shortcode
add_shortcode( 'parents_club_hero_intro', 'idl_loader_parents_club_hero_intro_shortcode' );

function idl_loader_parents_club_hero_intro_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'logo_image'       => '',
        'attr1_heading'    => '40,000+',
        'attr1_subheading' => 'Canadian Parents',
        'attr2_heading'    => 'Curriculum-',
        'attr2_subheading' => 'Aligned',
        'attr3_heading'    => 'Trusted',
        'attr3_subheading' => 'Since 1994',
        'btn1_text'        => "Join Parents' Club - It's Free!",
        'btn1_link'        => '#signup',
        'btn2_text'        => 'Member Login',
        'btn2_link'        => '#login',
    ), $atts );

    $logo_image       = $attributes['logo_image'];
    $attr1_heading    = esc_html( $attributes['attr1_heading'] );
    $attr1_subheading = esc_html( $attributes['attr1_subheading'] );
    $attr2_heading    = esc_html( $attributes['attr2_heading'] );
    $attr2_subheading = esc_html( $attributes['attr2_subheading'] );
    $attr3_heading    = esc_html( $attributes['attr3_heading'] );
    $attr3_subheading = esc_html( $attributes['attr3_subheading'] );
    $btn1_text        = esc_html( $attributes['btn1_text'] );
    $btn1_link        = esc_attr( $attributes['btn1_link'] );
    $btn2_text        = esc_html( $attributes['btn2_text'] );
    $btn2_link        = esc_attr( $attributes['btn2_link'] );

    // Enqueue the modular stylesheet natively
    wp_enqueue_style( 'parents-club-hero-brand', plugins_url( 'templates/css/parents-club-hero-brand.css', __FILE__ ) );

    // Resolve Logo URL
    $logo_url = '';
    if ( ! empty( $logo_image ) && is_numeric( $logo_image ) ) {
        $img_src = wp_get_attachment_image_src( $logo_image, 'full' );
        if ( $img_src ) {
            $logo_url = esc_url( $img_src[0] );
        }
    }
    if ( empty( $logo_url ) ) {
        $logo_url = plugins_url( 'templates/images/popularbook-logo-parents-club.png', __FILE__ );
    }

    ob_start();
    ?>
    <div class="brand-column">
        <!-- Parents Club Logo -->
        <div class="brand-logo-wrapper">
            <img src="<?php echo esc_url( $logo_url ); ?>" alt="Popular Book Company Canada Parents Club">
        </div>
        
        <!-- Attribute Checklist Row -->
        <div class="brand-attribute-list">
            <div class="attribute-item">
                <div class="attribute-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="attribute-text">
                    <strong><?php echo $attr1_heading; ?></strong>
                    <span><?php echo $attr1_subheading; ?></span>
                </div>
            </div>
            <div class="attribute-item">
                <div class="attribute-icon">
                    <svg viewBox="0 0 32 32" fill="currentColor">
                        <path d="M16.361,31h-0.72v-6.592l-7.596,0.949c-0.124,0.015-0.245-0.034-0.323-0.129
	c-0.079-0.096-0.103-0.225-0.063-0.343l0.917-2.753l-7.792-5.845c-0.107-0.081-0.161-0.213-0.14-0.346s0.115-0.242,0.242-0.284
	l2.606-0.869L1.678,11.16c-0.063-0.126-0.047-0.277,0.041-0.386s0.23-0.16,0.368-0.124l3.677,0.919l0.895-2.684
	c0.039-0.119,0.137-0.208,0.259-0.237C7.039,8.62,7.167,8.656,7.255,8.744l4.126,4.125L9.649,5.078
	c-0.028-0.126,0.014-0.259,0.11-0.345c0.097-0.087,0.234-0.115,0.355-0.074l2.727,0.909l2.852-4.752c0.13-0.217,0.487-0.217,0.617,0
	l2.852,4.752l2.727-0.909c0.126-0.041,0.259-0.013,0.355,0.074c0.097,0.086,0.139,0.219,0.11,0.345l-1.731,7.793l4.125-4.125
	c0.088-0.088,0.215-0.125,0.338-0.096c0.121,0.029,0.219,0.118,0.259,0.237l0.895,2.684l3.677-0.919
	c0.133-0.033,0.28,0.014,0.368,0.124c0.088,0.109,0.104,0.26,0.041,0.386L28.51,14.79l2.606,0.869
	c0.127,0.042,0.22,0.151,0.241,0.284c0.021,0.133-0.032,0.265-0.14,0.346l-7.792,5.844l0.918,2.754
	c0.039,0.118,0.015,0.247-0.063,0.343c-0.077,0.096-0.192,0.146-0.323,0.129l-7.596-0.949V31z M1.766,16.124l7.451,5.588
	c0.124,0.093,0.175,0.255,0.125,0.402l-0.819,2.458l7.434-0.93c0.03-0.005,0.06-0.003,0.089,0l7.434,0.93l-0.819-2.458
	c-0.049-0.147,0.002-0.31,0.126-0.402l7.45-5.588l-2.349-0.783c-0.099-0.033-0.179-0.107-0.219-0.204
	c-0.039-0.096-0.036-0.206,0.011-0.299l1.65-3.3l-3.241,0.81c-0.185,0.047-0.369-0.058-0.429-0.235l-0.815-2.447l-4.588,4.588
	c-0.113,0.114-0.29,0.138-0.428,0.061c-0.141-0.077-0.213-0.237-0.179-0.394l1.862-8.379l-2.397,0.799
	c-0.161,0.055-0.336-0.011-0.423-0.156L16.001,1.7L13.31,6.185c-0.087,0.145-0.264,0.209-0.422,0.156L10.49,5.542l1.863,8.379
	c0.035,0.156-0.038,0.317-0.178,0.394c-0.141,0.079-0.314,0.052-0.428-0.061L7.158,9.667l-0.816,2.447
	c-0.059,0.179-0.245,0.282-0.429,0.235l-3.241-0.81l1.65,3.3c0.047,0.093,0.051,0.203,0.011,0.299
	c-0.04,0.097-0.12,0.171-0.219,0.204L1.766,16.124z"/>
                    </svg>
                </div>
                <div class="attribute-text">
                    <strong><?php echo $attr2_heading; ?></strong>
                    <span><?php echo $attr2_subheading; ?></span>
                </div>
            </div>
            <div class="attribute-item">
                <div class="attribute-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <div class="attribute-text">
                    <strong><?php echo $attr3_heading; ?></strong>
                    <span><?php echo $attr3_subheading; ?></span>
                </div>
            </div>
        </div>
        
        <!-- Action CTA buttons -->
        <div class="brand-actions">
            <?php if ( ! empty( $btn1_text ) ) : ?>
                <a href="<?php echo $btn1_link; ?>" class="btn-crimson"><?php echo $btn1_text; ?></a>
            <?php endif; ?>
            <?php if ( ! empty( $btn2_text ) ) : ?>
                <a href="<?php echo $btn2_link; ?>" class="btn-outline-crimson">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <?php echo $btn2_text; ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Register [parents_club_benefits_glance] Shortcode
add_shortcode( 'parents_club_benefits_glance', 'idl_loader_parents_club_benefits_glance_shortcode' );

function idl_loader_parents_club_benefits_glance_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'show_banner_image'            => 'yes',
        'banner_image'                 => '',
        'card_title'                   => 'Benefits at a Glance',
        'benefit1_text'                => '50% OFF Complete Canadian Curriculum Series',
        'benefit1_icon_source'         => 'brand',
        'benefit1_brand_icon_type'     => 'tag',
        'benefit1_icon_library'        => 'fontawesome',
        'benefit1_icon_fontawesome'    => 'fa fa-tag',
        'benefit1_icon_linecons'       => 'vc_li vc_li-tag',
        'benefit1_custom_icon'         => '',
        'benefit2_text'                => 'Learning Tips & News Helpful parenting advice',
        'benefit2_icon_source'         => 'brand',
        'benefit2_brand_icon_type'     => 'idea',
        'benefit2_icon_library'        => 'fontawesome',
        'benefit2_icon_fontawesome'    => 'fa fa-lightbulb-o',
        'benefit2_icon_linecons'       => 'vc_li vc_li-bulb',
        'benefit2_custom_icon'         => '',
        'benefit3_text'                => '30% OFF All Other Workbooks',
        'benefit3_icon_source'         => 'brand',
        'benefit3_brand_icon_type'     => 'tag',
        'benefit3_icon_library'        => 'fontawesome',
        'benefit3_icon_fontawesome'    => 'fa fa-tag',
        'benefit3_icon_linecons'       => 'vc_li vc_li-tag',
        'benefit3_custom_icon'         => '',
        'benefit4_text'                => 'Special Promotions & Offers Exclusive member campaigns',
        'benefit4_icon_source'         => 'brand',
        'benefit4_brand_icon_type'     => 'gift',
        'benefit4_icon_library'        => 'fontawesome',
        'benefit4_icon_fontawesome'    => 'fa fa-gift',
        'benefit4_icon_linecons'       => 'vc_li vc_li-params',
        'benefit4_custom_icon'         => '',
        'benefit5_text'                => 'Free Worksheets & Resources Immediate print downloads',
        'benefit5_icon_source'         => 'brand',
        'benefit5_brand_icon_type'     => 'file',
        'benefit5_icon_library'        => 'fontawesome',
        'benefit5_icon_fontawesome'    => 'fa fa-file-text-o',
        'benefit5_icon_linecons'       => 'vc_li vc_li-paperplane',
        'benefit5_custom_icon'         => '',
        'benefit6_text'                => 'Access to eMathSmart Optional interactive portal',
        'benefit6_icon_source'         => 'brand',
        'benefit6_brand_icon_type'     => 'computer',
        'benefit6_icon_library'        => 'fontawesome',
        'benefit6_icon_fontawesome'    => 'fa fa-desktop',
        'benefit6_icon_linecons'       => 'vc_li vc_li-tv',
        'benefit6_custom_icon'         => '',
        'benefit7_text'                => '',
        'benefit7_icon_source'         => 'brand',
        'benefit7_brand_icon_type'     => 'star',
        'benefit7_icon_library'        => 'fontawesome',
        'benefit7_icon_fontawesome'    => 'fa fa-star',
        'benefit7_icon_linecons'       => 'vc_li vc_li-star',
        'benefit7_custom_icon'         => '',
        'benefit8_text'                => '',
        'benefit8_icon_source'         => 'brand',
        'benefit8_brand_icon_type'     => 'shield',
        'benefit8_icon_library'        => 'fontawesome',
        'benefit8_icon_fontawesome'    => 'fa fa-shield',
        'benefit8_icon_linecons'       => 'vc_li vc_li-key',
        'benefit8_custom_icon'         => '',
        'benefit9_text'                => '',
        'benefit9_icon_source'         => 'brand',
        'benefit9_brand_icon_type'     => 'tag',
        'benefit9_icon_library'        => 'fontawesome',
        'benefit9_icon_fontawesome'    => 'fa fa-tag',
        'benefit9_icon_linecons'       => 'vc_li vc_li-tag',
        'benefit9_custom_icon'         => '',
        'benefit10_text'               => '',
        'benefit10_icon_source'        => 'brand',
        'benefit10_brand_icon_type'    => 'idea',
        'benefit10_icon_library'       => 'fontawesome',
        'benefit10_icon_fontawesome'   => 'fa fa-lightbulb-o',
        'benefit10_icon_linecons'      => 'vc_li vc_li-bulb',
        'benefit10_custom_icon'        => '',
    ), $atts );

    $banner_image  = $attributes['banner_image'];
    $card_title    = esc_html( $attributes['card_title'] );
    $benefit1_text = esc_html( $attributes['benefit1_text'] );
    $benefit2_text = esc_html( $attributes['benefit2_text'] );
    $benefit3_text = esc_html( $attributes['benefit3_text'] );
    $benefit4_text = esc_html( $attributes['benefit4_text'] );
    $benefit5_text = esc_html( $attributes['benefit5_text'] );
    $benefit6_text = esc_html( $attributes['benefit6_text'] );
    $benefit7_text = esc_html( $attributes['benefit7_text'] );
    $benefit8_text = esc_html( $attributes['benefit8_text'] );
    $benefit9_text = esc_html( $attributes['benefit9_text'] );
    $benefit10_text = esc_html( $attributes['benefit10_text'] );

    // Enqueue styles programmatically
    wp_enqueue_style( 'parents-club-hero-benefits', plugins_url( 'templates/css/parents-club-hero-benefits.css', __FILE__ ) );

    // Resolve Banner Image URL
    $banner_url = '';
    if ( isset( $attributes['show_banner_image'] ) && $attributes['show_banner_image'] === 'yes' ) {
        if ( ! empty( $banner_image ) && is_numeric( $banner_image ) ) {
            $img_src = wp_get_attachment_image_src( $banner_image, 'full' );
            if ( $img_src ) {
                $banner_url = esc_url( $img_src[0] );
            }
        }
        if ( empty( $banner_url ) ) {
            $banner_url = plugins_url( 'templates/images/parents-club-banner.jpg', __FILE__ );
        }
    }

    // Helper closure to render visual SVGs, libraries or custom uploaded media
    $render_icon = function( $source, $brand_type, $library, $fa_icon, $li_icon, $custom_icon_id ) {
        if ( $source === 'custom' && ! empty( $custom_icon_id ) ) {
            $custom_url = '';
            if ( is_numeric( $custom_icon_id ) ) {
                $img_src = wp_get_attachment_image_src( $custom_icon_id, 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src[0];
                }
            } else {
                $custom_url = $custom_icon_id;
            }
            if ( ! empty( $custom_url ) ) {
                return '<img src="' . esc_url( $custom_url ) . '" alt="Icon" style="width: 18px; height: 18px; display: block; object-fit: contain; filter: brightness(0) invert(1);">';
            }
        }

        if ( $source === 'library' ) {
            if ( $library === 'linecons' ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                $icon_class = ! empty( $li_icon ) ? esc_attr( $li_icon ) : 'vc_li vc_li-tag';
                return '<i class="bullet-icon-lib ' . $icon_class . '" style="font-size: 18px; color: #ffffff; display: inline-flex; align-items: center; justify-content: center;"></i>';
            } else {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                $icon_class = ! empty( $fa_icon ) ? esc_attr( $fa_icon ) : 'fa fa-tag';
                return '<i class="bullet-icon-lib ' . $icon_class . '" style="font-size: 18px; color: #ffffff; display: inline-flex; align-items: center; justify-content: center;"></i>';
            }
        }

        switch ( $brand_type ) {
            case 'tag':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>';
            case 'idea':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A5 5 0 0 0 8 8c0 1.3.5 2.6 1.5 3.5.8.8 1.3 1.5 1.5 2.5"></path>
                            <line x1="9" y1="18" x2="15" y2="18"></line>
                            <line x1="10" y1="22" x2="14" y2="22"></line>
                        </svg>';
            case 'gift':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 12 20 22 4 22 4 12"></polyline>
                            <rect x="2" y="7" width="20" height="5"></rect>
                            <line x1="12" y1="22" x2="12" y2="7"></line>
                            <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                            <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                        </svg>';
            case 'file':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>';
            case 'computer':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>';
            case 'book':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>';
            case 'star':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>';
            case 'shield':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>';
            default:
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>';
        }
    };

    ob_start();
    ?>
    <div class="benefits-column">
        <!-- Premium Parents Club Banner -->
        <?php if ( ! empty( $banner_url ) ) : ?>
            <div class="benefits-image-wrapper">
                <img src="<?php echo esc_url( $banner_url ); ?>" alt="Learning Study Area">
            </div>
        <?php endif; ?>
        
        <!-- Red Benefits Glance Box -->
        <div class="benefits-glance-card">
            <h3><?php echo $card_title; ?></h3>
            <div class="benefits-glance-grid">
                
                <!-- Benefit 1 -->
                <?php if ( ! empty( $benefit1_text ) ) : ?>
                    <div class="benefit-bullet">
                        <span class="bullet-icon-wrapper">
                            <?php echo $render_icon(
                                $attributes['benefit1_icon_source'],
                                $attributes['benefit1_brand_icon_type'],
                                $attributes['benefit1_icon_library'],
                                $attributes['benefit1_icon_fontawesome'],
                                $attributes['benefit1_icon_linecons'],
                                $attributes['benefit1_custom_icon']
                            ); ?>
                        </span>
                        <div class="bullet-content">
                            <p><?php echo $benefit1_text; ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Benefit 2 -->
                <?php if ( ! empty( $benefit2_text ) ) : ?>
                    <div class="benefit-bullet">
                        <span class="bullet-icon-wrapper">
                            <?php echo $render_icon(
                                $attributes['benefit2_icon_source'],
                                $attributes['benefit2_brand_icon_type'],
                                $attributes['benefit2_icon_library'],
                                $attributes['benefit2_icon_fontawesome'],
                                $attributes['benefit2_icon_linecons'],
                                $attributes['benefit2_custom_icon']
                            ); ?>
                        </span>
                        <div class="bullet-content">
                            <p><?php echo $benefit2_text; ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Benefit 3 -->
                <?php if ( ! empty( $benefit3_text ) ) : ?>
                    <div class="benefit-bullet">
                        <span class="bullet-icon-wrapper">
                            <?php echo $render_icon(
                                $attributes['benefit3_icon_source'],
                                $attributes['benefit3_brand_icon_type'],
                                $attributes['benefit3_icon_library'],
                                $attributes['benefit3_icon_fontawesome'],
                                $attributes['benefit3_icon_linecons'],
                                $attributes['benefit3_custom_icon']
                            ); ?>
                        </span>
                        <div class="bullet-content">
                            <p><?php echo $benefit3_text; ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Benefit 4 -->
                <?php if ( ! empty( $benefit4_text ) ) : ?>
                    <div class="benefit-bullet">
                        <span class="bullet-icon-wrapper">
                            <?php echo $render_icon(
                                $attributes['benefit4_icon_source'],
                                $attributes['benefit4_brand_icon_type'],
                                $attributes['benefit4_icon_library'],
                                $attributes['benefit4_icon_fontawesome'],
                                $attributes['benefit4_icon_linecons'],
                                $attributes['benefit4_custom_icon']
                            ); ?>
                        </span>
                        <div class="bullet-content">
                            <p><?php echo $benefit4_text; ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Benefit 5 -->
                <?php if ( ! empty( $benefit5_text ) ) : ?>
                    <div class="benefit-bullet">
                        <span class="bullet-icon-wrapper">
                            <?php echo $render_icon(
                                $attributes['benefit5_icon_source'],
                                $attributes['benefit5_brand_icon_type'],
                                $attributes['benefit5_icon_library'],
                                $attributes['benefit5_icon_fontawesome'],
                                $attributes['benefit5_icon_linecons'],
                                $attributes['benefit5_custom_icon']
                            ); ?>
                        </span>
                        <div class="bullet-content">
                            <p><?php echo $benefit5_text; ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Benefit 6 -->
                <?php if ( ! empty( $benefit6_text ) ) : ?>
                    <div class="benefit-bullet">
                        <span class="bullet-icon-wrapper">
                            <?php echo $render_icon(
                                $attributes['benefit6_icon_source'],
                                $attributes['benefit6_brand_icon_type'],
                                $attributes['benefit6_icon_library'],
                                $attributes['benefit6_icon_fontawesome'],
                                $attributes['benefit6_icon_linecons'],
                                $attributes['benefit6_custom_icon']
                            ); ?>
                        </span>
                        <div class="bullet-content">
                            <p><?php echo $benefit6_text; ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Benefit 7 -->
                <?php if ( ! empty( $benefit7_text ) ) : ?>
                    <div class="benefit-bullet">
                        <span class="bullet-icon-wrapper">
                            <?php echo $render_icon(
                                $attributes['benefit7_icon_source'],
                                $attributes['benefit7_brand_icon_type'],
                                $attributes['benefit7_icon_library'],
                                $attributes['benefit7_icon_fontawesome'],
                                $attributes['benefit7_icon_linecons'],
                                $attributes['benefit7_custom_icon']
                            ); ?>
                        </span>
                        <div class="bullet-content">
                            <p><?php echo $benefit7_text; ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Benefit 8 -->
                <?php if ( ! empty( $benefit8_text ) ) : ?>
                    <div class="benefit-bullet">
                        <span class="bullet-icon-wrapper">
                            <?php echo $render_icon(
                                $attributes['benefit8_icon_source'],
                                $attributes['benefit8_brand_icon_type'],
                                $attributes['benefit8_icon_library'],
                                $attributes['benefit8_icon_fontawesome'],
                                $attributes['benefit8_icon_linecons'],
                                $attributes['benefit8_custom_icon']
                            ); ?>
                        </span>
                        <div class="bullet-content">
                            <p><?php echo $benefit8_text; ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Benefit 9 -->
                <?php if ( ! empty( $benefit9_text ) ) : ?>
                    <div class="benefit-bullet">
                        <span class="bullet-icon-wrapper">
                            <?php echo $render_icon(
                                $attributes['benefit9_icon_source'],
                                $attributes['benefit9_brand_icon_type'],
                                $attributes['benefit9_icon_library'],
                                $attributes['benefit9_icon_fontawesome'],
                                $attributes['benefit9_icon_linecons'],
                                $attributes['benefit9_custom_icon']
                            ); ?>
                        </span>
                        <div class="bullet-content">
                            <p><?php echo $benefit9_text; ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Benefit 10 -->
                <?php if ( ! empty( $benefit10_text ) ) : ?>
                    <div class="benefit-bullet">
                        <span class="bullet-icon-wrapper">
                            <?php echo $render_icon(
                                $attributes['benefit10_icon_source'],
                                $attributes['benefit10_brand_icon_type'],
                                $attributes['benefit10_icon_library'],
                                $attributes['benefit10_icon_fontawesome'],
                                $attributes['benefit10_icon_linecons'],
                                $attributes['benefit10_custom_icon']
                            ); ?>
                        </span>
                        <div class="bullet-content">
                            <p><?php echo $benefit10_text; ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Register [parents_club_why_join] Shortcode
add_shortcode( 'parents_club_why_join', 'idl_loader_parents_club_why_join_shortcode' );

function idl_loader_parents_club_why_join_shortcode( $atts ) {


    $attributes = shortcode_atts( array(
        'section_title' => "Why Join<br>Parents' Club?",
        'feat1_title'   => 'Save More',
        'feat1_desc'    => 'Workbook discounts all year long',
        'feat2_title'   => 'Free Resources',
        'feat2_desc'    => 'Worksheets, printables & learning tools',
        'feat3_title'   => 'Learning Support',
        'feat3_desc'    => 'Helpful tips for learning at home',
        'feat4_title'   => 'Special Perks',
        'feat4_desc'    => 'Exclusive promotions & giveaways',
        'feat5_title'   => 'Trusted Quality',
        'feat5_desc'    => 'Curriculum-aligned Canadian resources',
        'feat6_title'   => 'eMathSmart Access',
        'feat6_desc'    => 'Optional digital learning upgrade',
    ), $atts );

    $user_id = get_current_user_id();
    if ( $user_id ) {
        $section_title = "Your Parent's Club<br>Membership Perks";
    } else {
        $section_title = wp_kses_post( $attributes['section_title'] );
    }
    $feat1_title   = esc_html( $attributes['feat1_title'] );
    $feat1_desc    = esc_html( $attributes['feat1_desc'] );
    $feat2_title   = esc_html( $attributes['feat2_title'] );
    $feat2_desc    = esc_html( $attributes['feat2_desc'] );
    $feat3_title   = esc_html( $attributes['feat3_title'] );
    $feat3_desc    = esc_html( $attributes['feat3_desc'] );
    $feat4_title   = esc_html( $attributes['feat4_title'] );
    $feat4_desc    = esc_html( $attributes['feat4_desc'] );
    $feat5_title   = esc_html( $attributes['feat5_title'] );
    $feat5_desc    = esc_html( $attributes['feat5_desc'] );
    $feat6_title   = esc_html( $attributes['feat6_title'] );
    $feat6_desc    = esc_html( $attributes['feat6_desc'] );

    // Enqueue the modular stylesheet
    wp_enqueue_style( 'parents-club-section-2', plugins_url( 'templates/css/section-2.css', __FILE__ ) );

    ob_start();
    ?>
    <div class="feature-bar-wrapper">
        <!-- Left Title Stack -->
        <div class="bar-title-block">
            <h2><?php echo $section_title; ?></h2>
        </div>
        
        <!-- Right Features Grid -->
        <div class="bar-features-grid">
            <!-- Feature 1: Save More -->
            <?php if ( ! empty( $feat1_title ) ) : ?>
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a1.5 1.5 0 0 1-.44 1.06l-7.56 7.56a2 2 0 0 1-2.83 0l-5.66-5.66a2 2 0 0 1 0-2.83l7.56-7.56A1.5 1.5 0 0 1 13.13 4H19.5A1.5 1.5 0 0 1 21 5.5v6z"></path>
                            <circle cx="16.5" cy="7.5" r="1.5" fill="currentColor"></circle>
                            <circle cx="11.5" cy="12.5" r="1"></circle>
                        </svg>
                    </div>
                    <div class="feature-text-block">
                        <span class="feature-title"><?php echo $feat1_title; ?></span>
                        <span class="feature-desc"><?php echo $feat1_desc; ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Feature 2: Free Resources -->
            <?php if ( ! empty( $feat2_title ) ) : ?>
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 12 20 22 4 22 4 12"></polyline>
                            <rect x="2" y="7" width="20" height="5"></rect>
                            <line x1="12" y1="22" x2="12" y2="7"></line>
                            <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                            <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                        </svg>
                    </div>
                    <div class="feature-text-block">
                        <span class="feature-title"><?php echo $feat2_title; ?></span>
                        <span class="feature-desc"><?php echo $feat2_desc; ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Feature 3: Learning Support -->
            <?php if ( ! empty( $feat3_title ) ) : ?>
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="5"></circle>
                            <path d="M3 20c0-3.3 2.7-6 6-6h6c3.3 0 6 2.7 6 6"></path>
                            <circle cx="12" cy="8" r="1.5" fill="currentColor"></circle>
                        </svg>
                    </div>
                    <div class="feature-text-block">
                        <span class="feature-title"><?php echo $feat3_title; ?></span>
                        <span class="feature-desc"><?php echo $feat3_desc; ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Feature 4: Special Perks -->
            <?php if ( ! empty( $feat4_title ) ) : ?>
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <div class="feature-text-block">
                        <span class="feature-title"><?php echo $feat4_title; ?></span>
                        <span class="feature-desc"><?php echo $feat4_desc; ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Feature 5: Trusted Quality -->
            <?php if ( ! empty( $feat5_title ) ) : ?>
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <div class="feature-text-block">
                        <span class="feature-title"><?php echo $feat5_title; ?></span>
                        <span class="feature-desc"><?php echo $feat5_desc; ?></span>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Feature 6: eMathSmart Access -->
            <?php if ( ! empty( $feat6_title ) ) : ?>
                <div class="feature-item">
                    <div class="feature-icon-wrapper">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
                        </svg>
                    </div>
                    <div class="feature-text-block">
                        <span class="feature-title"><?php echo $feat6_title; ?></span>
                        <span class="feature-desc"><?php echo $feat6_desc; ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

// Register [parents_club_how_works] Shortcode
add_shortcode( 'parents_club_how_works', 'idl_loader_parents_club_how_works_shortcode' );

function idl_loader_parents_club_how_works_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'card_title' => "How Parents' Club Works",
        'steps'      => '',
    ), $atts );

    $card_title = esc_html( $attributes['card_title'] );

    // Enqueue the modular stylesheet
    wp_enqueue_style( 'parents-club-how-works', plugins_url( 'templates/css/parents-club-how-works.css', __FILE__ ) );

    // Helper closure to render visual SVGs, libraries or custom uploaded media
    $render_icon = function( $source, $brand_type, $library, $fa_icon, $li_icon, $custom_icon_id ) {
        if ( $source === 'custom' && ! empty( $custom_icon_id ) ) {
            $custom_url = '';
            if ( is_numeric( $custom_icon_id ) ) {
                $img_src = wp_get_attachment_image_src( $custom_icon_id, 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src[0];
                }
            } else {
                $custom_url = $custom_icon_id;
            }
            if ( ! empty( $custom_url ) ) {
                return '<img src="' . esc_url( $custom_url ) . '" alt="Step Icon" style="width: 44px; height: 44px; display: block; object-fit: contain;">';
            }
        }

        if ( $source === 'library' ) {
            if ( $library === 'linecons' ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                $icon_class = ! empty( $li_icon ) ? esc_attr( $li_icon ) : 'vc_li vc_li-tag';
                return '<i class="how-step-icon-lib ' . $icon_class . '" style="font-size: 32px; color: #af0128; display: inline-flex; align-items: center; justify-content: center; height: 44px; width: 44px;"></i>';
            } else {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                $icon_class = ! empty( $fa_icon ) ? esc_attr( $fa_icon ) : 'fa fa-tag';
                return '<i class="how-step-icon-lib ' . $icon_class . '" style="font-size: 32px; color: #af0128; display: inline-flex; align-items: center; justify-content: center; height: 44px; width: 44px;"></i>';
            }
        }

        switch ( $brand_type ) {
            case 'user_add':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>';
            case 'tag':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            <circle cx="11" cy="14" r="1"></circle>
                            <circle cx="14" cy="11" r="1"></circle>
                        </svg>';
            case 'users':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>';
            case 'idea':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A5 5 0 0 0 8 8c0 1.3.5 2.6 1.5 3.5.8.8 1.3 1.5 1.5 2.5"></path>
                            <line x1="9" y1="18" x2="15" y2="18"></line>
                            <line x1="10" y1="22" x2="14" y2="22"></line>
                        </svg>';
            case 'gift':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 12 20 22 4 22 4 12"></polyline>
                            <rect x="2" y="7" width="20" height="5"></rect>
                            <line x1="12" y1="22" x2="12" y2="7"></line>
                            <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                            <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                        </svg>';
            case 'file':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>';
            case 'computer':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>';
            case 'star':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>';
            case 'shield':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>';
            default:
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>';
        }
    };

    // Parse dynamic repeatable steps from param_group
    $steps_data = array();
    if ( ! empty( $attributes['steps'] ) ) {
        if ( function_exists( 'vc_param_group_parse_atts' ) ) {
            $steps_data = vc_param_group_parse_atts( $attributes['steps'] );
        } else {
            $steps_data = json_decode( urldecode( $attributes['steps'] ), true );
        }
    }

    $active_steps = array();
    if ( is_array( $steps_data ) ) {
        foreach ( $steps_data as $step ) {
            $title = isset( $step['title'] ) ? $step['title'] : '';
            if ( ! empty( $title ) ) {
                $num             = isset( $step['num'] ) ? $step['num'] : '';
                $desc            = isset( $step['desc'] ) ? $step['desc'] : '';
                $icon_source     = isset( $step['icon_source'] ) ? $step['icon_source'] : 'brand';
                $brand_icon_type = isset( $step['brand_icon_type'] ) ? $step['brand_icon_type'] : 'user_add';
                $icon_library    = isset( $step['icon_library'] ) ? $step['icon_library'] : 'fontawesome';
                $fa_icon         = isset( $step['icon_fontawesome'] ) ? $step['icon_fontawesome'] : '';
                $li_icon         = isset( $step['icon_linecons'] ) ? $step['icon_linecons'] : '';
                $custom_icon     = isset( $step['custom_icon'] ) ? $step['custom_icon'] : '';

                $active_steps[] = array(
                    'num'   => esc_html( $num ),
                    'title' => esc_html( $title ),
                    'desc'  => esc_html( $desc ),
                    'icon'  => $render_icon( $icon_source, $brand_icon_type, $icon_library, $fa_icon, $li_icon, $custom_icon )
                );
            }
        }
    }

    ob_start();
    ?>
    <div class="how-it-works-card">
        <?php if ( ! empty( $card_title ) ) : ?>
            <h2 class="how-title"><?php echo $card_title; ?></h2>
        <?php endif; ?>
        
        <div class="how-steps-wrapper">
            <?php 
            $total_steps = count( $active_steps );
            foreach ( $active_steps as $index => $step ) : 
            ?>
                <!-- Step -->
                <div class="how-step-item">
                    <div class="how-step-badge-row">
                        <?php if ( ! empty( $step['num'] ) ) : ?>
                            <span class="how-step-number"><?php echo $step['num']; ?></span>
                        <?php endif; ?>
                        <div class="how-step-icon">
                            <?php echo $step['icon']; ?>
                        </div>
                    </div>
                    <h3><?php echo $step['title']; ?></h3>
                    <?php if ( ! empty( $step['desc'] ) ) : ?>
                        <p><?php echo $step['desc']; ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Connector arrow (do not render after the last step) -->
                <?php if ( $index < $total_steps - 1 ) : ?>
                    <div class="how-connector">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode( 'esmart_login_card', 'idl_loader_esmart_login_card_shortcode' );

function idl_loader_esmart_login_card_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'brand_type'          => 'plugin_logo',
        'brand_image'         => '',
        'lead_title'          => 'Already have an eMathSmart account?',
        'description'         => 'Login here to access the program.',
        'cta_label'           => 'Login to eMathSmart',
        'cta_link'            => '#emathsmart-login',
        'cta_icon_library'    => 'arrow',
        'cta_icon_fontawesome'=> 'fa fa-arrow-right',
        'cta_icon_linecons'   => 'vc_li vc_li-arrow-right',
        'redirect_lead'       => 'New to eMathSmart?',
        'redirect_label'      => 'Learn more',
        'redirect_link'       => '#emathsmart-learn',
    ), $atts );

    // Enqueue gateway stylesheet
    wp_enqueue_style(
        'parents-club-esmart-gateway',
        plugins_url( 'templates/css/parents-club-esmart-gateway.css', __FILE__ ),
        array(),
        '1.0.0'
    );

    // --- Brand logo ---
    $brand_html = '';
    if ( 'custom_image' === $attributes['brand_type'] && ! empty( $attributes['brand_image'] ) ) {
        // Admin-uploaded custom logo
        $img_url = wp_get_attachment_image_url( absint( $attributes['brand_image'] ), 'medium' );
        if ( $img_url ) {
            $brand_html = '<div class="esmart-logo-row"><img src="' . esc_url( $img_url ) . '" alt="' . esc_attr__( 'eMathSmart Logo', 'book-junky' ) . '" class="esmart-custom-logo" /></div>';
        }
    }
    // Default: bundled plugin logo image
    if ( empty( $brand_html ) ) {
        $plugin_logo_url = plugins_url( 'templates/images/eMathSmart_logo_FINAL .png', __FILE__ );
        $brand_html = '<div class="esmart-logo-row"><img src="' . esc_url( $plugin_logo_url ) . '" alt="' . esc_attr__( 'eMathSmart', 'book-junky' ) . '" class="esmart-plugin-logo" /></div>';
    }

    // --- CTA icon ---
    $icon_html = '';
    $icon_lib  = $attributes['cta_icon_library'];
    if ( 'fontawesome' === $icon_lib && ! empty( $attributes['cta_icon_fontawesome'] ) ) {
        $icon_class = esc_attr( $attributes['cta_icon_fontawesome'] );
        $icon_html  = '<i class="' . $icon_class . '" aria-hidden="true"></i>';
    } elseif ( 'linecons' === $icon_lib && ! empty( $attributes['cta_icon_linecons'] ) ) {
        $icon_class = esc_attr( $attributes['cta_icon_linecons'] );
        $icon_html  = '<i class="' . $icon_class . '" aria-hidden="true"></i>';
    } else {
        // Default SVG arrow (matches staging HTML)
        $icon_html = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>';
    }

    // --- Sanitise text fields ---
    $lead_title     = esc_html( $attributes['lead_title'] );
    $description    = esc_html( $attributes['description'] );
    $cta_label      = esc_html( $attributes['cta_label'] );
    $cta_link       = esc_url( $attributes['cta_link'] );
    $redirect_lead  = esc_html( $attributes['redirect_lead'] );
    $redirect_label = esc_html( $attributes['redirect_label'] );
    $redirect_link  = esc_url( $attributes['redirect_link'] );

    ob_start();
    ?>
    <div class="esmart-login-card">

        <?php echo $brand_html; // already escaped above ?>

        <p class="esmart-login-lead"><?php echo $lead_title; ?></p>
        <p class="esmart-login-desc"><?php echo $description; ?></p>

        <a href="<?php echo $cta_link; ?>" class="btn-esmart-login">
            <?php echo $cta_label; ?>
            <?php echo $icon_html; // SVG/icon — safe, composed above ?>
        </a>

        <p class="esmart-redirect-text">
            <?php echo $redirect_lead; ?>
            <a href="<?php echo $redirect_link; ?>"><?php echo $redirect_label; ?></a>
        </p>

    </div>
    <?php
    return ob_get_clean();
}

// Register [emathsmart_plan_card] Shortcode
add_shortcode( 'emathsmart_plan_card', 'idl_loader_emathsmart_plan_card_shortcode' );

function idl_loader_emathsmart_plan_card_shortcode( $atts ) {


    $attributes = shortcode_atts( array(
        'card_layout'        => 'digital',
        'highlighted_border' => '',
        'best_value'         => '',
        'brand_type'         => 'image',
        'logo_image'         => '',
        'title'              => 'Digital Learning Program',
        'price'              => '$9.95',
        'period'             => '/month',
        'badge_text'         => 'For Grades 3 & 4',
        'description'        => '',
        'list_items'         => '',
        'footer_type'        => 'button',
        'button_text'        => 'Start 7-Day Free Trial',
        'button_link'        => '',
        'footer_image'       => '',
    ), $atts );

    // Enqueue the modular stylesheets
    wp_enqueue_style( 'parents-club-plans-base', plugins_url( 'templates/css/parents-club-plans-base.css', __FILE__ ) );
    
    $layout = $attributes['card_layout'];
    if ( 'digital' === $layout ) {
        wp_enqueue_style( 'parents-club-plan-digital', plugins_url( 'templates/css/parents-club-plan-digital.css', __FILE__ ) );
    } elseif ( 'pricing' === $layout ) {
        wp_enqueue_style( 'parents-club-plan-monthly', plugins_url( 'templates/css/parents-club-plan-monthly.css', __FILE__ ) );
        wp_enqueue_style( 'parents-club-plan-annual', plugins_url( 'templates/css/parents-club-plan-annual.css', __FILE__ ) );
    } elseif ( 'not_ready' === $layout ) {
        wp_enqueue_style( 'parents-club-plan-not-ready', plugins_url( 'templates/css/parents-club-plan-not-ready.css', __FILE__ ) );
    }

    // Process variables
    $title       = esc_html( $attributes['title'] );
    $badge_text  = esc_html( $attributes['badge_text'] );
    $price       = esc_html( $attributes['price'] );
    $period      = esc_html( $attributes['period'] );
    $description = wp_kses_post( $attributes['description'] );

    // Parse repeatable checklist items
    $list_data = array();
    if ( ! empty( $attributes['list_items'] ) ) {
        if ( function_exists( 'vc_param_group_parse_atts' ) ) {
            $list_data = vc_param_group_parse_atts( $attributes['list_items'] );
        } else {
            $list_data = json_decode( urldecode( $attributes['list_items'] ), true );
        }
    }

    // Default features checklist fallback if empty
    if ( empty( $list_data ) ) {
        if ( 'pricing' === $layout ) {
            $list_data = array(
                array( 'item_text' => 'Full access to eMathSmart', 'icon_source' => 'default' ),
                array( 'item_text' => 'Interactive exercises & tools', 'icon_source' => 'default' ),
                array( 'item_text' => 'Printable worksheets', 'icon_source' => 'default' ),
                array( 'item_text' => 'Progress tracking & reports', 'icon_source' => 'default' ),
                array( 'item_text' => 'All credits included', 'icon_source' => 'default' ),
            );
        } else {
            $list_data = array(
                array( 'item_text' => 'Interactive practice & games', 'icon_source' => 'brand', 'brand_icon_type' => 'gamepad' ),
                array( 'item_text' => 'Instant feedback & hints', 'icon_source' => 'brand', 'brand_icon_type' => 'idea' ),
                array( 'item_text' => 'Printable worksheets', 'icon_source' => 'brand', 'brand_icon_type' => 'file' ),
                array( 'item_text' => 'Progress tracking & reports', 'icon_source' => 'brand', 'brand_icon_type' => 'chart' ),
                array( 'item_text' => 'AI-powered learning tools', 'icon_source' => 'brand', 'brand_icon_type' => 'star' ),
                array( 'item_text' => 'Curriculum-aligned content', 'icon_source' => 'brand', 'brand_icon_type' => 'shield' ),
            );
        }
    }

    // Closure to render visual icons for checklists
    $render_item_icon = function( $item ) {
        $source = isset( $item['icon_source'] ) ? $item['icon_source'] : 'default';
        
        if ( 'brand' === $source ) {
            $brand_type = isset( $item['brand_icon_type'] ) ? $item['brand_icon_type'] : 'check';
            switch ( $brand_type ) {
                case 'gamepad':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect><path d="M6 12h12M12 6v12"></path></svg>';
                case 'idea':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A5 5 0 0 0 8 8c0 1.3.5 2.6 1.5 3.5.8.8 1.3 1.5 1.5 2.5"></path><line x1="9" y1="18" x2="15" y2="18"></line><line x1="10" y1="22" x2="14" y2="22"></line></svg>';
                case 'file':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>';
                case 'chart':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>';
                case 'star':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                case 'shield':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>';
                case 'check':
                default:
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
            }
        } elseif ( 'library' === $source ) {
            $lib = isset( $item['icon_library'] ) ? $item['icon_library'] : 'fontawesome';
            if ( 'fontawesome' === $lib && ! empty( $item['icon_fontawesome'] ) ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                return '<i class="' . esc_attr( $item['icon_fontawesome'] ) . '" aria-hidden="true"></i>';
            } elseif ( 'linecons' === $lib && ! empty( $item['icon_linecons'] ) ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                return '<i class="' . esc_attr( $item['icon_linecons'] ) . '" aria-hidden="true"></i>';
            }
        } elseif ( 'custom' === $source && ! empty( $item['custom_icon'] ) ) {
            $custom_url = '';
            if ( is_numeric( $item['custom_icon'] ) ) {
                $img_src = wp_get_attachment_image_url( absint( $item['custom_icon'] ), 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src;
                }
            } else {
                $custom_url = $item['custom_icon'];
            }
            if ( ! empty( $custom_url ) ) {
                return '<img src="' . esc_url( $custom_url ) . '" class="pc-plan-custom-icon-img" alt="" style="width:16px;height:16px;object-fit:contain;vertical-align:middle;display:inline-block;" />';
            }
        }
        
        // Default checkmark fallback
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
    };

    ob_start();

    if ( 'not_ready' === $layout ) : ?>
        <!-- CARD 4: Not Ready for eMathSmart? -->
        <div class="pc-plan-not-ready">
            <?php if ( ! empty( $title ) ) : ?>
                <h3 class="pc-not-ready-title"><?php echo $title; ?></h3>
            <?php endif; ?>
            <?php if ( ! empty( $description ) ) : ?>
                <p class="pc-not-ready-desc"><?php echo $description; ?></p>
            <?php endif; ?>
            
            <?php if ( 'graphic' === $attributes['footer_type'] ) : 
                $foot_graphic_url = '';
                if ( ! empty( $attributes['footer_image'] ) ) {
                    if ( is_numeric( $attributes['footer_image'] ) ) {
                        $foot_graphic_url = wp_get_attachment_image_url( absint( $attributes['footer_image'] ), 'full' );
                    } else {
                        $foot_graphic_url = $attributes['footer_image'];
                    }
                }
                if ( empty( $foot_graphic_url ) ) {
                    $foot_graphic_url = plugins_url( 'templates/images/not-ready-for-emathsmart.png', __FILE__ );
                }
                ?>
                <div class="pc-not-ready-graphic">
                    <img src="<?php echo esc_url( $foot_graphic_url ); ?>" alt="<?php echo esc_attr__( 'Not Ready for eMathSmart Illustration', 'book-junky' ); ?>">
                </div>
            <?php endif; ?>
        </div>
    <?php else : 
        // pricing vs digital card wrapper classes
        $card_classes = array( 'pc-plan-card' );
        if ( 'digital' === $layout ) {
            $card_classes[] = 'pc-plan-digital';
        } elseif ( 'pricing' === $layout ) {
            if ( 'yes' === $attributes['highlighted_border'] ) {
                $card_classes[] = 'pc-plan-annual';
            } else {
                $card_classes[] = 'pc-plan-monthly';
            }
        }
        $card_class_str = implode( ' ', $card_classes );
        ?>
        <div class="<?php echo esc_attr( $card_class_str ); ?>">
            <?php if ( 'pricing' === $layout && 'yes' === $attributes['best_value'] ) : ?>
                <span class="best-value-badge"><?php esc_html_e( 'Best Value', 'book-junky' ); ?></span>
            <?php endif; ?>

            <?php if ( 'digital' === $layout ) : ?>
                <div class="digital-logo-row">
                    <?php 
                    $logo_url = '';
                    if ( 'image' === $attributes['brand_type'] ) {
                        if ( ! empty( $attributes['logo_image'] ) ) {
                            if ( is_numeric( $attributes['logo_image'] ) ) {
                                $logo_url = wp_get_attachment_image_url( absint( $attributes['logo_image'] ), 'full' );
                            } else {
                                $logo_url = $attributes['logo_image'];
                            }
                        }
                        if ( empty( $logo_url ) ) {
                            $logo_url = plugins_url( 'templates/images/eMathSmart_logo_FINAL .png', __FILE__ );
                        }
                    }
                    if ( ! empty( $logo_url ) ) : ?>
                        <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php esc_attr_e( 'eMathSmart Logo', 'book-junky' ); ?>" class="digital-brand-logo">
                    <?php else : ?>
                        <div class="esmart-text-logo-inline" style="font-family:'Outfit',sans-serif; font-size:24px; font-weight:800; color:#af0128; letter-spacing:-0.5px;">
                            <span style="color:#f28538;">e</span>MathSmart<span style="font-size:12px;vertical-align:super;">®</span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $title ) ) : ?>
                <h3 class="pc-plan-title"><?php echo $title; ?></h3>
            <?php endif; ?>

            <?php if ( 'pricing' === $layout && ! empty( $price ) ) : ?>
                <div class="pc-plan-price">
                    <?php echo $price; ?>
                    <?php if ( ! empty( $period ) ) : ?>
                        <span class="pc-plan-period"><?php echo $period; ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $badge_text ) ) : ?>
                <span class="pc-plan-badge"><?php echo $badge_text; ?></span>
            <?php endif; ?>

            <?php if ( ! empty( $list_data ) ) : ?>
                <ul class="pc-plan-list">
                    <?php foreach ( $list_data as $item ) : 
                        $item_txt = isset( $item['item_text'] ) ? esc_html( $item['item_text'] ) : '';
                        if ( empty( $item_txt ) ) {
                            continue;
                        }
                        ?>
                        <li class="pc-plan-item">
                            <span class="pc-plan-item-icon">
                                <?php echo $render_item_icon( $item ); ?>
                            </span>
                            <p><?php echo $item_txt; ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php 
            if ( 'button' === $attributes['footer_type'] && ! empty( $attributes['button_text'] ) ) : 
                $link_url    = '#';
                $link_title  = esc_html( $attributes['button_text'] );
                $link_target = '';

                if ( ! empty( $attributes['button_link'] ) ) {
                    $link_parsed = vc_build_link( $attributes['button_link'] );
                    if ( ! empty( $link_parsed['url'] ) ) {
                        $link_url = $link_parsed['url'];
                    }
                    if ( ! empty( $link_parsed['title'] ) ) {
                        $link_title = $link_parsed['title'];
                    }
                    if ( ! empty( $link_parsed['target'] ) ) {
                        $link_target = $link_parsed['target'];
                    }
                }
                ?>
                <a href="<?php echo esc_url( $link_url ); ?>" class="pc-plan-btn" <?php echo ! empty( $link_target ) ? 'target="' . esc_attr( $link_target ) . '"' : ''; ?>>
                    <?php echo esc_html( $link_title ); ?>
                </a>
            <?php elseif ( 'graphic' === $attributes['footer_type'] ) : 
                $graphic_url = '';
                if ( ! empty( $attributes['footer_image'] ) ) {
                    if ( is_numeric( $attributes['footer_image'] ) ) {
                        $graphic_url = wp_get_attachment_image_url( absint( $attributes['footer_image'] ), 'full' );
                    } else {
                        $graphic_url = $attributes['footer_image'];
                    }
                }
                if ( empty( $graphic_url ) ) {
                    $graphic_url = plugins_url( 'templates/images/emathsmart-program.png', __FILE__ );
                }
                ?>
                <div class="plan-graphic-container">
                    <img src="<?php echo esc_url( $graphic_url ); ?>" alt="<?php esc_attr_e( 'eMathSmart Program Illustration', 'book-junky' ); ?>">
                </div>
            <?php endif; ?>
        </div>
    <?php endif;

    return ob_get_clean();
}

// Register [parents_club_member_perks] Shortcode
add_shortcode( 'parents_club_member_perks', 'idl_loader_parents_club_member_perks_shortcode' );

function idl_loader_parents_club_member_perks_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'section_title' => "What You Get as a Parents' Club Member",
        'perk1_line1'   => 'Free Worksheets',
        'perk1_line2'   => '& Resources',
        'perk1_icon'    => 'file',
        'perk2_line1'   => 'Learning Tips,',
        'perk2_line2'   => 'News & Articles',
        'perk2_icon'    => 'idea',
        'perk3_line1'   => 'Special Promotions',
        'perk3_line2'   => '& Discounts',
        'perk3_icon'    => 'discount',
        'perk4_line1'   => 'Early Access to',
        'perk4_line2'   => 'New Products',
        'perk4_icon'    => 'box',
        'perk5_line1'   => 'Contests, Giveaways',
        'perk5_line2'   => '& More!',
        'perk5_icon'    => 'gift',
        'bg_color'      => '#fdf9fb',
        'icon_color'    => '#af0128',
    ), $atts );

    // Sanitise text
    $section_title = esc_html( $attributes['section_title'] );
    $bg_color      = esc_attr( $attributes['bg_color'] );
    $icon_color    = esc_attr( $attributes['icon_color'] );

    $perks = array();
    for ( $i = 1; $i <= 5; $i++ ) {
        $perks[] = array(
            'line1' => esc_html( $attributes[ "perk{$i}_line1" ] ),
            'line2' => esc_html( $attributes[ "perk{$i}_line2" ] ),
            'icon'  => sanitize_key( $attributes[ "perk{$i}_icon" ] ),
        );
    }

    // Enqueue the section stylesheet
    wp_enqueue_style(
        'parents-club-member-perks-bar',
        plugins_url( 'templates/css/parents-club-member-perks-bar.css', __FILE__ )
    );

    // SVG library for brand icons
    $perk_svgs = array(
        'file' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <line x1="10" y1="9" x2="8" y2="9"></line>
                   </svg>',
        'idea' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="19.78" y1="4.22" x2="18.36" y2="5.64"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1.3.5 2.6 1.5 3.5.8.8 1.3 1.5 1.5 2.5"></path>
                        <line x1="9" y1="18" x2="15" y2="18"></line>
                        <line x1="10" y1="22" x2="14" y2="22"></line>
                   </svg>',
        'discount' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        <line x1="9.5" y1="14.5" x2="14.5" y2="9.5"></line>
                        <circle cx="10.5" cy="13.5" r="1" fill="currentColor" stroke="none"></circle>
                        <circle cx="13.5" cy="10.5" r="1" fill="currentColor" stroke="none"></circle>
                   </svg>',
        'box'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                   </svg>',
        'gift' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 12 20 22 4 22 4 12"></polyline>
                        <rect x="2" y="7" width="20" height="5"></rect>
                        <line x1="12" y1="22" x2="12" y2="7"></line>
                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                   </svg>',
        'star' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                   </svg>',
        'shield' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                   </svg>',
        'tag'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                   </svg>',
    );

    ob_start();
    ?>
    <div class="member-perks-card" style="background-color: <?php echo $bg_color; ?>;">
        <h2 class="member-perks-title"><?php echo $section_title; ?></h2>
        <div class="member-perks-grid">
            <?php foreach ( $perks as $index => $perk ) :
                $svg = isset( $perk_svgs[ $perk['icon'] ] ) ? $perk_svgs[ $perk['icon'] ] : $perk_svgs['file'];
            ?>
            <div class="member-perk-item">
                <div class="member-perk-icon" style="color: <?php echo esc_attr( $icon_color ); ?>;">
                    <?php echo $svg; ?>
                </div>
                <p class="member-perk-label">
                    <?php echo esc_html( $perk['line1'] ); ?>
                    <?php if ( ! empty( $perk['line2'] ) ) : ?><br><?php echo esc_html( $perk['line2'] ); ?><?php endif; ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div><!-- /.member-perks-grid -->
    </div><!-- /.member-perks-card -->
    <?php
    return ob_get_clean();
}

// -----------------------------------------------------------------------------
// SECTION 3: WPBakery Shortcode Class Binder
// -----------------------------------------------------------------------------

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_parents_club_hero extends WPBakeryShortCode {
        // Automatically maps backend layout rendering
    }
    
    class WPBakeryShortCode_parents_club_hero_intro extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Intro component
    }
    
    class WPBakeryShortCode_parents_club_benefits_glance extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Benefits component
    }
    
    class WPBakeryShortCode_parents_club_why_join extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Why Join features component
    }
    
    class WPBakeryShortCode_parents_club_how_works extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for How Works steps component
    }

    class WPBakeryShortCode_esmart_login_card extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for eMathSmart Gateway Login Card
    }

    class WPBakeryShortCode_emathsmart_plan_card extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for eMathSmart Plan Card
    }

    class WPBakeryShortCode_parents_club_member_perks extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Member Perks Bar
    }

    class WPBakeryShortCode_parents_club_need_help extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Need Help Panel
    }

    class WPBakeryShortCode_emathsmart_subscription_product_card extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for dynamic subscription product card
    }

    class WPBakeryShortCode_parents_club_cta_banner extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for CTA Banner component
    }

    class WPBakeryShortCode_parents_club_member_welcome extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Member Welcome component
    }

    class WPBakeryShortCode_parents_club_member_quick_links extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Member Quick Links component
    }

    class WPBakeryShortCode_parents_club_member_subscription extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Member Subscription Card
    }

    class WPBakeryShortCode_parents_club_member_account_overview extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Member Account Overview panel
    }

    class WPBakeryShortCode_parents_club_member_coins extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Member Coins card
    }

    class WPBakeryShortCode_parents_club_member_billing extends WPBakeryShortCode {
        // Automatically maps backend layout rendering for Member Billing History card
    }
}

// Register [parents_club_need_help] Shortcode
add_shortcode( 'parents_club_need_help', 'idl_loader_parents_club_need_help_shortcode' );

function idl_loader_parents_club_need_help_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'title'        => 'Need Help?',
        'subtitle'     => 'Our team is here to support you!',
        'illustration' => '',
        'contacts'     => '',
        'bg_color'     => '#fdf9fb',
    ), $atts );

    $title    = esc_html( $attributes['title'] );
    $subtitle = esc_html( $attributes['subtitle'] );
    $bg_color = esc_attr( $attributes['bg_color'] );

    // Enqueue the modular stylesheet
    wp_enqueue_style( 'parents-club-need-help', plugins_url( 'templates/css/parents-club-need-help.css', __FILE__ ) );

    // Illustration URL
    $illustration_url = '';
    if ( ! empty( $attributes['illustration'] ) ) {
        if ( is_numeric( $attributes['illustration'] ) ) {
            $illustration_url = wp_get_attachment_image_url( absint( $attributes['illustration'] ), 'full' );
        } else {
            $illustration_url = $attributes['illustration'];
        }
    }
    if ( empty( $illustration_url ) ) {
        $illustration_url = plugins_url( 'templates/images/books-pencils.png', __FILE__ );
    }

    // Helper closure to render dynamic icons matching custom source/library preferences
    $render_icon = function( $source, $brand_type, $library, $fa_icon, $li_icon, $custom_icon_id ) {
        if ( $source === 'custom' && ! empty( $custom_icon_id ) ) {
            $custom_url = '';
            if ( is_numeric( $custom_icon_id ) ) {
                $img_src = wp_get_attachment_image_src( $custom_icon_id, 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src[0];
                }
            } else {
                $custom_url = $custom_icon_id;
            }
            if ( ! empty( $custom_url ) ) {
                return '<img src="' . esc_url( $custom_url ) . '" alt="Contact Icon" style="width: 22px; height: 22px; display: block; object-fit: contain;">';
            }
        }

        if ( $source === 'library' ) {
            if ( $library === 'linecons' ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                $icon_class = ! empty( $li_icon ) ? esc_attr( $li_icon ) : 'vc_li vc_li-mail';
                return '<i class="' . $icon_class . '" style="font-size: 20px; color: #af0128; display: inline-flex; align-items: center; justify-content: center; height: 22px; width: 22px;"></i>';
            } else {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                $icon_class = ! empty( $fa_icon ) ? esc_attr( $fa_icon ) : 'fa fa-envelope-o';
                return '<i class="' . $icon_class . '" style="font-size: 20px; color: #af0128; display: inline-flex; align-items: center; justify-content: center; height: 22px; width: 22px;"></i>';
            }
        }

        switch ( $brand_type ) {
            case 'phone':
                return '<svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.78a16 16 0 0 0 6 6l1.27-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
            case 'clock':
                return '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
            case 'email':
            default:
                return '<svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"></rect><polyline points="2,4 12,13 22,4"></polyline></svg>';
        }
    };

    // Parse dynamic repeatable contacts from param_group
    $contacts_data = array();
    if ( ! empty( $attributes['contacts'] ) ) {
        if ( function_exists( 'vc_param_group_parse_atts' ) ) {
            $contacts_data = vc_param_group_parse_atts( $attributes['contacts'] );
        } else {
            $contacts_data = json_decode( urldecode( $attributes['contacts'] ), true );
        }
    }

    $active_contacts = array();
    if ( is_array( $contacts_data ) ) {
        foreach ( $contacts_data as $contact ) {
            $value = isset( $contact['value'] ) ? $contact['value'] : '';
            if ( ! empty( $value ) ) {
                $label           = isset( $contact['label'] ) ? $contact['label'] : '';
                $link            = isset( $contact['link'] ) ? $contact['link'] : '';
                $icon_source     = isset( $contact['icon_source'] ) ? $contact['icon_source'] : 'brand';
                $brand_icon_type = isset( $contact['brand_icon_type'] ) ? $contact['brand_icon_type'] : 'email';
                $icon_library    = isset( $contact['icon_library'] ) ? $contact['icon_library'] : 'fontawesome';
                $fa_icon         = isset( $contact['icon_fontawesome'] ) ? $contact['icon_fontawesome'] : '';
                $li_icon         = isset( $contact['icon_linecons'] ) ? $contact['icon_linecons'] : '';
                $custom_icon     = isset( $contact['custom_icon'] ) ? $contact['custom_icon'] : '';

                $active_contacts[] = array(
                    'label' => esc_html( $label ),
                    'value' => esc_html( $value ),
                    'link'  => esc_url( $link ),
                    'icon'  => $render_icon( $icon_source, $brand_icon_type, $icon_library, $fa_icon, $li_icon, $custom_icon )
                );
            }
        }
    }

    ob_start();
    ?>
    <div id="parents-club-section-6">
        <div class="need-help-panel" style="background-color: <?php echo $bg_color; ?>;">
            <div class="need-help-body">
                <?php if ( ! empty( $title ) ) : ?>
                    <h3 class="need-help-title"><?php echo $title; ?></h3>
                <?php endif; ?>
                <?php if ( ! empty( $subtitle ) ) : ?>
                    <p class="need-help-subtitle"><?php echo $subtitle; ?></p>
                <?php endif; ?>
                <div class="need-help-contacts">
                    <?php foreach ( $active_contacts as $contact ) : ?>
                        <div class="contact-row">
                            <div class="contact-icon">
                                <?php echo $contact['icon']; ?>
                            </div>
                            <div class="contact-info">
                                <?php if ( ! empty( $contact['label'] ) ) : ?>
                                    <span class="contact-label"><?php echo $contact['label']; ?></span>
                                <?php endif; ?>
                                <span class="contact-value">
                                    <?php if ( ! empty( $contact['link'] ) ) : ?>
                                        <a href="<?php echo $contact['link']; ?>"><?php echo $contact['value']; ?></a>
                                    <?php else : ?>
                                        <?php echo $contact['value']; ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div><!-- /.need-help-contacts -->
            </div><!-- /.need-help-body -->
            <?php if ( ! empty( $illustration_url ) ) : ?>
                <div class="need-help-image-wrapper">
                    <img src="<?php echo esc_url( $illustration_url ); ?>" alt="" class="need-help-illustration" aria-hidden="true">
                </div>
            <?php endif; ?>
        </div><!-- /.need-help-panel -->
    </div><!-- /#parents-club-section-6 -->
    <?php
    return ob_get_clean();
}

// -----------------------------------------------------------------------------
// SECTION 4: [emathsmart_subscription_product_card] Shortcode Handler
// -----------------------------------------------------------------------------

// Register [emathsmart_subscription_product_card] Shortcode
add_shortcode( 'emathsmart_subscription_product_card', 'idl_loader_emathsmart_subscription_product_card_shortcode' );

function idl_loader_emathsmart_subscription_product_card_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'product_id'          => '',
        'product_id_override' => '',
        'highlighted_border'  => '',
        'best_value'          => '',
        'title'               => '',
        'price'               => '',
        'period'              => '',
        'badge_text'          => '',
        'list_items'          => '',
        'button_text'         => '',
        'button_link'         => '',
        'show_to_subscribers' => '',
    ), $atts );




    // Enqueue the modular stylesheets
    wp_enqueue_style( 'parents-club-plans-base', plugins_url( 'templates/css/parents-club-plans-base.css', __FILE__ ) );
    wp_enqueue_style( 'parents-club-plan-monthly', plugins_url( 'templates/css/parents-club-plan-monthly.css', __FILE__ ) );
    wp_enqueue_style( 'parents-club-plan-annual', plugins_url( 'templates/css/parents-club-plan-annual.css', __FILE__ ) );

    // WooCommerce product selection & dynamic loading
    $product_id = 0;
    if ( ! empty( $attributes['product_id_override'] ) ) {
        $product_id = absint( $attributes['product_id_override'] );
    } elseif ( ! empty( $attributes['product_id'] ) ) {
        $product_id = absint( $attributes['product_id'] );
    }
    
    $product = null;
    if ( $product_id && function_exists( 'wc_get_product' ) ) {
        $product = wc_get_product( $product_id );
    }

    // Resolve card Title
    $title = '';
    if ( ! empty( $attributes['title'] ) ) {
        $title = esc_html( $attributes['title'] );
    } elseif ( $product ) {
        $title = esc_html( $product->get_name() );
    }

    // Resolve card Price
    $price = '';
    if ( ! empty( $attributes['price'] ) ) {
        $price = esc_html( $attributes['price'] );
    } elseif ( $product ) {
        $price = get_woocommerce_currency_symbol() . number_format( (float) $product->get_price(), 2 );
    }

    // Resolve billing Period
    $period = '';
    if ( ! empty( $attributes['period'] ) ) {
        $period = esc_html( $attributes['period'] );
    } elseif ( $product ) {
        $billing_period   = '';
        $billing_interval = 1;
        if ( class_exists( 'WC_Subscriptions_Product' ) ) {
            $billing_period   = WC_Subscriptions_Product::get_period( $product );
            $billing_interval = WC_Subscriptions_Product::get_interval( $product );
        } else {
            $billing_period   = $product->get_meta( '_subscription_period' );
            $billing_interval = absint( $product->get_meta( '_subscription_period_interval' ) );
        }
        if ( empty( $billing_interval ) ) {
            $billing_interval = 1;
        }
        
        if ( $billing_period ) {
            if ( 1 === absint( $billing_interval ) ) {
                $period = '/' . $billing_period;
            } else {
                $period = '/every ' . $billing_interval . ' ' . $billing_period . 's';
            }
        }
    }

    // Resolve dynamic free trial Badge (7-Day vs 14-Day based on Parents Club eligibility)
    $badge_text = '';
    if ( ! empty( $attributes['badge_text'] ) ) {
        $badge_text = esc_html( $attributes['badge_text'] );
    } elseif ( $product ) {
        // Get the baseline trial length configured in the database (e.g. 7 days)
        $db_trial_length = absint( $product->get_meta( '_subscription_trial_length' ) );
        
        // Check eligibility for 14-day free trial extension
        $user_id     = get_current_user_id();
        $is_eligible = $user_id && emathsmart_is_eligible_parents_club_member( $user_id );
        
        $filtered_trial_length = $db_trial_length;
        if ( class_exists( 'WC_Subscriptions_Product' ) ) {
            $filtered_trial_length = WC_Subscriptions_Product::get_trial_length( $product );
        } elseif ( $is_eligible && $db_trial_length > 0 ) {
            $filtered_trial_length = 14;
        }
        
        if ( $is_eligible && $db_trial_length > 0 && $filtered_trial_length > $db_trial_length ) {
            // Display baseline WooCommerce trial crossed out and new trial next to it
            $badge_text = sprintf(
                '<span class="pc-plan-badge-crossed" style="text-decoration: line-through; opacity: 0.6; margin-right: 8px; font-weight: 500;">%d-Day Free Trial</span>%d-Day Free Trial',
                $db_trial_length,
                $filtered_trial_length
            );
        } elseif ( $filtered_trial_length > 0 ) {
            $badge_text = sprintf( esc_html__( '%d-Day Free Trial', 'book-junky' ), $filtered_trial_length );
        }
    }

    // Parse repeatable checklist features
    $list_data = array();
    if ( ! empty( $attributes['list_items'] ) ) {
        if ( function_exists( 'vc_param_group_parse_atts' ) ) {
            $list_data = vc_param_group_parse_atts( $attributes['list_items'] );
        } else {
            $list_data = json_decode( urldecode( $attributes['list_items'] ), true );
        }
    }

    // Default features checklist fallback if empty
    if ( empty( $list_data ) ) {
        $list_data = array(
            array( 'item_text' => 'Full access to eMathSmart', 'icon_source' => 'default' ),
            array( 'item_text' => 'Interactive exercises & tools', 'icon_source' => 'default' ),
            array( 'item_text' => 'Printable worksheets', 'icon_source' => 'default' ),
            array( 'item_text' => 'Progress tracking & reports', 'icon_source' => 'default' ),
            array( 'item_text' => 'All credits included', 'icon_source' => 'default' ),
        );
    }

    // Closure to render checklist icons dynamically
    $render_item_icon = function( $item ) {
        $source = isset( $item['icon_source'] ) ? $item['icon_source'] : 'default';
        
        if ( 'brand' === $source ) {
            $brand_type = isset( $item['brand_icon_type'] ) ? $item['brand_icon_type'] : 'check';
            switch ( $brand_type ) {
                case 'gamepad':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect><path d="M6 12h12M12 6v12"></path></svg>';
                case 'idea':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A5 5 0 0 0 8 8c0 1.3.5 2.6 1.5 3.5.8.8 1.3 1.5 1.5 2.5"></path><line x1="9" y1="18" x2="15" y2="18"></line><line x1="10" y1="22" x2="14" y2="22"></line></svg>';
                case 'file':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>';
                case 'chart':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>';
                case 'star':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                case 'shield':
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>';
                case 'check':
                default:
                    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
            }
        } elseif ( 'library' === $source ) {
            $lib = isset( $item['icon_library'] ) ? $item['icon_library'] : 'fontawesome';
            if ( 'fontawesome' === $lib && ! empty( $item['icon_fontawesome'] ) ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                return '<i class="' . esc_attr( $item['icon_fontawesome'] ) . '" aria-hidden="true"></i>';
            } elseif ( 'linecons' === $lib && ! empty( $item['icon_linecons'] ) ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                return '<i class="' . esc_attr( $item['icon_linecons'] ) . '" aria-hidden="true"></i>';
            }
        } elseif ( 'custom' === $source && ! empty( $item['custom_icon'] ) ) {
            $custom_url = '';
            if ( is_numeric( $item['custom_icon'] ) ) {
                $img_src = wp_get_attachment_image_url( absint( $item['custom_icon'] ), 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src;
                }
            } else {
                $custom_url = $item['custom_icon'];
            }
            if ( ! empty( $custom_url ) ) {
                return '<img src="' . esc_url( $custom_url ) . '" class="pc-plan-custom-icon-img" alt="" style="width:16px;height:16px;object-fit:contain;vertical-align:middle;display:inline-block;" />';
            }
        }
        
        // Default checkmark fallback
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
    };

    // Resolve Button Label Override / Dynamic CTA button label
    $button_text = '';
    if ( ! empty( $attributes['button_text'] ) ) {
        $button_text = esc_html( $attributes['button_text'] );
    } else {
        $trial_length = 0;
        if ( $product ) {
            if ( class_exists( 'WC_Subscriptions_Product' ) ) {
                $trial_length = WC_Subscriptions_Product::get_trial_length( $product );
            } else {
                $trial_length = absint( $product->get_meta( '_subscription_trial_length' ) );
            }
            
            $user_id     = get_current_user_id();
            $is_eligible = $user_id && emathsmart_is_eligible_parents_club_member( $user_id );
            if ( $is_eligible && $trial_length > 0 ) {
                $trial_length = 14;
            }
        }
        if ( $trial_length > 0 ) {
            $button_text = sprintf( esc_html__( 'Start %d-Day Free Trial', 'book-junky' ), $trial_length );
        } else {
            $button_text = esc_html__( 'Subscribe Now', 'book-junky' );
        }
    }

    // Resolve Button Redirect URL
    $button_url   = '#';
    $link_target  = '';
    $button_title = $button_text;

    if ( ! empty( $attributes['button_link'] ) ) {
        $link_parsed = vc_build_link( $attributes['button_link'] );
        if ( ! empty( $link_parsed['url'] ) ) {
            $button_url = $link_parsed['url'];
        }
        if ( ! empty( $link_parsed['title'] ) ) {
            $button_title = $link_parsed['title'];
        }
        if ( ! empty( $link_parsed['target'] ) ) {
            $link_target = $link_parsed['target'];
        }
    } elseif ( $product_id ) {
        $button_url = home_url( '/subscription/?add-to-cart-login=' . $product_id );
    }

    // Pricing card class styling configurations
    $card_classes = array( 'pc-plan-card' );
    if ( 'yes' === $attributes['highlighted_border'] ) {
        $card_classes[] = 'pc-plan-annual';
    } else {
        $card_classes[] = 'pc-plan-monthly';
    }
    $card_class_str = implode( ' ', $card_classes );

    ob_start();
    ?>
    <div class="<?php echo esc_attr( $card_class_str ); ?>">
        <?php if ( 'yes' === $attributes['best_value'] ) : ?>
            <span class="best-value-badge"><?php esc_html_e( 'Best Value', 'book-junky' ); ?></span>
        <?php endif; ?>

        <?php if ( ! empty( $title ) ) : ?>
            <h3 class="pc-plan-title"><?php echo $title; ?></h3>
        <?php endif; ?>

        <?php if ( ! empty( $price ) ) : ?>
            <div class="pc-plan-price">
                <?php echo $price; ?>
                <?php if ( ! empty( $period ) ) : ?>
                    <span class="pc-plan-period"><?php echo $period; ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $badge_text ) ) : ?>
            <span class="pc-plan-badge"><?php echo $badge_text; ?></span>
        <?php endif; ?>

        <?php if ( ! empty( $list_data ) ) : ?>
            <ul class="pc-plan-list">
                <?php foreach ( $list_data as $item ) : 
                    $item_txt = isset( $item['item_text'] ) ? esc_html( $item['item_text'] ) : '';
                    if ( empty( $item_txt ) ) {
                        continue;
                    }
                    ?>
                    <li class="pc-plan-item">
                        <span class="pc-plan-item-icon">
                            <?php echo $render_item_icon( $item ); ?>
                        </span>
                        <p><?php echo $item_txt; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a href="<?php echo esc_url( $button_url ); ?>" class="pc-plan-btn" <?php echo ! empty( $link_target ) ? 'target="' . esc_attr( $link_target ) . '"' : ''; ?>>
            <?php echo esc_html( $button_title ); ?>
        </a>
    </div>
    <?php
    return ob_get_clean();
}

// -----------------------------------------------------------------------------
// SECTION 5: [parents_club_cta_banner] Shortcode Handler
// -----------------------------------------------------------------------------

// Register [parents_club_cta_banner] Shortcode
add_shortcode( 'parents_club_cta_banner', 'idl_loader_parents_club_cta_banner_shortcode' );

function idl_loader_parents_club_cta_banner_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'banner_image' => '',
        'title'        => esc_html__( "Ready to Support Your Child's Success?", "book-junky" ),
        'description'  => "Join 40,000+ Canadian parents and get trusted resources,\nworkbook savings, and learning support — for free!",
        'btn1_text'    => esc_html__( "Join Parents' Club – It's Free!", "book-junky" ),
        'btn1_link'    => '',
        'btn2_text'    => esc_html__( "Explore eMathSmart", "book-junky" ),
        'btn2_link'    => '',
    ), $atts );

    // Enqueue the modular stylesheet natively
    wp_enqueue_style( 'parents-club-cta-banner', plugins_url( 'templates/css/parents-club-cta-banner.css', __FILE__ ) );

    // Resolve Banner Image URL
    $image_url = '';
    if ( ! empty( $attributes['banner_image'] ) ) {
        if ( is_numeric( $attributes['banner_image'] ) ) {
            $image_url = wp_get_attachment_image_url( absint( $attributes['banner_image'] ), 'full' );
        } else {
            $image_url = $attributes['banner_image'];
        }
    }
    if ( empty( $image_url ) ) {
        $image_url = plugins_url( 'templates/images/parents-club-cta-tablet.png', __FILE__ );
    }

    // Resolve Button 1 (White) VC Link
    $btn1_title  = $attributes['btn1_text'];
    $btn1_url    = '#signup';
    $btn1_target = '';
    if ( ! empty( $attributes['btn1_link'] ) && function_exists( 'vc_build_link' ) ) {
        $link_data = vc_build_link( $attributes['btn1_link'] );
        if ( ! empty( $link_data['url'] ) ) {
            $btn1_url = $link_data['url'];
        }
        if ( ! empty( $link_data['title'] ) ) {
            $btn1_title = $link_data['title'];
        }
        if ( ! empty( $link_data['target'] ) ) {
            $btn1_target = $link_data['target'];
        }
    }

    // Resolve Button 2 (Orange) VC Link
    $btn2_title  = $attributes['btn2_text'];
    $btn2_url    = '#parents-club-section-4';
    $btn2_target = '';
    if ( ! empty( $attributes['btn2_link'] ) && function_exists( 'vc_build_link' ) ) {
        $link_data = vc_build_link( $attributes['btn2_link'] );
        if ( ! empty( $link_data['url'] ) ) {
            $btn2_url = $link_data['url'];
        }
        if ( ! empty( $link_data['title'] ) ) {
            $btn2_title = $link_data['title'];
        }
        if ( ! empty( $link_data['target'] ) ) {
            $btn2_target = $link_data['target'];
        }
    }

    // Prepare line breaks in description
    $description = nl2br( esc_html( $attributes['description'] ) );
    // Support basic inline HTML if tags were already passed as characters
    $description = str_replace( array( '&lt;br&gt;', '&lt;br /&gt;' ), '<br>', $description );

    ob_start();
    ?>
    <section id="parents-club-cta-banner">
        <div class="cta-banner-wrapper">
            <!-- Left: Image Column -->
            <div class="cta-banner-image-col">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $attributes['title'] ); ?>">
            </div>
            <!-- Right: Content Column with Text & Buttons -->
            <div class="cta-banner-content-col">
                <div class="cta-banner-text">
                    <h2><?php echo esc_html( $attributes['title'] ); ?></h2>
                    <p><?php echo $description; ?></p>
                </div>
                <div class="cta-banner-actions">
                    <?php if ( ! empty( $btn1_title ) ) : ?>
                        <a href="<?php echo esc_url( $btn1_url ); ?>" class="btn-join-club" <?php echo ! empty( $btn1_target ) ? 'target="' . esc_attr( $btn1_target ) . '"' : ''; ?>>
                            <?php echo esc_html( $btn1_title ); ?>
                        </a>
                    <?php endif; ?>
                    <?php if ( ! empty( $btn2_title ) ) : ?>
                        <a href="<?php echo esc_url( $btn2_url ); ?>" class="btn-explore-emath" <?php echo ! empty( $btn2_target ) ? 'target="' . esc_attr( $btn2_target ) . '"' : ''; ?>>
                            <?php echo esc_html( $btn2_title ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}


// -----------------------------------------------------------------------------
// SECTION 6: [parents_club_member_welcome] Shortcode Handler
// -----------------------------------------------------------------------------

// Register [parents_club_member_welcome] Shortcode
add_shortcode( 'parents_club_member_welcome', 'idl_loader_parents_club_member_welcome_shortcode' );

function idl_loader_parents_club_member_welcome_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'title_template' => "Hi, {user_name}! [wave]\nWelcome back!",
        'subtitle'       => "We’re here to support your child’s learning journey.",
        'bg_image'       => '',
        'attributes'     => '',
    ), $atts );

    // Enqueue the modular stylesheet natively
    wp_enqueue_style( 'parents-club-member-welcome', plugins_url( 'templates/css/parents-club-member-welcome.css', __FILE__ ) );

    // Fetch logged-in user name
    $current_user = wp_get_current_user();
    $user_name = '';
    if ( $current_user && $current_user->ID ) {
        $user_name = $current_user->user_firstname;
        if ( empty( $user_name ) ) {
            $user_name = $current_user->display_name;
        }
    }
    if ( empty( $user_name ) ) {
        $user_name = esc_html__( 'Member', 'book-junky' );
    }

    // Process title template
    $wave_html = '<span class="wave">👋</span>';
    $title = $attributes['title_template'];
    
    // Replace username template using regex to support backticks, brackets, braces, and HTML entities
    $user_pattern = '/(?:`|&#96;|&#x60;)?(?:\{|\&\#123;|\&\#x7b;|\&\#x7B;)(?:`|&#96;|&#x60;)?user_name(?:`|&#96;|&#x60;)?(?:\}|\&\#125;|\&\#x7d;|\&\#x7D;)(?:`|&#96;|&#x60;)?/i';
    $title = preg_replace( $user_pattern, esc_html( $user_name ), $title );
    
    // Replace wave template using regex to support backticks, brackets, braces, and HTML entities
    $wave_pattern = '/(?:`|&#96;|&#x60;)?(?:\[|\{|\&\#91;|\&\#123;|\&\#x5b;|\&\#x7b;)(?:`|&#96;|&#x60;)?wave(?:`|&#96;|&#x60;)?(?:\]|\}|\&\#93;|\&\#125;|\&\#x5d;|\&\#x7d;)(?:`|&#96;|&#x60;)?/i';
    $title = preg_replace( $wave_pattern, $wave_html, $title );
    
    $title = nl2br( esc_html( $title ) );
    
    // Allow the wave span and basic line breaks to be rendered as HTML by reversing the esc_html
    $title = str_replace( esc_html( $wave_html ), $wave_html, $title );
    $title = str_replace( 
        array( 
            '&lt;br&gt;', '&lt;br /&gt;', '&lt;br/&gt;',
            '&amp;lt;br&amp;gt;', '&amp;lt;br /&amp;gt;', '&amp;lt;br/&amp;gt;'
        ), 
        '<br>', 
        $title 
    );

    $subtitle = esc_html( $attributes['subtitle'] );

    // Custom background styling if custom image uploaded
    $welcome_id = uniqid( 'welcome-card-' );
    $custom_style = '';
    if ( ! empty( $attributes['bg_image'] ) ) {
        $image_url = '';
        if ( is_numeric( $attributes['bg_image'] ) ) {
            $image_url = wp_get_attachment_image_url( absint( $attributes['bg_image'] ), 'full' );
        } else {
            $image_url = $attributes['bg_image'];
        }
        if ( ! empty( $image_url ) ) {
            $custom_style = "<style>
                #{$welcome_id} {
                    background-image: linear-gradient(to right, #ffffff 0%, #ffffff 40%, rgba(255, 255, 255, 0.95) 55%, rgba(255, 255, 255, 0) 80%), url('" . esc_url( $image_url ) . "') !important;
                }
                @media (max-width: 768px) {
                    #{$welcome_id} {
                        background-image: linear-gradient(to bottom, #ffffff 0%, #ffffff 50%, rgba(255, 255, 255, 0.9) 65%, rgba(255, 255, 255, 0) 100%), url('" . esc_url( $image_url ) . "') !important;
                    }
                }
            </style>";
        }
    }

    // Helper closure to render attribute icons
    $render_attr_icon = function( $source, $brand_type, $library, $fa_icon, $li_icon, $custom_icon_id ) {
        if ( $source === 'custom' && ! empty( $custom_icon_id ) ) {
            $custom_url = '';
            if ( is_numeric( $custom_icon_id ) ) {
                $img_src = wp_get_attachment_image_src( $custom_icon_id, 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src[0];
                }
            } else {
                $custom_url = $custom_icon_id;
            }
            if ( ! empty( $custom_url ) ) {
                return '<img src="' . esc_url( $custom_url ) . '" alt="Attribute Icon" style="width: 22px; height: 22px; display: block; object-fit: contain;">';
            }
        }

        if ( $source === 'library' ) {
            if ( $library === 'linecons' ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                $icon_class = ! empty( $li_icon ) ? esc_attr( $li_icon ) : 'vc_li vc_li-user';
                return '<i class="' . $icon_class . '" style="font-size: 22px; display: inline-flex; align-items: center; justify-content: center; height: 22px; width: 22px;"></i>';
            } else {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                $icon_class = ! empty( $fa_icon ) ? esc_attr( $fa_icon ) : 'fa fa-user';
                return '<i class="' . $icon_class . '" style="font-size: 22px; display: inline-flex; align-items: center; justify-content: center; height: 22px; width: 22px;"></i>';
            }
        }

        switch ( $brand_type ) {
            case 'maple_star':
                return '<svg viewBox="0 0 32 32" fill="currentColor">
                            <path d="M16.361,31h-0.72v-6.592l-7.596,0.949c-0.124,0.015-0.245-0.034-0.323-0.129
        c-0.079-0.096-0.103-0.225-0.063-0.343l0.917-2.753l-7.792-5.845c-0.107-0.081-0.161-0.213-0.14-0.346s0.115-0.242,0.242-0.284
        l2.606-0.869L1.678,11.16c-0.063-0.126-0.047-0.277,0.041-0.386s0.23-0.16,0.368-0.124l3.677,0.919l0.895-2.684
        c0.039-0.119,0.137-0.208,0.259-0.237C7.039,8.62,7.167,8.656,7.255,8.744l4.126,4.125L9.649,5.078
        c-0.028-0.126,0.014-0.259,0.11-0.345c0.097-0.087,0.234-0.115,0.355-0.074l2.727,0.909l2.852-4.752c0.13-0.217,0.487-0.217,0.617,0
        l2.852,4.752l2.727-0.909c0.126-0.041,0.259-0.013,0.355,0.074c0.097,0.086,0.139,0.219,0.11,0.345l-1.731,7.793l4.125-4.125
        c0.088-0.088,0.215-0.125,0.338-0.096c0.121,0.029,0.219,0.118,0.259,0.237l0.895,2.684l3.677-0.919
        c0.133-0.033,0.28,0.014,0.368,0.124c0.088,0.109,0.104,0.26,0.041,0.386L28.51,14.79l2.606,0.869
        c0.127,0.042,0.22,0.151,0.241,0.284c0.021,0.133-0.032,0.265-0.14,0.346l-7.792,5.844l0.918,2.754
        c0.039,0.118,0.015,0.247-0.063,0.343c-0.077,0.096-0.192,0.146-0.323,0.129l-7.596-0.949V31z"/>
                           </svg>';
            case 'shield':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>';
            case 'tag':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>';
            case 'idea':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="19.78" y1="4.22" x2="18.36" y2="5.64"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1.3.5 2.6 1.5 3.5.8.8 1.3 1.5 1.5 2.5"></path>
                            <line x1="9" y1="18" x2="15" y2="18"></line>
                            <line x1="10" y1="22" x2="14" y2="22"></line>
                        </svg>';
            case 'gift':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 12 20 22 4 22 4 12"></polyline>
                            <rect x="2" y="7" width="20" height="5"></rect>
                            <line x1="12" y1="22" x2="12" y2="7"></line>
                            <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path>
                            <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path>
                        </svg>';
            case 'file':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>';
            case 'computer':
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>';
            case 'users':
            default:
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>';
        }
    };

    // Parse checklist attributes
    $attributes_data = array();
    if ( ! empty( $attributes['attributes'] ) ) {
        if ( function_exists( 'vc_param_group_parse_atts' ) ) {
            $attributes_data = vc_param_group_parse_atts( $attributes['attributes'] );
        } else {
            $attributes_data = json_decode( urldecode( $attributes['attributes'] ), true );
        }
    }

    $active_attributes = array();
    if ( is_array( $attributes_data ) ) {
        foreach ( $attributes_data as $item ) {
            $bold_text = isset( $item['bold_text'] ) ? $item['bold_text'] : '';
            if ( ! empty( $bold_text ) ) {
                $subtext         = isset( $item['subtext'] ) ? $item['subtext'] : '';
                $icon_source     = isset( $item['icon_source'] ) ? $item['icon_source'] : 'brand';
                $brand_icon_type = isset( $item['brand_icon_type'] ) ? $item['brand_icon_type'] : 'users';
                $icon_library    = isset( $item['icon_library'] ) ? $item['icon_library'] : 'fontawesome';
                $fa_icon         = isset( $item['icon_fontawesome'] ) ? $item['icon_fontawesome'] : '';
                $li_icon         = isset( $item['icon_linecons'] ) ? $item['icon_linecons'] : '';
                $custom_icon     = isset( $item['custom_icon'] ) ? $item['custom_icon'] : '';

                $active_attributes[] = array(
                    'bold_text' => esc_html( $bold_text ),
                    'subtext'   => esc_html( $subtext ),
                    'icon'      => $render_attr_icon( $icon_source, $brand_icon_type, $icon_library, $fa_icon, $li_icon, $custom_icon )
                );
            }
        }
    }

    ob_start();
    echo $custom_style;
    ?>
    <div id="<?php echo esc_attr( $welcome_id ); ?>" class="member-welcome-column">
        <div class="member-welcome-content">
            <h1 class="member-welcome-title"><?php echo $title; ?></h1>
            <?php if ( ! empty( $subtitle ) ) : ?>
                <p class="member-welcome-subtitle"><?php echo $subtitle; ?></p>
            <?php endif; ?>
        </div>
        
        <?php if ( ! empty( $active_attributes ) ) : ?>
            <div class="member-attribute-list">
                <?php foreach ( $active_attributes as $attr ) : ?>
                    <div class="attribute-item">
                        <div class="attribute-icon">
                            <?php echo $attr['icon']; ?>
                        </div>
                        <div class="attribute-text">
                            <strong><?php echo $attr['bold_text']; ?></strong>
                            <?php if ( ! empty( $attr['subtext'] ) ) : ?>
                                <span><?php echo $attr['subtext']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}


// -----------------------------------------------------------------------------
// SECTION 7: [parents_club_member_quick_links] Shortcode Handler
// -----------------------------------------------------------------------------

// Register [parents_club_member_quick_links] Shortcode
add_shortcode( 'parents_club_member_quick_links', 'idl_loader_parents_club_member_quick_links_shortcode' );

function idl_loader_parents_club_member_quick_links_shortcode( $atts ) {
    $attributes = shortcode_atts( array(
        'title'   => 'Quick Links',
        'buttons' => '',
    ), $atts );

    // Enqueue the modular stylesheet natively
    wp_enqueue_style( 'parents-club-member-quick-links', plugins_url( 'templates/css/parents-club-member-quick-links.css', __FILE__ ) );

    $title = esc_html( $attributes['title'] );

    // Helper closure to render button icons
    $render_btn_icon = function( $source, $library, $fa_icon, $li_icon, $custom_icon_id ) {
        if ( $source === 'none' ) {
            return '';
        }
        if ( $source === 'custom' && ! empty( $custom_icon_id ) ) {
            $custom_url = '';
            if ( is_numeric( $custom_icon_id ) ) {
                $img_src = wp_get_attachment_image_src( $custom_icon_id, 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src[0];
                }
            } else {
                $custom_url = $custom_icon_id;
            }
            if ( ! empty( $custom_url ) ) {
                return '<span class="btn-icon"><img src="' . esc_url( $custom_url ) . '" alt="" style="width: 16px; height: 16px; display: block; object-fit: contain;"></span>';
            }
        }

        if ( $source === 'library' ) {
            if ( $library === 'linecons' ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                $icon_class = ! empty( $li_icon ) ? esc_attr( $li_icon ) : 'vc_li vc_li-chevron-right';
                return '<span class="btn-icon"><i class="' . $icon_class . '" style="font-size: 14px; color: currentColor; display: inline-flex; align-items: center; justify-content: center;"></i></span>';
            } else {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                $icon_class = ! empty( $fa_icon ) ? esc_attr( $fa_icon ) : 'fa fa-angle-right';
                return '<span class="btn-icon"><i class="' . $icon_class . '" style="font-size: 14px; color: currentColor; display: inline-flex; align-items: center; justify-content: center;"></i></span>';
            }
        }

        // Default right chevron
        return '<span class="btn-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </span>';
    };

    // Parse buttons param group
    $buttons_data = array();
    if ( ! empty( $attributes['buttons'] ) ) {
        if ( function_exists( 'vc_param_group_parse_atts' ) ) {
            $buttons_data = vc_param_group_parse_atts( $attributes['buttons'] );
        } else {
            $buttons_data = json_decode( urldecode( $attributes['buttons'] ), true );
        }
    }

    // Default fallback
    if ( empty( $buttons_data ) ) {
        $buttons_data = array(
            array(
                'btn_text'    => 'Our Canadian Teachers',
                'btn_link'    => '/nominate-a-teacher',
                'icon_source' => 'default',
            ),
            array(
                'btn_text'    => 'Learning Tips',
                'btn_link'    => '/blog',
                'icon_source' => 'default',
            ),
            array(
                'btn_text'    => 'Free Worksheets',
                'btn_link'    => '/download-centre',
                'icon_source' => 'default',
            ),
        );
    }

    $rendered_buttons = array();
    if ( is_array( $buttons_data ) ) {
        foreach ( $buttons_data as $btn ) {
            $btn_text        = isset( $btn['btn_text'] ) ? $btn['btn_text'] : '';
            $btn_link        = isset( $btn['btn_link'] ) ? $btn['btn_link'] : '';
            $icon_source     = isset( $btn['icon_source'] ) ? $btn['icon_source'] : 'default';
            $icon_library    = isset( $btn['icon_library'] ) ? $btn['icon_library'] : 'fontawesome';
            $fa_icon         = isset( $btn['icon_fontawesome'] ) ? $btn['icon_fontawesome'] : '';
            $li_icon         = isset( $btn['icon_linecons'] ) ? $btn['icon_linecons'] : '';
            $custom_icon     = isset( $btn['custom_icon'] ) ? $btn['custom_icon'] : '';

            $btn_url    = '#';
            $btn_title  = $btn_text;
            $btn_target = '';

            if ( ! empty( $btn_link ) ) {
                if ( strpos( $btn_link, 'url:' ) !== false || strpos( $btn_link, 'title:' ) !== false ) {
                    $link_data = vc_build_link( $btn_link );
                    $btn_url   = isset( $link_data['url'] ) ? $link_data['url'] : '#';
                    $btn_target = isset( $link_data['target'] ) ? $link_data['target'] : '';
                    if ( empty( $btn_title ) && ! empty( $link_data['title'] ) ) {
                        $btn_title = $link_data['title'];
                    }
                } else {
                    $btn_url = $btn_link;
                }
            }

            if ( empty( $btn_title ) ) {
                continue;
            }

            $rendered_buttons[] = array(
                'url'    => esc_url( $btn_url ),
                'title'  => esc_html( $btn_title ),
                'target' => esc_attr( $btn_target ),
                'icon'   => $render_btn_icon( $icon_source, $icon_library, $fa_icon, $li_icon, $custom_icon )
            );
        }
    }

    ob_start();
    ?>
    <section id="parents-club-member-quick-links">
        <div class="quick-links-bar">
            <?php if ( ! empty( $title ) ) : ?>
                <div class="quick-links-title-wrapper">
                    <h2 class="quick-links-title"><?php echo $title; ?></h2>
                </div>
                <div class="quick-links-divider"></div>
            <?php endif; ?>
            
            <?php if ( ! empty( $rendered_buttons ) ) : ?>
                <div class="quick-links-buttons">
                    <?php foreach ( $rendered_buttons as $button ) : ?>
                        <a href="<?php echo $button['url']; ?>" class="quick-link-btn" <?php echo ! empty( $button['target'] ) ? 'target="' . $button['target'] . '"' : ''; ?>>
                            <span class="btn-text"><?php echo $button['title']; ?></span>
                            <?php echo $button['icon']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}


// -----------------------------------------------------------------------------
// SECTION 8: [parents_club_member_subscription] Shortcode Handler
// -----------------------------------------------------------------------------

/**
 * Helper function to retrieve payment method card details for a subscription.
 */
function idl_loader_get_subscription_payment_details( $subscription ) {
    $card_type = '';
    $card_last4 = '';
    $has_card = false;

    // Check payment tokens on the subscription
    if ( method_exists( $subscription, 'get_payment_tokens' ) ) {
        $tokens = $subscription->get_payment_tokens();
        if ( ! empty( $tokens ) ) {
            foreach ( $tokens as $token_id ) {
                $token = WC_Payment_Tokens::get( $token_id );
                if ( $token && $token instanceof WC_Payment_Token_CC ) {
                    $card_type = strtoupper( $token->get_card_type() );
                    $card_last4 = $token->get_last4();
                    $has_card = true;
                    break;
                }
            }
        }
    }

    // Fallback: Check Stripe metadata keys
    if ( ! $has_card ) {
        $brand = $subscription->get_meta( '_stripe_card_brand', true );
        $last4 = $subscription->get_meta( '_stripe_card_last4', true );
        if ( ! empty( $brand ) && ! empty( $last4 ) ) {
            $card_type = strtoupper( $brand );
            $card_last4 = $last4;
            $has_card = true;
        } else {
            $parent = $subscription->get_parent();
            if ( $parent ) {
                $brand = $parent->get_meta( '_stripe_card_brand', true );
                $last4 = $parent->get_meta( '_stripe_card_last4', true );
                if ( ! empty( $brand ) && ! empty( $last4 ) ) {
                    $card_type = strtoupper( $brand );
                    $card_last4 = $last4;
                    $has_card = true;
                }
            }
        }
    }

    // Fallback: Check payment method title
    if ( ! $has_card ) {
        $payment_title = $subscription->get_payment_method_title();
        if ( empty( $payment_title ) && $subscription->get_parent() ) {
            $payment_title = $subscription->get_parent()->get_payment_method_title();
        }
        
        if ( ! empty( $payment_title ) ) {
            if ( stripos( $payment_title, 'stripe' ) !== false || stripos( $payment_title, 'credit' ) !== false || stripos( $payment_title, 'card' ) !== false ) {
                $card_type = 'Credit Card';
                $card_last4 = '****';
            } else {
                $card_type = $payment_title;
                $card_last4 = '';
            }
        }
    }

    return array(
        'card_type'  => $card_type,
        'card_last4' => $card_last4,
    );
}

add_shortcode( 'parents_club_member_subscription', 'idl_loader_parents_club_member_subscription_shortcode' );

function idl_loader_parents_club_member_subscription_shortcode( $atts ) {


    $attributes = shortcode_atts( array(
        'logo_image'      => '',
        'status_text'     => 'Active',
        'portal_btn_text' => 'Go to eMathSmart',
        'portal_btn_link' => '',
        'title'           => 'Your eMathSmart Subscription',
        'tablet_image'    => '',
        'details_list'    => '',
        'included_title'  => "What's Included",
        'included_list'   => '',
        'actions_list'    => '',
    ), $atts );

    // Enqueue subscription stylesheet
    wp_enqueue_style( 'parents-club-dashboard-subscription', plugins_url( 'templates/css/parents-club-dashboard-subscription.css', __FILE__ ) );

    // Resolve Logo Image
    $logo_url = '';
    if ( ! empty( $attributes['logo_image'] ) ) {
        if ( is_numeric( $attributes['logo_image'] ) ) {
            $img_src = wp_get_attachment_image_src( $attributes['logo_image'], 'full' );
            if ( $img_src ) {
                $logo_url = $img_src[0];
            }
        } else {
            $logo_url = $attributes['logo_image'];
        }
    }
    if ( empty( $logo_url ) ) {
        $logo_url = plugins_url( 'templates/images/eMathSmart_logo_FINAL .png', __FILE__ );
    }

    // Resolve Tablet Image
    $tablet_url = '';
    if ( ! empty( $attributes['tablet_image'] ) ) {
        if ( is_numeric( $attributes['tablet_image'] ) ) {
            $img_src = wp_get_attachment_image_src( $attributes['tablet_image'], 'full' );
            if ( $img_src ) {
                $tablet_url = $img_src[0];
            }
        } else {
            $tablet_url = $attributes['tablet_image'];
        }
    }
    if ( empty( $tablet_url ) ) {
        $tablet_url = plugins_url( 'templates/images/subscription-active.png', __FILE__ );
    }

    // Resolve Portal Button Link
    $base_portal_url    = '#';
    $portal_target = '';
    if ( ! empty( $attributes['portal_btn_link'] ) ) {
        if ( strpos( $attributes['portal_btn_link'], 'url:' ) !== false || strpos( $attributes['portal_btn_link'], 'title:' ) !== false ) {
            $link_data = vc_build_link( $attributes['portal_btn_link'] );
            $base_portal_url = isset( $link_data['url'] ) ? $link_data['url'] : '#';
            $portal_target = isset( $link_data['target'] ) ? $link_data['target'] : '';
        } else {
            $base_portal_url = $attributes['portal_btn_link'];
        }
    }

    // Fetch dynamic database content if user is logged in
    $user_id = get_current_user_id();
    $formatted_subs = array();

    if ( $user_id ) {
        $subscriptions = function_exists( 'wcs_get_users_subscriptions' ) ? wcs_get_users_subscriptions( $user_id ) : array();
        
        if ( ! empty( $subscriptions ) ) {
            foreach ( $subscriptions as $subscription ) {
                $sub_status = $subscription->get_status();
                $is_in_trial = ( 'active' === $sub_status && $subscription->get_date( 'trial_end' ) > 0 && time() < strtotime( $subscription->get_date( 'trial_end' ) ) );
                
                $portal_url = $base_portal_url;
                $status_pill_text = $attributes['status_text'];
                $details_data = array();
                $actions_data = array();

                // Subscription Type (Product Name)
                $sub_type = 'eMathSmart Subscription';
                foreach ( $subscription->get_items() as $item ) {
                    $sub_type = $item->get_name();
                    break;
                }

                $plan_name = 'Full Access Plan';

                if ( 'active' === $sub_status ) {
                    $status_val = $is_in_trial ? 'On Free Trial' : 'Active';
                    $status_pill_text = $is_in_trial ? 'Free Trial' : 'Active';
                } elseif ( 'pending-cancel' === $sub_status ) {
                    $status_val = 'Pending Cancellation';
                    $status_pill_text = 'Pending Cancellation';
                } elseif ( 'on-hold' === $sub_status ) {
                    $status_val = 'On Hold';
                    $status_pill_text = 'On Hold';
                } else {
                    $status_val = ucfirst( $sub_status );
                    $status_pill_text = ucfirst( $sub_status );
                }

                $details_data[] = array(
                    'label'                => 'Subscription Type',
                    'value'                => $sub_type,
                    'value_type'           => 'normal',
                    'icon_source'          => 'brand',
                    'predefined_icon_type' => 'checkmark',
                );

                $details_data[] = array(
                    'label'                => 'Plan',
                    'value'                => $plan_name,
                    'value_type'           => 'normal',
                    'icon_source'          => 'brand',
                    'predefined_icon_type' => 'checkmark',
                );

                $details_data[] = array(
                    'label'                => 'Status',
                    'value'                => $status_val,
                    'value_type'           => 'pill',
                    'icon_source'          => 'brand',
                    'predefined_icon_type' => 'checkmark',
                );

                if ( $is_in_trial ) {
                    $trial_end_time = strtotime( $subscription->get_date( 'trial_end' ) );
                    $days_left = ceil( ( $trial_end_time - time() ) / DAY_IN_SECONDS );
                    if ( $days_left < 0 ) {
                        $days_left = 0;
                    }
                    $trial_ends_val = date_i18n( get_option( 'date_format' ), $trial_end_time ) . ' (' . sprintf( _n( '%s day left', '%s days left', $days_left, 'book-junky' ), $days_left ) . ')';

                    $details_data[] = array(
                        'label'                => 'Trial Ends',
                        'value'                => $trial_ends_val,
                        'value_type'           => 'blue_highlight',
                        'icon_source'          => 'brand',
                        'predefined_icon_type' => 'checkmark',
                    );
                }

                if ( in_array( $sub_status, array( 'active', 'on-hold' ) ) && ! $is_in_trial ) {
                    $next_payment = $subscription->get_date( 'next_payment' );
                    if ( $next_payment > 0 ) {
                        $next_payment_time = strtotime( $next_payment );
                        $details_data[] = array(
                            'label'                => 'Next Billing Date',
                            'value'                => date_i18n( get_option( 'date_format' ), $next_payment_time ),
                            'value_type'           => 'normal',
                            'icon_source'          => 'brand',
                            'predefined_icon_type' => 'checkmark',
                        );
                    }
                } elseif ( 'pending-cancel' === $sub_status ) {
                    $end_date = $subscription->get_date( 'end' );
                    if ( $end_date > 0 ) {
                        $end_date_time = strtotime( $end_date );
                        $details_data[] = array(
                            'label'                => 'Expires On',
                            'value'                => date_i18n( get_option( 'date_format' ), $end_date_time ),
                            'value_type'           => 'normal',
                            'icon_source'          => 'brand',
                            'predefined_icon_type' => 'checkmark',
                        );
                    }
                } elseif ( in_array( $sub_status, array( 'cancelled', 'expired' ) ) ) {
                    $end_date = $subscription->get_date( 'end' );
                    if ( $end_date > 0 ) {
                        $end_date_time = strtotime( $end_date );
                        $details_data[] = array(
                            'label'                => 'Ended On',
                            'value'                => date_i18n( get_option( 'date_format' ), $end_date_time ),
                            'value_type'           => 'normal',
                            'icon_source'          => 'brand',
                            'predefined_icon_type' => 'checkmark',
                        );
                    }
                }

                $recurring_total = $subscription->get_total();
                $billing_period = $subscription->get_billing_period();
                $billing_interval = $subscription->get_billing_interval();
                $currency_symbol = get_woocommerce_currency_symbol( $subscription->get_currency() );

                $cycle_str = $currency_symbol . number_format( (float) $recurring_total, 2 ) . ' / ';
                if ( $billing_interval > 1 ) {
                    $cycle_str .= 'every ' . $billing_interval . ' ' . $billing_period . 's';
                } else {
                    $cycle_str .= $billing_period;
                }

                $details_data[] = array(
                    'label'                => 'Billing Cycle',
                    'value'                => $cycle_str,
                    'value_type'           => 'normal',
                    'icon_source'          => 'brand',
                    'predefined_icon_type' => 'checkmark',
                );

                $payment_details = idl_loader_get_subscription_payment_details( $subscription );
                if ( $payment_details['card_last4'] ) {
                    $next_charge_text = '';
                    if ( in_array( $sub_status, array( 'active', 'on-hold' ) ) ) {
                        $next_payment = $subscription->get_date( 'next_payment' );
                        if ( $next_payment > 0 ) {
                            $next_charge_text = 'Next Charge: ' . date_i18n( get_option( 'date_format' ), strtotime( $next_payment ) );
                        } elseif ( $is_in_trial ) {
                            $trial_end = $subscription->get_date( 'trial_end' );
                            if ( $trial_end > 0 ) {
                                $next_charge_text = 'Next Charge: ' . date_i18n( get_option( 'date_format' ), strtotime( $trial_end ) );
                            }
                        }
                    }

                    $details_data[] = array(
                        'label'                => 'Payment Method',
                        'value'                => 'ending in ' . $payment_details['card_last4'],
                        'value_type'           => 'card',
                        'card_type'            => $payment_details['card_type'],
                        'card_subtext'         => $next_charge_text,
                        'icon_source'          => 'brand',
                        'predefined_icon_type' => 'card',
                    );
                } else {
                    $details_data[] = array(
                        'label'                => 'Payment Method',
                        'value'                => $payment_details['card_type'] ? $payment_details['card_type'] : 'None',
                        'value_type'           => 'normal',
                        'icon_source'          => 'brand',
                        'predefined_icon_type' => 'card',
                    );
                }

                $access_grade = '<span class="pc-sub-access-val" data-sub-id="' . esc_attr( $subscription->get_id() ) . '"><span class="pc-skeleton-loader" style="display:inline-block; width:140px; height: 16px; background: rgba(0,0,0,0.08); border-radius:4px; animation: pc-pulse 1.5s infinite ease-in-out;"></span></span>';
                $credits_balance = 0;
                $sub_child_name = 'None';

                $details_data[] = array(
                    'label'                => 'Access',
                    'value'                => $access_grade,
                    'value_type'           => 'normal',
                    'icon_source'          => 'brand',
                    'predefined_icon_type' => 'user',
                );

                // Actions
                $update_payment_url = wp_nonce_url( add_query_arg( array( 'change_payment_method' => $subscription->get_id() ), $subscription->get_checkout_payment_url() ) );
                $actions_data[] = array(
                    'btn_text'             => 'Update Payment Method',
                    'btn_link'             => $update_payment_url,
                    'icon_source'          => 'brand',
                    'predefined_icon_type' => 'card',
                );

                $actions_data[] = array(
                    'btn_text'             => 'Add Another Subscription',
                    'btn_link'             => home_url( '/subscription/' ),
                    'icon_source'          => 'brand',
                    'predefined_icon_type' => 'plus',
                );

                $cancel_url = function_exists( 'wcs_get_users_change_status_link' )
                    ? wcs_get_users_change_status_link( $subscription->get_id(), 'cancelled' )
                    : $subscription->get_view_order_url();
                $actions_data[] = array(
                    'btn_text'             => 'Cancel Subscription',
                    'btn_link'             => $cancel_url,
                    'icon_source'          => 'brand',
                    'predefined_icon_type' => 'cancel',
                );

                // Override Portal URL for active subscriptions
                if ( in_array( $sub_status, array( 'active', 'on-hold' ) ) ) {
                    $portal_url = emathsmart_get_api_url();
                }

                $formatted_subs[] = array(
                    'id'               => $subscription->get_id(),
                    'sub_type'         => $sub_type,
                    'status_val'       => $status_val,
                    'status_pill_text' => $status_pill_text,
                    'portal_url'       => $portal_url,
                    'details_data'     => $details_data,
                    'actions_data'     => $actions_data,
                    'credits_balance'  => $credits_balance,
                    'child_name'       => $sub_child_name,
                );
            }
        } else {
            // Logged in, NO subscription
            $access_grade = '<span class="pc-sub-access-val" data-sub-id="none"><span class="pc-skeleton-loader" style="display:inline-block; width:140px; height: 16px; background: rgba(0,0,0,0.08); border-radius:4px; animation: pc-pulse 1.5s infinite ease-in-out;"></span></span>';

            $formatted_subs[] = array(
                'id'               => 'none',
                'sub_type'         => 'None',
                'status_val'       => 'Inactive',
                'status_pill_text' => 'Inactive',
                'portal_url'       => $base_portal_url,
                'details_data'     => array(
                    array(
                        'label'                => 'Subscription Type',
                        'value'                => 'None',
                        'value_type'           => 'normal',
                        'icon_source'          => 'brand',
                        'predefined_icon_type' => 'checkmark',
                    ),
                    array(
                        'label'                => 'Status',
                        'value'                => 'Inactive',
                        'value_type'           => 'pill',
                        'icon_source'          => 'brand',
                        'predefined_icon_type' => 'checkmark',
                    ),
                    array(
                        'label'                => 'Access',
                        'value'                => $access_grade,
                        'value_type'           => 'normal',
                        'icon_source'          => 'brand',
                        'predefined_icon_type' => 'user',
                    ),
                ),
                'actions_data'     => array(
                    array(
                        'btn_text'             => 'Subscribe Now',
                        'btn_link'             => home_url( '/subscription/' ),
                        'icon_source'          => 'brand',
                        'predefined_icon_type' => 'plus',
                    ),
                )
            );
        }
    } else {
        // Guest fallback (uses WPBakery parameter mapping)
        $details_data = array();
        $actions_data = array();
        if ( ! empty( $attributes['details_list'] ) ) {
            if ( function_exists( 'vc_param_group_parse_atts' ) ) {
                $details_data = vc_param_group_parse_atts( $attributes['details_list'] );
            } else {
                $details_data = json_decode( urldecode( $attributes['details_list'] ), true );
            }
        }
        if ( ! empty( $attributes['actions_list'] ) ) {
            if ( function_exists( 'vc_param_group_parse_atts' ) ) {
                $actions_data = vc_param_group_parse_atts( $attributes['actions_list'] );
            } else {
                $actions_data = json_decode( urldecode( $attributes['actions_list'] ), true );
            }
        }
        $formatted_subs[] = array(
            'id'               => 'guest',
            'sub_type'         => 'Guest',
            'status_val'       => $attributes['status_text'],
            'status_pill_text' => $attributes['status_text'],
            'portal_url'       => $base_portal_url,
            'details_data'     => $details_data,
            'actions_data'     => $actions_data,
        );
    }

    // Helper closure to render detail list icon
    $render_detail_icon = function( $source, $predefined_type, $library, $fa_icon, $li_icon, $custom_icon_id ) {
        if ( $source === 'custom' && ! empty( $custom_icon_id ) ) {
            $custom_url = '';
            if ( is_numeric( $custom_icon_id ) ) {
                $img_src = wp_get_attachment_image_src( $custom_icon_id, 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src[0];
                }
            } else {
                $custom_url = $custom_icon_id;
            }
            if ( ! empty( $custom_url ) ) {
                return '<span class="ic"><img src="' . esc_url( $custom_url ) . '" alt="" style="width: 22px; height: 22px; display: block; object-fit: contain;"></span>';
            }
        }

        if ( $source === 'library' ) {
            if ( $library === 'linecons' ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                $icon_class = ! empty( $li_icon ) ? esc_attr( $li_icon ) : 'vc_li vc_li-check';
                return '<span class="ic"><i class="' . $icon_class . '" style="font-size: 18px; color: #F2711C; display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px;"></i></span>';
            } else {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                $icon_class = ! empty( $fa_icon ) ? esc_attr( $fa_icon ) : 'fa fa-check';
                return '<span class="ic"><i class="' . $icon_class . '" style="font-size: 18px; color: #F2711C; display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px;"></i></span>';
            }
        }

        // Predefined SVGs
        if ( $predefined_type === 'card' ) {
            return '<span class="ic"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F2711C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="5" width="19" height="14" rx="3"/><path d="M2.5 9.5h19"/></svg></span>';
        }
        if ( $predefined_type === 'user' ) {
            return '<span class="ic"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F2711C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.6"/><path d="M5.5 19.5a6.5 6.5 0 0 1 13 0"/></svg></span>';
        }
        return '<span class="ic"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"><rect x="2.5" y="2.5" width="19" height="19" rx="6" stroke="#F2711C" stroke-width="2"/><path d="M7 12.3l3.3 3.3L17 8.6" stroke="#F2711C" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';
    };

    // Helper closure to render action icon
    $render_action_icon = function( $source, $predefined_type, $library, $fa_icon, $li_icon, $custom_icon_id ) {
        if ( $source === 'none' ) {
            return '';
        }
        if ( $source === 'custom' && ! empty( $custom_icon_id ) ) {
            $custom_url = '';
            if ( is_numeric( $custom_icon_id ) ) {
                $img_src = wp_get_attachment_image_src( $custom_icon_id, 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src[0];
                }
            } else {
                $custom_url = $custom_icon_id;
            }
            if ( ! empty( $custom_url ) ) {
                return '<img src="' . esc_url( $custom_url ) . '" alt="" style="width: 18px; height: 18px; display: block; object-fit: contain; flex: none;">';
            }
        }

        if ( $source === 'library' ) {
            if ( $library === 'linecons' ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                $icon_class = ! empty( $li_icon ) ? esc_attr( $li_icon ) : 'vc_li vc_li-check';
                return '<i class="' . $icon_class . '" style="font-size: 16px; color: currentColor; display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; flex: none;"></i>';
            } else {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                $icon_class = ! empty( $fa_icon ) ? esc_attr( $fa_icon ) : 'fa fa-check';
                return '<i class="' . $icon_class . '" style="font-size: 16px; color: currentColor; display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; flex: none;"></i>';
            }
        }

        // Predefined SVGs
        if ( $predefined_type === 'plus' ) {
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/></svg>';
        }
        if ( $predefined_type === 'cancel' ) {
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9 9l6 6M15 9l-6 6"/></svg>';
        }
        // Card (default)
        return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="5" width="19" height="14" rx="3"/><path d="M2.5 9.5h19"/></svg>';
    };

    // Included checklist parsing
    $included_data = array();
    if ( ! empty( $attributes['included_list'] ) ) {
        if ( function_exists( 'vc_param_group_parse_atts' ) ) {
            $included_data = vc_param_group_parse_atts( $attributes['included_list'] );
        } else {
            $included_data = json_decode( urldecode( $attributes['included_list'] ), true );
        }
    }

    $rendered_included = '';
    if ( is_array( $included_data ) && ! empty( $included_data ) ) {
        foreach ( $included_data as $inc ) {
            $item_text = isset( $inc['item_text'] ) ? $inc['item_text'] : '';
            if ( ! empty( $item_text ) ) {
                $rendered_included .= '<div class="inc-item">
                    <span class="dot">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12.5l4 4L19 7"/>
                        </svg>
                    </span>
                    ' . esc_html( $item_text ) . '
                </div>';
            }
        }
    }

    $has_multiple_subs = count( $formatted_subs ) > 1;

    ob_start();
    ?>
    <section id="parents-club-dashboard" style="padding: 0 !important; background-color: transparent !important;">
        
        <?php foreach ( $formatted_subs as $index => $sub ) : 
            // Render Details
            $rendered_details = '';
            if ( is_array( $sub['details_data'] ) && ! empty( $sub['details_data'] ) ) {
                foreach ( $sub['details_data'] as $detail ) {
                    $label                = isset( $detail['label'] ) ? $detail['label'] : '';
                    $value                = isset( $detail['value'] ) ? $detail['value'] : '';
                    $value_type           = isset( $detail['value_type'] ) ? $detail['value_type'] : 'normal';
                    $card_type            = isset( $detail['card_type'] ) ? $detail['card_type'] : 'VISA';
                    $card_subtext         = isset( $detail['card_subtext'] ) ? $detail['card_subtext'] : '';
                    $icon_source          = isset( $detail['icon_source'] ) ? $detail['icon_source'] : 'brand';
                    $predefined_icon_type = isset( $detail['predefined_icon_type'] ) ? $detail['predefined_icon_type'] : 'checkmark';
                    $icon_library         = isset( $detail['icon_library'] ) ? $detail['icon_library'] : 'fontawesome';
                    $fa_icon              = isset( $detail['icon_fontawesome'] ) ? $detail['icon_fontawesome'] : '';
                    $li_icon              = isset( $detail['icon_linecons'] ) ? $detail['icon_linecons'] : '';
                    $custom_icon          = isset( $detail['custom_icon'] ) ? $detail['custom_icon'] : '';

                    $icon_html = $render_detail_icon( $icon_source, $predefined_icon_type, $icon_library, $fa_icon, $li_icon, $custom_icon );

                    $value_html = '';
                    if ( $value_type === 'pill' ) {
                        $value_html = '<span><span class="trial-pill">' . esc_html( $value ) . '</span></span>';
                    } elseif ( $value_type === 'blue_highlight' ) {
                        if ( preg_match( '/(.*?)(\(.*?\))/', $value, $matches ) ) {
                             $value_html = '<span class="blue">' . esc_html( trim( $matches[1] ) ) . ' <span class="days">' . esc_html( trim( $matches[2] ) ) . '</span></span>';
                        } else {
                             $value_html = '<span class="blue">' . esc_html( $value ) . '</span>';
                        }
                    } elseif ( $value_type === 'card' ) {
                        $value_html = '<span class="pay">
                            <span class="visa">' . esc_html( $card_type ) . '</span>
                            <span class="dval">' . esc_html( $value ) . '</span>';
                        if ( ! empty( $card_subtext ) ) {
                            $value_html .= '<span class="next">' . esc_html( $card_subtext ) . '</span>';
                        }
                        $value_html .= '</span>';
                    } else {
                        $value_html = '<span class="dval">' . wp_kses_post( $value ) . '</span>';
                    }

                    $rendered_details .= '<div class="drow">
                        ' . $icon_html . '
                        <span class="dlabel">' . esc_html( $label ) . '</span>
                        ' . $value_html . '
                    </div>';
                }
            }

            // Prepend the "See ALL Subscriptions" link if applicable
            if ( $has_multiple_subs ) {
                $rendered_details = '<div class="drow-all-subs">
                    <a href="javascript:void(0);" onclick="jQuery(\'.sub-card-view\').addClass(\'hidden-card\'); jQuery(\'#sub-list-view\').removeClass(\'hidden-card\'); return false;">See ALL Subscriptions</a>
                </div>' . $rendered_details;
            }

            // Render Actions
            $rendered_actions = '';
            if ( is_array( $sub['actions_data'] ) && ! empty( $sub['actions_data'] ) ) {
                foreach ( $sub['actions_data'] as $action ) {
                    $btn_text             = isset( $action['btn_text'] ) ? $action['btn_text'] : '';
                    $btn_link             = isset( $action['btn_link'] ) ? $action['btn_link'] : '';
                    $icon_source          = isset( $action['icon_source'] ) ? $action['icon_source'] : 'brand';
                    $predefined_icon_type = isset( $action['predefined_icon_type'] ) ? $action['predefined_icon_type'] : 'card';
                    $icon_library         = isset( $action['icon_library'] ) ? $action['icon_library'] : 'fontawesome';
                    $fa_icon              = isset( $action['icon_fontawesome'] ) ? $action['icon_fontawesome'] : '';
                    $li_icon              = isset( $action['icon_linecons'] ) ? $action['icon_linecons'] : '';
                    $custom_icon          = isset( $action['custom_icon'] ) ? $action['custom_icon'] : '';

                    $btn_url    = '#';
                    $btn_target = '';
                    if ( ! empty( $btn_link ) ) {
                        if ( strpos( $btn_link, 'url:' ) !== false || strpos( $btn_link, 'title:' ) !== false ) {
                            $link_data = vc_build_link( $btn_link );
                            $btn_url   = isset( $link_data['url'] ) ? $link_data['url'] : '#';
                            $btn_target = isset( $link_data['target'] ) ? $link_data['target'] : '';
                        } else {
                            $btn_url = $btn_link;
                        }
                    }

                    $btn_icon_html = $render_action_icon( $icon_source, $predefined_icon_type, $icon_library, $fa_icon, $li_icon, $custom_icon );

                    $rendered_actions .= '<a href="' . esc_url( $btn_url ) . '" class="obtn" ' . ( ! empty( $btn_target ) ? 'target="' . esc_attr( $btn_target ) . '"' : '' ) . '>
                        ' . $btn_icon_html . ' ' . esc_html( $btn_text ) . '
                    </a>';
                }
            }
        ?>

        <section class="card sub-card sub-card-view <?php echo $index === 0 ? '' : 'hidden-card'; ?>" id="sub-card-view-<?php echo esc_attr( $index ); ?>">
            <div class="sub-top">
                <div class="sub-logo-group">
                    <div class="sub-logo-badge">
                        <img class="logo" src="<?php echo esc_url( $logo_url ); ?>" alt="eMathSmart">
                        <span class="active-pill"><?php echo esc_html( $sub['status_pill_text'] ); ?></span>
                    </div>
                    <h2 class="sub-title"><?php echo esc_html( $attributes['title'] ); ?></h2>
                </div>
                <?php if ( ! empty( $attributes['portal_btn_text'] ) ) : ?>
                    <a href="<?php echo esc_url( $sub['portal_url'] ); ?>" class="go-btn" <?php echo ! empty( $portal_target ) ? 'target="' . $portal_target . '"' : ''; ?>>
                        <?php echo esc_html( $attributes['portal_btn_text'] ); ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 17 17 7M9 7h8v8"></path>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>

            <div class="sub-body">
                <!-- Illustration -->
                <img class="illu" src="<?php echo esc_url($tablet_url); ?>" alt="eMathSmart tablet, books and supplies">

                <!-- Details list -->
                <div class="details">
                    <?php echo $rendered_details; ?>
                </div>

                <!-- What's included -->
                <div class="included">
                    <h4><?php echo esc_html( $attributes['included_title'] ); ?></h4>
                    <?php echo $rendered_included; ?>
                </div>
            </div>

            <?php if ( ! empty( $rendered_actions ) ) : ?>
                <div class="actions">
                    <?php echo $rendered_actions; ?>
                </div>
            <?php endif; ?>
        </section>

        <?php endforeach; ?>

        <?php if ( $has_multiple_subs ) : ?>
        <section class="card sub-card hidden-card" id="sub-list-view">
            <div class="sub-top" style="border-bottom: 1px solid #eaeaea; padding-bottom: 20px; margin-bottom: 20px;">
                <div class="sub-logo-group">
                    <h2 class="sub-title" style="margin: 0;">All Subscriptions</h2>
                </div>
                <a href="javascript:void(0);" class="go-btn" onclick="if(window.pcSwitchSubscription){ window.pcSwitchSubscription(0, '<?php echo esc_js( $formatted_subs[0]['id'] ); ?>'); } else { jQuery('#sub-list-view').addClass('hidden-card'); jQuery('#sub-card-view-0').removeClass('hidden-card'); } return false;" style="background: transparent; color: var(--brand-text-dark); border: 1px solid #ccc; font-weight: 500;">
                    Go Back
                </a>
            </div>
            <div class="sub-body" style="padding: 0;">
                <div class="subscription-list-items">
                    <?php foreach ( $formatted_subs as $index => $sub ) : ?>
                        <a href="javascript:void(0);" onclick="if(window.pcSwitchSubscription){ window.pcSwitchSubscription(<?php echo esc_attr( $index ); ?>, '<?php echo esc_js( $sub['id'] ); ?>'); } else { jQuery('#sub-list-view').addClass('hidden-card'); jQuery('#sub-card-view-<?php echo esc_attr( $index ); ?>').removeClass('hidden-card'); } return false;" class="sub-list-item">
                            <div class="sub-list-item-info">
                                <span class="sub-list-item-title"><?php echo esc_html( $sub['sub_type'] ); ?></span>
                                <span class="sub-list-item-status"><?php echo esc_html( $sub['status_val'] ); ?></span>
                            </div>
                            <div class="sub-list-item-arrow">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

    </section>
    <?php
    return ob_get_clean();
}


// -----------------------------------------------------------------------------
// SECTION 9: [parents_club_member_account_overview] Shortcode Handler
// -----------------------------------------------------------------------------

add_shortcode( 'parents_club_member_account_overview', 'idl_loader_parents_club_member_account_overview_shortcode' );

function idl_loader_parents_club_member_account_overview_shortcode( $atts ) {


    $attributes = shortcode_atts( array(
        'title'      => 'Account Overview',
        'items_list' => '',
    ), $atts );

    // Enqueue account overview stylesheet
    wp_enqueue_style( 'parents-club-dashboard-account-overview', plugins_url( 'templates/css/parents-club-dashboard-account-overview.css', __FILE__ ) );

    $title = esc_html( $attributes['title'] );

    // Parse items param group
    $items_data = array();
    if ( ! empty( $attributes['items_list'] ) ) {
        if ( function_exists( 'vc_param_group_parse_atts' ) ) {
            $items_data = vc_param_group_parse_atts( $attributes['items_list'] );
        } else {
            $items_data = json_decode( urldecode( $attributes['items_list'] ), true );
        }
    }

    // Helper closure to render overview item icon
    $render_item_icon = function( $source, $predefined_type, $library, $fa_icon, $li_icon, $custom_icon_id ) {
        if ( $source === 'custom' && ! empty( $custom_icon_id ) ) {
            $custom_url = '';
            if ( is_numeric( $custom_icon_id ) ) {
                $img_src = wp_get_attachment_image_src( $custom_icon_id, 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src[0];
                }
            } else {
                $custom_url = $custom_icon_id;
            }
            if ( ! empty( $custom_url ) ) {
                return '<img src="' . esc_url( $custom_url ) . '" alt="" style="width: 24px; height: 24px; display: block; object-fit: contain;">';
            }
        }

        if ( $source === 'library' ) {
            if ( $library === 'linecons' ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                $icon_class = ! empty( $li_icon ) ? esc_attr( $li_icon ) : 'vc_li vc_li-user';
                return '<i class="' . $icon_class . '" style="font-size: 20px; color: currentColor; display: inline-flex; align-items: center; justify-content: center;"></i>';
            } else {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                $icon_class = ! empty( $fa_icon ) ? esc_attr( $fa_icon ) : 'fa fa-user';
                return '<i class="' . $icon_class . '" style="font-size: 20px; color: currentColor; display: inline-flex; align-items: center; justify-content: center;"></i>';
            }
        }

        // Predefined SVGs
        if ( $predefined_type === 'chart' ) {
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>';
        }
        if ( $predefined_type === 'gear' ) {
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>';
        }
        // Graduation (default)
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
            <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
        </svg>';
    };

    $user_id = get_current_user_id();

    $api_base_url = function_exists( 'emathsmart_get_api_url' ) ? emathsmart_get_api_url() : 'https://test.emathsmart.ca';
    $parent_link  = rtrim( $api_base_url, '/' ) . '/parent';

    $rendered_items = '';
    if ( is_array( $items_data ) && ! empty( $items_data ) ) {
        $count = count( $items_data );
        $i = 0;
        foreach ( $items_data as $item ) {
            $item_title           = isset( $item['item_title'] ) ? $item['item_title'] : '';
            $desc_line1           = isset( $item['desc_line1'] ) ? $item['desc_line1'] : '';
            $desc_line2           = isset( $item['desc_line2'] ) ? $item['desc_line2'] : '';
            $action_text          = isset( $item['action_text'] ) ? $item['action_text'] : '';
            $action_link          = isset( $item['action_link'] ) ? $item['action_link'] : '';
            $icon_source          = isset( $item['icon_source'] ) ? $item['icon_source'] : 'brand';
            $predefined_icon_type = isset( $item['predefined_icon_type'] ) ? $item['predefined_icon_type'] : 'graduation';
            $icon_library         = isset( $item['icon_library'] ) ? $item['icon_library'] : 'fontawesome';
            $fa_icon              = isset( $item['icon_fontawesome'] ) ? $item['icon_fontawesome'] : '';
            $li_icon              = isset( $item['icon_linecons'] ) ? $item['icon_linecons'] : '';
            $custom_icon          = isset( $item['custom_icon'] ) ? $item['custom_icon'] : '';

            $action_url    = '#';
            $action_target = '';
            if ( ! empty( $action_link ) ) {
                if ( strpos( $action_link, 'url:' ) !== false || strpos( $action_link, 'title:' ) !== false ) {
                    $link_data = vc_build_link( $action_link );
                    $action_url = isset( $link_data['url'] ) ? $link_data['url'] : '#';
                    $action_target = isset( $link_data['target'] ) ? $link_data['target'] : '';
                } else {
                    $action_url = $action_link;
                }
            }

            // Identify card type and override settings contextually
            $is_students = ( $predefined_icon_type === 'graduation' || stripos( $item_title, 'student' ) !== false || stripos( $action_text, 'student' ) !== false );
            $is_reports  = ( $predefined_icon_type === 'chart' || stripos( $item_title, 'report' ) !== false || stripos( $action_text, 'report' ) !== false || stripos( $item_title, 'progress' ) !== false );
            $is_settings = ( $predefined_icon_type === 'gear' || stripos( $item_title, 'setting' ) !== false || stripos( $action_text, 'setting' ) !== false );

            if ( $is_students ) {
                if ( $user_id ) {
                    $desc_line1 = '<span id="pc-member-student-count"><span class="pc-skeleton-loader" style="display:inline-block; width:80px; height: 16px; background: rgba(0,0,0,0.08); border-radius:4px; animation: pc-pulse 1.5s infinite ease-in-out; margin-top: 4px;"></span></span>';
                    $desc_line2 = '<span id="pc-member-student-grades"><span class="pc-skeleton-loader" style="display:inline-block; width:120px; height: 16px; background: rgba(0,0,0,0.08); border-radius:4px; animation: pc-pulse 1.5s infinite ease-in-out; margin-top: 4px;"></span></span>';
                }
                $action_url    = $parent_link;
                $action_target = '_blank';
            } elseif ( $is_reports ) {
                $action_url    = $parent_link;
                $action_target = '_blank';
            } elseif ( $is_settings ) {
                $action_url    = home_url( '/my-account/' );
                $action_target = '';
            }

            $item_icon_html = $render_item_icon( $icon_source, $predefined_icon_type, $icon_library, $fa_icon, $li_icon, $custom_icon );

            $rendered_items .= '<div class="overview-item">
                <div class="overview-icon-box">
                    ' . $item_icon_html . '
                </div>
                <div class="overview-details">
                    <div class="item-title">' . esc_html( $item_title ) . '</div>';
            if ( ! empty( $desc_line1 ) ) {
                $rendered_items .= '<div class="item-desc">' . wp_kses_post( $desc_line1 ) . '</div>';
            }
            if ( ! empty( $desc_line2 ) ) {
                $rendered_items .= '<div class="item-desc">' . wp_kses_post( $desc_line2 ) . '</div>';
            }
            if ( ! empty( $action_text ) ) {
                $rendered_items .= '<a class="item-link" href="' . esc_url( $action_url ) . '" ' . ( ! empty( $action_target ) ? 'target="' . esc_attr( $action_target ) . '"' : '' ) . '>' . esc_html( $action_text ) . '</a>';
            }
            $rendered_items .= '</div>
            </div>';

            $i++;
            if ( $i < $count ) {
                $rendered_items .= '<div class="overview-divider"></div>';
            }
        }
    }

    ob_start();
    ?>
    <section id="parents-club-dashboard" style="padding: 0 !important; background-color: transparent !important; margin-top: 0 !important;">
        <div id="account-overview" style="margin-top: 0 !important;">
            <?php if ( ! empty( $title ) ) : ?>
                <h3 class="overview-title"><?php echo $title; ?></h3>
            <?php endif; ?>
            <div class="overview-content">
                <?php echo $rendered_items; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}


// -----------------------------------------------------------------------------
// SECTION 10: [parents_club_member_coins] Shortcode Handler
// -----------------------------------------------------------------------------

add_shortcode( 'parents_club_member_coins', 'idl_loader_parents_club_member_coins_shortcode' );

/**
 * Query and sum all coins purchased by the customer via completed or processing WooCommerce orders.
 */
function idl_loader_get_user_total_purchased_coins( $user_id ) {
    if ( empty( $user_id ) || ! function_exists( 'wc_get_orders' ) ) {
        return 0;
    }
    
    $orders = wc_get_orders( array(
        'customer' => $user_id,
        'status'   => array( 'completed', 'processing' ),
        'limit'    => -1,
    ) );
    
    $total_coins = 0;
    foreach ( $orders as $order ) {
        foreach ( $order->get_items() as $item ) {
            // 1. Try to read coins directly from the order item metadata (allows database/phpMyAdmin adjustments)
            $coins = $item->get_meta( 'coins' );
            if ( empty( $coins ) ) {
                $coins = $item->get_meta( 'pa_coins' );
            }
            
            // 2. Fallback to product variation catalog settings if not found in order item metadata
            if ( empty( $coins ) ) {
                $product = $item->get_product();
                if ( $product ) {
                    $coins = $product->get_attribute( 'coins' );
                    if ( empty( $coins ) ) {
                        $coins = $product->get_meta( 'attribute_pa_coins', true );
                        if ( empty( $coins ) ) {
                            $coins = $product->get_meta( 'attribute_coins', true );
                        }
                    }
                }
            }
            
            $coins_num = intval( $coins );
            if ( $coins_num > 0 ) {
                $total_coins += $coins_num * $item->get_quantity();
            }
        }
    }
    return $total_coins;
}

function idl_loader_parents_club_member_coins_shortcode( $atts ) {


    $attributes = shortcode_atts( array(
        'title'          => 'AI Coins Balance',
        'coin_image'     => '',
        'description'    => 'Use AI coins to chat with your AI helper, and mark worksheets.',
        'purchase_title' => 'Purchase AI Coins',
        'product_id'     => '',
        'packages_list'  => '',
    ), $atts );

    // Enqueue the modular coins stylesheet contextually
    wp_enqueue_style( 'parents-club-dashboard-coins', plugins_url( 'templates/css/parents-club-dashboard-coins.css', __FILE__ ) );

    $title = esc_html( $attributes['title'] );
    $description = esc_html( $attributes['description'] );
    $purchase_title = esc_html( $attributes['purchase_title'] );

    // Fetch dynamic balance if user is logged in
    $user_id = get_current_user_id();
    $balance_html = '0';
    if ( $user_id ) {
        $balance_html = '<span class="pc-skeleton-loader" style="display:inline-block; width:50px; height: 24px; background: rgba(0,0,0,0.08); border-radius:4px; animation: pc-pulse 1.5s infinite ease-in-out; vertical-align: middle;"></span>';
    }

    // Resolve Coin Image
    $coin_img_url = '';
    if ( ! empty( $attributes['coin_image'] ) ) {
        if ( is_numeric( $attributes['coin_image'] ) ) {
            $img_src = wp_get_attachment_image_src( $attributes['coin_image'], 'full' );
            if ( $img_src ) {
                $coin_img_url = $img_src[0];
            }
        } else {
            $coin_img_url = $attributes['coin_image'];
        }
    }
    if ( empty( $coin_img_url ) ) {
        $coin_img_url = plugins_url( 'templates/images/coin.png', __FILE__ );
    }

    // Parse packages list repeatable container or load variations dynamically from selected product
    $packages_data = array();
    $selected_product_id = isset( $attributes['product_id'] ) ? intval( $attributes['product_id'] ) : 0;

    if ( $selected_product_id > 0 && function_exists( 'wc_get_product' ) ) {
        $product = wc_get_product( $selected_product_id );
        if ( $product && $product->is_type( 'variable' ) ) {
            $variations = $product->get_available_variations();
            foreach ( $variations as $var_data ) {
                $var_id = $var_data['variation_id'];
                $var_obj = wc_get_product( $var_id );
                if ( $var_obj ) {
                    $coins = $var_obj->get_attribute('coins');
                    if ( empty( $coins ) ) {
                        $coins = $var_obj->get_meta('attribute_pa_coins', true);
                        if ( empty( $coins ) ) {
                            $coins = $var_obj->get_meta('attribute_coins', true);
                        }
                    }
                    $coins_num = intval( $coins );
                    if ( $coins_num > 0 ) {
                        $qty_text = $coins_num . ' Coins';
                        
                        // Extract cleanly formatted price
                        $price_val = html_entity_decode(strip_tags(wc_price($var_obj->get_price())));
                        
                        $packages_data[] = array(
                            'package_qty'   => $qty_text,
                            'package_price' => $price_val,
                            'package_link'  => add_query_arg('add-to-cart', $var_id, wc_get_checkout_url()),
                        );
                    }
                }
            }

            // Sort variations by coin quantity ascending
            if ( ! empty( $packages_data ) ) {
                usort( $packages_data, function( $a, $b ) {
                    $a_coins = intval( preg_replace( '/[^0-9]/', '', $a['package_qty'] ) );
                    $b_coins = intval( preg_replace( '/[^0-9]/', '', $b['package_qty'] ) );
                    return $a_coins - $b_coins;
                });
            }
        }
    }

    if ( empty( $packages_data ) ) {
        if ( ! empty( $attributes['packages_list'] ) ) {
            if ( function_exists( 'vc_param_group_parse_atts' ) ) {
                $packages_data = vc_param_group_parse_atts( $attributes['packages_list'] );
            } else {
                $packages_data = json_decode( urldecode( $attributes['packages_list'] ), true );
            }
        }
    }

    // Fallback if empty
    if ( empty( $packages_data ) ) {
        $packages_data = array(
            array(
                'package_qty'   => '100 Coins',
                'package_price' => '$5.99',
                'package_link'  => '',
            ),
            array(
                'package_qty'   => '250 Coins',
                'package_price' => '$9.99',
                'package_link'  => '',
            ),
        );
    }

    $rendered_packages = array();
    if ( is_array( $packages_data ) ) {
        foreach ( $packages_data as $pkg ) {
            $pkg_qty   = isset( $pkg['package_qty'] ) ? $pkg['package_qty'] : '';
            $pkg_price = isset( $pkg['package_price'] ) ? $pkg['package_price'] : '';
            $pkg_link  = isset( $pkg['package_link'] ) ? $pkg['package_link'] : '';

            $btn_url    = '#';
            $btn_target = '';

            if ( ! empty( $pkg_link ) ) {
                if ( strpos( $pkg_link, 'url:' ) !== false || strpos( $pkg_link, 'title:' ) !== false ) {
                    $link_data  = vc_build_link( $pkg_link );
                    $btn_url    = isset( $link_data['url'] ) ? $link_data['url'] : '#';
                    $btn_target = isset( $link_data['target'] ) ? $link_data['target'] : '';
                } else {
                    $btn_url = $pkg_link;
                }
            } else {
                $btn_url = home_url( '/product/ai-coins/' );
            }

            $rendered_packages[] = array(
                'qty'    => esc_html( $pkg_qty ),
                'price'  => esc_html( $pkg_price ),
                'url'    => esc_url( $btn_url ),
                'target' => esc_attr( $btn_target ),
            );
        }
    }

    ob_start();
    ?>
    <section id="parents-club-dashboard" style="padding: 0 !important; background-color: transparent !important;">
        <div class="card coins-card">
            <div class="rh"><?php echo $title; ?></div>
            <div class="coin-row" style="position: relative !important; display: flex !important; align-items: center !important;">
                <img class="coin-ic" src="<?php echo esc_url( $coin_img_url ); ?>" alt="AI coin">
                <span class="coin-amt" id="pc-member-coin-balance-val">
                    <?php echo $balance_html; ?> <span>coins</span>
                </span>
                <?php if ( $user_id ) : ?>
                    <button class="see-details-btn" id="pc-member-see-details-btn" style="display: none;" onclick="toggleCoinBreakdown()"><?php esc_html_e( 'See details', 'book-junky' ); ?></button>
                <?php endif; ?>
            </div>
            
            <?php if ( $user_id ) : ?>
                <div id="coin-breakdown-details" class="coin-breakdown-panel" style="display: none;">
                </div>
                
                <script>
                    function toggleCoinBreakdown() {
                        var panel = document.getElementById("coin-breakdown-details");
                        var btn = document.getElementById("pc-member-see-details-btn");
                        if (panel.style.display === "none" || panel.style.display === "") {
                            panel.style.display = "block";
                            btn.innerText = "<?php esc_html_e( 'Hide details', 'book-junky' ); ?>";
                        } else {
                            panel.style.display = "none";
                            btn.innerText = "<?php esc_html_e( 'See details', 'book-junky' ); ?>";
                        }
                    }
                </script>
            <?php endif; ?>

            <p class="coins-desc"><?php echo $description; ?></p>

            <?php if ( ! empty( $purchase_title ) ) : ?>
                <div class="purchase-h"><?php echo $purchase_title; ?></div>
            <?php endif; ?>

            <?php if ( ! empty( $rendered_packages ) ) : ?>
                <div class="coin-grid">
                    <?php foreach ( $rendered_packages as $pkg ) : ?>
                        <div class="coin-opt">
                            <div class="qty"><?php echo $pkg['qty']; ?></div>
                            <div class="price"><?php echo $pkg['price']; ?></div>
                            <a href="<?php echo $pkg['url']; ?>" class="buy-btn" <?php echo ! empty( $pkg['target'] ) ? 'target="' . $pkg['target'] . '"' : ''; ?> style="display: block; text-align: center; text-decoration: none;">BUY NOW</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ( $user_id ) : ?>
                <script>
                jQuery(document).ready(function($) {
                    var endpoint = '/wp-json/idl-parents-club/v1/dashboard-data';
                    $.ajax({
                        url: endpoint,
                        method: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-WP-Nonce': '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>'
                        },
                        success: function(response) {
                            if (response && response.success) {
                                // Update Coins Card Total Balance
                                var balanceValEl = $('#pc-member-coin-balance-val');
                                if (balanceValEl.length) {
                                    balanceValEl.html(response.total_balance + ' <span>coins</span>');
                                }

                                // Update Coins Card Details Panel
                                var seeDetailsBtn = $('#pc-member-see-details-btn');
                                var breakdownPanel = $('#coin-breakdown-details');
                                
                                if (breakdownPanel.length) {
                                    var html = '<div class="coin-breakdown-title">Coin Balance Breakdown</div>';
                                    
                                    if (response.students && response.students.length > 0) {
                                        response.students.forEach(function(student) {
                                            html += '<div class="coin-breakdown-row">' +
                                                '<span class="label">' + escHtml(student.name) + '</span>' +
                                                '<span class="val">' +
                                                '<img src="' + getCoinImgUrl() + '" class="small-coin-ic" alt="coin"> ' +
                                                student.coins + ' coins' +
                                                '</span>' +
                                                '</div>';
                                        });
                                    }
                                    
                                    html += '<div class="coin-breakdown-divider"></div>' +
                                        '<div class="coin-breakdown-row">' +
                                        '<span class="label" style="font-style: italic;">Unallocated balance</span>' +
                                        '<span class="val">' +
                                        '<img src="' + getCoinImgUrl() + '" class="small-coin-ic" alt="coin"> ' +
                                        response.unallocated_balance + ' coins' +
                                        '</span>' +
                                        '</div>';
                                    
                                    breakdownPanel.html(html);
                                    
                                    if (response.students.length > 0 || response.unallocated_balance > 0) {
                                        seeDetailsBtn.show();
                                    }
                                }

                                // Update Overview Card (Student count and Grades)
                                var studentCountEl = $('#pc-member-student-count');
                                var studentGradesEl = $('#pc-member-student-grades');
                                if (studentCountEl.length) {
                                    studentCountEl.html(response.student_desc);
                                }
                                if (studentGradesEl.length) {
                                    studentGradesEl.html(response.grades_desc);
                                }

                                // Update Subscription Card(s) Access Fields
                                if (response.subscriptions_access) {
                                    $('.pc-sub-access-val').each(function() {
                                        var subId = $(this).attr('data-sub-id');
                                        if (response.subscriptions_access[subId]) {
                                            $(this).html(escHtml(response.subscriptions_access[subId]));
                                        } else {
                                            $(this).html('None');
                                        }
                                    });
                                }
                                
                                window.pcDashboardData = response;
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Parents Club Dashboard AJAX error:', error);
                            $('#pc-member-coin-balance-val').html('0 <span>coins</span>');
                            $('#pc-member-student-count').html('0 Students');
                            $('#pc-member-student-grades').html('No Grades');
                            $('.pc-sub-access-val').html('None');
                        }
                    });

                    function escHtml(str) {
                        if (!str) return '';
                        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
                    }
                    
                    function getCoinImgUrl() {
                        return '<?php echo esc_url( $coin_img_url ); ?>';
                    }
                });

                window.pcSwitchSubscription = function(index, subId) {
                    jQuery('#sub-list-view').addClass('hidden-card');
                    jQuery('.sub-card-view').addClass('hidden-card');
                    jQuery('#sub-card-view-' + index).removeClass('hidden-card');
                    
                    if (window.pcDashboardData) {
                        var bal = window.pcDashboardData.total_balance;
                        if (subId && subId !== 'none' && subId !== 'guest') {
                            if (window.pcDashboardData.subscriptions_credits && window.pcDashboardData.subscriptions_credits[subId] !== undefined) {
                                bal = window.pcDashboardData.subscriptions_credits[subId];
                            }
                        }
                        jQuery('#pc-member-coin-balance-val').html(bal + ' <span>coins</span>');
                    }
                };
                </script>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// -----------------------------------------------------------------------------
// SECTION 11: [parents_club_member_billing] Shortcode Handler
// -----------------------------------------------------------------------------

add_shortcode( 'parents_club_member_billing', 'idl_loader_parents_club_member_billing_shortcode' );

function idl_loader_parents_club_member_billing_shortcode( $atts ) {


    $attributes = shortcode_atts( array(
        'title'                => 'Billing History',
        'view_all_text'        => 'View all',
        'view_all_link'        => '',
        'billing_rows'         => '',
        'download_btn_text'    => 'DOWNLOAD RECEIPT HISTORY',
        'download_btn_link'    => '',
        'icon_source'          => 'brand',
        'predefined_icon_type' => 'download',
        'icon_library'         => 'fontawesome',
        'icon_fontawesome'     => '',
        'icon_linecons'        => '',
        'custom_icon'          => '',
    ), $atts );

    // Enqueue modular billing stylesheet contextually
    wp_enqueue_style( 'parents-club-dashboard-billing', plugins_url( 'templates/css/parents-club-dashboard-billing.css', __FILE__ ) );

    $title             = esc_html( $attributes['title'] );
    $view_all_text     = esc_html( $attributes['view_all_text'] );
    $download_btn_text = esc_html( $attributes['download_btn_text'] );

    // Resolve View All Link
    $view_all_url    = '#';
    $view_all_target = '';
    if ( ! empty( $attributes['view_all_link'] ) ) {
        if ( strpos( $attributes['view_all_link'], 'url:' ) !== false || strpos( $attributes['view_all_link'], 'title:' ) !== false ) {
            $link_data = vc_build_link( $attributes['view_all_link'] );
            $view_all_url = isset( $link_data['url'] ) ? $link_data['url'] : '#';
            $view_all_target = isset( $link_data['target'] ) ? $link_data['target'] : '';
        } else {
            $view_all_url = $attributes['view_all_link'];
        }
    }

    // Resolve Download Button Link
    $download_url    = '#';
    $download_target = '';
    if ( ! empty( $attributes['download_btn_link'] ) ) {
        if ( strpos( $attributes['download_btn_link'], 'url:' ) !== false || strpos( $attributes['download_btn_link'], 'title:' ) !== false ) {
            $link_data = vc_build_link( $attributes['download_btn_link'] );
            $download_url = isset( $link_data['url'] ) ? $link_data['url'] : '#';
            $download_target = isset( $link_data['target'] ) ? $link_data['target'] : '';
        } else {
            $download_url = $attributes['download_btn_link'];
        }
    }

    // Parse billing rows repeatable container
    $rows_data = array();
    if ( ! empty( $attributes['billing_rows'] ) ) {
        if ( function_exists( 'vc_param_group_parse_atts' ) ) {
            $rows_data = vc_param_group_parse_atts( $attributes['billing_rows'] );
        } else {
            $rows_data = json_decode( urldecode( $attributes['billing_rows'] ), true );
        }
    }

    // Fallback if empty
    if ( empty( $rows_data ) ) {
        $rows_data = array(
            array(
                'date'      => 'May 15, 2024',
                'plan_name' => 'Annual Plan',
                'amount'    => '$69.95',
            ),
            array(
                'date'      => 'May 15, 2023',
                'plan_name' => 'Annual Plan',
                'amount'    => '$69.95',
            ),
            array(
                'date'      => 'May 15, 2022',
                'plan_name' => 'Annual Plan',
                'amount'    => '$69.95',
            ),
        );
    }

    // Replace placeholder rows with real billing history for logged-in users
    $user_id = get_current_user_id();
    if ( $user_id ) {
        $real_rows = array();

        $orders = wc_get_orders( array(
            'customer_id' => $user_id,
            'status'      => array( 'completed', 'processing' ),
            'limit'       => -1,
            'orderby'     => 'date',
            'order'       => 'DESC',
        ) );

        foreach ( $orders as $order ) {
            foreach ( $order->get_items() as $item ) {
                $product = $item->get_product();
                if ( ! $product ) {
                    continue;
                }

                $product_id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
                if ( ! has_term( 'emathsmart-woo', 'product_cat', $product_id ) ) {
                    continue;
                }

                $currency_symbol = get_woocommerce_currency_symbol( $order->get_currency() );

                $real_rows[] = array(
                    'date'       => wc_format_datetime( $order->get_date_created(), get_option( 'date_format' ) ),
                    'plan_name'  => $item->get_name(),
                    'amount'     => $currency_symbol . number_format( (float) $item->get_total(), 2 ),
                    '_timestamp' => $order->get_date_created()->getTimestamp(),
                );
            }
        }

        usort( $real_rows, function( $a, $b ) {
            return $b['_timestamp'] <=> $a['_timestamp'];
        } );

        foreach ( $real_rows as &$row ) {
            unset( $row['_timestamp'] );
        }
        unset( $row );

        $rows_data = $real_rows;
    }

    // Helper closure to render download icon
    $render_download_icon = function( $source, $predefined_type, $library, $fa_icon, $li_icon, $custom_icon_id ) {
        if ( $source === 'none' ) {
            return '';
        }
        if ( $source === 'custom' && ! empty( $custom_icon_id ) ) {
            $custom_url = '';
            if ( is_numeric( $custom_icon_id ) ) {
                $img_src = wp_get_attachment_image_src( $custom_icon_id, 'thumbnail' );
                if ( $img_src ) {
                    $custom_url = $img_src[0];
                }
            } else {
                $custom_url = $custom_icon_id;
            }
            if ( ! empty( $custom_url ) ) {
                return '<img src="' . esc_url( $custom_url ) . '" alt="" style="width: 16px; height: 16px; display: block; object-fit: contain; flex: none;">';
            }
        }

        if ( $source === 'library' ) {
            if ( $library === 'linecons' ) {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'linecons' );
                }
                $icon_class = ! empty( $li_icon ) ? esc_attr( $li_icon ) : 'vc_li vc_li-paperplane';
                return '<i class="' . $icon_class . '" style="font-size: 14px; color: currentColor; display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; flex: none;"></i>';
            } else {
                if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
                    vc_icon_element_fonts_enqueue( 'fontawesome' );
                }
                $icon_class = ! empty( $fa_icon ) ? esc_attr( $fa_icon ) : 'fa fa-download';
                return '<i class="' . $icon_class . '" style="font-size: 14px; color: currentColor; display: inline-flex; align-items: center; justify-content: center; width: 16px; height: 16px; flex: none;"></i>';
            }
        }

        // Predefined Download SVG
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12m0 0 4-4m-4 4-4-4M4 21h16"/></svg>';
    };

    $icon_source = isset( $attributes['icon_source'] ) ? $attributes['icon_source'] : 'brand';
    $predefined  = isset( $attributes['predefined_icon_type'] ) ? $attributes['predefined_icon_type'] : 'download';
    $lib         = isset( $attributes['icon_library'] ) ? $attributes['icon_library'] : 'fontawesome';
    $fa          = isset( $attributes['icon_fontawesome'] ) ? $attributes['icon_fontawesome'] : '';
    $li          = isset( $attributes['icon_linecons'] ) ? $attributes['icon_linecons'] : '';
    $custom      = isset( $attributes['custom_icon'] ) ? $attributes['custom_icon'] : '';

    $btn_icon_html = $render_download_icon( $icon_source, $predefined, $lib, $fa, $li, $custom );

    $visible_limit = 4;
    $has_more      = count( $rows_data ) > $visible_limit;
    $billing_id    = 'pc-billing-' . uniqid();

    ob_start();
    ?>
    <section id="parents-club-billing" style="padding: 0 !important; background-color: transparent !important;">
        <div class="card bill-card" id="<?php echo esc_attr( $billing_id ); ?>">
            <div class="bill-head">
                <div class="rh"><?php echo $title; ?></div>
                <?php if ( $has_more && ! empty( $view_all_text ) ) : ?>
                    <span class="view-all" data-show-text="<?php echo esc_attr( $view_all_text ); ?>" data-hide-text="<?php echo esc_attr__( 'Show less', 'book-junky' ); ?>"><?php echo esc_html( $view_all_text ); ?></span>
                <?php endif; ?>
            </div>
            <div style="margin-top:8px;">
                <?php if ( empty( $rows_data ) ) : ?>
                    <div class="bill-row">
                        <span class="bill-plan"><?php esc_html_e( 'No billing history yet.', 'book-junky' ); ?></span>
                    </div>
                <?php else : ?>
                    <?php foreach ( $rows_data as $index => $row ) :
                        $row_date   = isset( $row['date'] ) ? $row['date'] : '';
                        $row_plan   = isset( $row['plan_name'] ) ? $row['plan_name'] : '';
                        $row_amount = isset( $row['amount'] ) ? $row['amount'] : '';
                        $is_extra   = $index >= $visible_limit;
                        ?>
                        <div class="bill-row<?php echo $is_extra ? ' bill-row-extra' : ''; ?>">
                            <span class="bill-date"><?php echo esc_html( $row_date ); ?></span>
                            <span class="bill-plan"><?php echo esc_html( $row_plan ); ?></span>
                            <span class="bill-amt"><?php echo esc_html( $row_amount ); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if ( ! empty( $download_btn_text ) ) : ?>
                <a href="<?php echo esc_url( $download_url ); ?>" class="download-btn" <?php echo ! empty( $download_target ) ? 'target="' . $download_target . '"' : ''; ?> style="text-decoration: none;">
                    <?php echo $download_btn_text; ?>
                    <?php echo $btn_icon_html; ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php if ( $has_more ) : ?>
        <script>
        (function () {
            var card = document.getElementById( <?php echo wp_json_encode( $billing_id ); ?> );
            if ( ! card ) {
                return;
            }
            var toggle = card.querySelector( '.view-all' );
            if ( ! toggle ) {
                return;
            }
            toggle.addEventListener( 'click', function () {
                var expanded = card.classList.toggle( 'is-expanded' );
                toggle.textContent = expanded ? toggle.getAttribute( 'data-hide-text' ) : toggle.getAttribute( 'data-show-text' );
            } );
        })();
        </script>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}

// -----------------------------------------------------------------------------
// SECTION: [parents_club_user_registration_form] Shortcode Handler
// -----------------------------------------------------------------------------

add_shortcode( 'parents_club_user_registration_form', 'idl_loader_parents_club_user_registration_form_shortcode' );

function idl_loader_parents_club_user_registration_form_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'form_title'        => '',
        'form_description'  => '',
        'form_id'           => '',
        'login_title'       => 'Welcome Back',
        'login_description' => 'Login to your Parents Club account',
        'default_view'      => 'register',
        'enable_toggle'     => 'yes',
        'footer_text'       => 'Already a member?',
        'footer_link'       => '',
    ), $atts, 'parents_club_user_registration_form' );

    // Extract link
    $link = function_exists('vc_build_link') ? vc_build_link( $atts['footer_link'] ) : array();
    $link_url = ! empty( $link['url'] ) ? esc_url( $link['url'] ) : '';
    $link_title = ! empty( $link['title'] ) ? esc_html( $link['title'] ) : 'Login';
    $link_target = ! empty( $link['target'] ) ? esc_attr( $link['target'] ) : '_self';

    $default_view = in_array( $atts['default_view'], array( 'register', 'login' ), true ) ? $atts['default_view'] : 'register';
    $enable_toggle = ( 'yes' === $atts['enable_toggle'] );

    $register_style = ( 'register' === $default_view ) ? '' : 'display: none;';
    $login_style    = ( 'login' === $default_view ) ? '' : 'display: none;';

    // Enqueue style to ensure design is loaded
    wp_enqueue_style(
        'parents-club-hero-signup',
        plugins_url( 'templates/css/parents-club-hero-signup.css', __FILE__ ),
        array(),
        '1.0.0'
    );

    ob_start();
    ?>
    <div class="user-registration ur-frontend-form custom-ur-form-wrapper">
        <!-- Registration Panel -->
        <div class="ur-panel-wrapper ur-register-panel" style="<?php echo $register_style; ?>">
            <?php if ( ! empty( $atts['form_title'] ) || ! empty( $atts['form_description'] ) ) : ?>
                <div class="ur-custom-header" style="background: transparent !important; box-shadow: none !important; border: none !important; padding: 0 !important; margin-bottom: 0;">
                    <?php if ( ! empty( $atts['form_title'] ) ) : ?>
                        <h2 class="user-registration-registration-title"><?php echo esc_html( $atts['form_title'] ); ?></h2>
                    <?php endif; ?>
                    <?php if ( ! empty( $atts['form_description'] ) ) : ?>
                        <p class="user-registration-registration-description"><?php echo esc_html( $atts['form_description'] ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php 
            if ( ! empty( $atts['form_id'] ) ) {
                echo do_shortcode( '[user_registration_form id="' . esc_attr( $atts['form_id'] ) . '"]' );
            }
            ?>

            <?php if ( ! empty( $atts['footer_text'] ) || ! empty( $link_url ) || $enable_toggle ) : ?>
                <div class="ur-footer-text" style="text-align: center; margin-top: 15px; font-family: 'Inter', sans-serif; font-size: 13.5px; color: #666666;">
                    <?php echo esc_html( $atts['footer_text'] ); ?> 
                    <?php if ( $enable_toggle ) : ?>
                        <a href="#" class="ur-toggle-to-login" style="color: #af0128; font-weight: 600; text-decoration: none;"><?php esc_html_e( 'Login', 'book-junky' ); ?></a>
                    <?php elseif ( ! empty( $link_url ) ) : ?>
                        <a href="<?php echo $link_url; ?>" target="<?php echo $link_target; ?>" style="color: #af0128; font-weight: 600; text-decoration: none;"><?php echo $link_title; ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Login Panel -->
        <div class="ur-panel-wrapper ur-login-panel" style="<?php echo $login_style; ?>">
            <?php if ( ! empty( $atts['login_title'] ) || ! empty( $atts['login_description'] ) ) : ?>
                <div class="ur-custom-header" style="background: transparent !important; box-shadow: none !important; border: none !important; padding: 0 !important; margin-bottom: 0;">
                    <?php if ( ! empty( $atts['login_title'] ) ) : ?>
                        <h2 class="user-registration-registration-title"><?php echo esc_html( $atts['login_title'] ); ?></h2>
                    <?php endif; ?>
                    <?php if ( ! empty( $atts['login_description'] ) ) : ?>
                        <p class="user-registration-registration-description"><?php echo esc_html( $atts['login_description'] ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php 
            echo do_shortcode( '[user_registration_login]' );
            ?>

            <div class="ur-login-footer-links" style="margin-top: 15px; font-family: 'Inter', sans-serif; font-size: 13.5px; color: #666666; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                <a href="<?php echo esc_url( home_url( '/password-reset/' ) ); ?>" class="ur-password-reset-link" style="color: #666666; text-decoration: underline; font-weight: 500; font-size: 13px;"><?php esc_html_e( 'Forgot Password?', 'book-junky' ); ?></a>
                
                <?php if ( $enable_toggle ) : ?>
                    <div style="font-size: 13.5px;">
                        <?php esc_html_e( 'Not a member?', 'book-junky' ); ?> 
                        <a href="#" class="ur-toggle-to-register" style="color: #af0128; font-weight: 600; text-decoration: none;"><?php esc_html_e( 'Register', 'book-junky' ); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <script type="text/javascript">
        (function($) {
            $(function() {
                // Helper to flash border with animation restart
                var borderTimeout;
                function flashBorder($card) {
                    $card.removeClass('temp-highlight-border');
                    if ($card.length && $card[0]) {
                        // Force DOM reflow to restart CSS transitions
                        void $card[0].offsetWidth;
                    }
                    $card.addClass('temp-highlight-border');

                    clearTimeout(borderTimeout);
                    borderTimeout = setTimeout(function() {
                        $card.removeClass('temp-highlight-border');
                    }, 1600);
                }

                // Switch panels helper with subtle scale-and-fade transition
                function switchAuthPanel($wrapper, view) {
                    // Start transition (scales down slightly and fades out)
                    $wrapper.addClass('is-flipping');
                    
                    setTimeout(function() {
                        if (view === 'login') {
                            $wrapper.find('.ur-register-panel').hide();
                            $wrapper.find('.ur-login-panel').show();
                        } else {
                            $wrapper.find('.ur-login-panel').hide();
                            $wrapper.find('.ur-register-panel').show();
                        }
                        
                        // Transition back in
                        $wrapper.removeClass('is-flipping');
                        
                        // Flash border glow
                        flashBorder($wrapper);
                    }, 250); // matches 0.25s CSS transition duration
                }

                <?php if ( $enable_toggle ) : ?>
                // Toggling registration -> login
                $(document).on('click', '.ur-toggle-to-login', function(e) {
                    e.preventDefault();
                    var $wrapper = $(this).closest('.custom-ur-form-wrapper');
                    switchAuthPanel($wrapper, 'login');
                });

                // Toggling login -> registration
                $(document).on('click', '.ur-toggle-to-register', function(e) {
                    e.preventDefault();
                    var $wrapper = $(this).closest('.custom-ur-form-wrapper');
                    switchAuthPanel($wrapper, 'register');
                });
                <?php endif; ?>

                // Handle global CTA anchors pointing to #signup, #login, or .brand-actions buttons
                $(document).on('click', 'a[href^="#signup"], a[href^="#login"], a[href^="#parents-club-login"], .brand-actions a', function(e) {
                    var href = $(this).attr('href');
                    if (!href || href.indexOf('#') !== 0) {
                        return;
                    }
                    
                    var $targetForm = $('.custom-ur-form-wrapper');
                    if (!$targetForm.length) {
                        return;
                    }
                    
                    e.preventDefault();
                    
                    // Determine which panel to show
                    var showLogin = (href.indexOf('login') !== -1 || $(this).hasClass('btn-outline-crimson'));
                    var currentView = $targetForm.find('.ur-login-panel').is(':visible') ? 'login' : 'register';
                    var targetView = showLogin ? 'login' : 'register';
                    
                    if (currentView !== targetView) {
                        // Scroll first, then flip during scroll
                        $('html, body').animate({
                            scrollTop: $targetForm.offset().top - 120
                        }, 500);
                        
                        setTimeout(function() {
                            switchAuthPanel($targetForm, targetView);
                        }, 200);
                    } else {
                        // Already on target view: scroll and trigger animation flash
                        $('html, body').animate({
                            scrollTop: $targetForm.offset().top - 120
                        }, 600, function() {
                            flashBorder($targetForm);
                        });
                    }
                });

                // Check URL parameter on page load
                var params = new URLSearchParams(window.location.search);
                if (params.get('action') === 'login') {
                    $('.custom-ur-form-wrapper').each(function() {
                        $(this).find('.ur-register-panel').hide();
                        $(this).find('.ur-login-panel').show();
                    });
                }
            });
        })(jQuery);
        </script>
    </div>
    <?php
    return ob_get_clean();
}

// Register WPBakery class for this shortcode
if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_parents_club_user_registration_form' ) ) {
    class WPBakeryShortCode_parents_club_user_registration_form extends WPBakeryShortCode {}
}

/**
 * Check if a user is a Parents Club member by checking their user profile meta value.
 */
function idl_loader_is_parents_club_member( $user_id ) {
    if ( ! $user_id ) {
        return false;
    }
    $pc_meta = get_user_meta( $user_id, 'user_registration_check_box_1661192013', true );
    if ( is_array( $pc_meta ) ) {
        return in_array( 'parent_club_member', $pc_meta, true );
    }
    return ( $pc_meta === 'parent_club_member' );
}

/**
 * Reusable parameter for WPBakery element visibility controls.
 */
function idl_loader_get_visibility_param() {
    return array(
        "type"        => "dropdown",
        "heading"     => esc_html__( "Visibility", "book-junky" ),
        "param_name"  => "pc_visibility",
        "value"       => array(
            esc_html__( "Show to Everyone", "book-junky" ) => "all",
            esc_html__( "Guests Only (Logged Out)", "book-junky" ) => "guests",
            esc_html__( "Users non Parent's Club Members Only", "book-junky" ) => "non_members",
            esc_html__( "Parent's Club Members Only (All)", "book-junky" ) => "members",
            esc_html__( "Parent's Club Members (No Active Subscription) Only", "book-junky" ) => "members_no_sub",
            esc_html__( "Parent's Club Members with Active Subscription Only", "book-junky" ) => "active_subscribers",
            esc_html__( "Everyone Except Active Subscribers", "book-junky" ) => "exclude_active_subscribers",
        ),
        "std"         => "all",
        "admin_label" => true,
        "description" => esc_html__( "Select who can see this element on the frontend.", "book-junky" ),
        "group"       => esc_html__( "Visibility", "book-junky" ),
    );
}

/**
 * Global shortcode filter to enforce custom element visibility restrictions.
 */
add_filter( 'do_shortcode_tag', 'idl_loader_parents_club_shortcode_visibility_filter', 10, 3 );
function idl_loader_parents_club_shortcode_visibility_filter( $output, $tag, $attr ) {
    $custom_tags = array(
        'parents_club_hero',
        'parents_club_hero_intro',
        'parents_club_benefits_glance',
        'parents_club_why_join',
        'parents_club_how_works',
        'esmart_login_card',
        'emathsmart_plan_card',
        'parents_club_member_perks',
        'parents_club_need_help',
        'emathsmart_subscription_product_card',
        'parents_club_cta_banner',
        'parents_club_member_welcome',
        'parents_club_member_quick_links',
        'parents_club_member_subscription',
        'parents_club_member_account_overview',
        'parents_club_member_coins',
        'parents_club_member_billing',
        'parents_club_user_registration_form'
    );

    if ( ! in_array( $tag, $custom_tags, true ) ) {
        return $output;
    }

    // Do not filter in backend/frontend VC editor
    $is_vc_editor = is_admin() || ( function_exists( 'vc_is_frontend_editor' ) && vc_is_frontend_editor() );
    if ( $is_vc_editor ) {
        return $output;
    }

    // Determine the visibility setting with fallback to legacy hardcoded defaults if parameter is not set/saved
    $visibility = isset( $attr['pc_visibility'] ) ? $attr['pc_visibility'] : null;

    if ( $visibility === null || $visibility === 'all' ) {
        // Fallback checks for legacy shortcodes
        if ( $visibility === null ) {
            // Dashboard modules require active subscription by default
            $active_sub_only_tags = array(
                'parents_club_member_subscription',
                'parents_club_member_account_overview',
                'parents_club_member_coins',
                'parents_club_member_billing'
            );
            if ( in_array( $tag, $active_sub_only_tags, true ) ) {
                $visibility = 'active_subscribers';
            }
            
            // Plan cards and why_join default to excluding active subscribers
            $exclude_sub_tags = array(
                'parents_club_why_join',
                'emathsmart_plan_card'
            );
            if ( in_array( $tag, $exclude_sub_tags, true ) ) {
                $visibility = 'exclude_active_subscribers';
            }

            if ( 'emathsmart_subscription_product_card' === $tag ) {
                $show_to_subscribers = isset( $attr['show_to_subscribers'] ) ? $attr['show_to_subscribers'] : '';
                if ( 'yes' !== $show_to_subscribers ) {
                    $visibility = 'exclude_active_subscribers';
                } else {
                    $visibility = 'all';
                }
            }
        }
        
        // If still all or not matched by fallbacks, show to everyone
        if ( $visibility === null || $visibility === 'all' ) {
            return $output;
        }
    }

    $user_id = get_current_user_id();
    $is_logged_in = ! empty( $user_id );
    
    // Check if user is Parents Club Member
    $is_member = false;
    if ( $is_logged_in ) {
        $is_member = idl_loader_is_parents_club_member( $user_id );
    }

    // Check if user has active subscription
    $has_sub = false;
    if ( $is_logged_in && function_exists( 'wcs_user_has_subscription' ) ) {
        $has_sub = wcs_user_has_subscription( $user_id, '', 'active' );
    }

    switch ( $visibility ) {
        case 'guests':
            if ( $is_logged_in ) {
                return '';
            }
            break;
        case 'non_members':
            if ( ! $is_logged_in || $is_member ) {
                return '';
            }
            break;
        case 'members':
            if ( ! $is_logged_in || ! $is_member ) {
                return '';
            }
            break;
        case 'members_no_sub':
            if ( ! $is_logged_in || ! $is_member || $has_sub ) {
                return '';
            }
            break;
        case 'active_subscribers':
            if ( ! $is_logged_in || ! $is_member || ! $has_sub ) {
                return '';
            }
            break;
        case 'exclude_active_subscribers':
            if ( $has_sub ) {
                return '';
            }
            break;
    }

    return $output;
}

