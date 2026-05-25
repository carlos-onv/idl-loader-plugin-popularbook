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
}
