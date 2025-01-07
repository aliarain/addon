<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

$config = array(
    'vendor_payouts' => array(
        'version' => '1.0',
        'minimal_version' => '4.11.0',
        'installer' => array(
            'script' => 'install.php',
            'migrations' => array(
                'vendor_payouts_100.php',
            ),
        ),
        'settings' => array(
            'general' => array(
                'type' => 'header',
                'items' => array(
                    'encryption_key' => array(
                        'type' => 'input',
                        'default' => ''
                    ),
                    'minimum_payout' => array(
                        'type' => 'input',
                        'default' => '100'
                    ),
                    'require_verification' => array(
                        'type' => 'checkbox',
                        'default' => 'Y'
                    ),
                    'auto_payout' => array(
                        'type' => 'checkbox',
                        'default' => 'N'
                    )
                )
            )
        )
    )
);

return $config; 