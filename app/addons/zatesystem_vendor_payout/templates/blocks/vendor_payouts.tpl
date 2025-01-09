<div class="dashboard-card">
    <div class="dashboard-card-title">
        {__("vendor_payouts")}
    </div>
    <div class="dashboard-card-content">
        <h3>{__("available_balance")}</h3>
        <div class="dashboard-main-content">
            {include file="common/price.tpl" value=$balance}
            
            {if $balance >= $minimum_payout}
                <a href="{"vendor_payouts.request"|fn_url}" class="btn btn-primary">{__("request_payout")}</a>
            {else}
                <p class="muted">
                    {__("minimum_payout_required", ["[amount]" => $minimum_payout])}
                </p>
            {/if}
        </div>

        <div class="dashboard-recent-activity">
            <h4>{__("recent_payouts")}</h4>
            {if $recent_payouts}
                <table class="table table-condensed">
                    <tr>
                        <th>{__("amount")}</th>
                        <th>{__("date")}</th>
                        <th>{__("status")}</th>
                    </tr>
                    {foreach from=$recent_payouts item="payout"}
                        <tr>
                            <td>{include file="common/price.tpl" value=$payout.payout_amount}</td>
                            <td>{$payout.created_at|date_format:$settings.Appearance.date_format}</td>
                            <td>
                                <span class="label label-{if $payout.status == "C"}success{elseif $payout.status == "P"}warning{else}danger{/if}">
                                    {$payout.status}
                                </span>
                            </td>
                        </tr>
                    {/foreach}
                </table>
            {else}
                <p class="no-items">{__("no_payouts")}</p>
            {/if}
        </div>
    </div>
</div> 