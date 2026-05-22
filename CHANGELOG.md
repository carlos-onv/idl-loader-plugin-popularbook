# Changelog - PopularBook eMathSmart Integration

All notable changes to this project will be documented in this file for both human developers and AI agents.

## [2026-05-22] - AI Coins Custom Template & Sleek Glass-Minimalist UI (Option B)

### Added
- **Feature: Dynamic Product Description Display:** Dynamically fetched and rendered the WooCommerce product description (`$product->get_description()`) using a premium Outfit/Inter Glassmorphic typography block styling, cleanly formatting all HTML sub-elements (headings, lists, blockquotes, horizontal rules) inside the isolated `#emathsmart-custom-coins-product` scope.
- **Feature: Full-Width Layout Container:** Expanded the `.product-container` element to be 100% full-width (`max-width: 100%`), allowing the Option B Glassmorphic columns and sections to stretch beautifully on all viewport sizes.
- **Feature: Programmatic Custom WooCommerce Product Template Loading:** Implemented a new `template_include` filter in `functions-esmart.php` that dynamically detects single product requests for the `'ai-coins'` slug and redirects page loading to `/templates/single-product-ai-coins.php`.
- **Feature: High-Fidelity Sleek Glass-Minimalist UI Layout:** Created a bespoke product page template `/wp-content/plugins/idl-loader/templates/single-product-ai-coins.php` using Option B (frosted light-mode glassmorphic styling, champagne and rose-gold details, Outfit/Inter typography, and SSL trust indicators).
- **Feature: Isolated Theme Scoping:** Scoped all visual selectors and custom typography links strictly under the wrapper `#emathsmart-custom-coins-product`, ensuring the active theme's Porto header, navigation menu, announcement banner, and footer are completely native and untouched.
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
