<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if (!fn_auth_is_vendor()) {
    return [CONTROLLER_STATUS_DENIED];
}

$vendor_id = $auth['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'update') {
        $detail_id = !empty($_REQUEST['detail_id']) ? $_REQUEST['detail_id'] : 0;
        $bank_data = $_REQUEST['bank_data'];
        
        // Add vendor ID to data
        $bank_data['vendor_id'] = $vendor_id;
        
        // Validate required fields
        $required_fields = ['bank_name', 'account_holder', 'account_number', 'swift_code', 'branch_name', 'bank_address'];
        $is_valid = true;
        
        foreach ($required_fields as $field) {
            if (empty($bank_data[$field])) {
                fn_set_notification('E', __('error'), __('field_required', ['[field]' => __($field)]));
                $is_valid = false;
            }
        }
        
        if ($is_valid) {
            if (fn_update_vendor_bank_details($bank_data, $detail_id)) {
                fn_set_notification('N', __('notice'), __('bank_details_updated_successfully'));
            }
        }
        
        return [CONTROLLER_STATUS_REDIRECT, 'vendor_bank_details.manage'];
    }
    
    return [CONTROLLER_STATUS_OK];
}

if ($mode == 'manage') {
    $params = $_REQUEST;
    $params['vendor_id'] = $vendor_id;
    
    list($bank_details, $search) = fn_get_vendor_bank_details($vendor_id, $params);
    
    Tygh::$app['view']->assign([
        'bank_details' => $bank_details,
        'search' => $search
    ]);
    
} elseif ($mode == 'update') {
    $detail_id = !empty($_REQUEST['detail_id']) ? $_REQUEST['detail_id'] : 0;
    
    if ($detail_id) {
        $bank_detail = fn_get_vendor_bank_details_by_id($detail_id);
        
        // Check if bank detail belongs to current vendor
        if (empty($bank_detail) || $bank_detail['vendor_id'] != $vendor_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }
        
        Tygh::$app['view']->assign('bank_detail', $bank_detail);
    }
    
    // Get available statuses for display
    $statuses = [
        'P' => __('pending'),
        'A' => __('approved'),
        'R' => __('rejected')
    ];
    
    Tygh::$app['view']->assign('statuses', $statuses);
}