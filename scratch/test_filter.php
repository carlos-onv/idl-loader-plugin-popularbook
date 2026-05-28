<?php
// Include WordPress bootstrap
define('WP_USE_THEMES', false);
require_once('../../../../../wp-load.php');

echo "========================================\n";
echo "Testing Trial Length Filter Directly (Clean Cache)\n";
echo "========================================\n";

// Scenario 1: Guest (User ID = 0)
wp_set_current_user(0);
$trial_length_guest = emathsmart_dynamic_trial_length_for_parents_club(7, null);
echo "1. Guest (Default 7-day expected):\n";
echo "   Result: $trial_length_guest days\n\n";

// Scenario 2: Eligible Old Member
// Create a temporary dummy eligible user
$username = 'dummy_test_el_' . rand(1000, 9999);
$user_id = wp_create_user($username, 'password123', $username . '@example.com');
if (!is_wp_error($user_id)) {
    global $wpdb;
    $wpdb->update($wpdb->users, ['user_registered' => '2026-05-28 12:00:00'], ['ID' => $user_id]);
    update_user_meta($user_id, 'user_registration_check_box_1661192013', 'parent_club_member');
    clean_user_cache($user_id);
    
    wp_set_current_user($user_id);
    $trial_length_eligible = emathsmart_dynamic_trial_length_for_parents_club(7, null);
    echo "2. Eligible Old Member (14-day expected):\n";
    echo "   Result: $trial_length_eligible days\n\n";
    
    wp_delete_user($user_id);
}

// Scenario 3: Ineligible New Member
$username = 'dummy_test_inel_' . rand(1000, 9999);
$user_id = wp_create_user($username, 'password123', $username . '@example.com');
if (!is_wp_error($user_id)) {
    global $wpdb;
    $wpdb->update($wpdb->users, ['user_registered' => '2026-05-30 12:00:00'], ['ID' => $user_id]);
    update_user_meta($user_id, 'user_registration_check_box_1661192013', 'parent_club_member');
    clean_user_cache($user_id);
    
    wp_set_current_user($user_id);
    $trial_length_ineligible = emathsmart_dynamic_trial_length_for_parents_club(7, null);
    echo "3. Ineligible New Member (7-day expected):\n";
    echo "   Result: $trial_length_ineligible days\n\n";
    
    wp_delete_user($user_id);
}
