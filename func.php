<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Registry;
use Tygh\Enum\YesNo;

/**
 * Installation function
 */
function fn_vendor_payouts_install()
{
    // Create encryption key if not exists
    if (empty(Registry::get('addons.vendor_payouts.encryption_key'))) {
        $encryption_key = fn_generate_encryption_key();
        db_query("UPDATE ?:addon_settings SET value = ?s WHERE addon = 'vendor_payouts' AND name = 'encryption_key'", $encryption_key);
    }
}

/**
 * Generate secure encryption key
 */
function fn_generate_encryption_key()
{
    return bin2hex(random_bytes(32));
}

/**
 * Encrypt sensitive data
 */
function fn_encrypt_bank_details($data)
{
    $key = Registry::get('addons.vendor_payouts.encryption_key');
    return openssl_encrypt($data, 'AES-256-CBC', $key, 0, substr($key, 0, 16));
}

/**
 * Decrypt sensitive data
 */
function fn_decrypt_bank_details($encrypted_data)
{
    $key = Registry::get('addons.vendor_payouts.encryption_key');
    return openssl_decrypt($encrypted_data, 'AES-256-CBC', $key, 0, substr($key, 0, 16));
}

/**
 * Get vendor bank details
 */
function fn_get_vendor_bank_details($vendor_id, $params = [])
{
    $default_params = [
        'status' => null,
        'items_per_page' => 0,
    ];
    
    $params = array_merge($default_params, $params);
    
    $conditions = ['vendor_id = ?i'];
    $limit = '';
    
    if (!empty($params['status'])) {
        $conditions[] = 'status = ?s';
    }
    
    if (!empty($params['items_per_page'])) {
        $limit = db_paginate($params['page'], $params['items_per_page']);
    }
    
    $bank_details = db_get_array(
        "SELECT * FROM ?:vendor_bank_details WHERE " . implode(' AND ', $conditions) . " ORDER BY created_at DESC " . $limit,
        $vendor_id,
        $params['status']
    );
    
    foreach ($bank_details as &$detail) {
        $detail['account_number'] = fn_decrypt_bank_details($detail['account_number']);
        $detail['iban'] = !empty($detail['iban']) ? fn_decrypt_bank_details($detail['iban']) : '';
    }
    
    return $bank_details;
}

/**
 * Update vendor bank details
 */
function fn_update_vendor_bank_details($data, $detail_id = 0)
{
    if (empty($data['vendor_id'])) {
        return false;
    }
    
    // Encrypt sensitive data
    $data['account_number'] = fn_encrypt_bank_details($data['account_number']);
    if (!empty($data['iban'])) {
        $data['iban'] = fn_encrypt_bank_details($data['iban']);
    }
    
    $data['updated_at'] = TIME;
    
    if (empty($detail_id)) {
        $data['created_at'] = TIME;
        $data['status'] = 'P'; // Set as pending
        return db_query("INSERT INTO ?:vendor_bank_details ?e", $data);
    } else {
        // Reset verification if details are changed
        $data['verified_at'] = null;
        $data['verified_by'] = null;
        $data['status'] = 'P';
        
        return db_query("UPDATE ?:vendor_bank_details SET ?u WHERE detail_id = ?i", $data, $detail_id);
    }
}

/**
 * Verify vendor bank details
 */
function fn_verify_vendor_bank_details($detail_id, $status, $verification_notes = '', $admin_id = 0)
{
    if (!in_array($status, ['A', 'R'])) {
        return false;
    }
    
    $data = [
        'status' => $status,
        'verified_at' => TIME,
        'verified_by' => $admin_id,
        'verification_notes' => $verification_notes
    ];
    
    $result = db_query("UPDATE ?:vendor_bank_details SET ?u WHERE detail_id = ?i", $data, $detail_id);
    
    if ($result) {
        // Notify vendor about verification result
        $bank_details = fn_get_vendor_bank_details_by_id($detail_id);
        fn_send_bank_details_notification($bank_details, $status);
    }
    
    return $result;
}

/**
 * Get bank details by ID
 */
function fn_get_vendor_bank_details_by_id($detail_id)
{
    $detail = db_get_row("SELECT * FROM ?:vendor_bank_details WHERE detail_id = ?i", $detail_id);
    if ($detail) {
        $detail['account_number'] = fn_decrypt_bank_details($detail['account_number']);
        $detail['iban'] = !empty($detail['iban']) ? fn_decrypt_bank_details($detail['iban']) : '';
    }
    return $detail;
}

/**
 * Send notification about bank details verification
 */
function fn_send_bank_details_notification($bank_details, $status)
{
    $notification_data = [
        'bank_name' => $bank_details['bank_name'],
        'status' => $status,
        'notes' => $bank_details['verification_notes']
    ];
    
    fn_send_notification_to_vendor($bank_details['vendor_id'], 'bank_details_status_changed', $notification_data);
}
function fn_vendor_payouts_send_status_notification($vendor_id, $status, $old_status) {
    if ($status != $old_status) {
        $notification_data = [
            'status' => $status,
            'old_status' => $old_status
        ];
        fn_send_notification_to_vendor($vendor_id, 'bank_details_status_changed', $notification_data);
    }
}

/**
 * Check if vendor can receive payouts
 */
function fn_can_vendor_receive_payouts($vendor_id)
{
    $has_verified_details = db_get_field(
        "SELECT COUNT(*) FROM ?:vendor_bank_details WHERE vendor_id = ?i AND status = 'A'",
        $vendor_id
    );
    
    return $has_verified_details > 0;
}

/**
 * Hook handler: Check bank details before processing payout
 */
function fn_vendor_payouts_process_vendor_payout_pre($vendor_id, &$params)
{
    if (!fn_can_vendor_receive_payouts($vendor_id)) {
        fn_set_notification('E', __('error'), __('error_no_verified_bank_details'));
        return false;
    }
    
    return true;
}

function fn_vendor_payouts_handle_errors($error_code) {
    $error_messages = array(
        'INVALID_BANK_DETAILS' => __('error_invalid_bank_details'),
        'INSUFFICIENT_FUNDS' => __('error_insufficient_funds'),
        'PAYOUT_FAILED' => __('error_payout_failed')
    );
    
    return isset($error_messages[$error_code]) ? $error_messages[$error_code] : __('error_unknown');
}

function fn_vendor_payouts_log_action($action, $data = array()) {
    fn_log_event('vendor_payouts', $action, $data);
}

/**
 * Encrypt sensitive data
 */
function fn_encrypt_sensitive_data($data, $key) {
    $cipher = "aes-256-gcm";
    $iv_len = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($iv_len);
    $tag = "";
    
    $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
    return base64_encode($iv . $tag . $encrypted);
}