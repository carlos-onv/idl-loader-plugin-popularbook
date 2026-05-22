<?php
/**
 * Custom WooCommerce Single Product Template for AI Coins
 * Direction: Option B - The Sleek Glass-Minimalist (Frosted Apple-style)
 * 
 * Satisfies: "Dont change the header in the design"
 * This template selectively styles the inner content area under an isolated namespace
 * while using standard get_header() and get_footer() of the active Porto theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Safe guard
}

// Ensure WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
    wc_get_template( 'single-product.php' );
    return;
}

get_header( 'shop' ); 

// Start the WordPress loop
while ( have_posts() ) : the_post();
    $product = wc_get_product( get_the_ID() );
    if ( ! $product || ! $product->is_type( 'variable' ) ) {
        // Fallback for non-variable or missing product
        wc_get_template_part( 'content', 'single-product' );
        continue;
    }

    $variations = $product->get_available_variations();
    ?>
    
    <!-- Load premium Google fonts Outfit and Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scoped Custom Styling to avoid modifying Book Junky header or footer -->
    <style>
        /* Force parent theme wrappers in Book Junky / Porto to be full width on this specific page template */
        body.single-product .wrap-boxed,
        body.single-product #page,
        body.single-product #content.site-content,
        body.single-product #main,
        body.single-product #main .container,
        body.single-product #main .main-content,
        body.single-product #main .col-lg-12,
        body.single-product #main .col-md-12,
        body.single-product #main .product-layout-default,
        body.single-product #main .main-content-inner,
        body.single-product #main .row {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            background-color: transparent !important;
        }

        /* Keep header, banner, and footer width centered and unaffected */
        body.single-product #masthead,
        body.single-product .site-footer {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        body.single-product #masthead .container,
        body.single-product .site-footer .container,
        body.single-product #footer-bottom .container,
        body.single-product #header .container,
        body.single-product #footer .container,
        body.single-product #header-inner .container,
        body.single-product #footer-inner .container {
            max-width: 1170px !important; /* Standard theme container max-width */
            margin: 0 auto !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        #emathsmart-custom-coins-product {
            --body-bg: #fde4ba;
            --text-dark: #5a3e2b;
            --text-muted: #8a6f59;
            --primary-btn: #f28538;
            --primary-btn-hover: #e07427;
            --container-bg: #ffffff;
            --card-border-selected: #f28637;

            font-family: 'Outfit', 'Inter', sans-serif;
            background-color: var(--body-bg);
            background-image: linear-gradient(180deg, #fde4ba 0%, #fedb9b 100%);
            color: var(--text-dark);
            padding: 60px 20px 80px 20px;
            position: relative;
            overflow: hidden;
            width: 100%;
            min-height: 100vh;
            border-top: 1px solid rgba(90, 62, 43, 0.05);
        }

        /* Decorative vertical slats pattern to match eMathSmart background */
        #emathsmart-custom-coins-product::before {
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

        #emathsmart-custom-coins-product .product-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            padding: 50px;
            background: var(--container-bg);
            border-radius: 28px;
            border: 2px solid #ffe9d2;
            box-shadow: 0 12px 40px rgba(90, 62, 43, 0.06);
        }

        /* Custom Header styling inside content area only */
        #emathsmart-custom-coins-product .product-header {
            text-align: center;
            margin-bottom: 50px;
        }

        #emathsmart-custom-coins-product .badge {
            display: inline-block;
            padding: 6px 18px;
            background: #ffffff;
            border: 1px solid #ffe2ca;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 700;
            color: var(--primary-btn);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 10px rgba(90, 62, 43, 0.03);
        }

        #emathsmart-custom-coins-product h1.coins-title {
            font-family: 'Outfit', sans-serif;
            font-size: 38px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }

        #emathsmart-custom-coins-product .coins-sub {
            font-size: 16px;
            color: var(--text-muted);
            max-width: 680px;
            margin: 0 auto;
            line-height: 1.5;
        }

        /* Selection Grid - Friendly Cards */
        #emathsmart-custom-coins-product .packages-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 50px;
        }

        #emathsmart-custom-coins-product .package-card {
            background-color: #fffcf7;
            border: 2px solid #ffe6cf;
            border-radius: 20px;
            padding: 35px 24px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 6px 18px rgba(90, 62, 43, 0.02);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            overflow: hidden;
            color: var(--text-dark);
        }

        /* Hover animation */
        #emathsmart-custom-coins-product .package-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-btn);
            box-shadow: 0 12px 30px rgba(90, 62, 43, 0.05);
        }

        /* Active Plan Card Styling: Golden/Yellow Gradient from Screenshot */
        #emathsmart-custom-coins-product .package-card.selected {
            background: linear-gradient(135deg, #ffdc8f 0%, #fdb558 100%);
            border-color: var(--card-border-selected);
            box-shadow: 0 10px 25px rgba(242, 134, 55, 0.2);
        }

        /* Card Gloss Reflection to match subscription card sheens */
        #emathsmart-custom-coins-product .package-card.selected::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.28) 0%, transparent 100%);
            pointer-events: none;
        }

        /* Best Value Floating Badge styled like Status Pills */
        #emathsmart-custom-coins-product .popular-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ffffff;
            border: 1px solid var(--primary-btn);
            color: var(--primary-btn);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            z-index: 3;
            box-shadow: 0 2px 6px rgba(242, 133, 55, 0.1);
        }

        /* Card Elements */
        #emathsmart-custom-coins-product .coin-icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            background: #ffffff;
            border: 2px solid #ffe6cf;
            box-shadow: inset 0 2px 4px rgba(90,62,43,0.02);
            transition: all 0.3s ease;
            z-index: 2;
        }

        #emathsmart-custom-coins-product .package-card.selected .coin-icon-wrapper {
            background: #ffffff;
            border-color: var(--primary-btn);
            box-shadow: 0 4px 12px rgba(242, 133, 55, 0.15);
        }

        #emathsmart-custom-coins-product .package-card:hover .coin-icon-wrapper {
            transform: scale(1.08);
        }

        #emathsmart-custom-coins-product .coin-icon {
            font-size: 32px;
            filter: drop-shadow(0 2px 4px rgba(90, 62, 43, 0.08));
        }

        #emathsmart-custom-coins-product .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            z-index: 2;
        }

        #emathsmart-custom-coins-product .coin-quantity {
            font-size: 28px;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            color: var(--text-dark);
            margin-bottom: 12px;
            z-index: 2;
        }

        #emathsmart-custom-coins-product .card-desc {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.45;
            margin-bottom: 20px;
            flex-grow: 1;
            z-index: 2;
        }

        #emathsmart-custom-coins-product .package-card.selected .card-desc {
            color: #634935;
        }

        #emathsmart-custom-coins-product .card-divider {
            width: 100%;
            height: 1px;
            background: rgba(90, 62, 43, 0.06);
            margin-bottom: 15px;
            z-index: 2;
        }

        #emathsmart-custom-coins-product .package-card.selected .card-divider {
            background: rgba(90, 62, 43, 0.1);
        }

        /* Dynamic WooCommerce Price mapping */
        #emathsmart-custom-coins-product .card-price {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
            z-index: 2;
        }

        #emathsmart-custom-coins-product .card-price .price-free {
            font-size: 14px;
            color: #10b981;
            font-weight: 700;
            background: rgba(16, 185, 129, 0.08);
            padding: 4px 10px;
            border-radius: 6px;
        }

        /* Selection Indicator Checkbox */
        #emathsmart-custom-coins-product .select-indicator {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid rgba(90,62,43,0.18);
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 2;
        }

        #emathsmart-custom-coins-product .package-card.selected .select-indicator {
            border-color: #ffffff;
            background-color: var(--primary-btn);
            box-shadow: 0 0 0 2px var(--primary-btn);
        }

        #emathsmart-custom-coins-product .select-indicator::after {
            content: '✓';
            color: white;
            font-size: 12px;
            font-weight: 800;
            opacity: 0;
            transition: all 0.2s ease;
        }

        #emathsmart-custom-coins-product .package-card.selected .select-indicator::after {
            opacity: 1;
        }

        /* Interactive checkout wrapper */
        #emathsmart-custom-coins-product .checkout-row {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 50px auto;
        }

        /* Orange Pill Button matching the Portal Buttons */
        #emathsmart-custom-coins-product .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-btn);
            background-image: linear-gradient(135deg, #f59652 0%, #eb731f 100%);
            color: white !important;
            padding: 16px 44px;
            border-radius: 100px;
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(242, 133, 55, 0.25);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            width: 100%;
            max-width: 320px;
        }

        #emathsmart-custom-coins-product .action-btn:hover {
            background-color: var(--primary-btn-hover);
            background-image: linear-gradient(135deg, #eb731f 0%, #d85c07 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(242, 133, 55, 0.35);
        }

        #emathsmart-custom-coins-product .action-btn:active {
            transform: translateY(0);
        }

        /* Sleek payment trust icons */
        #emathsmart-custom-coins-product .payment-badges {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
            font-size: 13px;
            color: var(--text-muted);
        }

        #emathsmart-custom-coins-product .payment-badges svg {
            height: 18px;
            opacity: 0.65;
            transition: opacity 0.3s ease;
        }

        #emathsmart-custom-coins-product .payment-badges svg:hover {
            opacity: 0.9;
        }

        /* eMathSmart-themed Product Description Box */
        #emathsmart-custom-coins-product .description-section {
            background-color: #fffdfb;
            border: 2px solid #ffe9d2;
            border-radius: 24px;
            padding: 45px 40px;
            margin-bottom: 40px;
            box-shadow: 0 6px 20px rgba(90, 62, 43, 0.02);
        }

        #emathsmart-custom-coins-product .description-section h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--text-dark);
            margin-top: 0;
            margin-bottom: 20px;
            letter-spacing: -0.01em;
        }

        #emathsmart-custom-coins-product .description-section h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 30px;
            margin-bottom: 15px;
        }

        #emathsmart-custom-coins-product .description-section p {
            font-size: 15px;
            line-height: 1.6;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        #emathsmart-custom-coins-product .description-section ul {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 25px;
        }

        #emathsmart-custom-coins-product .description-section li {
            position: relative;
            padding-left: 28px;
            margin-bottom: 12px;
            font-size: 14.5px;
            line-height: 1.5;
            color: var(--text-muted);
        }

        #emathsmart-custom-coins-product .description-section li strong {
            color: var(--text-dark);
        }

        /* Friendly orange stars as list bullet icons */
        #emathsmart-custom-coins-product .description-section li::before {
            content: '✦';
            position: absolute;
            left: 2px;
            top: 1px;
            color: var(--primary-btn);
            font-weight: 900;
            font-size: 15px;
        }

        #emathsmart-custom-coins-product .description-section blockquote {
            background: #fffbf6;
            border-left: 4px solid var(--primary-btn);
            padding: 22px 25px;
            margin: 30px 0;
            border-radius: 0 16px 16px 0;
            box-shadow: inset 0 1px 3px rgba(90,62,43,0.01);
        }

        #emathsmart-custom-coins-product .description-section blockquote p {
            margin-bottom: 12px;
            font-weight: 700;
            font-size: 15px;
        }

        #emathsmart-custom-coins-product .description-section blockquote ul {
            margin-bottom: 0;
        }

        #emathsmart-custom-coins-product .description-section blockquote li {
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        #emathsmart-custom-coins-product .description-section blockquote li::before {
            content: '•';
            color: var(--primary-btn);
            font-size: 18px;
            left: 5px;
            top: -2px;
        }

        #emathsmart-custom-coins-product .description-section hr {
            border: 0;
            height: 1px;
            background: linear-gradient(to right, rgba(90,62,43,0), rgba(90,62,43,0.08), rgba(90,62,43,0));
            margin: 35px 0;
        }

        /* Hide native WooCommerce elements visual display */
        #emathsmart-hidden-woo-form {
            display: none !important;
        }

        /* Responsive modifications */
        @media (max-width: 900px) {
            #emathsmart-custom-coins-product .product-container {
                padding: 35px 20px;
                border-radius: 22px;
            }
            #emathsmart-custom-coins-product .packages-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            #emathsmart-custom-coins-product h1.coins-title {
                font-size: 30px;
            }
        }
    </style>

    <div id="emathsmart-custom-coins-product">
        <div class="product-container">
            
            <!-- Custom Header -->
            <div class="product-header">
                <div class="badge">eMathSmart Integration</div>
                <h1 class="coins-title">AI Personalized Tutoring Coins</h1>
                <p class="coins-sub">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
            </div>

            <!-- Custom Packages Selection Grid -->
            <div class="packages-grid">
                
                <!-- Package 1: 100 Coins (Starter) -->
                <?php
                // Fetch variation price or data programmatically
                $price_100 = '';
                $price_html_100 = '<span class="price-free">Included in Subscription</span>';
                foreach ($variations as $var) {
                    if (isset($var['attributes']['attribute_pa_coins']) && $var['attributes']['attribute_pa_coins'] == '100') {
                        $price_100 = $var['display_price'];
                        if ($price_100 > 0) {
                            $price_html_100 = wc_price($price_100);
                        }
                        break;
                    }
                }
                ?>
                <div class="package-card theme-starter" data-value="100">
                    <div class="coin-icon-wrapper">
                        <span class="coin-icon">🥉</span>
                    </div>
                    <h3 class="card-title">Starter Bundle</h3>
                    <div class="coin-quantity">100 Coins</div>
                    <p class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <div class="card-divider"></div>
                    <div class="card-price"><?php echo $price_html_100; ?></div>
                    <div class="select-indicator"></div>
                </div>

                <!-- Package 2: 500 Coins (Popular) -->
                <?php
                $price_500 = '';
                $price_html_500 = '<span class="price-free">Included in Subscription</span>';
                foreach ($variations as $var) {
                    if (isset($var['attributes']['attribute_pa_coins']) && $var['attributes']['attribute_pa_coins'] == '500') {
                        $price_500 = $var['display_price'];
                        if ($price_500 > 0) {
                            $price_html_500 = wc_price($price_500);
                        }
                        break;
                    }
                }
                ?>
                <div class="package-card theme-popular selected" data-value="500">
                    <div class="popular-tag">Best Value</div>
                    <div class="coin-icon-wrapper">
                        <span class="coin-icon">🥈</span>
                    </div>
                    <h3 class="card-title">Popular challenge</h3>
                    <div class="coin-quantity">500 Coins</div>
                    <p class="card-desc">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <div class="card-divider"></div>
                    <div class="card-price"><?php echo $price_html_500; ?></div>
                    <div class="select-indicator"></div>
                </div>

                <!-- Package 3: 1000 Coins (Elite) -->
                <?php
                $price_1000 = '';
                $price_html_1000 = '<span class="price-free">Included in Subscription</span>';
                foreach ($variations as $var) {
                    if (isset($var['attributes']['attribute_pa_coins']) && $var['attributes']['attribute_pa_coins'] == '1000') {
                        $price_1000 = $var['display_price'];
                        if ($price_1000 > 0) {
                            $price_html_1000 = wc_price($price_1000);
                        }
                        break;
                    }
                }
                ?>
                <div class="package-card theme-elite" data-value="1000">
                    <div class="coin-icon-wrapper">
                        <span class="coin-icon">🥇</span>
                    </div>
                    <h3 class="card-title">Elite Tutor Bundle</h3>
                    <div class="coin-quantity">1000 Coins</div>
                    <p class="card-desc">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <div class="card-divider"></div>
                    <div class="card-price"><?php echo $price_html_1000; ?></div>
                    <div class="select-indicator"></div>
                </div>

            </div>

            <!-- Checkout Action Buttons -->
            <div class="checkout-row">
                <button type="button" class="action-btn" id="emathsmart-submit-btn">Secure Checkout</button>
                
                <div class="payment-badges">
                    <span>🔒 Secured by SSL</span>
                    <!-- Inline SVG Icons for Credit Cards -->
                    <svg viewBox="0 0 36 24" width="28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="36" height="24" rx="3" fill="#0A2540"/>
                        <path d="M11.6 15.6l1.2-7.2H11l-1.2 7.2h1.8zm3.2 0l1-5.7.5.3c.4.3.9.5 1.5.5.9 0 1.6-.4 1.8-1.2.2-.8-.2-1.3-.9-1.3-.4 0-.8.1-1.1.3l.1-.8h-.8l-1.2 7.2h1.5zm6.8 0l1.2-7.2h-1.6l-.3 1.9c-.2-.3-.6-.6-1.1-.6-1 0-1.9.9-2.1 2-.2 1.1.4 2 1.4 2 .5 0 .9-.2 1.2-.5l-.1.8h1.6zm-15.5 0H7.7l.8-5h1.6l-.3 1.9c.2-.3.6-.6 1.1-.6 1 0 1.9.9-2.1 2-.2 1.1.4 2 1.4 2 .5 0 .9-.2 1.2-.5l-.1.8zm23.6-7.2h-1.7c-.5 0-.9.3-1.1.7l-2.4 5.2-.9-4.8c-.1-.7-.6-1.1-1.3-1.1H21l.1.5c1 .2 1.5.6 1.7 1.4l1.8 7.3h1.7l3-7.2z" fill="white"/>
                    </svg>
                    <svg viewBox="0 0 36 24" width="28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="36" height="24" rx="3" fill="#1A1919"/>
                        <circle cx="15.5" cy="12" r="7" fill="#EB001B"/>
                        <circle cx="20.5" cy="12" r="7" fill="#F79E1B" fill-opacity="0.8"/>
                    </svg>
                    <svg viewBox="0 0 36 24" width="28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="36" height="24" rx="3" fill="#003087"/>
                        <path d="M12 9.2h2.8c1 0 1.7.2 2 .5.3.3.4.7.3 1.2-.1.8-.7 1.3-1.6 1.3h-2l-.5 2.8H11l1-6zm6.8 0H21c.9 0 1.5.2 1.8.5.2.3.3.6.2 1-.1.7-.6 1.2-1.4 1.2h-1.8l-.5 2.8h-2l1-6z" fill="#0079C1"/>
                    </svg>
                </div>
            </div>

            <!-- Dynamic Product Description Block -->
            <?php 
            $description = $product->get_description();
            if ( ! empty( $description ) ) : 
            ?>
                <div class="description-section">
                    <?php echo apply_filters( 'the_content', $description ); ?>
                </div>
            <?php endif; ?>


        </div>

        <!-- Hidden Native WooCommerce Add-To-Cart Form to preserve WooCommerce checkout logic -->
        <div id="emathsmart-hidden-woo-form">
            <?php woocommerce_variable_add_to_cart(); ?>
        </div>
    </div>

    <!-- Scoped Integration Script to seamlessly map cards to hidden Woo dropdowns -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.querySelector('#emathsmart-custom-coins-product');
            if (!wrapper) return;

            const cards = wrapper.querySelectorAll('.package-card');
            const submitBtn = wrapper.querySelector('#emathsmart-submit-btn');

            // Find hidden WooCommerce inputs
            const hiddenFormContainer = wrapper.querySelector('#emathsmart-hidden-woo-form');
            if (!hiddenFormContainer) return;

            // Attempt to bind attributes selector
            let wooSelect = hiddenFormContainer.querySelector('select[name="attribute_pa_coins"]') || 
                            hiddenFormContainer.querySelector('select[name="attribute_coins"]');

            if (!wooSelect) {
                // If the dropdown select is named slightly differently, search by attribute prefix
                wooSelect = hiddenFormContainer.querySelector('select[name^="attribute_"]');
            }

            const wooSubmitBtn = hiddenFormContainer.querySelector('.single_add_to_cart_button');

            // Synchronize starting state based on default selection (500 coins)
            syncSelectedValue('500');

            // Card click handler
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    cards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');

                    const value = this.getAttribute('data-value');
                    syncSelectedValue(value);
                });
            });

            // Action button click handler -> triggers native woocommerce submission
            if (submitBtn && wooSubmitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Double check value is set
                    const selected = wrapper.querySelector('.package-card.selected');
                    if (selected && wooSelect) {
                        const val = selected.getAttribute('data-value');
                        if (wooSelect.value !== val) {
                            syncSelectedValue(val);
                        }
                    }

                    // Perform programmatic click on hidden native button
                    wooSubmitBtn.click();
                });
            }

            function syncSelectedValue(value) {
                if (!wooSelect) return;

                // Update select dropdown option
                wooSelect.value = value;
                
                // Dispatch change event so WooCommerce JS binds properly and resolves price/variation ID
                wooSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    </script>
    
    <?php
endwhile; // End WordPress loop

get_footer( 'shop' ); 
?>
