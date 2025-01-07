<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_zatesystem_vendor_payout_install()
{
    // Create necessary tables first
    $tables = array(
        'zatesystem_vendor_payout_bank_details',
        'zatesystem_vendor_payout_main',
        'zatesystem_vendor_payout_history'
    );
    
    foreach ($tables as $table) {
        if (!db_get_field("SHOW TABLES LIKE '?:$table'")) {
            fn_zatesystem_vendor_payout_create_table($table);
        }
    }

    // Generate encryption key if not exists
    if (empty(Registry::get('addons.zatesystem_vendor_payout.encryption_key'))) {
        $encryption_key = fn_generate_encryption_key();
        Settings::instance()->updateValue('encryption_key', $encryption_key, 'zatesystem_vendor_payout');
    }

    return true;
}

function fn_zatesystem_vendor_payout_uninstall()
{
    return true;
}

function fn_zatesystem_vendor_payout_get_order_commission($vendor_id)
{
    return db_get_field(
        "SELECT SUM(payout_amount) FROM ?:zatesystem_vendor_payout_main WHERE vendor_id = ?i AND status = 'C'",
        $vendor_id
    );
}

function fn_zatesystem_vendor_payout_get_processed_payouts($vendor_id)
{
    return db_get_field(
        "SELECT SUM(payout_amount) FROM ?:zatesystem_vendor_payout_main WHERE vendor_id = ?i AND status = 'P'",
        $vendor_id
    );
}

function fn_zatesystem_vendor_payout_get_refunds($vendor_id)
{
    return 0; // Implement refund logic if needed
} 