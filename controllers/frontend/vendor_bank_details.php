<?php
use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

// Only vendors can access this controller
if (!fn_allowed_for('MULTIVENDOR')) {
    return [CONTROLLER_STATUS_DENIED];
}

// Ensure user is logged in and is a vendor
if (empty($auth['user_id']) || !fn_auth_is_vendor()) {
    return [CONTROLLER_STATUS_REDIRECT, 'auth.login_form?return_url=' . urlencode(Registry::get('config.current_url'))];
}

$vendor_id = fn_get_vendor_id_by_auth($auth);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    fn_trusted_vars(
        'bank_data'
    );

    if ($mode === 'update') {
        $detail_id = isset($_REQUEST['detail_id']) ? (int) $_REQUEST['detail_id'] : 0;
        $bank_data = $_REQUEST['bank_data'];
        
        // Security check - ensure bank detail belongs to vendor if editing
        if ($detail_id) {
            $existing_detail = fn_get_vendor_bank_details_by_id($detail_id);
            if (empty($existing_detail) || $existing_detail['vendor_id'] != $vendor_id) {
                fn_set_notification('E', __('error'), __('access_denied'));
                return [CONTROLLER_STATUS_DENIED];
            }
        }

        // Validate required fields
        $required_fields = [
            'bank_name',
            'account_holder',
            'account_number',
            'swift_code',
            'branch_name',
            'bank_address'
        ];

        $is_valid = true;
        foreach ($required_fields as $field) {
            if (empty($bank_data[$field])) {
                fn_set_notification('E', __('error'), __('error_required_field', [
                    '[field]' => __('bank_details.' . $field)
                ]));
                $is_valid = false;
            }
        }

        if ($is_valid) {
            // Add vendor ID and sanitize data
            $bank_data['vendor_id'] = $vendor_id;
            $bank_data = array_map('trim', $bank_data);
            
            // Validate account number format
            if (!fn_validate_bank_account_number($bank_data['account_number'])) {
                fn_set_notification('E', __('error'), __('error_invalid_account_number'));
                $is_valid = false;
            }

            // Validate SWIFT/BIC code
            if (!fn_validate_swift_code($bank_data['swift_code'])) {
                fn_set_notification('E', __('error'), __('error_invalid_swift_code'));
                $is_valid = false;
            }

            // Validate IBAN if provided
            if (!empty($bank_data['iban']) && !fn_validate_iban($bank_data['iban'])) {
                fn_set_notification('E', __('error'), __('error_invalid_iban'));
                $is_valid = false;
            }

            if ($is_valid) {
                $detail_id = fn_update_vendor_bank_details($bank_data, $detail_id);
                
                if ($detail_id) {
                    fn_set_notification('N', __('notice'), __('bank_details_updated_successfully'));
                    
                    // Check if vendor has pending payouts
                    $pending_payouts = fn_check_vendor_pending_payouts($vendor_id);
                    if ($pending_payouts) {
                        fn_set_notification('W', __('warning'), __('pending_payouts_notice'));
                    }
                } else {
                    fn_set_notification('E', __('error'), __('error_updating_bank_details'));
                }
            }
        }

        $suffix = !empty($detail_id) ? '?detail_id=' . $detail_id : '';
        return [CONTROLLER_STATUS_REDIRECT, 'vendor_bank_details.update' . $suffix];
    }

    if ($mode === 'delete') {
        $detail_id = isset($_REQUEST['detail_id']) ? (int) $_REQUEST['detail_id'] : 0;
        
        if ($detail_id) {
            $bank_detail = fn_get_vendor_bank_details_by_id($detail_id);
            
            if (!empty($bank_detail) && $bank_detail['vendor_id'] == $vendor_id) {
                if (fn_delete_vendor_bank_details($detail_id)) {
                    fn_set_notification('N', __('notice'), __('bank_details_deleted'));
                }
            }
        }

        return [CONTROLLER_STATUS_REDIRECT, 'vendor_bank_details.manage'];
    }

    return [CONTROLLER_STATUS_OK];
}

if ($mode === 'manage') {
    $params = array_merge($_REQUEST, [
        'vendor_id' => $vendor_id,
        'items_per_page' => Registry::get('settings.Appearance.admin_elements_per_page')
    ]);

    list($bank_details, $search) = fn_get_vendor_bank_details($vendor_id, $params);
    
    // Get verification requirements
    $require_verification = Registry::get('addons.vendor_payouts.require_verification');
    
    // Get status statistics
    $status_stats = fn_get_vendor_bank_details_stats($vendor_id);

    Tygh::$app['view']->assign([
        'bank_details' => $bank_details,
        'search' => $search,
        'require_verification' => $require_verification,
        'status_stats' => $status_stats
    ]);

} elseif ($mode === 'update') {
    $detail_id = isset($_REQUEST['detail_id']) ? (int) $_REQUEST['detail_id'] : 0;
    
    if ($detail_id) {
        $bank_detail = fn_get_vendor_bank_details_by_id($detail_id);
        
        if (empty($bank_detail) || $bank_detail['vendor_id'] != $vendor_id) {
            return [CONTROLLER_STATUS_NO_PAGE];
        }
        
        Tygh::$app['view']->assign('bank_detail', $bank_detail);
    }

    // Get available statuses for display
    $statuses = [
        'P' => [
            'title' => __('pending'),
            'color' => 'yellow'
        ],
        'A' => [
            'title' => __('approved'),
            'color' => 'green'
        ],
        'R' => [
            'title' => __('rejected'),
            'color' => 'red'
        ]
    ];
    
    // Get minimum payout amount
    $minimum_payout = Registry::get('addons.vendor_payouts.minimum_payout');
    
    // Add stronger validation
    $validation_rules = array(
        'account_number' => array('required' => true, 'min_length' => 8),
        'swift_code' => array('required' => true, 'pattern' => '/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/'),
        'iban' => array('required' => false, 'pattern' => '/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/')
    );
    
    Tygh::$app['view']->assign([
        'statuses' => $statuses,
        'minimum_payout' => $minimum_payout
    ]);

} elseif ($mode === 'quick_view') {
    if (!defined('AJAX_REQUEST')) {
        return [CONTROLLER_STATUS_REDIRECT, 'vendor_bank_details.manage'];
    }

    $detail_id = isset($_REQUEST['detail_id']) ? (int) $_REQUEST['detail_id'] : 0;
    
    if ($detail_id) {
        $bank_detail = fn_get_vendor_bank_details_by_id($detail_id);
        
        if (!empty($bank_detail) && $bank_detail['vendor_id'] == $vendor_id) {
            Tygh::$app['view']->assign('bank_detail', $bank_detail);
            Tygh::$app['view']->display('addons/vendor_payouts/views/vendor_bank_details/quick_view.tpl');
        }
    }
    
    exit;
}