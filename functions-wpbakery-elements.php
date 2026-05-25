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

// -----------------------------------------------------------------------------
// SECTION 3: WPBakery Shortcode Class Binder
// -----------------------------------------------------------------------------

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_parents_club_hero extends WPBakeryShortCode {
        // Automatically maps backend layout rendering
    }
}
