(function(_, $) {
    $(document).ready(function() {
        var refreshBalance = function() {
            $.ceAjax('request', fn_url('vendor_payouts.get_balance'), {
                method: 'get',
                data: {
                    vendor_id: _.vendor_id
                },
                callback: function(data) {
                    if (data.balance) {
                        $('.dashboard-balance').text(data.balance);
                    }
                }
            });
        };

        // Refresh balance every 5 minutes
        setInterval(refreshBalance, 300000);

        // Handle payout request form
        $('.payout-request-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            
            $.ceAjax('request', form.attr('action'), {
                method: 'post',
                data: form.serialize(),
                callback: function(data) {
                    if (data.success) {
                        $.ceNotification('show', {
                            type: 'N',
                            title: _.tr('notice'),
                            message: data.message
                        });
                        refreshBalance();
                    }
                }
            });
        });
    });
}(Tygh, jQuery)); 