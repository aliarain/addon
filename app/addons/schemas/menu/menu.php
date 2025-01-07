<?php
$schema['central']['vendors']['items']['vendor_payouts'] = array(
    'attrs' => array(
        'class' => 'is-addon'
    ),
    'href' => 'vendor_payouts.manage',
    'position' => 900,
    'subitems' => array(
        'manage_payouts' => array(
            'href' => 'vendor_payouts.manage',
            'position' => 100
        ),
        'bank_details' => array(
            'href' => 'vendor_bank_details.manage',
            'position' => 200
        ),
        'payout_settings' => array(
            'href' => 'vendor_payouts.settings',
            'position' => 300,
            'type' => 'setting'
        )
    )
);

return $schema; 