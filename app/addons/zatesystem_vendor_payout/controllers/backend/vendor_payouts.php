<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'update_bank_details') {
        $bank_detail_id = fn_update_vendor_bank_details($_REQUEST['bank_detail']);
        
        if ($bank_detail_id) {
            fn_set_notification('N', __('notice'), __('bank_details_updated'));
        }
        
        return array(CONTROLLER_STATUS_OK, 'vendor_payouts.bank_details');
    }
    return array(CONTROLLER_STATUS_OK);
}

if ($mode == 'manage') {
    // List of payouts
    $payouts = fn_get_vendor_payouts();
    Tygh::$app['view']->assign('payouts', $payouts);

} elseif ($mode == 'bank_details') {
    // Bank details form
    $bank_detail = array();
    if (!empty($_REQUEST['bank_detail_id'])) {
        $bank_detail = fn_get_vendor_bank_details($_REQUEST['bank_detail_id']);
    }
    
    Tygh::$app['view']->assign([
        'bank_detail' => $bank_detail,
        'currencies' => fn_get_currencies_list()
    ]);
} 