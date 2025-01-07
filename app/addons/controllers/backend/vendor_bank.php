<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'update') {
        $bank_detail_id = fn_update_vendor_bank_details($_REQUEST['bank_detail']);
        
        if ($bank_detail_id) {
            fn_set_notification('N', __('notice'), __('bank_details_updated'));
        }
        
        return array(CONTROLLER_STATUS_OK, 'vendor_bank.details');
    }
}

if ($mode == 'details') {
    $bank_detail = array();
    if (!empty($_REQUEST['bank_detail_id'])) {
        $bank_detail = fn_get_vendor_bank_details($_REQUEST['bank_detail_id']);
    }
    
    Tygh::$app['view']->assign('bank_detail', $bank_detail);
    Tygh::$app['view']->assign('currencies', fn_get_currencies_list());
} 