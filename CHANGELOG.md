# Changelog - PopularBook eMathSmart Integration

All notable changes to this project will be documented in this file for both human developers and AI agents.

## [2026-06-04] - Member Dashboard & Account Overview High-Fidelity Redesign

### Added
- **Account Overview Component & Insulated CSS**:
  - Extracted the inline Tailwind-style rules for the `#account-overview` section from the dashboard mockup.
  - Created a new insulated standalone modular stylesheet: [parents-club-dashboard-account-overview.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-dashboard-account-overview.css) with all styles scoped under `#parents-club-dashboard #account-overview` and fully insulated for WordPress theme compliance.
  - Linked the new account overview stylesheet contextually inside the `<head>` of [parents-club_member.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club_member.html).
  - Integrated the clean, semantic markup structure for `#account-overview` directly inside the `.container` element as a sibling below the main cards layout inside the staging sandbox mockup.
  - Replaced the font-based icon codes in the Account Overview component with high-fidelity inline SVGs to remove dependencies on external icon fonts.

### Changed
- **Member Dashboard Template Overhaul**:
  - Replaced the old `#parents-club-dashboard` section in [parents-club_member.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club_member.html) with the high-fidelity three-card layout (`.sub-card`, `.coins-card`, `.bill-card`) copied from `eMathSmart Subscription.html`.
  - Updated image paths to point to correct local resources (`images/eMathSmart_logo_FINAL%20.png`, `images/subscription-active.png`, and `images/coin.png`) in the templates folder.
  - Aligned the subscription action buttons (`.obtn`) and receipt download button (`.download-btn`) styling with `.pc-plan-btn` (Outfit typography, uppercase text, font-weight 800, 8px border-radius, color-fill hover transitions, hover box-shadow, and current-color SVG stroke inheritance).
- **Modular Dashboard CSS Separation**:
  - Separated subscription, coins, and billing card styles from the static sandbox template into individual, modular CSS files.
  - Overwrote the template-specific CSS files:
    - [parents-club-dashboard-subscription.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-dashboard-subscription.css)
    - [parents-club-dashboard-coins.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-dashboard-coins.css)
    - [parents-club-dashboard-billing.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-dashboard-billing.css)
  - Scoped all CSS rules under `#parents-club-dashboard` and applied strict Porto/WordPress theme insulation controls.
  - Removed the background color from the `.included` element inside the subscription component stylesheet.
- **Member Dashboard & Account Overview Layout Backgrounds**:
  - Removed the global cream background (`var(--cream)`) from `#parents-club-dashboard` to let it blend cleanly into the main WordPress theme container.
  - Added the cream background color (`var(--cream)`) specifically to the `.card.sub-card` subscription card component and the `#account-overview` component for high-fidelity contrast.

### Technical Notes for AI Agents
- The newly structured member dashboard HTML has been integrated into the sandbox template.
- All styles are insulated contextually under the `#parents-club-dashboard` container selector.

## [2026-06-03] - Parents Club Member Quick Links WPBakery Element

### Added
- **Parents Club Member Quick Links Element**:
  - Registered custom WPBakery element `parents_club_member_quick_links` (under "eMathSmart Elements" category) based on the `#parents-club-member-quick-links` mockup markup.
  - Implemented configurable header title (default: "Quick Links").
  - Configured visual parameters utilizing repeatable `param_group` to allow adding, editing, reordering, and deleting buttons dynamically.
  - Equipped buttons with text fields, VC link fields, and a selection of icon sources: predefined chevron right SVG, icon picker libraries (Font Awesome / Linecons), or custom SVG/image upload.
  - Automatically enqueues the component-specific modular stylesheet `parents-club-member-quick-links.css` contextually in the shortcode renderer.

### Technical Notes for AI Agents
- Shortcode: `[parents_club_member_quick_links]`
- Visual Composer Base: `parents_club_member_quick_links`
- Associated Class: `WPBakeryShortCode_parents_club_member_quick_links`

## [2026-06-03] - Welcome Element Wave Tag Support

### Fixed
- **Welcome Element Wave Placeholder**: Support both `{wave}` and `[wave]` placeholders in the welcome title template to ensure the animated waving hand emoji renders correctly on the front end.

## [2026-06-02] - Member Hero Section & parents-club_member.html Template Staging

### Added
- **Parents Club Member Welcome Element**:
  - Registered a new custom WPBakery element `parents_club_member_welcome` (under "eMathSmart Elements" category) based on the `#member-welcome-column` dashboard section layout.
  - Dynamically fetches and outputs the logged-in user's first name, falling back to the display name, or a default name if logged out.
  - Implemented full parameterization for the title template (with support for `{user_name}` and `[wave]` for the waving hand emoji 👋) and subtitle.
  - Equipped with background image modularity (`attach_image` background field) with responsive gradient overlays.
  - Equipped checklist badge attributes with a dynamic, repeatable `param_group` container supporting bold texts, subtexts, and a selection of brand SVG icons, standard picker libraries, or custom upload assets.
  - Contextually enqueues `parents-club-member-welcome.css` to keep stylesheet footprints clean.
- **Member Quick Links Section**:
  - Integrated high-fidelity "Quick Links" section (`#parents-club-member-quick-links`) between Member Hero and Member Dashboard sections inside [parents-club_member.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club_member.html).
  - Features a rounded horizontal bar with a light grey/slate background, vertical divider line, and three vivid brand red buttons ("Our Canadian Teachers", "Learning Tips", and "Free Worksheets") with right-facing chevrons.
- **Section Layout Reordering & Plans Section Integration**:
  - Relocated Section 2 (`#parents-club-section-2`: Why Join Features Bar) from the bottom of the template to sit immediately after the new Quick Links section.
  - Copied and integrated Section 4 (`#parents-club-section-4`: eMathSmart Plans Card Section) from [parents-club.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club.html) to sit immediately after Section 2.
  - Configured layout to leverage already linked plans CSS files contextually.
- **Dedicated Quick Links CSS**:
  - Created and refined [parents-club-member-quick-links.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-member-quick-links.css) to style the section.
  - Implemented high-fidelity details: Outfit typography (`font-weight: 800` for main title, `700` for button labels), `#b30026` button fill matching the design color, precise custom shadows (`rgba(179, 0, 38, 0.16)`), and SVG chevron stroke widths.
  - Applied the custom light rose background color `#fdf9fb` for the quick links bar container as requested.
  - Built full responsiveness: stacks elements vertically, centers alignment, and scales buttons to 100% width on tablet/mobile screens.
- **New HTML Template**:
  - Created [parents-club_member.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club_member.html) sandbox mockup for logged-in parents.
  - Replaced the landing-page sign-up/login hero section with the new member-specific welcome and benefits section layout.
  - Replaced welcome card markup structure: moved `.member-attribute-list` outside of `.member-welcome-content` to enable Flexbox's `justify-content: space-between` to push the attributes checklist to the bottom boundary of the welcome card.
- **Dedicated Welcome & Brand Component CSS**:
  - Created [parents-club-member-welcome.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-member-welcome.css) to style the welcome card (tuned with a high-fidelity `24px` border-radius).
  - Switched background scaling from `background-size: cover` to `background-size: auto 100%` to solve the vertical cropping zoom bug, restoring full visibility of the kid, father, and learning environment.
  - Refined horizontal linear-gradient overlay parameters to fade the left portion (covering the out-of-focus mother) and blend seamlessly into the card's solid white background.
  - Tightened welcome header `line-height` to `1.1` and subtitle `line-height` to `1.45` for precise typographical balance.
  - Copied and integrated the Canadian parents, curriculum-aligned, and trusted badge attributes from the landing page.
- **Dedicated Benefits Card Component CSS**:
  - Created [parents-club-member-benefits.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-member-benefits.css) to style the red benefits card (tuned with a high-fidelity `24px` border-radius).
  - Copied and adapted the `.benefits-column` layout into a clean flexbox container that stretches to align with the welcome card on desktop.
  - Configured a responsive two-column grid list of benefits with hover zoom/slide transitions, transparent circular outline icons, and updated Benefit 3 to use the standard document icon.

### Technical Notes for AI Agents
- **Staging Files**:
  - HTML Sandbox mockup: `/wp-content/plugins/idl-loader/templates/parents-club_member.html`
  - Welcome CSS: `/wp-content/plugins/idl-loader/templates/css/parents-club-member-welcome.css`
  - Benefits CSS: `/wp-content/plugins/idl-loader/templates/css/parents-club-member-benefits.css`
  - Quick Links CSS: `/wp-content/plugins/idl-loader/templates/css/parents-club-member-quick-links.css`
- **Design Guidelines**:
  - Element 1 (`.member-welcome-column`) displays a wave animation on the waving hand emoji 👋.
  - Element 2 (`.member-benefits-column`) uses a solid crimson red background (`#af0128`) and features a two-column grid layout for list items.
  - Quick Links Section uses a slate-light background (`#f8fafc`), Outfit/Inter typography, and red buttons with SVG chevron indicators.

## [2026-06-01] - AI Coins Gated Redirect Update

### Changed
- **Gated Access Redirection Target**:
  - Modified `emathsmart_gate_ai_coins_access` to redirect unauthenticated/logged-out users attempting to visit the restricted `/product/ai-coins/` page (or its variations) to the custom eMathSmart login gateway `/emathsmart-login` instead of the general `/parents-club` landing page.
  - The redirection successfully preserves the `restricted_access=ai-coins` and `reason=not_logged_in` parameters.
- **Custom Restricted Access Warning Notice**:
  - Extended the `the_content` notice renderer `emathsmart_display_gated_notice_on_parents_club` to run on the `/emathsmart-login` page.
  - Custom-tailored the notice text when viewed on `/emathsmart-login` to read: *"AI Coins are exclusively available to active subscribers. Please log in below to access the purchase page."*

## [2026-05-29] - Custom eMathSmart OAuth Redirect, Troubleshooting Support & Sandbox CTA Section

### Added
- **Premium Support CTA Section (Section 5-b)**:
  - Integrated the HTML markup for a new horizontal promotional CTA banner (`#parents-club-cta-banner`) in [parents-club.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club.html) placed dynamically before Section 6 (FAQ + Need Help).
  - Features the high-quality cropped `parents-club-cta-tablet.png` illustration of a mother and child using a tablet on the left.
  - Features a clean solid crimson background (`#af0128`) containing dynamic title and description text on the right (with a forced `<br>` line break after *'trusted resources,'* to match the visual reference exactly).
  - Includes a vertical stack of two high-fidelity buttons: a white solid button (*"Join Parents' Club - It's Free!"*) and a rich eMathSmart themed orange button (*"Explore eMathSmart"*).
- **New Modular WPBakery Element `[parents_club_cta_banner]`**:
  - Registered the custom element `parents_club_cta_banner` inside `functions-wpbakery-elements.php` under the *'eMathSmart Elements'* visual builder category.
  - Equipped with full parameterization: Left Banner Image selector (`attach_image`), Banner Title (`textfield`), Banner Description (`textarea`), and button label and custom target mappings (`vc_link` parameter type) for both the white and orange CTA action buttons.
  - Added shortcode renderer handler `idl_loader_parents_club_cta_banner_shortcode` which dynamically enqueues its modular stylesheet, resolves image assets with custom-uploaded options, builds custom visual composer links, and formats description line-breaks safely.
  - Registered helper binder class `WPBakeryShortCode_parents_club_cta_banner` for backend page layout mapping.
- **Dedicated Modular Stylesheet**:
  - Created a new standalone stylesheet [parents-club-cta-banner.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-cta-banner.css) to manage all styling, hover transitions, and viewport-specific layouts of the new CTA banner.
  - Linked the new stylesheet contextually inside the `<head>` of the [parents-club.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club.html) file.
