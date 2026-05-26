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
// SECTION 1: WPBakery Element Registration (vc_map)
// -----------------------------------------------------------------------------

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
                "type"        => "attach_image",
                "heading"     => esc_html__( "Top Banner Image", "book-junky" ),
                "param_name"  => "banner_image",
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
    if ( ! empty( $banner_image ) && is_numeric( $banner_image ) ) {
        $img_src = wp_get_attachment_image_src( $banner_image, 'full' );
        if ( $img_src ) {
            $banner_url = esc_url( $img_src[0] );
        }
    }
    if ( empty( $banner_url ) ) {
        $banner_url = plugins_url( 'templates/images/parents-club-banner.jpg', __FILE__ );
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

    $section_title = wp_kses_post( $attributes['section_title'] );
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
    <section id="parents-club-section-2">
        <div class="container">
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
        </div>
    </section>
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
}
