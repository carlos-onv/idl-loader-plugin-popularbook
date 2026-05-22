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

    <!-- Scoped Custom Styling to avoid modifying Porto header or footer -->
    <style>
        #emathsmart-custom-coins-product {
            --body-bg: #faf8f5;
            --text-dark: #2c2520;
            --text-muted: #70655d;
            --glass-bg: rgba(255, 255, 255, 0.42);
            --glass-border: rgba(255, 255, 255, 0.55);
            --glass-shadow: rgba(43, 34, 27, 0.05);
            --accent-champagne: #ecdccb;
            --accent-champagne-glow: rgba(236, 220, 203, 0.45);
            --accent-rosegold: #f5c2bc;
            --accent-rosegold-glow: rgba(245, 194, 188, 0.45);
            --accent-gold: #e5a93c;
            --accent-gold-glow: rgba(229, 169, 60, 0.45);
            --primary-btn: #3a2e2b;
            --primary-btn-hover: #1e1614;

            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-dark);
            padding: 60px 20px 80px 20px;
            position: relative;
            overflow: hidden;
            width: 100%;
            border-top: 1px solid rgba(0, 0, 0, 0.03);
        }

        /* Background ambient lighting glows */
        #emathsmart-custom-coins-product .ambient-glow-1 {
            position: absolute;
            width: 650px;
            height: 650px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 194, 188, 0.25) 0%, rgba(236, 220, 203, 0.08) 50%, rgba(0,0,0,0) 80%);
            top: -250px;
            right: -150px;
            z-index: 1;
            pointer-events: none;
        }

        #emathsmart-custom-coins-product .ambient-glow-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(236, 220, 203, 0.2) 0%, rgba(245, 194, 188, 0.05) 50%, rgba(0,0,0,0) 80%);
            bottom: -150px;
            left: -150px;
            z-index: 1;
            pointer-events: none;
        }

        #emathsmart-custom-coins-product .product-container {
            max-width: 1120px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* Custom Header styling inside content area only */
        #emathsmart-custom-coins-product .product-header {
            text-align: center;
            margin-bottom: 50px;
        }

        #emathsmart-custom-coins-product .badge {
            display: inline-block;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
        }

        #emathsmart-custom-coins-product h1.coins-title {
            font-family: 'Outfit', sans-serif;
            font-size: 38px;
            font-weight: 800;
            color: #2c2520;
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

        /* Glassmorphic Selector Grid */
        #emathsmart-custom-coins-product .packages-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 50px;
        }

        #emathsmart-custom-coins-product .package-card {
            background-color: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 30px 24px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 10px 30px var(--glass-shadow);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            overflow: hidden;
        }

        #emathsmart-custom-coins-product .package-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.25),
                transparent
            );
            transition: 0.5s;
        }

        #emathsmart-custom-coins-product .package-card:hover::before {
            left: 100%;
        }

        #emathsmart-custom-coins-product .package-card:hover {
            transform: translateY(-6px);
            border-color: rgba(255, 255, 255, 0.85);
            box-shadow: 0 20px 40px rgba(43, 34, 27, 0.08);
        }

        #emathsmart-custom-coins-product .package-card.selected {
            border-color: #ffffff;
            background-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 15px 35px rgba(43, 34, 27, 0.12);
        }

        /* Card Themes */
        #emathsmart-custom-coins-product .package-card.theme-starter.selected {
            box-shadow: 0 0 0 3px var(--accent-champagne), 0 15px 35px var(--glass-shadow);
        }
        #emathsmart-custom-coins-product .package-card.theme-popular.selected {
            box-shadow: 0 0 0 3px var(--accent-rosegold), 0 15px 35px var(--glass-shadow);
        }
        #emathsmart-custom-coins-product .package-card.theme-elite.selected {
            box-shadow: 0 0 0 3px var(--accent-gold), 0 15px 35px var(--glass-shadow);
        }

        /* Popular Floating Badge */
        #emathsmart-custom-coins-product .popular-tag {
            position: absolute;
            top: 15px;
            right: 15px;
            background: linear-gradient(135deg, #f5c2bc 0%, #ecdccb 100%);
            border: 1px solid rgba(255, 255, 255, 0.6);
            color: #3a2e2b;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Card Content */
        #emathsmart-custom-coins-product .coin-icon-wrapper {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
        }

        #emathsmart-custom-coins-product .package-card:hover .coin-icon-wrapper {
            transform: scale(1.08) rotate(5deg);
        }

        #emathsmart-custom-coins-product .coin-icon {
            font-size: 32px;
            filter: drop-shadow(0 2px 4px rgba(43, 34, 27, 0.1));
        }

        #emathsmart-custom-coins-product .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #3c332d;
            margin-bottom: 6px;
        }

        #emathsmart-custom-coins-product .coin-quantity {
            font-size: 28px;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            color: #1e1614;
            margin-bottom: 12px;
        }

        #emathsmart-custom-coins-product .card-desc {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.4;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        #emathsmart-custom-coins-product .card-divider {
            width: 100%;
            height: 1px;
            background: rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
        }

        /* Dynamic WooCommerce Price mapping */
        #emathsmart-custom-coins-product .card-price {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #1e1614;
        }

        #emathsmart-custom-coins-product .card-price .price-free {
            font-size: 15px;
            color: #10b981;
            font-weight: 600;
            background: rgba(16, 185, 129, 0.08);
            padding: 4px 10px;
            border-radius: 6px;
        }

        /* Selection Indicator Checkbox */
        #emathsmart-custom-coins-product .select-indicator {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid rgba(0,0,0,0.15);
            background-color: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        #emathsmart-custom-coins-product .package-card.selected .select-indicator {
            border-color: #3a2e2b;
            background-color: #3a2e2b;
        }

        #emathsmart-custom-coins-product .select-indicator::after {
            content: '✓';
            color: white;
            font-size: 11px;
            font-weight: bold;
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

        #emathsmart-custom-coins-product .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-btn);
            color: white !important;
            padding: 16px 44px;
            border-radius: 30px;
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(58, 46, 43, 0.2);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            width: 100%;
            max-width: 320px;
        }

        #emathsmart-custom-coins-product .action-btn:hover {
            background-color: var(--primary-btn-hover);
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(58, 46, 43, 0.3);
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
            font-size: 12px;
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

        /* Features/Benefits list layout */
        #emathsmart-custom-coins-product .benefits-section {
            background-color: rgba(255, 255, 255, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 24px;
            padding: 40px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 8px 30px var(--glass-shadow);
        }

        #emathsmart-custom-coins-product .benefits-section h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #2c2520;
            margin-bottom: 30px;
            text-align: center;
        }

        #emathsmart-custom-coins-product .benefits-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        #emathsmart-custom-coins-product .benefit-item {
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }

        #emathsmart-custom-coins-product .benefit-icon {
            font-size: 22px;
            line-height: 1.2;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.9);
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.02);
            flex-shrink: 0;
        }

        #emathsmart-custom-coins-product .benefit-details h3 {
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 600;
            color: #3c332d;
            margin-bottom: 5px;
        }

        #emathsmart-custom-coins-product .benefit-details p {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.45;
        }

        /* Hide native WooCommerce elements visual display */
        #emathsmart-hidden-woo-form {
            display: none !important;
        }

        /* Responsive modifications */
        @media (max-width: 900px) {
            #emathsmart-custom-coins-product .packages-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            #emathsmart-custom-coins-product .benefits-grid {
                grid-template-columns: 1fr;
            }
            #emathsmart-custom-coins-product h1.coins-title {
                font-size: 32px;
            }
        }
    </style>

    <div id="emathsmart-custom-coins-product">
        <div class="ambient-glow-1"></div>
        <div class="ambient-glow-2"></div>

        <div class="product-container">
            
            <!-- Custom Header -->
            <div class="product-header">
                <div class="badge">eMathSmart Integration</div>
                <h1 class="coins-title">AI Personalized Tutoring Coins</h1>
                <p class="coins-sub">Unlock standard-aligned AI math support, animated explanations, and dynamic parent diagnostics. Choose the package that matches your child's goals.</p>
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
                    <p class="card-desc">Perfect for targeted math practice and homework assistance. Provides a solid introduction to smart AI tutoring features.</p>
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
                    <p class="card-desc">Ideal for weekly learning challenges and comprehensive skill remediation. Recommended for active curriculum reinforcement.</p>
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
                    <p class="card-desc">Complete math mastery bundle for full curriculum coverage and exam preparation. Ensures continuous adaptive guidance.</p>
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

            <!-- Features & Benefits Detail Block -->
            <div class="benefits-section">
                <h2>What eMathSmart AI Coins Unlock</h2>
                <div class="benefits-grid">
                    
                    <div class="benefit-item">
                        <div class="benefit-icon">🧠</div>
                        <div class="benefit-details">
                            <h3>AI-Powered Homework Explanations</h3>
                            <p>Instantly breaks down any challenging math problem into dynamic visual steps, empowering children to learn visually rather than just copying answers.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">🎓</div>
                        <div class="benefit-details">
                            <h3>24/7 Smart Curriculum Tutor</h3>
                            <p>An intelligent, highly encouraging tutor that leverages standard-aligned curriculum models to guide children through conceptual blockers step-by-step.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">🏆</div>
                        <div class="benefit-details">
                            <h3>Gamified Rewards & Achievements</h3>
                            <p>Keeps learning highly engaging with custom badges, printable wizard reward certificates, and digital milestone awards.</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-icon">📊</div>
                        <div class="benefit-details">
                            <h3>Smart Diagnostics Dashboard</h3>
                            <p>Delivers detailed diagnostic insights directly to parents, tracking progress on Canadian standards, highlights strengths, and uncovers mastery gaps.</p>
                        </div>
                    </div>

                </div>
            </div>

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
