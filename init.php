<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    // Vendor panel hooks
    'vendor_panel_tabs_pre',
    'vendor_panel_general_update',
    'vendor_account_update_pre',
    
    // Admin panel hooks
    'admin_payment_details_update',
    'get_payments_pre',
    'vendor_payouts_update_pre',
    
    // Payout processing hooks
    'get_vendor_payout_pre',
    'process_vendor_payout_pre',
    'process_vendor_payout_post',
    
    // Notification hooks
    'vendor_payout_notification',
    'bank_details_status_changed',
    
    // Add more specific hooks
    'vendor_payouts_calculate_commission',
    'vendor_payouts_minimum_threshold_check',
    'vendor_payouts_bank_details_validation'
);

// Register permissions
fn_register_permissions('vendor_payouts', array(
    'manage_bank_details' => array('admin', 'vendor'),
    'view_bank_details' => array('admin', 'vendor'),
    'verify_bank_details' => array('admin'),
    'process_payouts' => array('admin')
));