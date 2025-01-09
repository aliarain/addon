<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'request') {
        $payout_data = $_REQUEST['payout_data'];
        $vendor_id = $auth['company_id'];
        
        // Validate minimum amount
        $minimum_payout = Registry::get('addons.vendor_payouts.minimum_payout');
        if ($payout_data['amount'] < $minimum_payout) {
            fn_set_notification('E', __('error'), __('minimum_payout_amount_not_reached'));
            return array(CONTROLLER_STATUS_REDIRECT, 'vendor_payouts.request');
        }
        
        // Validate bank details
        if (!fn_can_vendor_receive_payouts($vendor_id)) {
            fn_set_notification('E', __('error'), __('no_verified_bank_details'));
            return array(CONTROLLER_STATUS_REDIRECT, 'vendor_payouts.request');
        }
        
        // Create payout request
        $payout_id = db_query(
            "INSERT INTO ?:vendor_payouts ?e",
            array(
                'vendor_id' => $vendor_id,
                'payout_amount' => $payout_data['amount'],
                'bank_details_id' => $payout_data['bank_details_id'],
                'notes' => $payout_data['notes'],
                'status' => 'P',
                'created_at' => TIME
            )
        );
        
        if ($payout_id) {
            // Notify admin
            fn_send_payout_request_notification($payout_id);
            fn_set_notification('N', __('notice'), __('payout_request_submitted'));
        }
        
        return array(CONTROLLER_STATUS_REDIRECT, 'vendor_payouts.manage');
    }
    return array(CONTROLLER_STATUS_OK);
}

if ($mode == 'request') {
    $vendor_id = $auth['company_id'];
    
    // Get verified bank details
    $bank_details = db_get_array(
        "SELECT * FROM ?:vendor_bank_details WHERE vendor_id = ?i AND status = 'A'",
        $vendor_id
    );
    
    Tygh::$app['view']->assign(array(
        'bank_details' => $bank_details,
        'minimum_payout' => Registry::get('addons.vendor_payouts.minimum_payout')
    ));
} 