- **Dynamic eMathSmart OAuth Guest Redirect**:
  - Implemented the `emathsmart_custom_oauth_login_url` callback filter hooked into `login_url` to capture unauthenticated SSO authorization requests containing `/oauth/authorize`.
  - Automatically redirects unauthorized guests coming from the eMathSmart portal to the dedicated `/emathsmart-login` page instead of the default `/wp-login.php` or `/parents-club#login`, while preserving the critical `redirect_to` destination parameter to successfully resume the SSO session upon login.
- **Universal Frontend Redirection Binder**:
  - Added a client-side JavaScript injection hook (`emathsmart_inject_redirect_to_js`) in `wp_footer` that automatically captures the `redirect_to` parameter from the URL and binds/injects it to any active login forms on the page (supporting WPBakery custom forms, WooCommerce, Ultimate Member, WPEverest, etc.). This ensures that no matter what login element is used in `/emathsmart-login`, the redirection back to eMathSmart completes successfully on form submission.
- **Dynamic SSO Action Shortcodes**:
  - Registered `[emathsmart_continue_button]`, `[emathsmart_logout_link]`, and `[emathsmart_logout_url]` helper shortcodes to provide dynamically-computed SSO continuation buttons, account switching logouts, and raw secure logout URLs (complete with active WordPress security nonces and query redirection targets) that can be pasted directly into WPBakery button link fields.

### Changed
- **Robust Client-Side Registration Link Rewrite**:
  - Refined the `emathsmart_inject_redirect_to_js` client-side script to use a highly robust checking pattern that matches absolute, relative, and query-parameter based registration URLs (e.g. `?action=register` or `/wp-login.php?action=register`) and directly targets WPEverest User Registration Pro containers (`.user-registration-register` and `.register`). This guarantees the "Register now" link is successfully rewritten to the Parents' Club sign-up landing page (`/parents-club/`) under all conditions on the `/emathsmart-login` page.
- **Scoped eMathSmart Login Form Endpoint Overrides**:
  - Hooked into both `lostpassword_url` (WordPress/WooCommerce) and `register_url` filters (`emathsmart_scoped_lostpassword_url` & `emathsmart_scoped_register_url`) to force correct absolute endpoints (`/my-account/lost-password/` and `/parents-club/` respectively) **exclusively** on the `/emathsmart-login` page. This prevents WPBakery login widgets from generating broken relative paths.
- **Redesigned eMathSmart Continue Button**:
  - Upgraded the visual styling of the `[emathsmart_continue_button]` shortcode output to a warm peach-orange color palette (`background: #f9954b`, `border-radius: 22px`, `color: #FFFFFF`) with full CSS hover states (transitioning smoothly to a white background with a peach outline and text color).
- **Optimized Frontend Redirection Script Scope**:
  - Scoped the client-side JavaScript injection hook (`emathsmart_inject_redirect_to_js`) to load and run exclusively on the dedicated `/emathsmart-login` page or when an active OAuth authorize query is present, ensuring a 100% clean footprint on standard frontend pages.
- **Redirection Target Destination**:
  - Updated the eMathSmart guest SSO redirect URL target from `/parents-club?redirect_to=...#login` to `/emathsmart-login?redirect_to=...`, allowing users to land on a clean, dedicated eMathSmart login page.
- **URL Query & Hash Anchor Order Correction**:
  - Fixed standard redirection URL construction structure: moved the `redirect_to` query parameter *before* any client-side hash elements to ensure the web server successfully receives and reads the `$_REQUEST['redirect_to']` parameters.
- **Bypassed Ultimate Member Redirection Hijacking**:
  - Updated the custom callback handler `um_pc_default_page_user_login()` inside `functions.php` to immediately detect and prioritize outgoing SSO redirects. This stops Ultimate Member from hijacking the post-login destination and forcing Parents' Club members to the main `/parents-club` dashboard when they arrive from an external OAuth flow.
- **Robust Redirect Matching & Ultra-High Hook Priority**:
  - Upgraded `login_url` hook priority to `999999` to successfully bypass other active URL-obfuscation and page redirection plugins (like WP Hide & Security Enhancer which rewrites `wp-login.php` to `portal.php`).
  - Added a fallback comparison against the `$redirect` parameter inside `emathsmart_custom_oauth_login_url` to ensure redirects are captured even if the server environment rewrites standard `$_SERVER['REQUEST_URI']` queries during internally routed OAuth 2.0 authorization requests.

### Technical Notes for AI Agents
- **Redirect Filter Handler**: `emathsmart_custom_oauth_login_url` in `/wp-content/plugins/idl-loader/functions-esmart.php`
- **UM Login Hook Override**: `um_pc_default_page_user_login` in `/wp-content/plugins/idl-loader/functions.php`

## [2026-05-28] - eMathSmart Dynamic Subscription Product Card & 14-Day Free Trial Extension

