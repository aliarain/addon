<?php
namespace Tygh\Notifications;

class VendorPayouts
{
    public static function sendPayoutNotification($payout_data)
    {
        $notification_data = array(
            'amount' => $payout_data['payout_amount'],
            'vendor' => fn_get_company_name($payout_data['vendor_id']),
            'status' => $payout_data['status'],
            'date' => date('Y-m-d H:i:s', $payout_data['created_at'])
        );

        fn_set_notification(
            'N', 
            __('notice'), 
            __('payout_notification', $notification_data)
        );

        // Send email
        $mailer = \Tygh::$app['mailer'];
        $mailer->send(array(
            'to' => 'vendor@email.com',
            'from' => 'admin@email.com',
            'data' => $notification_data,
            'template_code' => 'vendor_payout_notification',
            'tpl' => 'addons/vendor_payouts/notifications/payout.tpl'
        ));
    }
} 