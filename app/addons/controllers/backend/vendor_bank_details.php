<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'verify') {
        $detail_id = $_REQUEST['detail_id'];
        $bank_detail = $_REQUEST['bank_detail'];
        
        // Update verification status
        $result = db_query(
            "UPDATE ?:vendor_bank_details SET ?u WHERE detail_id = ?i",
            array(
                'status' => $bank_detail['status'],
                'verification_notes' => $bank_detail['verification_notes'],
                'verified_at' => TIME,
                'verified_by' => $auth['user_id']
            ),
            $detail_id
        );
        
        if ($result) {
            // Send notification to vendor
            $vendor_id = db_get_field("SELECT vendor_id FROM ?:vendor_bank_details WHERE detail_id = ?i", $detail_id);
            fn_send_bank_detail_verification_notification($vendor_id, $bank_detail['status']);
            
            fn_set_notification('N', __('notice'), __('bank_details_verification_updated'));
        }
        
        return array(CONTROLLER_STATUS_REDIRECT, 'vendor_bank_details.manage');
    }
    return array(CONTROLLER_STATUS_OK);
}

if ($mode == 'verify') {
    $detail_id = $_REQUEST['detail_id'];
    
    $bank_detail = db_get_row(
        "SELECT vbd.*, c.company as company_name FROM ?:vendor_bank_details vbd "
        . "LEFT JOIN ?:companies c ON c.company_id = vbd.vendor_id "
        . "WHERE vbd.detail_id = ?i",
        $detail_id
    );
    
    if (empty($bank_detail)) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }
    
    Tygh::$app['view']->assign('bank_detail', $bank_detail);
} 