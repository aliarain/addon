<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

$schema['central']['vendors']['items']['vendor_payouts'] = [
    'attrs' => [
        'class' => 'is-addon'
    ],
    'href' => 'vendor_payouts.manage',
    'position' => 900,
    'subitems' => [
        'bank_details' => [
            'href' => 'vendor_bank_details.manage',
            'position' => 100
        ],
        'pending_verification' => [
            'href' => 'vendor_bank_details.manage?status=P',
            'position' => 200
        ],
        'payout_requests' => [
            'href' => 'vendor_payouts.manage',
            'position' => 300
        ]
    ]
];

return $schema;