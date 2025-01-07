<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

use Tygh\Common\VendorPayouts;
use Tygh\Registry;

/**
 * Hook handler: Check vendor balance before getting products
 */
function fn_vendor_payouts_get_products_pre($params, &$items_per_page, $lang_code)
{
    if (!empty($params['company_id'])) {
        if (!VendorPayouts::checkVendorBalance($params['company_id'])) {
            if (Registry::get('addons.vendor_payouts.hide_products') === 'Y') {
                $params['status'] = 'H';
            }
        }
    }
}

/**
 * Process vendor payout
 */
function fn_process_vendor_payout($payout_id, $params = array())
{
    $payout_data = fn_get_vendor_payout_info($payout_id);
    if (empty($payout_data)) {
        return false;
    }

    // Check if vendor can receive payouts
    if (!fn_can_vendor_receive_payouts($payout_data['vendor_id'])) {
        return false;
    }

    // Process payout
    $result = fn_update_vendor_payout_status($payout_id, 'C');
    if ($result) {
        // Update vendor balance
        fn_update_vendor_balance($payout_data['vendor_id'], -$payout_data['payout_amount']);
        
        // Send notification
        fn_send_payout_notification($payout_data);
    }

    return $result;
}

/**
 * Get vendor payout info
 */
function fn_get_vendor_payout_info($payout_id)
{
    return db_get_row(
        "SELECT * FROM ?:vendor_payouts WHERE payout_id = ?i",
        $payout_id
    );
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
 * Hook handler: Add bank details validation
 */
function fn_vendor_payouts_bank_details_validation_rules(&$rules)
{
    $rules['swift_code'] = array(
        'required' => true,
        'pattern' => '/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/'
    );
    $rules['iban'] = array(
        'required' => false,
        'pattern' => '/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/'
    );
}

/**
 * Hook handler: Add payout link to vendor panel
 */
function fn_vendor_payouts_vendor_panel_main_links($auth, &$links)
{
    if (!empty($auth['company_id'])) {
        $links['vendor_payouts'] = array(
            'text' => __('vendor_payouts'),
            'href' => 'vendor_payouts.manage',
            'position' => 500
        );
    }
}

/**
 * Hook handler: Add bank details tab to vendor profile
 */
function fn_vendor_payouts_admin_vendor_tabs_update($vendor_id, &$tabs)
{
    $tabs['bank_details'] = array(
        'title' => __('bank_details'),
        'js' => true,
        'href' => "vendor_bank_details.manage?vendor_id={$vendor_id}"
    );
}

/**
 * Hook handler: Process bank details before update
 */
function fn_vendor_payouts_vendor_bank_details_update_pre($bank_data, &$detail_id)
{
    // Encrypt sensitive data
    if (!empty($bank_data['account_number'])) {
        $bank_data['account_number'] = fn_encrypt_bank_details($bank_data['account_number']);
    }
    if (!empty($bank_data['iban'])) {
        $bank_data['iban'] = fn_encrypt_bank_details($bank_data['iban']);
    }
}

/**
 * Hook handler: Process payout request
 */
function fn_vendor_payouts_vendor_payout_request_pre($payout_data, &$vendor_id)
{
    // Check vendor balance
    $balance = fn_get_vendor_balance($vendor_id);
    if ($balance < $payout_data['amount']) {
        fn_set_notification('E', __('error'), __('insufficient_balance'));
        return false;
    }

    // Check minimum payout amount
    $minimum_payout = Registry::get('addons.vendor_payouts.minimum_payout');
    if ($payout_data['amount'] < $minimum_payout) {
        fn_set_notification('E', __('error'), __('minimum_payout_amount_not_reached'));
        return false;
    }

    return true;
}

/**
 * Hook handler: Additional payout actions
 */
function fn_vendor_payouts_vendor_payouts_actions_list(&$actions)
{
    $actions['verify'] = array(
        'name' => __('verify'),
        'permission' => 'manage_vendor_payouts'
    );
    $actions['reject'] = array(
        'name' => __('reject'),
        'permission' => 'manage_vendor_payouts'
    );
}

// Add all other functions from the original func.php 