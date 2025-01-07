<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    'get_companies_list_post',
    'update_company_pre',
    'delete_company_pre',
    'zatesystem_vendor_payout_update_balance'
); 