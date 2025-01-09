<?php
$schema['top']['administration']['items']['zatesystem_vendor_payout'] = array(
    'href' => 'zatesystem_vendor_payout.manage',
    'position' => 900,
    'title' => 'Vendor Payouts',
    'items' => array(
        'manage_payouts' => array(
            'href' => 'zatesystem_vendor_payout.manage',
            'position' => 100,
            'title' => 'Manage Payouts'
        ),
        'bank_details' => array(
            'href' => 'zatesystem_vendor_payout.bank_details',
            'position' => 200,
            'title' => 'Bank Details'
        )
    )
);

return $schema; 