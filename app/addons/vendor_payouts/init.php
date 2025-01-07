<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

fn_register_hooks(
    'get_company_data_post',
    'update_company_pre',
    'get_companies_pre',
    'delete_company_pre',
    'update_order_details_post'
); 