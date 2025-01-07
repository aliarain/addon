<?php
$schema['central']['vendors']['items']['zatesystem_vendor_payout'] = array(
    'attrs' => array(
        'class' => 'is-addon'
    ),
    'href' => 'zatesystem_vendor_payout.manage',
    'position' => 900,
    'subitems' => array(
        'manage_payouts' => array(
            'href' => 'zatesystem_vendor_payout.manage',
            'position' => 100
        ),
        'bank_details' => array(
            'href' => 'zatesystem_bank_details.manage',
            'position' => 200
        )
    )
);

return $schema; 