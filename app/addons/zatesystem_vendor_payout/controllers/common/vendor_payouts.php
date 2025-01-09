<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($mode == 'get_balance') {
    $vendor_id = $_REQUEST['vendor_id'];
    $balance = fn_get_vendor_balance($vendor_id);
    
    Tygh::$app['ajax']->assign('balance', $balance);
    exit;
}

if ($mode == 'check_status') {
    $payout_id = $_REQUEST['payout_id'];
    $status = fn_get_payout_status($payout_id);
    
    Tygh::$app['ajax']->assign('status', $status);
    exit;
} 