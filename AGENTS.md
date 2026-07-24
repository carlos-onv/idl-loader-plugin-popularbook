# Workspace Rules — Custom Function Loader Plugin (`idl-loader`)

## Overview
This repository contains the dedicated **Custom Function Loader Plugin (`idl-loader`)** for `dev.popularbook.ca`. All custom WooCommerce hooks, eMathSmart API integrations, backend business logic, and WPBakery element definitions live inside this plugin.

- **Remote Repository**: `https://github.com/carlos-onv/idl-loader-plugin-popularbook.git` (`main` branch)
- **Local Path**: `/wp-content/plugins/idl-loader/`

---

## Core Guidelines for AI Agents

1. **Clean Core & Debug Isolation**:
   - Keep all test/debug scenarios isolated inside `functions-esmart-debug.php`.
   - Never place temporary hacks or hardcoded staging keys in production files (`functions-esmart.php`, `functions.php`).

2. **Changelog First**:
   - Before ending a task or starting a new feature, update `CHANGELOG.md`.
   - Document technical API signatures, parameters, and architecture decisions.

3. **Explicit File Path Communication**:
   - When reporting code modifications or file edits to the user, always specify the full container and relative path (e.g. `wp-content/plugins/idl-loader/functions.php` or `wp-content/plugins/idl-loader/functions-wpbakery-elements.php`) rather than generic filenames.

4. **Commit & Push Requirement**:
   - Stage, commit with descriptive messages, and push immediately to `origin main` after completing any feature or fix:
     ```bash
     cd wp-content/plugins/idl-loader
     git add .
     git commit -m "Feature: Brief description of change"
     git push origin main
     ```
