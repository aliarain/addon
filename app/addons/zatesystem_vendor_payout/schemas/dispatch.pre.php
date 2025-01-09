<?php
$schema['zatesystem_vendor_payout'] = array(
    'modes' => array(
        'manage' => array(
            'permissions' => 'view_payouts'
        ),
        'bank_details' => array(
            'permissions' => 'manage_payouts'
        ),
        'update_bank_details' => array(
            'permissions' => 'manage_payouts'
        )
    ),
    'permissions' => array(
        'GET' => 'view_payouts',
        'POST' => 'manage_payouts'
    )
);

return $schema; 