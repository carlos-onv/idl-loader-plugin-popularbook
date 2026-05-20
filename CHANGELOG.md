# Changelog - PopularBook eMathSmart Integration

All notable changes to this project will be documented in this file for both human developers and AI agents.

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
