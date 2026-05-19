# Changelog - PopularBook eMathSmart Integration

All notable changes to this project will be documented in this file for both human developers and AI agents.

## [2026-05-19] - Security & Performance Audit of Live Server Copy

### Added
- **Malware & Performance Analysis Report:** Added complete documentation of findings to `malware_performance_analysis.md`.

### Discovered
- **SEO Spam Cloaking Malware:**
  - Found sophisticated SEO cloaking code in the live copy (`public_html/index.php`) and `.htaccess` designed to intercept Googlebots/crawlers and serve Turkish gambling spam (`Meritking 2026`) located in a rogue root file `public_html/wp-options.php`.
- **Performance Cheating Hook:**
  - Located custom JS `bCheck()` function in the child theme's `header.php` which detects Lighthouse, GTmetrix, and other performance crawlers, then completely skips enqueuing GTM, Meta Pixel, Visual Composer styles, and Revolution Slider scripts. This fakes a high PageSpeed score on speed tools while real users suffer the full impact of unoptimized scripts.

### Technical Notes for AI Agents
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
