<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    // Vendor balance hooks
    'get_vendor_balance_pre',
    'get_vendor_balance_post',
    'update_vendor_balance_pre',
    'update_vendor_balance_post',
    
    // Vendor status hooks
    'change_vendor_status_pre',
    'change_vendor_status_post',
    
    // Product visibility hooks
    'get_products_pre',
    'get_products_post',
    
    // Admin access hooks
    'check_admin_permissions',
    'login_user_pre',
    
    // Payment hooks
    'process_payment_pre',
    'process_payment_post',
    
    // Notification hooks
    'send_notification_pre',
    'send_notification_post',
    
    // Vendor data hooks
    'update_vendor_pre',
    'update_vendor_post',
    'get_vendor_data_pre',
    'get_vendor_data_post',

    // New hooks for bank details and payouts
    'vendor_bank_details_update_pre',
    'vendor_bank_details_update_post',
    'vendor_bank_details_delete_pre',
    'vendor_bank_details_delete_post',
    'vendor_payout_request_pre',
    'vendor_payout_request_post',
    'vendor_payout_process_pre',
    'vendor_payout_process_post',
    'vendor_payouts_get_list',
    'get_vendor_bank_details',
    'verify_bank_details_pre',
    'verify_bank_details_post',

    // UI hooks
    'vendor_panel_main_links',
    'admin_vendor_tabs_update',
    'vendor_payouts_actions_list',
    'bank_details_extra_fields',
    'bank_details_validation_rules'
); 