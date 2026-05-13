# Changelog - PopularBook eMathSmart Integration

All notable changes to this project will be documented in this file for both human developers and AI agents.

## [2026-05-13] - API Integration Hardening (In Progress)

### Added
- **Feature 1: API Logging Infrastructure**
  - Created `wp_emathsmart_log` database table using `dbDelta`.
  - Captures: Order ID, Payload, Response Code, HTTP Status, and cURL errors.
- **Feature 2: Reliability & Retry Logic**
  - Implemented 3-attempt retry loop in `process_subscription_custom`.
  - Added smart regeneration of `nonce` and `timestamp` on every retry.
  - Added `emathsmart_log_api_error` helper function for persistent failure tracking.
- **Feature 3: WooCommerce Admin Visibility**
  - Added automatic WooCommerce Order Notes for API results.
  - Distinct notes for: Success ✅, Idempotent Success (40101) ✅, and Failures ❌.
- **Testing Suite**
  - Created `functions-esmart-debug.php` to simulate error scenarios (Invalid Signature, Network Timeout, 500 Errors).
  - Restricted debug triggers to administrators only.

### Technical Notes for AI Agents
- **Primary Logic:** Located in `functions-esmart.php`.
- **Signature Algorithm:** HMAC-SHA256 with Base64url encoding (no padding).
- **Critical Secret:** `yZ.qmUuVYz,h_=Wzj:4!naWAoxW.vjLm` (Verified for APIs 5 & 6).
- **Retry Logic Update:** Added support for error code `20306` (observed live) which differs from the documented `40001` for signature mismatches.
- **Known Blockers:** API #9 (`getPublicExamQuestions`) returns 40001; suspecting a different secret key for the `customer-center` module.
- **Field Handling:** Strings are required for the HMAC signature, but integers are required for several fields in the JSON body (Split Type logic).

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
