<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'process_payout') {
        if (!empty($_REQUEST['payout_id'])) {
            $payout_id = $_REQUEST['payout_id'];
            
            if (fn_can_vendor_receive_payouts($_REQUEST['vendor_id'])) {
                $result = fn_process_vendor_payout($payout_id);
                if ($result) {
                    fn_set_notification('N', __('notice'), __('payout_processed_successfully'));
                }
            }
        }
        
        return [CONTROLLER_STATUS_REDIRECT, 'vendor_payouts.manage'];
    }
    
    return [CONTROLLER_STATUS_OK];
}

if ($mode == 'manage') {
    $params = $_REQUEST;
    
    $cache_key = "vendor_payouts_{$params['vendor_id']}";
    if (!($payouts = Registry::get($cache_key))) {
        $payouts = fn_get_vendor_payouts($params);
        Registry::set($cache_key, $payouts, SECONDS_IN_HOUR);
    }
    
    Tygh::$app['view']->assign([
        'payouts' => $payouts,
        'search' => $search
    ]);
    
} elseif ($mode == 'details') {
    $payout_id = isset($_REQUEST['payout_id']) ? $_REQUEST['payout_id'] : 0;
    
    if ($payout_id) {
        $payout = fn_get_vendor_payout_info($payout_id);
        $bank_details = fn_get_vendor_bank_details($payout['vendor_id'], ['status' => 'A']);
        
        Tygh::$app['view']->assign([
            'payout' => $payout,
            'bank_details' => $bank_details
        ]);
    }
}