<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * Install function for vendor payouts addon
 */
function fn_vendor_payouts_install()
{
    // Generate encryption key if not exists
    if (empty(Registry::get('addons.vendor_payouts.encryption_key'))) {
        $encryption_key = fn_generate_encryption_key();
        db_query(
            "UPDATE ?:addon_settings SET value = ?s WHERE addon = 'vendor_payouts' AND name = 'encryption_key'",
            $encryption_key
        );
    }

    // Create necessary directories
    $dirs = array(
        'bank_documents',
        'verification_docs'
    );

    foreach ($dirs as $dir) {
        $path = fn_get_files_dir_path() . 'vendor_payouts/' . $dir;
        fn_mkdir($path);
    }

    // Add vendor balance field if not exists
    $vendor_balance_exists = db_get_field(
        "SHOW COLUMNS FROM ?:companies WHERE Field = 'vendor_balance'"
    );
    
    if (!$vendor_balance_exists) {
        db_query("ALTER TABLE ?:companies ADD COLUMN vendor_balance decimal(12,2) NOT NULL DEFAULT '0.00'");
    }

    return true;
}

/**
 * Generate secure encryption key
 */
function fn_generate_encryption_key()
{
    return bin2hex(openssl_random_pseudo_bytes(32));
}

/**
 * Uninstall function for vendor payouts addon
 */
function fn_vendor_payouts_uninstall()
{
    // Clean up files
    $path = fn_get_files_dir_path() . 'vendor_payouts';
    fn_rm($path);

    return true;
}

/**
 * Hook handler for calculating vendor balance
 */
function fn_vendor_payouts_calculate_vendor_balance($vendor_id)
{
    $balance = db_get_field(
        "SELECT vendor_balance FROM ?:companies WHERE company_id = ?i",
        $vendor_id
    );
    
    return floatval($balance);
} 