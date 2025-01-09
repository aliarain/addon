<?php
$schema['vendor_payouts'] = array(
    'content' => array(
        'items' => array(
            'type' => 'function',
            'function' => array('fn_get_vendor_payout_summary')
        )
    ),
    'templates' => array(
        'addons/vendor_payouts/blocks/vendor_payouts.tpl' => array(),
    ),
    'wrappers' => 'blocks/wrappers',
    'cache' => array(
        'update_handlers' => array('vendor_payouts', 'vendor_bank_details'),
        'callable_handlers' => array(
            'company_id' => array('fn_get_runtime_company_id'),
        )
    )
);

return $schema; 