### Added
- **Staging Webhook Sandbox Compatibility Support (API #5 paymentNotify)**:
  - Implemented Dual-Key support: the JSON body transmits both v1.3 keys and v1.4 keys.
  - **INVESTIGATION COMPLETE (May 28, 2026):** After exhaustive bulk probe testing (15 field-subset combinations, 5 secret key variants, real IDs, fake IDs, historical orderIds, integer vs string types) — the root cause of `20306` is confirmed:
    - The eMathSmart **staging server was upgraded to v1.4** at some point after our last successful call (orderId 116377).
    - The v1.4 server **mandates `parentId`** in every request body (v1.3 format now returns `400 parentClubParentId is required`).
    - The v1.4 server uses a **NEW HMAC secret key** that has not been provided to us. Our key `yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm` is rejected for ALL request formats.
  - **Action Required:** Contact eMathSmart (Jatin) and request the new staging API secret key for `appId=ParentClub`.
  - **Message to send:** *"Please provide the current staging API secret key for the ParentClub appId — the key was rotated during the v1.4 server update and our webhook integration is returning 20306 for all request formats."*

- **API Secret Key Admin Panel** (`WooCommerce → eMathSmart Settings`):
  - Added a new WordPress admin settings page (`functions-esmart-admin.php`) that exposes the HMAC secret key as an editable field.
  - When eMathSmart provides the new key, paste it in the admin panel and click Save — **no file upload required**.
  - Shows current key status (default vs custom), SHA-256 fingerprint, and key length for verification.
  - Includes "Reset to Default" button and full WordPress nonce security.

- **Secret Key Externalized from Code** (`functions-esmart.php`):
  - Both hardcoded `$secret = "..."` literals replaced with `get_option('emathsmart_api_secret', 'fallback')`.
  - Applies to both the paymentNotify/refundNotify loop (line ~440) and the publicExams helper function (line ~167).



- **Dynamic 14-Day Trial Hook & Eligibility Checks**:
  - Implemented `emathsmart_is_eligible_parents_club_member( $user_id )` to verify if the user has the user meta `'user_registration_check_box_1661192013'` set to `'parent_club_member'` and was registered on or before `2026-05-28` UTC (cutoff of `2026-05-29 00:00:00 UTC`).
  - Added filter callback hooked into `woocommerce_subscriptions_product_trial_length` to override the trial length from 7 days to 14 days dynamically for eligible old Parents Club members.
  - Relocated eligibility check and WooCommerce subscriptions filter code from `functions.php` to `functions-esmart.php` for consolidated management.
  - Added an `is_admin()` bypass check to the dynamic trial length filter `emathsmart_dynamic_trial_length_for_parents_club` to prevent the filter from overriding inputs and causing side-effects when administrators modify and save subscription products inside the WordPress Admin product editor.
- **New Modular WPBakery Element `[emathsmart_subscription_product_card]`**:
  - Registered the custom element via `vc_map()` with a dynamic dropdown product selector that lists WooCommerce parent subscriptions and child subscription variations (`idl_loader_get_subscription_products()`).
  - Added a `product_id_override` custom textfield parameter fallback so administrators can manually enter any WooCommerce Product/Variation ID directly, overriding the dropdown selector.
  - Equipped with highlight borders, "Best Value" visual capsules, customizable overrides (Title, Price, Period, Badge text, Button CTA text, and dynamic redirect target), and the WPBakery `param_group` for dynamic visual checklists.
- **Shortcode Renderer & Class Binder**:
  - Registered shortcode handler `idl_loader_emathsmart_subscription_product_card_shortcode()`.
  - Contextually enqueues plans layout stylesheets: `parents-club-plans-base`, `parents-club-plan-monthly`, and `parents-club-plan-annual`.
  - Automatically queries active WooCommerce products, dynamically computes dynamic eligibility visual badges ("14-Day Free Trial" vs "7-Day Free Trial"), and binds the button CTA redirection target dynamically to `/subscription/?add-to-cart-login=PRODUCT_ID`.
  - Bound `WPBakeryShortCode_emathsmart_subscription_product_card` class within WPBakery's layout renderer.

### Changed
- **WooCommerce Product Configuration**:
  - Corrected the default database subscription trial length meta field `_subscription_trial_length` of the Yearly Subscription product (`116578`) from `14` days to `7` days to match standard trials. This prevents guests/ineligible users from receiving 14 days, ensuring only eligible old Parents Club members dynamically receive the 14-day trial extension.

### Technical Notes for AI Agents
- **Eligibility Helper**: `emathsmart_is_eligible_parents_club_member()`
- **Core Filter Hook**: `woocommerce_subscriptions_product_trial_length`
- **WPBakery Selector Query**: `idl_loader_get_subscription_products()`
- **Shortcode Handle**: `emathsmart_subscription_product_card`
- **Class Binder**: `WPBakeryShortCode_emathsmart_subscription_product_card`

## [2026-05-28] - Parents Club Member Dashboard Integration

### Added
- **New Section: Member Dashboard**: Integrated a high-fidelity visual dashboard section (`#parents-club-dashboard`) right before Section 5 inside [parents-club.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club.html).
- **Component 1: eMathSmart Subscription**: Created dynamic responsive card featuring eMathSmart logo, active badge status, key-value checklist details grid with custom checkmark square inline SVGs, a tablet graphic layout, a list of what's included, and interactive action CTAs.
- **Component 2: AI Coins**: Created Coins balance profile section incorporating `coin.png` glow animation, balance labels, and purchase boxes for coin packages equipped with dynamic orange button hover highlights.
- **Component 3: Billing History**: Created a clean transaction list table with dashed borders, light slate grey dates, and a solid crimson outlined "Download Receipt History" button at the bottom.
- **Modular Stylesheets**: Structured and saved three new independent CSS files in `templates/css/` to manage elements modularity:
  - [parents-club-dashboard-subscription.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-dashboard-subscription.css)
  - [parents-club-dashboard-coins.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-dashboard-coins.css)
  - [parents-club-dashboard-billing.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-dashboard-billing.css)

### Changed & Refined
- **AI Coins Centerpiece Centering & Variation Images Support**:
  - Restored and centered the single AI coin image (`coin.png`) directly above the description paragraph in the dynamic single product centerpiece card.
  - Added programmatic support to load and display the high-fidelity variation images from WooCommerce dynamically inside each package selection box (`.coin-package-box`).
  - Adjusted the grid package card CSS styling (`min-height: 250px`, `.coin-package-image-wrapper`, `.coin-package-image`) to gracefully accommodate the variation image.
- **AI Coins Purchase Template Overhaul**: Redesigned the variable product selection template [single-product-ai-coins.php](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/single-product-ai-coins.php) using the refined dashboard coins aesthetics:
  - Swapped out the 🥉, 🥈, and 🥇 emoji placeholders on the starter, popular, and elite cards for the actual high-fidelity `coin.png` image asset.
  - Formatted and styled the `.coin-icon-wrapper` and `.coin-icon` to be completely transparent, flat, and shadow-free to match the dashboard design alignment.
  - Implemented a highly robust programmatic price-sync loop that dynamically extracts display prices from all variation attributes using a clean number-extractor, ensuring flawless WooCommerce price synchronization and preventing database attribute-mismatches.
  - Upgraded the client-side JavaScript synchronizer to search select options by cleaned numeric digits, guaranteeing reliable dropdown binding even if variation values are formatted differently (e.g. `'100'`, `'100-coins'`).
- **AI Coins Page Actions Refinement**: Removed the redundant "Secure Checkout" button, the "Secured by SSL" trust text badge, and the credit card logo SVGs completely from the single product page [single-product-ai-coins.php](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/single-product-ai-coins.php). Additionally, cleaned up all unused checkout-related CSS classes (`.checkout-row`, `.action-btn`, `.payment-badges`) and obsolete JavaScript `submitBtn` variables/event listeners to keep the codebase perfectly pristine. Letting users purchase packages seamlessly and directly via the high-fidelity package cards.
- **Refined AI Coins Card Centering & Cleanup**: Redesigned and simplified the centerpiece card structure in [single-product-ai-coins.php](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/single-product-ai-coins.php):
  - Changed the centerpiece card title from "AI Coins Balance" to "AI Coins" (removing the word "Balance").
  - Centered both the card title (`.coins-card-title`) and the card description (`.coins-card-desc`) perfectly inside the container using `text-align: center !important`.
  - Completely removed the dynamic balance indicator row displaying the "120 coins" amount and coin icon, cleaning up all corresponding unused CSS selectors.
  - Hidden the `.coins-divider` line entirely to maintain design simplicity.
  - Styled `.btn-buy-coins` as a clean, flat button by removing its drop shadow.
  - Incremented version parameter to `?v=1.3.1` in [parents-club.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club.html) to bust aggressive style caches.
- **Clean Core Functions.php Revert**: Reverted and completely removed all temporary dashboard enqueues from [functions.php](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/functions.php). Since `parents-club.html` serves strictly as a sandbox playground for WPBakery element design, enqueuing them globally is unnecessary. This fully restores `functions.php` to its pristine core baseline.
- **Stylesheet Cache-Busting Versioning**: Implemented robust version query overrides (`1.3.0`) across all dashboard assets to bypass aggressive client-side browser and server caching:
  - **Static link tags**: Appended `?v=1.3.0` directly to the dashboard `<link>` styles in [parents-club.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club.html).
- **High-Fidelity AI Coins Component Overhaul**: Redesigned the entire `AI Coins` dashboard module inside [parents-club.html](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/parents-club.html) and [parents-club-dashboard-coins.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-dashboard-coins.css) to perfectly match high-fidelity visual specs:
  - **Warm Peach Balance Banner**: Styled `.coins-balance-row` with a warm peach-cream background (`#fffdf6`) and a distinct peach border (`#ffdcb5`).
  - **White Glow Icon Ring**: Added a circular white background with soft gold glow and drop shadow behind the coin graphic wrapper (`.coins-balance-icon-wrapper`) to make it pop visually.
  - **Spacious Divider Line**: Integrated a distinct `.coins-divider` element with solid light border-top and spacious vertical margins to cleanly isolate the balance section from the purchase container.
  - **Premium Solid Buy Buttons**: Overhauled purchase card boxes with spacious padding and warm peach borders, and styled `.btn-buy-coins` to be taller, with `8px` rounded corners, and colored in bold vivid brand red-orange (`#ff3e1d`) transitioning into bright orange (`#ff9500`) on hover.
- **Sleek CSS Grid Footer Layout**: Transitions `.sub-card-footer` from standard flexbox-wrap to CSS Grid. Implements a responsive 2-and-1 button layout (Row 1: Update Payment Method & Add Another Subscription side-by-side in equal columns; Row 2: Cancel Subscription centered and spanning 100% width) matching the exact high-fidelity mockup specifications. Stacks vertically into 1 column on mobile viewports (`< 600px`).
- **Logo Asset Encoding**: URL-encoded the remaining trailing-space logo reference (`eMathSmart_logo_FINAL .png` inside Card 1 of the plans section) to `eMathSmart_logo_FINAL%20.png` to avoid browser parsing anomalies.

### Technical Notes for AI Agents
- **Section Selector**: `#parents-club-dashboard`
- **Asset Fallbacks**:
  - Tablet active banner: `/wp-content/plugins/idl-loader/templates/images/subscription-active.png`
  - Balance coin icon: `/wp-content/plugins/idl-loader/templates/images/coin.png`
- **Theme Insulation**: Applied highly specific rules and strict use of `!important` to prevent Book Junky/Porto layout styles bleed on the frontend components.

## [2026-05-28] - AI Coins Selection Page Overhaul & Parents Club Section 4 Styling

### Changed
- **Premium Section 4 Card Overhaul**: Completely redesigned the Starter, Popular, and Elite tutor package selection cards inside [single-product-ai-coins.php](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/single-product-ai-coins.php) to align with Parents' Club Section 4 pricing plans layout:
  - Cards now utilize premium Outfit and Inter typography and high-end borders (`border: 1px solid #e2e8f0; border-radius: 20px;`) with a light, clean container.
  - Hover triggers a smooth 8px vertical lift translation and premium soft box-shadows (`--shadow-hover`).
  - Active selected cards show a crisp 2.5px solid border and soft color glow shadows matched to the card's theme color.
- **Dynamic Themed Accent Styling**:
  - **Starter Bundle (100 coins)**: Themed with a Deep Azure Blue accent (`#0066ff`).
  - **Popular Challenge (500 coins)**: Themed with a Bright Coral Orange accent (`#ff5a36`) and features a floating absolute-centered **BEST VALUE** badge at the top card boundary.
  - **Elite Tutor Bundle (1000 coins)**: Themed with a Premium Indigo/Purple accent (`#7c3aed`) to convey exclusive concept support.
- **Colored Features Checklists**: Replaced flat description paragraphs on each card with detailed `.pc-plan-list` bullet features checklists featuring custom inline SVG checkmark vectors dynamically colored to match each card's theme accent.
- **Sleek Interactive Outline Buttons**: Positioned a clean outline select button (`.pc-plan-btn.select-btn`) at the bottom of each card. The buttons dynamically fill completely with their accent color on hover, and transition into locked active solid fills when the corresponding card is selected.
- **Dynamic Selection Indicators & Labels**:
  - Relocated the `.select-indicator` checkbox to the top-left corner of each card to prevent visual button conflicts.
  - Upgraded the DOM event listener script to bubble card selections and dynamically rewrite the button text inside the cards to show active selection labels (e.g. *"Starter Selected ✓"*, *"Popular Selected ✓"*, *"Elite Selected ✓"*).

### Technical Notes for AI Agents
- **Target File**: `/wp-content/plugins/idl-loader/templates/single-product-ai-coins.php`
- **Interactive JS Bindings**: JavaScript selection binds to both `.package-card` click bubbles and `.select-btn` buttons, programmatically mapping selections to the hidden native WooCommerce attributes form select (`select[name="attribute_pa_coins"]`) to preserve WooCommerce variable pricing, cart validation logic, and automated single-item cart rules.

## [2026-05-27] - WPBakery Element: Parents Club Need Help Panel

### Added
- **New Modular WPBakery Element `[parents_club_need_help]`** registered inside `idl_loader_register_parents_club_elements()` in `functions-wpbakery-elements.php`.
  - Parameters include: Panel Title, Panel Subtitle, and Panel Top Illustration image selector (`attach_image`).
  - **Dynamic Repeatable Contacts List (`param_group`)**: Completely equips administrators to dynamically add, edit, reorder, and remove contact rows visually from the admin builder.
  - Fully supports selecting/uploading a custom contact icon or SVG image (`attach_image`), leveraging standard Font Awesome or Linecons picker libraries, or fallback outline SVG designs (Email, Phone, Clock/Hours).
  - Integrates `link` parameter to programmatically wrap values in anchor links (e.g. `mailto:` or `tel:` scopes).
- **Shortcode Renderer `idl_loader_parents_club_need_help_shortcode()`** registered via `add_shortcode()`:
  - Conditionally enqueues `templates/css/parents-club-need-help.css` using `wp_enqueue_style()`.
  - Resolves standard illustration fallbacks (`templates/images/books-pencils.png`).
  - Scopes output under `#parents-club-section-6` to guarantee that all premium CSS styling applies accurately.
- **`WPBakeryShortCode_parents_club_need_help`** class added to WPBakery backend class binder.

### Technical Notes for AI Agents
- **Shortcode Handle**: `parents_club_need_help`
- **Class Identifier**: `WPBakeryShortCode_parents_club_need_help`
- **Asset Fallback**: `/wp-content/plugins/idl-loader/templates/images/books-pencils.png`

## [2026-05-27] - eMathSmart Outbound Webhook Fields Alignment & SSO Logout Fix

### Changed
- **Reverted Webhook Field Names (API #5 and API #6):** In [functions-esmart.php](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/functions-esmart.php), reverted `parentClubParentId` -> `parentId` (Type 1 and Type 2) and `parentClubSubscriptionId` -> `subscribeId` (Type 1) inside `process_subscription_custom()`. This makes outbound webhook payloads 100% compliant with the newly proposed `v1.4` API specification.
- **Enhanced SSO Logout Redirection Support:** Updated the eMathSmart logout endpoint handler `emathsmart_handle_sso_logout()` to inspect dynamic `redirect_uri` or `redirect_url` query parameters in the request, letting the WordPress engine cleanly return the user back to the eMathSmart portal upon logout instead of locking them on our homepage.

### Technical Notes for AI Agents
- **Validation Status:** 
  1. A diagnostic execution of `process_subscription_custom()` confirms that the outgoing webhook payload correctly transmits `parentId` and `subscribeId`. The test server currently responds with `parentClubParentId is required` (Code 400), confirming that the local plugin is successfully sending the new field names, and the remote eMathSmart server is awaiting our confirmation email to deploy their matching field updates.
  2. A local network `cURL` test on `?emathsmart_logout=1&redirect_uri=https://test.emathsmart.ca` confirms that the site logs out and responds with a clean `302 Found` redirection header pointing to `https://test.emathsmart.ca` dynamically.

## [2026-05-27] - Section 6 FAQ and Need Help Redesign

### Changed
- **FAQ Accordion Styling**: Refined spacing, shadow, and borders of the FAQ white accordion cards to match high-fidelity specs perfectly. Question buttons are styled as bold (`font-weight: 700`) at `13.5px` and chevron indicators are darkened to a highly visible slate gray (`#475569`) with smooth transition rotations.
- **Premium Accordion Animation**: Integrated a responsive, clean fade-in and slide-down transition (`faqFadeIn`) for active/open accordion answers to make the panel feel dynamic and interactive.
- **Need Help Panel Makeover**: 
  - Changed the contact icons from solid red circular badges to standalone transparent outline SVG elements styled in pure crimson red (`#af0128`).
  - Mapped contact details links (Email and Call info values) to standard dark slate (`#475569`) with crimson hover styles for optimal reading hierarchy.
  - Omitted the redundant bold "Office Hours" header, rendering the operating hours as a single-line value perfectly aligned with the clock outline SVG.
  - Positioned the large books and pencils graphic (`books-pencils.png`) dynamically with z-indexing at the bottom right, allowing it to elegantly overlap and overflow card boundaries.

## [2026-05-27] - Parents Club Page Container Responsive Breakpoints

### Fixed
- **Container Sizing and Centering**: Re-engineered wrapper overrides inside [parents-club-template.css](file:///Users/carlos/Local%20Sites/dev-popularbook/app/public/wp-content/plugins/idl-loader/templates/css/parents-club-template.css) to isolate outer sections (which continue to stretch fluidly edge-to-edge for premium glassmorphic/gradient backgrounds) from `.container` elements.
- **Responsive max-width Breakpoints**: Implemented standard responsive breakpoint limits on `.container` elements inside the Parents Club template to ensure optimal desktop reading line-lengths and crisp, centered layout structures:
  - `min-width: 576px` -> `max-width: 540px`
  - `min-width: 768px` -> `max-width: 720px`
  - `min-width: 992px` -> `max-width: 960px`
  - `min-width: 1200px` -> `max-width: 1170px`
  - `min-width: 1400px` -> `max-width: 1360px` (Expanded to fit wider desktop screen geometry)
  - `min-width: 1440px` -> `max-width: 1400px` (Ultra-wide desktop screen optimization)
- **Custom Column Widths**: Appended custom column sizing rules (`.col-exact-4`, `.col-exact-3-5`, `.col-exact-4-5`) to apply optimized, pixel-perfect column splits on tablet and desktop screens (`min-width: 768px`).
- **Brand Logo Sizing**: Increased `.brand-column .brand-logo-wrapper` `max-width` to **`395px`** inside `parents-club-hero-brand.css` for enhanced visual layout spacing.
- **Why Join Element Refinement**: Removed `<section id=\"parents-club-section-2\">` and `.container` wrappers from the `[parents_club_why_join]` element renderer. The shortcode output now starts natively at `.feature-bar-wrapper` to allow seamless visual rows nested inside WPBakery drag-and-drop elements layouts.
- **Section 2 CSS Rescoping**: Overwrote `section-2.css` to scope all styling cleanly under the `.feature-bar-wrapper` root class (instead of the old `#parents-club-section-2` wrapper). This fixes the layout break where elements were growing vertically instead of horizontally on desktop screens.
- **WPBakery Row Spacing**: Programmed a global layout standardize rule inside `parents-club-template.css` to automatically apply a uniform **`15px` top and bottom margin** (`margin-top: 15px !important; margin-bottom: 15px !important;`) on all standard WPBakery rows (`.vc_row`) inside the Parents Club landing page template. This guarantees spacing consistency for all existing and future editor rows.

## [2026-05-27] - WPBakery Element: eMathSmart Unified Plan Card

### Added
- **New Unified WPBakery Element `[emathsmart_plan_card]`** registered inside `idl_loader_register_parents_club_elements()` in `functions-wpbakery-elements.php`.
  - Supports all 4 custom plans/layout cards in one visual builder element: `Digital Learning`, `Pricing Plan`, and `Alternative Info / Not Ready`.
  - Conditional settings mapping: checkboxes for **Highlighted Border** and **"Best Value" Badge** (shown only for Pricing Plan layout).
  - Customizable logo branding option: text eMathSmart® logo or custom uploaded image logo (`attach_image`).
  - Parameterized title, price, billing period, capsule badge texts, and descriptive texts.
  - **Dynamic Repeatable Checklist Items (`param_group`)**: Completely equips each checklist item with custom text and multiple icon source types. Administrators can pick standard checks, predefined outline SVG templates (Gamepad, Lightbulb, Worksheet File, Bar Chart, Star, Shield, Checkmark), Font Awesome/Linecons library pickers, or custom SVG/image uploads per feature item.
  - Conditional bottom/footer elements selector: button CTAs (with WPBakery visual link selector `vc_link`), graphic animations illustration (`attach_image`), or empty.
- **Shortcode Renderer `idl_loader_emathsmart_plan_card_shortcode()`**: Parses the unified card attributes, handles fallback graphic/logo enqueuing, decodes WPBakery VC Link objects, parses repeatable checklist feature items, and programmatically loads the exact corresponding scoped stylesheets (`parents-club-plans-base.css` + `parents-club-plan-digital.css` or `parents-club-plan-monthly.css`/`parents-club-plan-annual.css` or `parents-club-plan-not-ready.css`) based on card layout selections.
- **`WPBakeryShortCode_emathsmart_plan_card`** class registered to the visual composer class binder.

### Technical Notes for AI Agents
- **Shortcode Handle**: `emathsmart_plan_card`
- **Class Identifier**: `WPBakeryShortCode_emathsmart_plan_card`
- **Asset Fallbacks**:
  - eMathSmart Brand Logo: `/wp-content/plugins/idl-loader/templates/images/eMathSmart_logo_FINAL .png` (Note the space in filename).
  - Digital learning bottom graphic: `/wp-content/plugins/idl-loader/templates/images/emathsmart-program.png`.
  - Not ready bottom graphic: `/wp-content/plugins/idl-loader/templates/images/not-ready-for-emathsmart.png`.

## [2026-05-27] - Parents Club Plans Section & Premium Styling

### Added
- **Plans Section HTML Markup**: Implemented `#parents-club-section-4` section inside `templates/parents-club.html` right below the onboarding step components, containing 4 structured plan cards: eMathSmart Digital Learning Program (Card 1), Monthly Plan (Card 2), Annual Plan (Card 3), and "Not ready for eMathSmart?" (Card 4).
- **Extremely Modular CSS Styling**: Designed a highly decoupled CSS system to separate layout geometry from dynamic card variations:
  - `parents-club-plans-section.css`: Manages grid layout columns, gap rules, and responsive media queries.
  - `parents-club-plans-base.css`: Shared properties (geometry, borders, padding, typographies, checklists, outline buttons) for the 3 first plan cards.
  - `parents-club-plan-digital.css`: Custom overrides for Card 1 (Grade Level badge, styled text logo, unique vector icons, bottom tablet graphic containment and floating animation).
  - `parents-club-plan-monthly.css`: Custom overrides for Card 2 (blue colors, blue-bordered badges, checkmarks, and outline button hover states).
  - `parents-club-plan-annual.css`: Custom overrides for Card 3 (orange colors, highlighted orange border, orange-bordered badges, orange checkmarks, outline button hover states, and absolute center positioning of the **BEST VALUE** banner).
  - `parents-club-plan-not-ready.css`: Specialized styling for Card 4 (light-grey background, dashed boundary border, centered typographies, and books illustration floating animation).
- **High-Fidelity Illustration Renders**: Generated and integrated premium, modern 3D renders matching the playful child-portal aesthetics of eMathSmart:
  - `emathsmart-tablet-illustration.png`: tablet showing progress charts, fresh red apple, and colorful pencil holder.
  - `parents-club-bag-books-illustration.png`: red Canadian shopping bag, fresh apple, and stack of colorful books.
- **Native WordPress Asset Loader**: Registered and enqueued the 6 new modular stylesheets in `idl_loader_parents_club_template_styles()` inside the plugin's `functions.php` to run seamlessly inside active WordPress theme layouts.

### Technical Notes for AI Agents
- **Section ID**: `#parents-club-section-4` with grid class `.plans-grid`.
- **Card Modular classes**: `.plan-card` (base shared style), `.plan-digital` (Card 1), `.plan-monthly` (Card 2), `.plan-annual` (Card 3), and `.plan-not-ready` (Card 4).
- **WordPress script handles**: Enqueued conditional to the Parents Club page inside `wp_enqueue_scripts` action hooks.

## [2026-05-26] - WPBakery Element: eMathSmart Gateway Login Card

### Added
- **New WPBakery Element `[esmart_login_card]`** registered inside `idl_loader_register_parents_club_elements()` in `functions-wpbakery-elements.php`.
  - **Card Brand Type** dropdown: "Styled Text Logo (eMathSmart®)" or "Custom Brand Image Upload".
  - **Custom Brand Image** (`attach_image`) field with dependency on the Custom Image branch.
  - **Lead Title Text** (default: *"Already have an eMathSmart account?"*).
  - **Description Paragraph** (default: *"Login here to access the program."*).
  - **CTA Button Label** (default: *"Login to eMathSmart"*) and **CTA Button Link** (default: `#emathsmart-login`).
  - **CTA Button Icon** dropdown with three options: default SVG arrow, Font Awesome (`iconpicker`), Linecons (`iconpicker`) — each shown/hidden via dependency rules.
  - **Redirect Lead Text** (default: *"New to eMathSmart?"*), **Redirect Link Label** (default: *"Learn more"*), and **Redirect Link URL** (default: `#emathsmart-learn`).
- **Shortcode renderer `idl_loader_esmart_login_card_shortcode()`** registered via `add_shortcode()`.
  - Dynamically enqueues `templates/css/parents-club-esmart-gateway.css` via `wp_enqueue_style()`.
  - Renders the exact card markup from `templates/parents-club.html` (lines 480–500) using Inter and Outfit typography.
  - Gracefully falls back to the styled text logo when no custom image is configured.
  - All user-supplied values are escaped (`esc_html`, `esc_url`, `esc_attr`, `absint`) before output.
- **`WPBakeryShortCode_esmart_login_card`** class appended to the Section 3 class binder block.

### Technical Notes for AI Agents
- **Element base:** `esmart_login_card`; registered under the "eMathSmart Elements" WPBakery category.
- **CSS handle:** `parents-club-esmart-gateway` → `templates/css/parents-club-esmart-gateway.css`.
- **Brand logic:** `brand_type === 'custom_image'` with a valid `brand_image` attachment ID renders an `<img>` tag; anything else renders the five-span styled text logo.
- **Icon logic:** `cta_icon_library` controls which iconpicker param is active; the default `arrow` branch outputs an inline SVG that matches the staging HTML exactly.

## [2026-05-26] - Custom Full-Width Page Template

### Added
- **Feature: Dynamic Repeatable Steps via WPBakery param_group:** Upgraded the custom page builder element `[parents_club_how_works]` inside `functions-wpbakery-elements.php` to utilize WPBakery's `param_group` field container. This replaces separate static fields with a clean, dynamic, and repeatable visual steps editor.
- **Visual Icon and Upload Selector per Step:** Equipped each repeatable step with full icon modularity inside the group editor. Administrators can click **"+ Add step"** to dynamically add, remove, and reorder steps visually, setting dynamic badge numbers, titles, and descriptions. For each step, they can select predefined brand outline SVGs (User Add, Price Tag, Users, parenting Tips/Lightbulb, Offers/Gift Box, Worksheets/File, eMathSmart/Computer, Star, Shield), pick Font Awesome & Linecons libraries, or upload custom images/SVGs.
- **Plugin-Only Page Template:** Designed and implemented `parents-club-template.php` strictly inside `/wp-content/plugins/idl-loader/templates/` (keeping the active theme files untouched).
- **Dynamic Template Registration:** Hooked into WordPress's `theme_page_templates` filter to make the template selectable as "Parents Club Landing (No Sidebar, Full Width)" in the Page attributes dropdown.
- **Dynamic Template Include Hook:** Leveraged the `template_include` filter to automatically route requests for pages using this template or with the `parents-club` slug to our plugin's custom template file.
- **Pure Full-Width CSS Overrides:** Programmatically overrode the Porto theme's wrapper classes and `#main` container paddings, forcing standard theme layouts to render true edge-to-edge content on the Parents Club landing page.
- **Dynamic Theme Layout Filter Overrides:** Hooked into Porto's custom layout filters (`porto_meta_layout` and `porto_meta_default_layout`) to programmatically strip sidebars and force `fullwidth` mode directly from the plugin environment.
- **Externalized Layout Stylesheet:** Shifted all inline style declarations out of the PHP template file and consolidated them into `/templates/css/parents-club-template.css`. Configured `wp_enqueue_scripts` in the plugin to load this stylesheet cleanly and conditionally on the active template page.
- **User Registration Form Styling Integration:** Re-engineered `/templates/css/parents-club-hero-signup.css` to natively target the markup structure generated by the *User Registration & Membership* WordPress plugin (elements like `.ur-frontend-form`, `.ur-form-row`, `.ur-frontend-field`, `.user-registration-password-strength` and `.ur-submit-button`). This provides a seamless, premium visual match for the high-fidelity redesign mockup without requiring core form adjustments.
- **Feature: Parents Club Why Join Bar Element:** Implemented and registered the custom page builder element `[parents_club_why_join]` (Option 1) inside `functions-wpbakery-elements.php` to handle Section 2 (horizontal features bar). Includes fully customizable fields for the section stacked heading and all 6 individual feature items' text.
- **Feature: Customizable Benefits Glance Icons:** Updated the `[parents_club_benefits_glance]` WPBakery element to support choosing a predefined SVG outline icon (Tag, parenting idea, Gift Box, Worksheets file, Computer Portal, Book, Star, Shield) or attaching a custom image/SVG for each of the benefit items.
- **Shortcode Callback Icon Logic Update:** Completed mapping and updating the `idl_loader_parents_club_benefits_glance_shortcode` renderer in `functions-wpbakery-elements.php` to parse and render these dynamic icons. Integrated `vc_icon_element_fonts_enqueue` to auto-load Font Awesome or Linecons styles on-demand if the library source option is selected.
- **Feature: Expanded Benefit Items Capacity:** Extended the `[parents_club_benefits_glance]` element's capability from 6 items up to 10 fully custom visual benefits. Configured visual mappings, conditional settings, brand SVGs, library selections, and frontend render templates dynamically for items 7 through 10.
- **Staging: Onboarding Steps & eMathSmart Login Widget:** Implemented the third section layout grid and two new distinct responsive components in `parents-club.html` (How Parents' Club Works Steps and eMathSmart Gateway Login Box).
- **Externalized Component Stylesheets:** Created scoped CSS files `/templates/css/section-3.css`, `/templates/css/parents-club-how-works.css`, and `/templates/css/parents-club-esmart-gateway.css` to allow seamless separate conversions into modular WPBakery Page Builder elements in the future.






### Fixed
- **Stylesheet Loading Mismatch:** Added active enqueues for `parents-club-hero-signup.css` and sibling stylesheets inside the plugin's core template assets hook. This guarantees that form designs render on active pages.
- **Dynamic Body Class Injection:** Added a hook on WordPress's `body_class` filter (`idl_loader_parents_club_body_classes`) to programmatically inject `.page-template-parents-club-template` and `.parents-club-landing-page` body classes. This resolves layout breakdown issues caused by the absence of template-specific body selectors when template inclusion is bypassed or force-loaded via slug fallback.
- **Form Layout Refinements:**
    - Added `min-width: stretch` (with `-webkit-fill-available` and `-moz-available` vendor fallbacks) to `.user-registration.ur-frontend-form` container in `parents-club-hero-signup.css` to achieve complete fluid responsiveness.
    - Reduced default container padding on `.user-registration.ur-frontend-form` to `20px 24px` to fit columns tightly.
  - Expanded submit button selectors to cover Porto theme overrides and forced the crimson red color, high-fidelity font hierarchy, shadow, and full-width width styling.
  - Styled form title `.user-registration-registration-title` to render centered, in a bold premium design matching `Outfit` typography, with a `26px` font size and `0px` margin bottom.
  - Styled description `.user-registration-registration-description` to render centered with muted, balanced spacing.
- **Benefits Column Spacing and Scoping:**
  - Added linear-gradient CSS mask (`mask-image` and `-webkit-mask-image`) to `.benefits-column .benefits-image-wrapper img` to smoothly fade in starting at 5% from the left edge.
  - Retained the primary vertical element sequence in `/templates/parents-club.html` (with `.benefits-image-wrapper` rendered first at the top, and `.benefits-glance-card` rendered second below it).
  - Removed standard `gap: 20px` from `.benefits-column` to prevent conflicting layout spacing.
  - Implemented smart adjacent sibling selectors (`+`) inside `parents-club-hero-benefits.css` to apply a negative `margin-top: -20px !important`, `position: relative !important`, and `z-index: 2 !important` to the crimson `.benefits-glance-card`. This shifts the card upwards to overlap 20px on top of the banner image for a high-fidelity 3D layering effect.
  - Modularized selectors in `parents-club-hero-benefits.css` by removing `#parents-club-section-1` scope to allow seamless layout matching.
  - Adjusted the padding of the crimson `.benefits-glance-card` to exactly `20px 24px !important` to align perfectly with the layout geometry of the sibling signup card.
  - Refined benefits card details: removed the bottom border and padding from the `h3` header and added a balanced bottom margin; replaced the `.bullet-icon-wrapper` semi-transparent background fill with a transparent background and clean 1px solid white border; removed bold formatting on `span.bullet-title` by merging all text into a single `<p>` tag in the staging HTML template, and configuring the WPBakery CSS renderer to display them inline together with standard weight/size as fallback to ensure a highly elegant, consistent, and clean visual flow.
  - Updated `.benefits-glance-card` to utilize a modern, semi-transparent background color (`rgba(175, 1, 40, 0.9) !important`) for a premium glassmorphic layered effect.













## [2026-05-25] - Parents Club Hero Intro WPBakery Element

### Added
- **Feature: Parents Club Hero Intro Element:** Implemented the custom page builder element `[parents_club_hero_intro]` inside `functions-wpbakery-elements.php` to handle Column 1 of the Hero section.
- **Dynamic Element Parameters:** Configured `vc_map()` to support customizable fields for custom logo image attachment, attribute headers and subheadings, solid button label/target, and outline button label/target.
- **Native Style Enqueueing:** The shortcode callback enqueues `parents-club-hero-brand.css` dynamically inside the WordPress execution flow using `wp_enqueue_style()`, optimizing performance by loading stylesheet resources only on pages where the intro block appears.

### Fixed
- **CSS Selector Scoping:** Adjusted selectors in `parents-club-hero-brand.css` from `#parents-club-section-1 .brand-column` to use the modular `.brand-column` as the root element directly. This ensures WPBakery layouts successfully match and render the CSS regardless of outer container wrapping IDs.


## [2026-05-25] - Parents Club Section 1 and Section 2 Redesign Visual Enhancements

### Added
- **Divided Section 1 CSS for WPBakery Mappings:** Split the monolithic `section-1.css` layout into 3 separate CSS component files (`parents-club-hero-brand.css`, `parents-club-hero-signup.css`, and `parents-club-hero-benefits.css`) to map 1-to-1 to 3 distinct WPBakery Page Builder elements. Pruned redundant component rules from `section-1.css` to keep it strictly focused on master outer grid and container layouts.
- **Scoped Component Stylesheets:** Re-introduced high specificity scoping `#parents-club-section-1` to all selectors in the three divided component stylesheets to insulate them from global theme overrides, ensuring perfect visual rendering.
- **Feature: Staging Section 2 ("Why Join Parents' Club?"):** Designed and implemented the complete horizontal feature bar for Section 2 inside `/templates/parents-club.html` and a new modular stylesheet `/templates/css/section-2.css`.
- **Responsive 6-Column Grid:** Configured Section 2's feature grid to dynamically scale: 1-column on mobile, 3-columns on tablets, and 6-columns on desktop.
- **Official Parents Club Banner Integration:** Switched the benefits banner image to the official high-quality `parents-club-banner.jpg` asset inside `/templates/images/parents-club-banner.jpg`, removing the temporary cropped placeholder.
- **Full-Width Viewport Staging:** Removed card-based viewport height constraints and body centering rules, and updated the `.container` widths in both `section-1.css` and `section-2.css` to `max-width: 100%; padding: 0 4%;` to ensure a true, premium fluid layout stretching fully to the edge of the viewport.
- **Removed Attribute Icon Backgrounds:** Cleaned up the brand attribute checklist below the logo by removing the circular background fills and border radiuses, allowing the raw red SVGs to sit beautifully beside the text.
- **High-Fidelity SVG Icons:** Replaced basic visual emojis across Column 1 attributes and Column 3 benefits cards with crisp, custom stroke-width white and red inline vector SVGs matching the brand theme.
- **Horizontal Desktop Buttons:** Styled the left column buttons ("Join Parents' Club" and "Member Login") side-by-side on desktop screens with micro-animations, adding an elegant user silhouette icon to the outline button.
- **Responsive 2-Column Benefits Grid:** Fixed the CSS media queries in `/templates/css/section-1.css` so that the "Benefits at a Glance" card renders as a clean, balanced two-column list on all tablet and desktop viewports.

### Technical Notes for AI Agents
- The cropped father-and-child study image is placed inside `/wp-content/plugins/idl-loader/templates/images/parents-study-photo.png` at exactly `375x178` resolution.
- Responsive styles for Section 2 are isolated inside `/wp-content/plugins/idl-loader/templates/css/section-2.css` to prevent layout interference.
- High-fidelity vector icons are rendered as standard XML inline elements (`<svg>`) rather than external image resources, ensuring instant load times and pixel-perfect rendering across standard and high-DPI/Retina screens.

## [2026-05-25] - Parents Club Custom WPBakery Hero Element

### Added
- **Feature: Dynamic Parents Club Hero WPBakery Element:** Implemented a new custom page builder element `[parents_club_hero]` registered programmatically inside the `idl-loader` plugin directory.
- **WPBakery Integration Hooks:** Leveraged the `vc_before_init` hook and `vc_map()` function inside a new `functions-wpbakery-elements.php` file to register visual field controls (Title, Subtitle, CTA text, CTA Link, Background Image attachment ID) inside WPBakery's custom element repository.
- **Outfit/Inter Premium Styled Layout:** Created the shortcode callback handler that outputs a high-fidelity centered visual container matching the eMathSmart dashboard warm aesthetics: linear peach/orange gradient backgrounds, subtle vertical slat textures, Inter and Outfit fonts, and pill-shaped animated buttons.
- **Shortcode Class Autoloader:** Bound the custom shortcode callback logic with the WPBakery backend layout auto-generator using the standard `WPBakeryShortCode` class interface extension.

### Technical Notes for AI Agents
- The custom shortcodes and `vc_map` bindings are initialized from the new plugin loader file `/wp-content/plugins/idl-loader/functions-wpbakery-elements.php` which is required at the top of the production `functions.php` file.
- Google Fonts Outfit & Inter are enqueued directly inside the shortcode execution loop to optimize page speed loads, loading only on pages rendering the Parents Club components.
- The CSS style classes are strictly scoped under the dynamically-generated `#pc-hero-[id]` wrapper block to protect global site headers and footers from layout bleed.
- The `bg_image` parameter is built to safely accept either standard absolute image URLs or native WordPress media attachment IDs, dynamically pulling the source URL via `wp_get_attachment_image_src()`.

## [2026-05-25] - Parents Club Redesign Template & Dynamic Dashboards Plan

### Added
- **Feature: Architectural Design for Parents Club Redesign Template:** Created a comprehensive implementation plan to programmatically register and load a new WordPress page template `parents-club-template.php` inside the active `idl-loader` plugin to preserve clean-core requirements.
- **ACF Integration Specifications:** Defined the ACF field mappings to make all sections of the landing page fully editable by the user via standard, repeater, and flexible custom fields inside the page editor.
- **Dynamic State Logic:** Outlined a state-based layout manager that serves context-appropriate content depending on user authorization (guest visitors, logged-in non-subscribers, active eMathSmart subscribers).
- **Interactive Documentation Dashboard:** Saved the premium interactive HTML plan at `/app/public/dev_assets/ai_documentation/Parents_Club_redesign_plan.html` for alignment and testing.

## [2026-05-23] - AI Coins Post-Login Redirection Hook & WPBakery/AJAX Compatibility

### Added
- **Feature: Auto-Redirect After Gated Login:** Added custom redirection filters (`woocommerce_login_redirect` and `login_redirect`) that dynamically catch when a user logs in successfully via the `/parents-club` page (or any form where they arrived with the query parameters `restricted_access=ai-coins`).
- **WPEverest Custom Redirect Hook Integration:** Added the `user_registration_login_redirect` filter hook to natively intercept WPEverest's "User Registration" login form (shortcode `[user_registration_login]` inside the WPBakery layout). Since WPEverest processes login submissions via a custom AJAX routine (`ur_process_login`), it bypasses core WordPress and WooCommerce filters; adding this hook ensures WPEverest successfully returns the correct `/product/ai-coins/` redirect URL in its JSON success payload.
- **Dynamic Gated Verification:** Automatically routes the authenticated user straight back to `/product/ai-coins/` if either the login request parameters or the HTTP referrer contains `restricted_access=ai-coins`.
- **WPBakery & AJAX Compatibility Script:** Injected an active inline script into the gated error notice output (`the_content` filter). The script dynamically detects WPBakery login widgets and AJAX form inputs, forcefully rewriting form actions and hidden redirect fields (`redirect`, `redirect_to`, `url`, `_wp_http_referer`) to `/product/ai-coins/` on DOM ready and after rendering timeouts, ensuring successful redirection.

## [2026-05-22] - Visual Redesign for eMathSmart Style Alignment

### Changed
- **Visual Theme Redesign:** Redesigned the product page styling to align with the warm, playful child-portal branding from the eMathSmart screenshot.
- **Warm striped background:** Replaced the frosted glass theme with a beautiful, rich warm cream-yellow vertical linear gradient background `#fde4ba` and added a custom vertical slat texture to mimic the pinstripe background pattern.
- **Portal Card styling:** Styled the `.product-container` as a centered, white profile card with a friendly warm outline (`2px solid #ffe9d2`) and a soft shadow.
- **Active Subscription Card highlights:** Designed the package selector cards to have a friendly cream background (`#fffcf7`) and light outlines, transforming on select into beautiful golden/yellow glossy gradient pills matching the eMathSmart subscription selectors.
- **Pill Buttons:** Redesigned interactive badges and buttons with vibrant peach/orange glossy linear gradients (`linear-gradient(135deg, #f59652 0%, #eb731f 100%)`) and pill shapes (`border-radius: 100px`) for high visual alignment.

## [2026-05-22] - UI Refinements & Content Placeholders

### Changed
- **Content Placeholder Integration:** Replaced the default package subheader text and individual starter/popular/elite card descriptions with layout-friendly Lorem Ipsum chunks to support visual staging.
- **Removed Benefits Block:** Completely removed the `#emathsmart-custom-coins-product .benefits-section` HTML element ("What eMathSmart AI Coins Unlock" benefits grid) and all associated CSS layout rules (`.benefits-section`, `.benefits-grid`, `.benefit-item`, `.benefit-icon`, `.benefit-details`) from `/templates/single-product-ai-coins.php`.

## [2026-05-22] - AI Coins Custom Template & Sleek Glass-Minimalist UI (Option B)

### Added
- **Feature: Dynamic Product Description Display:** Dynamically fetched and rendered the WooCommerce product description (`$product->get_description()`) using a premium Outfit/Inter Glassmorphic typography block styling, cleanly formatting all HTML sub-elements (headings, lists, blockquotes, horizontal rules) inside the isolated `#emathsmart-custom-coins-product` scope.
- **Feature: Full-Width Layout Container:** Expanded the `.product-container` element to be centered with a premium maximum width (`max-width: 1200px`) and added high-utility parent layout overrides (targeting `.wrap-boxed`, `#page`, `#content.site-content`, `#main`, and inner wrapper containers under `body.single-product`) to successfully break out of active Book Junky (and fallback Porto) theme's constrained page wrappers. This allows the Option B Glassmorphic columns and sections to stretch beautifully edge-to-edge on all viewport sizes while protecting the native header, banner, and footer width centering.
- **Feature: Programmatic Custom WooCommerce Product Template Loading:** Implemented a new `template_include` filter in `functions-esmart.php` that dynamically detects single product requests for the `'ai-coins'` slug and redirects page loading to `/templates/single-product-ai-coins.php`.
- **Feature: High-Fidelity Sleek Glass-Minimalist UI Layout:** Created a bespoke product page template `/wp-content/plugins/idl-loader/templates/single-product-ai-coins.php` using Option B (frosted light-mode glassmorphic styling, champagne and rose-gold details, Outfit/Inter typography, and SSL trust indicators).
- **Feature: Isolated Theme Scoping:** Scoped all visual selectors and custom typography links strictly under the wrapper `#emathsmart-custom-coins-product`, ensuring the active Book Junky theme's header (#masthead), navigation menu, and footer (.site-footer) are completely native, centered, and untouched.
- **Feature: Hybrid Variation Form Sync:** Developed custom JavaScript integration inside the template that binds card click events directly to the hidden, native WooCommerce variable add-to-cart select attributes, preserving all standard cart validations and discount rules.

### Technical Notes for AI Agents
- The custom loader routes single variable products matching the `'ai-coins'` parent slug or child variations to `/wp-content/plugins/idl-loader/templates/single-product-ai-coins.php`.
- Standard theme structures are fully preserved by initiating the WordPress post loop and including the standard `get_header('shop')` and `get_footer('shop')` template parts.
- Hidden form integration ensures that all custom add-to-cart validations (`woocommerce_add_to_cart_validation`) and coupon exclusions remain 100% active and correct.

## [2026-05-22] - AI Coins Option A Integration & Type 2 Payment Notifications

### Added
- **Feature: AI Coins Option A Dynamic Conversion:** Implemented dynamic eMathSmart package conversion (`emathsmart_order_has_additional_packages()`) that reads the existing WooCommerce `coins` variation attribute (`100`, `500`, `1000`) and programmatically divides it by `100` (`$packages = intval($coins_attribute) / 100`) to derive the exact package count, avoiding the need for any additional custom admin settings or database fields.
- **Feature: Outbound Webhook Payment Notification for AI Coins:** Fully integrated API #5 (`type = 2`) additional package payment notifications in `process_subscription_custom()`. When an AI Coins purchase is detected, the webhook switches to a `type = 2` JSON payload featuring `additionalPackageQuantity`.
- **Feature: Webhook Signature Subscription Key Exclusions:** Refactored HMAC-SHA256 signature calculations to cleanly omit recurring subscription-specific keys (`subscribeId`, `subscriptionType`, `trialType`, `expireTimestamp`) from both the payload and the sorted parameter array before signing when `type = 2`.
- **Feature: Inbound Redirect GET /pay Compatibility:** Restored and updated the `restapi_pay()` inbound REST endpoint redirect to cleanly intercept `type = 2` additional package checkouts and dynamically route them to `/product/ai-coins/` using `home_url()`.
- **Feature: Diagnostic Simulation Suite for Type 2:** Added an interactive test suite `?test_type2=1` inside `functions-esmart-debug.php` to simulate a mock payment notification for AI Coins (500 coins = 5 packages) under order `116377`, validating the HMAC signature and database log entries.

### Technical Notes for AI Agents
- The helper `emathsmart_order_has_additional_packages` loops through order items, fetches the `pa_coins` (or variations `coins`) attribute value, and dynamically returns `coins / 100` multiplied by line-item quantity.
- Webhook payloads for `type = 2` omit `expireTimestamp`, `subscriptionType`, `trialType`, and `parentClubSubscriptionId` completely, conforming strictly to the eMathSmart interface specification.
- Test endpoint `?test_type2=1` verifies the signature using the active sandbox secret and registers logs directly into the `wp_emathsmart_log` table.

## [2026-05-22] - AI Coins Skip-Cart Redirect

### Added
- **Feature: AI Coins Add-to-Cart Skip Cart:** When any AI Coins product (or variation) is added to the cart, the user is immediately redirected to `/checkout` instead of the cart page, removing an unnecessary step from the purchase flow.

### Technical Notes for AI Agents
- Hooked into `woocommerce_add_to_cart_redirect` filter in `functions-esmart.php`.
- Detects the product by slug (`ai-coins`) on both the parent product and its variations using `get_parent_id()`.
- Returns `wc_get_checkout_url()` to redirect; falls back to the original `$url` for all other products.

## [2026-05-22] - Gated Access Redirect Notice Persistence & Guest Messaging Fix

### Added
- **Feature: Logged-in Non-Subscriber Subscription Redirect:** Logged-in users without active subscriptions are now redirected to `/subscription` (instead of `/parents-club`) with the query parameter `?restricted_access=ai-coins&reason=no_subscription`.
- **Feature: Multi-page Gated Notice Display:** Extended the early `the_content` filter to render the error banner on both the Parents Club (`/parents-club`) and Subscription (`/subscription`) landing pages.
- **Feature: Query-Parameter-based Notice Persistence:** Resolved a WooCommerce session persistence issue on early `template_redirect` hooks by redirecting unauthorized users to `/parents-club/?restricted_access=ai-coins` or `/subscription/?restricted_access=ai-coins`.
- **Feature: Context-Aware Redirect Reason:** Appends a `reason` parameter (`not_logged_in` or `no_subscription`) based on whether the guest has logged in, providing clear navigation instructions.
- **Feature: Dynamic WooCommerce Alert Prepending:** Implemented an early `the_content` filter that automatically prepends a native-styled WooCommerce error banner at the top of the Parents Club and Subscription landing pages when the query parameter is present.
- **Feature: Intelligent SSO Login Link:** For guests (`reason=not_logged_in`), dynamically embeds a high-utility login link using `wp_login_url(home_url('/product/ai-coins/'))` that automatically logs them in and redirects them straight back to the restricted AI Coins product page to purchase.

### Changed
- **Feature: Guest Login Link Anchor Target:** Updated the guest gated-access notice login link to point to `#parents-club-login` instead of `wp_login_url(...)` to smoothly scroll visitors directly to the custom login form row on the Parents Club landing page.

### Technical Notes for AI Agents
- The redirect targets `restricted_access` and `reason` parameters.
- The `emathsmart_display_gated_notice_on_parents_club` filter hooks to `the_content` with a priority of `1` to render standard `.woocommerce-error` element tags before any page builders format the body, supporting both `/parents-club` and `/subscription` pages.
- The guest login link is set directly to `#parents-club-login` to scroll the user to the corresponding login form row.

## [2026-05-22] - eMathSmart Migration to Dedicated Plugin Repository

### Added
- **Plugin Repository Migration:** Successfully transitioned the active custom WooCommerce and subscription integration logic from the obsolete `book-junky-child` theme to the dedicated `idl-loader` plugin folder (`/wp-content/plugins/idl-loader/`).
- **Complete Commit Preservation:** Replicated and preserved 100% of the historical Git commits and author logs by migrating the `.git` database. Staged the new `idl-loader.php` loader file.
- **Theme-Specific File Pruning:** Safely deleted all theme-specific visual templates, WooCommerce templates, element maps, assets, and config files from the tracking index of the new dedicated plugin repository.
- **New Remote Repository:** Pointed the plugin's Git remote to `https://github.com/carlos-onv/idl-loader-plugin-popularbook.git` and successfully pushed the unified `main` branch to the new repository.

### Technical Notes for AI Agents
- The child theme (`book-junky-child`) has been retired, and all hook files (`functions.php`, `functions-esmart.php`, `functions-esmart-debug.php`, `functions-esmart-admin.php`, `functions-restapi.php`, and `clientcenter-api-library.php`) are now fully powered by the `idl-loader` plugin directory.
- A compatibility polyfill for `getallheaders()` remains active at the top of `functions-restapi.php` to prevent PHP Fatal Errors under WP-CLI command-line contexts.
- Future enhancements should be developed inside the `idl-loader` repository and pushed directly to `https://github.com/carlos-onv/idl-loader-plugin-popularbook.git`.

## [2026-05-22] - eMathSmart AI Coins Coupon Exclusion (Phase 1)

### Added
- **Feature: AI Coins Coupon Exclusion Guard:** Prevented the automatic `"parents-club-discount"` coupon from being applied when the cart contains AI Coins (variable product ID `116676` or active variations `116679`, `116680`, `116681`). If the coupon is already in the cart when AI Coins is added, it is automatically removed.
- **Feature: Manual Coupon Restriction:** Blocked manual coupon code applications on checkout when the cart contains AI Coins by hooking into `woocommerce_coupon_is_valid`.

### Technical Notes for AI Agents
- Added helper `emathsmart_cart_contains_ai_coins($cart)` in `functions-esmart.php` to dynamically verify if any cart item matches the `'ai-coins'` slug (or its parent slug if it is a variation).
- Integrated `emathsmart_restrict_coupons_for_ai_coins` via the `woocommerce_coupon_is_valid` filter to block manual coupon additions when AI Coins is in the cart.
- Modified `apply_group_discount_coupon()` in `functions.php` to bypass automatic application of the parent's club discount and automatically force-remove it if `emathsmart_cart_contains_ai_coins()` is true.

## [2026-05-21] - eMathSmart AI Coins Integration (Phase 1)


### Added
- **Feature: AI Coins Gated Access Control:** Restricted access to the `/product/ai-coins/` product page and variation checkouts exclusively to logged-in users with an active core WooCommerce subscription using `wcs_user_has_subscription()`. Unauthorized users are safely redirected to the Core Plans (`/parents-club`) with a custom premium error notice.
- **Feature: Add-To-Cart Security Guard:** Integrated a strict validation hook (`woocommerce_add_to_cart_validation`) to prevent unauthorized cart additions and link/API bypasses for the AI Coins variable product.
- **Feature: Single-Item Checkout Enforcement:** Implemented a single-item cart rule inside cart validation. Adding "AI Coins" or any recurring subscription plan automatically clears any existing cart items to prevent checkout payload and sync conflicts. Conversely, adding a standard book or utility product when an "AI Coins" package or subscription is in the cart empties the cart first.

### Fixed
- **Theme Frontend Support for Product Variations:** Resolved a frontend layout issue where variable product variations (dropdown select controls) were entirely missing from single product pages. Replaced the child theme's hardcoded template call `woocommerce_simple_add_to_cart()` with the dynamic core `woocommerce_template_single_add_to_cart()` inside `woocommerce/content-single-product.php` (line 156). This fully restores native WooCommerce variations dropdowns and javascript events for variable products, while retaining custom child theme styling for simple products.

### Technical Notes for AI Agents
- The gated checks target product and variation slug `'ai-coins'` dynamically.
- `woocommerce_add_to_cart_validation` handles cart clearance via `WC()->cart->empty_cart()` synchronously during validation to ensure standard products and restricted subscription/utility coins packages are never mixed.
- Calling `woocommerce_template_single_add_to_cart()` dynamically triggers standard variation markup on variable pages by leveraging core WooCommerce fallbacks, resolving layout limits on the customized single-product wrapper.

## [2026-05-21] - Refactored Subscription Data Processing in Outbound Hook

### Changed
- **Unified Subscription Data Loading Loop:** Refactored `process_subscription_custom` in `functions-esmart.php` to query WooCommerce subscriptions exactly once instead of twice. 
- **Dynamic Trial Type Resolution:** Replaced the hardcoded `trialType = 1` default with a dynamic calculation. The code now computes the actual duration in days between subscription creation (`date_created`) and `trial_end` to accurately map to `1` (7 days) or `2` (14 days) based on actual checkout configurations.
- **Refined Variable Fallbacks:** Ensured robust defaults remain active in case an order has no valid subscriptions.

### Technical Notes for AI Agents
- The core integration continues to use the clean parent `order_id` in order to comply with strict one-subscription-per-transaction assumptions.
- The `wcs_get_subscriptions_for_order` query is now executed exactly once, cutting database query redundancy in half during status transition hooks.

## [2026-05-21] - API Audit Documentation Alignment & Vulnerability Mapping

### Updated
- **API Audit Documentation (`api_audit.html`):** Fully updated the technical audit dashboard documentation at `app/public/dev_assets/ai_documentation/api_audit.html` to align with the actual codebase state:
  - **Inbound Pull Compensation (API #7 & API #8):** Corrected the source file from `functions-esmart-compensation.php` to `functions-restapi.php` and mapped the active production REST routes as `/wp-json/wp/v2/orderpaymentcompensate` and `/wp-json/wp/v2/orderrefundcompensate`. Mapped these as a **Critical Security Vulnerability** because the active legacy endpoints perform **zero signature verification**, leaving client data exposed without authentication. Documented that `functions-esmart-compensation.php` is disabled in `functions.php`.
  - **AI Tokens & Additional Packages (type=2):** Documented eMathSmart's specification for additional packages (`type=2`), showing how they should load credits via `sourceType=ADDITIONAL` without extending active subscriptions. Identified and documented the active codebase gaps in the GET `/pay` checkout redirect and `process_subscription_custom()` outgoing payment webhook (which currently hardcodes `type=1`).
  - **Get Public Exam Questions (API #9):** Corrected the documented active lines inside `functions-esmart.php` (lines 136-201 &amp; lines 517-553) and mapped the email hooks to the correct automated triggers `woocommerce_email_customer_details` and `woocommerce_subscriptions_email_order_details` (focusing on manual/auto trial expiration templates) instead of chronological crons.
  - **Line Range Refinements:** Corrected shifts and ranges for **API #3** (lines 76-168 in `functions-restapi.php`), **API #4** (lines 182-239 in `functions-restapi.php`), **API #5** (lines 74-84 & lines 256-472 in `functions-esmart.php`), and **API #6** (lines 86-96 & lines 256-472 in `functions-esmart.php`).
  - **Priority Action Items Checklist:** Redesigned the priority checklist at the bottom of the document to place securing inbound pull routes as **Priority #1**, followed by adding AI Token `type=2` checkout and webhook support as **Priority #3**, ensuring proper prioritization for future development cycles.

### Technical Notes for AI Agents
- **Disabled File Status:** `functions-esmart-compensation.php` exists and contains secure signature checks for compensation, but is entirely commented out of `functions.php`. All actual inbound compensation calls are handled insecurely in `functions-restapi.php`.
- **AI Token type=2 Spec:** Webhooks must pass `type => 2` for additional packages, and checkouts must map `type=2` parameters to distinct non-recurring product SKU checkouts to avoid breaking active subscription end timestamps.

## [2026-05-20] - Chronological Timeline Correction for Order Notes

### Fixed
- **Timeline Order of Order Notes:** Corrected the chronological timeline sequence of WooCommerce order notes when order status changes.
  - Subscription status changes (`emathsmart_cancel_subscription_on_refund` and `emathsmart_reactivate_subscription_on_completed`) and eMathSmart API Sync notes (`process_subscription_custom`) are now fully deferred using `emathsmart_defer_order_note()`.
  - This ensures they are written to the database during the WordPress `shutdown` hook, *after* WooCommerce core writes the standard order status transition note.
  - The correct chronological order is now guaranteed:
    1. **Order status change** (e.g. *"Order status changed from completed to refunded"* or vice-versa)
    2. **Subscription change** (e.g. *"Subscription #X automatically cancelled/reactivated..."*)
    3. **eMathSmart Sync** (e.g. *"eMathSmart Refund/Payment Notify: Synced ✅"*)
- **Invalid HTML Break Tags in Trial Expiration Email:** Refactored the email details injection helper (`emathsmart_inject_exam_links_to_email`) to use highly compatible block-level `<div>` wrappers with margin styling for each PDF link, replacing fragile nested `<p>` and malformed break tags to guarantee consistent layout rendering and line breaks across all modern email clients.

### Technical Notes for AI Agents
- **Deferred Flushing in Simulations:** Since the debug and simulation endpoints in `functions-esmart-debug.php` use `exit;` to terminate execution early (which bypasses WordPress's normal `shutdown` hook execution), we have updated the debug and simulation handlers to manually invoke `emathsmart_flush_deferred_notes()` before calling `exit;`. This ensures that all deferred notes are correctly flushed and written to the database during testing and manual simulation execution.

## [2026-05-20] - Auto-Reactivate Subscription on Parent Order Completed Reversal


### Added
- **Feature: Auto-reactivate cancelled subscriptions on order status reversal**
  - Added `emathsmart_reactivate_subscription_on_completed()` hooked to `woocommerce_order_status_completed` (priority 10) in `functions-esmart.php`.
  - Automatically transitions `cancelled` subscriptions back to `active` when their parent order is changed back to completed (manual reversal of a refund).
  - Safely bypasses WooCommerce Subscriptions native transition restrictions (which block direct 'cancelled' to 'active' status changes) by directly updating the status and saving (`$subscription->set_status('active'); $subscription->save();`).
  - Automatically triggers the `Payment` notification to eMathSmart immediately afterwards (priority 20) with active subscription details.
  - Appends timeline notes to both the subscription and the parent order:
    - Parent order: *"Subscription #X automatically reactivated after this order status was changed back to completed."*
    - Subscription: *"Subscription automatically reactivated because parent order #Y status was changed to completed."*
- **Diagnostic Tool: `?simulate_completed_trigger=<order_id>`** (Clean Core)
  - Created a debug tool inside `functions-esmart-debug.php` to simulate the manual completed reversal flow.
  - Triggers reactivation of the database subscription, prints status before/after, and shows live request/response of the eMathSmart payment notification.

### Technical Notes for AI Agents
- **Bypassing Native Transition Limits:** Directly using `$subscription->update_status('active')` on a cancelled subscription will throw a fatal PHP exception because WooCommerce Subscriptions natively does not allow transition from cancelled directly back to active. Bypassed safely using:
  ```php
  $subscription->set_status('active');
  $subscription->save();
  ```
- **Execution Order (Priority):** Hooked to `woocommerce_order_status_completed` at priority 10, running *before* the eMathSmart paymentNotify hook at priority 20 (`emathsmart_trigger_payment_notification`). This ensures the subscription is marked `active` in the DB before the API payload is prepared, so the API payload accurately computes the active dates and states.
- **Modified files:** `functions-esmart.php` (production hook), `functions-esmart-debug.php` (diagnostic simulation utility). Clean Core rule fully respected.

## [2026-05-20] - eMathSmart SSO Logout Endpoint

### Added
- **Feature: WordPress logout via eMathSmart logout button**
  - Added `emathsmart_handle_sso_logout()` in `functions-esmart.php`.
  - When eMathSmart redirects the user to `https://popularbook.ca/?emathsmart_logout=1` after their logout, WordPress automatically ends the user's session and redirects them to the homepage.
  - No `parent_id` or extra parameters needed — the user's browser carries the WordPress session cookie with the redirect.

### Technical Notes for AI Agents
- **Hook:** `add_action('init', 'emathsmart_handle_sso_logout')` — fires early in the WP lifecycle before headers are sent, so `wp_redirect()` works correctly.
- **Trigger URL:** `https://popularbook.ca/?emathsmart_logout=1` — eMathSmart must be configured to redirect to this URL after their logout flow.
- **How it works:** Since the user's browser makes the redirect request, their WP session cookie is automatically included. `wp_logout()` destroys that session; no user ID lookup needed.
- **Safety:** The `is_user_logged_in()` check prevents errors if an already-logged-out user hits the URL.
- **Modified file:** `functions-esmart.php` only (Clean Core rule respected).

## [2026-05-20] - Auto-Cancel Subscription on Order Refund

### Added
- **Feature: Auto-cancel active subscriptions on refund**
  - Added `emathsmart_cancel_subscription_on_refund()` hooked to `woocommerce_order_status_refunded` (priority 30) in `functions-esmart.php`.
  - Automatically cancels subscriptions with `active` or `on-hold` status when their parent order is refunded.
  - Adds an order note to the subscription: *"Subscription automatically cancelled because order #X was refunded."*
  - Adds an order note to the parent order: *"Subscription #Y automatically cancelled after this order was refunded."*
- **Diagnostic Tool: `?simulate_refund_trigger=<order_id>`** (Clean Core)
  - Created a debug tool inside `functions-esmart-debug.php` to simulate refund events.
  - Safely cancels parent order subscriptions on WordPress, and prints out full API communication payload with eMathSmart's refundNotify endpoint.
  - Bypasses standard admin verification via secure environment testing, then reverts to strict `manage_options` check.

### Technical Notes for AI Agents
- **Why custom code?** WCS built-in `maybe_cancel_subscription_on_full_refund()` (hooked at default priority to `woocommerce_order_fully_refunded`) only cancels subscriptions already in `pending-cancel` status. It does NOT touch `active` subscriptions.
- **Hook used:** `woocommerce_order_status_refunded` at priority 30 (after the eMathSmart refund notify at priority 20).
- **Scope:** Only acts on `parent` order type subscriptions — renewal orders don't trigger a cancellation.
- **Safety check:** Uses `can_be_updated_to('cancelled')` before updating to avoid invalid state transitions.
- **Simulation utility:** Use `?simulate_refund_trigger=<order_id>` (requires admin privileges) to run manual test scenarios.
- **Modified files:** `functions-esmart.php` (production hook), `functions-esmart-debug.php` (diagnostic simulation utility). Clean Core rule fully respected.

## [2026-05-20] - API Endpoint Migration to test.emathsmart.ca + Trial Email Injection Fix + Debug Tools

### Changed
- **API Endpoint Migration:** Updated all eMathSmart API calls from `math-pro-cms.dcraysai.com` → `test.emathsmart.ca` across:
  - `functions-esmart.php`: API #5 (`paymentNotify` ~line 236), API #6 (`refundNotify` ~line 295), API #9 (`getPublicExamQuestions` ~lines 103 & 312)
  - `functions.php`: API #5 payment notify calls (~lines 3146, 3203, 3239)
  - `functions-esmart-debug.php`: All 3 diagnostic endpoint references (~lines 86, 234, 404)

### Fixed
- **WCS Trial Expiration Email — PDF Injection Not Firing:**
  - Root cause: The WooCommerce Subscriptions automatic trial expiry email (`WCS_Email_Customer_Notification_Auto_Trial_Expiration`) uses the template hook `woocommerce_subscriptions_email_order_details`, NOT the standard `woocommerce_email_customer_details` hook.
  - Fix: Added `add_action('woocommerce_subscriptions_email_order_details', ...)` in `functions-esmart.php` (~line 479). The original hook was never firing for this email type.
  - PDF exam links from API #9 now correctly appear below the subscription table in the trial expiration email.

### Added (Debug Tools — `functions-esmart-debug.php` only)
- **`?test_trial_email=<subscription_id>`** — Directly sends the WCS automatic trial expiration email, bypassing the WCS staging environment block (`should_send_notification()` only allows `production` environment type). Useful for local/dev testing.
- **`?set_trial_end=<subscription_id>`** — Resets the `trial_end` date on a subscription to +7 days from now. Also sets billing name to "Test User" if the field is empty. Includes a link to fire the test email immediately after. Both tools call `exit` and require admin login.

### Technical Notes for AI Agents
- **WCS Staging Block:** `WC_Subscriptions_Email_Notifications::should_send_notification()` checks `wp_get_environment_type()` and only allows emails on `production`. On `local` or `development` environments, ALL subscription notification emails are silently suppressed. The `?test_trial_email=` tool bypasses this by calling `$email->send()` directly (not `$email->trigger()`).
- **Correct Hook for WCS Trial Email:** Use `woocommerce_subscriptions_email_order_details` (not `woocommerce_email_customer_details`) to inject content into the WCS trial expiration email. This hook is called inside the WCS template `customer-notification-auto-trial-ending.php`.
- **API #9 Call Chain:** `emathsmart_inject_exam_links_to_email($order_id, $email)` → calls `emathsmart_get_public_exam_links($order_id)` → expects a **WC Order ID** (e.g. `117583`), then internally uses `wcs_get_subscriptions_for_order()` to resolve to subscription `117584`.
- **parentId in API payloads** = WordPress user ID (`$order->get_user_id()`), e.g. `60773` for the test order.
- **Test Subscription:** Order `117583`, Subscription `117584`, Parent/User `60773`.
- **API Credentials:** Secret: `yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm`, AppId: `ParentClub`.
- **Production Domain Question:** Confirm with eMathSmart whether `test.emathsmart.ca` is the permanent production domain or a temporary test domain before going live. May need to update again.

## [2026-05-20] - AI Documentation Organization

### Added
- **AI Documentation Directory:** Created a dedicated `/app/public/dev_assets/ai_documentation` directory.
- **Documentation Migration:** Organized and copied all key technical specifications, walkthroughs, drafts, implementation plans, and scratch scripts generated during the eMathSmart and audit phases:
  - Technical specs and briefs (eMathSmart webhook signature issue, API #9 briefing, system prompt templates).
  - Main task trackers, implementation plans, and walkthroughs.
  - Scratch PHP scripts used for brute-forcing API signature permutations and testing (moved to `/ai_documentation/scratch/`).

### Technical Notes for AI Agents
- All technical specifications, design documents, and exploratory PHP scripts are now consolidated in `dev_assets/ai_documentation`. Future agents can reference these files to understand the API reverse-engineering processes (specifically signature permutations, field exclusions, and brute-forcing details).
- Avoid creating temporary files in the root folder; place any future scratch/exploratory files inside `dev_assets/ai_documentation/scratch/`.

## [2026-05-19] - Security, Plugin & Performance Audit of Live Server Copy

### Added
- **Malware, Plugin Security & Performance Analysis Report:** Added complete documentation of findings to `malware_performance_analysis.md`.

### Discovered
- **SEO Spam Cloaking Malware:**
  - Found sophisticated SEO cloaking code in the live copy (`public_html/index.php`) and `.htaccess` designed to intercept Googlebots/crawlers and serve Turkish gambling spam (`Meritking 2026`) located in a rogue root file `public_html/wp-options.php`.
- **Plugin Security Flaws (Potential Initial Attack Vectors):**
  - **WooCommerce Fulfillment IDL:** Discovered a massive PII Information Disclosure vulnerability where order XML payloads containing names, email/billing/shipping addresses, phone numbers, and purchased books are saved sequentially to the public web root folder: `ordersdata/order-[order_id].xml`. Scrapers can easily download every customer order without authentication.
  - **User Meta IDL:** Discovered a highly dangerous PHP Object Injection vulnerability at line 75 of `idl-user-meta.php` where it performs `unserialize()` on an insecure third-party HTTP call (`http://www.geoplugin.net/php.gp?ip=$user_ip`). A compromised domain or MITM can trigger Remote Code Execution (RCE) on the server.
- **Performance Cheating Hook:**
  - Located custom JS `bCheck()` function in the child theme's `header.php` which detects Lighthouse, GTmetrix, and other performance crawlers, then completely skips enqueuing GTM, Meta Pixel, Visual Composer styles, and Revolution Slider scripts. This fakes a high PageSpeed score on speed tools while real users suffer the full impact of unoptimized scripts.

### Technical Notes for AI Agents
- **Plugin Audits:** Third-party plugins (including `revslider` version 6.7.34 and `js_composer` version 8.7.2) were audited via regex scan and verified clean of backdoors.
- **Exposure Remediations:**
  - Move the `ordersdata/` directory outside the public web root, or configure an immediate `.htaccess` rule inside the folder: `Deny from all`.
  - Rewrite the `idl-user-meta.php` geoplugin call to use secure JSON via HTTPS and decode it using `json_decode()` instead of `unserialize()`.
- The active child theme contains cheating logic that masks the true rendering performance from Lighthouse audits. To perform real-world optimizations, `bCheck()` checks must be removed or bypassed.
- Do NOT upload or migrate the infected `index.php`, `.htaccess`, or `wp-options.php` files from `dev_assets/public_html` to any production or developer environment.

## [2026-05-15] - API #9 PDF Links in Automatic Trial Expiry Email + Email Copy Update

### Changed
- **Feature: Auto Trial Expiry Email now includes PDF links**
  - Extended `emathsmart_inject_exam_links_to_email()` to also target `customer_notification_auto_trial_expiry` (WooCommerce Subscriptions automatic email), in addition to the existing `customer_notification_manual_trial_expiry`.
  - PDF exam links from API #9 (`getPublicExamQuestions`) are now sent to the user when their trial expires automatically.

- **Email copy updated** in `emathsmart_inject_exam_links_to_email()`:
  - Heading: `🎁 Your Bonus Public Exam Files` → `📚 Download Your Exam Papers`
  - Body: `As a thank you for trying eMathSmart...` → `Here are the exam papers and answer keys included with your eMathSmart subscription:`

### Technical Notes for AI Agents
- **Modified file:** `functions-esmart.php` — single condition change on the `$email->id` check in `emathsmart_inject_exam_links_to_email()`
- **Email IDs handled:** `customer_notification_manual_trial_expiry` (admin-triggered) AND `customer_notification_auto_trial_expiry` (fires via `woocommerce_scheduled_subscription_trial_end` hook)
- **PDF links source:** `emathsmart_get_public_exam_links($order_id)` — calls API #9 with HMAC-SHA256 signature; `expireTimestamp` excluded from signature
- **No new hooks or functions added** — the existing inject function already handles HTML and plain text formats

## [2026-05-13] - API Integration Hardening (Finalized APIs 5 & 6)

### Added
- **Feature 1: API Logging Infrastructure**
  - Created `wp_emathsmart_log` database table using `dbDelta`.
  - Captures: Order ID, Payload, Response Code, HTTP Status, and cURL errors.
- **Feature 2: Reliability & Retry Logic**
  - Implemented 3-attempt retry loop in `process_subscription_custom`.
  - Added smart regeneration of `nonce` and `timestamp` on every retry.
  - Added `emathsmart_log_api_error` helper function for persistent failure tracking.
- **Feature 5: API #6 (Refund) Hardening**
  - Added missing required fields: `parentId` and `refundTimestamp`.
  - Discovered and documented that API #6 signature **excludes** `parentId` and `refundTimestamp` despite their presence in the JSON body.
  - Implemented specific response handling for refund codes: `40201` (Parked), `40202` (Duplicate), `40203` (Mismatch), `40204` (Timestamp).
- **Feature 7: Admin Logs Dashboard (WooCommerce > eMathSmart Logs)**
  - Built a custom paginated table to view API communication history.
  - Implemented filters for: Order ID, API Type (Payment/Refund/Exam), and Date Range.
  - Added expandable "Details" view with pretty-printed JSON payloads and responses.
  - Implemented status badges (Success, API Error, Critical Error) with brand-aligned styling.
- **Feature 8: Deferred Order Note System**
  - Implemented `emathsmart_defer_order_note` using the `shutdown` hook.
  - Ensures eMathSmart API success/failure notes appear at the very top of the order notes timeline, after WooCommerce's default status-change notes.
- **Feature 9: Log Maintenance & Cleanup**
  - Added daily WP Cron job to automatically delete logs older than 30 days.
  - Added "Clear All Logs" manual button with a safety confirmation prompt.

### Technical Notes for AI Agents
- **Primary Logic:** Located in `functions-esmart.php`.
- **Admin UI:** Located in `functions-esmart-admin.php`.
- **Signature Algorithm:** HMAC-SHA256 with Base64url encoding (no padding).
- **Critical Secret:** `yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm` (Verified for APIs 5 & 6).
- **Order Note Timing:** Standard `add_order_note` calls during status transitions are often "buried" by WooCommerce's own system notes. Always use the deferred system (`emathsmart_defer_order_note`) for eMathSmart status updates.
- **API #6 Signature Exclusion:** Verified that `parentId` and `refundTimestamp` must be **excluded** from the HMAC signature for API #6, even though they are required in the JSON payload. Including them triggers `20306`.
- **Subscription Guard:** Production hooks now use `wcs_get_subscriptions_for_order` to prevent triggering eMathSmart for regular product sales.
- **Table Guard:** The logs page includes a `SHOW TABLES LIKE` guard to prevent errors if the plugin table isn't initialized yet.

## [2026-05-12] - Core API Synchronization

### Added
- **API #3: SSO User Identity**
  - Refactored to support OAuth2 Bearer tokens.
  - Maps Bearer tokens to local WordPress User IDs.
- **API #7 & #8: Compensation (Sync) APIs**
  - Developed endpoints for eMathSmart to "pull" missing order/refund data.
  - Supports date-based range filtering.
- **Webhook Synchronization**
  - Linked `woocommerce_order_status_completed` to eMathSmart Payment Notify.
  - Linked `woocommerce_order_status_refunded` to eMathSmart Refund Notify.
