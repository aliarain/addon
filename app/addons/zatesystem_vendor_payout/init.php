<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    'get_companies_list_post',
    'update_company_pre',
    'delete_company_pre',
    'vendor_payouts_update_balance'
);

fn_register_dispatch('zatesystem_vendor_payout'); 