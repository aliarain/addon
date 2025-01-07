<?php
return array(
    'calculate_balance' => array(
        'order_commission' => array(
            'type' => 'plus',
            'callback' => 'fn_vendor_payouts_get_order_commission'
        ),
        'payouts' => array(
            'type' => 'minus',
            'callback' => 'fn_vendor_payouts_get_processed_payouts'
        ),
        'refunds' => array(
            'type' => 'minus',
            'callback' => 'fn_vendor_payouts_get_refunds'
        )
    )
); 