<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

$schema['vendor_payouts'] = [
    'permissions' => [
        'GET' => 'view_bank_details',
        'POST' => 'manage_bank_details'
    ],
    'modes' => [
        'verify' => 'verify_bank_details',
        'process_payout' => 'process_payouts',
        'update_status' => 'verify_bank_details',
        'delete' => 'manage_bank_details',
        'export' => 'view_bank_details'
    ]
];

$schema['vendor_bank_details'] = [
    'permissions' => [
        'GET' => 'view_bank_details',
        'POST' => 'manage_bank_details'
    ],
    'modes' => [
        'update' => 'manage_bank_details',
        'delete' => 'manage_bank_details'
    ]
];

return $schema;