<?php
/**
 * Custom WooCommerce Single Product Template for AI Coins
 * Styled to perfectly match the Parents Club Member Dashboard AI Coins Card Design
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
    if ( ! $product ) {
        continue;
    }

    // If it's a variation product, load its parent instead to render the full variations grid!
    if ( $product->is_type( 'variation' ) ) {
        $parent_id = $product->get_parent_id();
        if ( $parent_id ) {
            $product = wc_get_product( $parent_id );
        }
    }

    if ( ! $product || ! $product->is_type( 'variable' ) ) {
        // Fallback for non-variable or missing product
        wc_get_template_part( 'content', 'single-product' );
        continue;
    }

    $variations = $product->get_available_variations();
    $package_variations = [];

    foreach ( $variations as $var ) {
        $var_id = $var['variation_id'];
        
        // Find the attribute value that corresponds to the coins amount
        $coin_amount = 0;
        if ( ! empty( $var['attributes'] ) ) {
            foreach ( $var['attributes'] as $attr_key => $attr_val ) {
                if ( strpos( $attr_key, 'attribute_' ) === 0 ) {
                    // Extract only numbers from the attribute value, e.g. "100" -> 100
                    $clean_val = preg_replace( '/[^0-9]/', '', $attr_val );
                    if ( ! empty( $clean_val ) ) {
                        $coin_amount = intval( $clean_val );
                        break;
                    }
                }
            }
        }
        
        // If we didn't find any coin amount in the attributes, try to parse the variation description or image title
        if ( $coin_amount <= 0 ) {
            $clean_title = preg_replace( '/[^0-9]/', '', $var['image']['title'] ?? '' );
            if ( ! empty( $clean_title ) ) {
                $coin_amount = intval( $clean_title );
            }
        }
        
        // Fallback if still not found (skip invalid variation)
        if ( $coin_amount <= 0 ) {
            continue;
        }
        
        // Get variation price
        $display_price = $var['display_price'];
        $price_html = wc_price( $display_price );
        
        $package_variations[] = [
            'id' => $var_id,
            'coins' => $coin_amount,
            'price_html' => $price_html,
            'display_price' => $display_price,
            'attributes' => $var['attributes'],
        ];
    }

    // Sort variations by coin amount ascending
    usort( $package_variations, function( $a, $b ) {
        return $a['coins'] - $b['coins'];
    });

    // Determine the default selected coin package (e.g. 250 Coins if present, otherwise middle package)
    $default_coins = 250;
    $has_default = false;
    foreach ( $package_variations as $p ) {
        if ( $p['coins'] == $default_coins ) {
            $has_default = true;
            break;
        }
    }
    if ( ! $has_default && ! empty( $package_variations ) ) {
        $selected_index = floor( count( $package_variations ) / 2 );
        $default_coins = $package_variations[ $selected_index ]['coins'];
    }

    // Generate coin icon image URL relative to this template folder
    $coin_image_url = esc_url( plugins_url( 'images/coin.png', __FILE__ ) );
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
            --brand-text-dark: #1e293b;
            --brand-text-muted: #64748b;
            --border-light: #f1f5f9;
            --shadow-premium: 0 10px 30px -10px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.02);
            --shadow-hover: 0 20px 40px -15px rgba(0, 0, 0, 0.12), 0 4px 6px rgba(0, 0, 0, 0.04);
            --esmart-crimson: #ff3e1d;
            --esmart-crimson-hover: #e53212;
            --esmart-blue: #007aff;
            --esmart-green: #34c759;
            --esmart-orange: #ff9500;
            --body-bg: #fafbfc;
            --primary-btn: #ff3e1d;
            --primary-btn-hover: #e53212;
            --container-bg: #ffffff;

            font-family: 'Outfit', 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--brand-text-dark);
            padding: 60px 20px 80px 20px;
            position: relative;
            overflow: hidden;
            width: 100%;
            min-height: 100vh;
            box-sizing: border-box;
        }

        #emathsmart-custom-coins-product * {
            box-sizing: border-box;
        }

        #emathsmart-custom-coins-product .product-container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            padding: 45px;
            background: var(--container-bg);
            border-radius: 28px;
            border: 1px solid #eef2f6;
            box-shadow: var(--shadow-premium);
        }

        /* Custom Header styling inside content area only */
        #emathsmart-custom-coins-product .product-header {
            text-align: center;
            margin-bottom: 40px;
        }

        #emathsmart-custom-coins-product .badge {
            display: inline-block;
            padding: 6px 16px;
            background: #fff0eb;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 700;
            color: #ff3e1d;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        #emathsmart-custom-coins-product h1.coins-title {
            font-family: 'Outfit', sans-serif;
            font-size: 38px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: -0.02em;
            line-height: 1.15;
            text-transform: none;
        }

        #emathsmart-custom-coins-product .coins-sub {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            color: var(--brand-text-muted);
            max-width: 680px;
            margin: 0 auto;
            line-height: 1.5;
        }

        /* General Dashboard Card Centerpiece */
        #emathsmart-custom-coins-product .dashboard-card {
            background-color: #ffffff !important;
            border: 1px solid #eef2f6 !important;
            border-radius: 20px !important;
            padding: 30px !important;
            box-shadow: var(--shadow-premium) !important;
            position: relative !important;
            display: flex !important;
            flex-direction: column !important;
            margin-bottom: 40px !important;
        }

        #emathsmart-custom-coins-product .ai-coins-card {
            gap: 16px !important;
        }

        #emathsmart-custom-coins-product .coins-card-title {
            font-family: 'Outfit', sans-serif !important;
            font-size: 18px !important;
            font-weight: 700 !important;
            color: var(--brand-text-dark) !important;
            margin-bottom: 4px !important;
            margin-top: 0 !important;
            text-align: left !important;
            line-height: 1.25 !important;
        }

        /* Balance display area - Clean inline display without border or background box */
        #emathsmart-custom-coins-product .coins-balance-row {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            background: none !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            margin: 8px 0 4px 0 !important;
            box-shadow: none !important;
        }

        /* Coin wrapper - transparent and flush left */
        #emathsmart-custom-coins-product .coins-balance-icon-wrapper {
            flex-shrink: 0 !important;
            width: 38px !important;
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
        }

        #emathsmart-custom-coins-product .coins-balance-image {
            width: 100% !important;
            height: auto !important;
            display: block !important;
        }

        #emathsmart-custom-coins-product .coins-balance-text {
            display: flex !important;
            align-items: baseline !important;
            gap: 6px !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        #emathsmart-custom-coins-product .coins-balance-amount {
            font-family: 'Outfit', sans-serif !important;
            font-size: 34px !important;
            font-weight: 800 !important;
            color: var(--brand-text-dark) !important;
            line-height: 1 !important;
        }

        #emathsmart-custom-coins-product .coins-balance-label {
            font-family: 'Inter', sans-serif !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            color: var(--brand-text-muted) !important;
        }

        /* Description text starts precisely where the coin image starts */
        #emathsmart-custom-coins-product .coins-card-desc {
            font-family: 'Inter', sans-serif !important;
            font-size: 13.5px !important;
            line-height: 1.6 !important;
            color: var(--brand-text-muted) !important;
            margin-top: 4px !important;
            margin-bottom: 8px !important;
            text-align: left !important;
            padding-left: 0 !important;
            margin-left: 0 !important;
        }

        /* Elegant horizontal divider line - Hidden per design specs */
        #emathsmart-custom-coins-product .coins-divider {
            display: none !important;
        }

        /* Purchase section */
        #emathsmart-custom-coins-product .purchase-coins-section {
            display: flex !important;
            flex-direction: column !important;
            gap: 14px !important;
            margin-top: 10px !important;
        }

        #emathsmart-custom-coins-product .purchase-title {
            font-family: 'Outfit', sans-serif !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            color: var(--brand-text-dark) !important;
            margin-top: 0 !important;
            margin-bottom: 4px !important;
            text-align: left !important;
        }

        /* Responsive packages grid inside centerpiece */
        #emathsmart-custom-coins-product .purchase-packages-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 12px !important;
            width: 100% !important;
        }

        @media (min-width: 768px) {
            #emathsmart-custom-coins-product .purchase-packages-grid {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 16px !important;
            }
        }

        #emathsmart-custom-coins-product .coin-package-box {
            background-color: #ffffff !important;
            border: 1px solid #ffdcb5 !important;
            border-radius: 14px !important;
            padding: 22px 16px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: space-between !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            cursor: pointer !important;
            text-align: center !important;
            position: relative !important;
            min-height: 180px !important;
        }

        #emathsmart-custom-coins-product .coin-package-box:hover {
            border-color: var(--esmart-orange) !important;
            transform: translateY(-4px) !important;
            box-shadow: 0 10px 20px rgba(255, 149, 0, 0.08) !important;
        }

        #emathsmart-custom-coins-product .coin-package-box.selected {
            border-color: #ff3e1d !important;
            background-color: #fffbf9 !important;
            box-shadow: 0 10px 25px rgba(255, 62, 29, 0.08) !important;
            border-width: 2px !important;
        }

        #emathsmart-custom-coins-product .coin-package-amount {
            font-family: 'Outfit', sans-serif !important;
            font-size: 13.5px !important;
            font-weight: 700 !important;
            color: var(--brand-text-dark) !important;
            margin-bottom: 8px !important;
            display: block !important;
        }

        #emathsmart-custom-coins-product .coin-package-price {
            font-family: 'Outfit', sans-serif !important;
            font-size: 28px !important;
            font-weight: 800 !important;
            color: var(--brand-text-dark) !important;
            margin-bottom: 14px !important;
            letter-spacing: -0.5px !important;
            display: block !important;
            line-height: 1.1 !important;
        }

        /* Flat Premium Red-Orange CTA Buy Buttons */
        #emathsmart-custom-coins-product .btn-buy-coins {
            width: 100% !important;
            background-color: #ff3e1d !important;
            color: #ffffff !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            padding: 10px 14px !important;
            border-radius: 8px !important;
            text-transform: uppercase !important;
            text-decoration: none !important;
            text-align: center !important;
            letter-spacing: 0.5px !important;
            transition: all 0.2s ease !important;
            display: block !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: none !important;
            line-height: 1.2 !important;
        }

        #emathsmart-custom-coins-product .coin-package-box:hover .btn-buy-coins {
            background-color: #ff9500 !important;
            color: #ffffff !important;
            box-shadow: none !important;
        }

        #emathsmart-custom-coins-product .coin-package-box.selected .btn-buy-coins {
            background-color: #ff3e1d !important;
            color: #ffffff !important;
        }

        #emathsmart-custom-coins-product .coin-package-box.selected:hover .btn-buy-coins {
            background-color: #ff9500 !important;
            color: #ffffff !important;
        }

        /* Interactive checkout wrapper */
        #emathsmart-custom-coins-product .checkout-row {
            text-align: center;
            max-width: 600px;
            margin: 10px auto 40px auto;
        }

        /* Orange Pill Button matching the Portal Buttons */
        #emathsmart-custom-coins-product .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ff3e1d;
            background-image: linear-gradient(135deg, #ff5c38 0%, #ff3e1d 100%);
            color: white !important;
            padding: 16px 44px;
            border-radius: 100px;
            font-family: 'Outfit', sans-serif;
            font-size: 17px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(255, 62, 29, 0.25);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            width: 100%;
            max-width: 320px;
            line-height: 1.2;
        }

        #emathsmart-custom-coins-product .action-btn:hover {
            background-color: #ff9500;
            background-image: linear-gradient(135deg, #ffa61f 0%, #ff9500 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 149, 0, 0.35);
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
            color: var(--brand-text-muted);
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
            border: 1px solid #eef2f6;
            border-radius: 24px;
            padding: 45px 40px;
            margin-bottom: 10px;
            box-shadow: var(--shadow-premium);
            text-align: left;
        }

        #emathsmart-custom-coins-product .description-section h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 20px;
            letter-spacing: -0.01em;
            line-height: 1.2;
            text-transform: none;
        }

        #emathsmart-custom-coins-product .description-section h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 30px;
            margin-bottom: 15px;
            text-transform: none;
            line-height: 1.3;
        }

        #emathsmart-custom-coins-product .description-section p {
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            line-height: 1.6;
            color: #334155;
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
            color: var(--brand-text-muted);
        }

        #emathsmart-custom-coins-product .description-section li strong {
            color: #0f172a;
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
            color: #0f172a;
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
                padding: 30px 20px;
                border-radius: 22px;
            }
            #emathsmart-custom-coins-product h1.coins-title {
                font-size: 30px;
            }
            #emathsmart-custom-coins-product .dashboard-card {
                padding: 20px !important;
            }
        }
    </style>

    <div id="emathsmart-custom-coins-product">
        <div class="product-container">
            
            <!-- Custom Header -->
            <div class="product-header">
                <div class="badge">eMathSmart Integration</div>
                <h1 class="coins-title">AI Personalized Tutoring Coins</h1>
                <p class="coins-sub">Purchase interactive tutoring coins to unlock advanced personalized support, step-by-step interactive guidance, and real-time concept feedback within the eMathSmart learning portal.</p>
            </div>

            <!-- Centerpiece Dashboard Card: AI Coins Card -->
            <div class="dashboard-card ai-coins-card">
                <h3 class="coins-card-title">AI Coins Balance</h3>
                
                <!-- Balance Indicator Row (displays logged in user's dynamic balance or standard mockup fallback) -->
                <div class="coins-balance-row">
                    <div class="coins-balance-icon-wrapper">
                        <img src="<?php echo $coin_image_url; ?>" alt="AI Coin" class="coins-balance-image">
                    </div>
                    <div class="coins-balance-text">
                        <span class="coins-balance-amount"><?php 
                            $current_user_id = get_current_user_id();
                            $coins_balance = 0;
                            if ($current_user_id) {
                                // Attempt to load user's real balance
                                $coins_balance = get_user_meta($current_user_id, 'emathsmart_coins', true);
                                if (!$coins_balance) {
                                    $coins_balance = get_user_meta($current_user_id, 'additional_packages', true);
                                }
                                if (!$coins_balance) {
                                    $coins_balance = 120; // Default mockup balance
                                }
                            } else {
                                $coins_balance = 120; // Default mockup balance
                            }
                            echo esc_html($coins_balance);
                        ?></span>
                        <span class="coins-balance-label">coins</span>
                    </div>
                </div>
                
                <!-- Description -->
                <p class="coins-card-desc">Use AI coins to chat with your AI helper, and mark worksheets.</p>
                
                <!-- Divider Line -->
                <div class="coins-divider"></div>
                
                <!-- Purchase Options -->
                <div class="purchase-coins-section">
                    <h4 class="purchase-title">Purchase AI Coins</h4>
                    <div class="purchase-packages-grid">
                        
                        <?php foreach ( $package_variations as $pack ) : 
                            $is_selected = ( $pack['coins'] == $default_coins ) ? 'selected' : '';
                        ?>
                            <!-- Pack: <?php echo esc_html($pack['coins']); ?> Coins -->
                            <div class="coin-package-box <?php echo $is_selected; ?>" data-value="<?php echo esc_attr($pack['coins']); ?>" data-variation-id="<?php echo esc_attr($pack['id']); ?>">
                                <span class="coin-package-amount"><?php echo esc_html($pack['coins']); ?> Coins</span>
                                <span class="coin-package-price"><?php echo $pack['price_html']; ?></span>
                                <button type="button" class="btn-buy-coins">BUY NOW</button>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>

            <!-- Checkout Action Buttons -->
            <div class="checkout-row">
                <div class="payment-badges">
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

            const cards = wrapper.querySelectorAll('.coin-package-box');
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

            // Synchronize starting state based on default selection
            const initialSelectedCard = wrapper.querySelector('.coin-package-box.selected');
            if (initialSelectedCard) {
                const initialVal = initialSelectedCard.getAttribute('data-value');
                const initialVarId = initialSelectedCard.getAttribute('data-variation-id');
                syncSelectedValue(initialVal, initialVarId);
            }

            // Card click handler
            cards.forEach(card => {
                card.addEventListener('click', function(e) {
                    cards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');

                    const value = this.getAttribute('data-value');
                    const varId = this.getAttribute('data-variation-id');
                    syncSelectedValue(value, varId);

                    // If they clicked the BUY NOW button itself, immediately submit
                    if (e.target.classList.contains('btn-buy-coins')) {
                        e.preventDefault();
                        if (wooSubmitBtn) {
                            wooSubmitBtn.click();
                        }
                    }
                });
            });

            // Action button click handler -> triggers native woocommerce submission
            if (submitBtn && wooSubmitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Double check value is set
                    const selected = wrapper.querySelector('.coin-package-box.selected');
                    if (selected) {
                        const val = selected.getAttribute('data-value');
                        const varId = selected.getAttribute('data-variation-id');
                        syncSelectedValue(val, varId);
                    }

                    // Perform programmatic click on hidden native button
                    wooSubmitBtn.click();
                });
            }

            function syncSelectedValue(value, variationId) {
                if (wooSelect) {
                    // Find the option that robustly matches the numeric value (e.g. contains 100, 250, 500)
                    let targetOptionValue = value;
                    for (let i = 0; i < wooSelect.options.length; i++) {
                        const optVal = wooSelect.options[i].value;
                        const optText = wooSelect.options[i].text;
                        const optNumVal = optVal.replace(/[^0-9]/g, '');
                        const optNumText = optText.replace(/[^0-9]/g, '');
                        if (optNumVal === value || optNumText === value) {
                            targetOptionValue = optVal;
                            break;
                        }
                    }

                    // Update select dropdown option
                    wooSelect.value = targetOptionValue;
                    
                    // Dispatch change event so WooCommerce JS binds properly and resolves price/variation ID
                    wooSelect.dispatchEvent(new Event('change', { bubbles: true }));
                }

                // Also update variation_id hidden field if present
                const variationIdInput = hiddenFormContainer.querySelector('input[name="variation_id"]');
                if (variationIdInput && variationId) {
                    variationIdInput.value = variationId;
                    variationIdInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        });
    </script>
    
    <?php
endwhile; // End WordPress loop

get_footer( 'shop' ); 
?>
