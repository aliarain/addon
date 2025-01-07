{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="manage_payouts_form">

{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id="pagination_contents_payouts"}

{if $payouts}
    <div class="table-responsive-wrapper">
        <table class="table table-middle table-responsive">
            <thead>
                <tr>
                    <th width="1%">
                        {include file="common/check_items.tpl"}
                    </th>
                    <th width="15%">{__("vendor")}</th>
                    <th width="15%">{__("amount")}</th>
                    <th width="20%">{__("bank_details")}</th>
                    <th width="15%">{__("status")}</th>
                    <th width="15%">{__("date")}</th>
                    <th width="10%">&nbsp;</th>
                </tr>
            </thead>
            {foreach from=$payouts item=payout}
                <tr class="cm-row-status-{$payout.status|lower}">
                    <td>
                        <input type="checkbox" name="payout_ids[]" value="{$payout.payout_id}" class="cm-item" />
                    </td>
                    <td data-th="{__("vendor")}">
                        <a href="{"companies.update?company_id=`$payout.vendor_id`"|fn_url}">{$payout.company_name}</a>
                    </td>
                    <td data-th="{__("amount")}">
                        {include file="common/price.tpl" value=$payout.payout_amount}
                    </td>
                    <td data-th="{__("bank_details")}">
                        {$payout.bank_details.bank_name}<br>
                        {$payout.bank_details.account_holder}
                    </td>
                    <td data-th="{__("status")}">
                        {if $payout.status == "P"}
                            <span class="label label-warning">{__("pending")}</span>
                        {elseif $payout.status == "C"}
                            <span class="label label-success">{__("completed")}</span>
                        {else}
                            <span class="label label-danger">{__("failed")}</span>
                        {/if}
                    </td>
                    <td data-th="{__("date")}">
                        {$payout.created_at|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
                    </td>
                    <td class="right nowrap" data-th="{__("tools")}">
                        {capture name="tools_list"}
                            <li>{btn type="list" text=__("view") href="vendor_payouts.details?payout_id=`$payout.payout_id`"}</li>
                            {if $payout.status == "P"}
                                <li>{btn type="list" class="cm-confirm" text=__("process_payout") href="vendor_payouts.process?payout_id=`$payout.payout_id`" method="POST"}</li>
                                <li>{btn type="list" class="cm-confirm" text=__("decline") href="vendor_payouts.decline?payout_id=`$payout.payout_id`" method="POST"}</li>
                            {/if}
                        {/capture}
                        <div class="hidden-tools">
                            {dropdown content=$smarty.capture.tools_list}
                        </div>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{include file="common/pagination.tpl" div_id="pagination_contents_payouts"}

</form>

{capture name="sidebar"}
    {include file="addons/vendor_payouts/views/vendor_payouts/components/balance_info.tpl"}
    {include file="common/saved_search.tpl" dispatch="vendor_payouts.manage" view_type="vendor_payouts"}
    {include file="addons/vendor_payouts/views/vendor_payouts/components/payouts_search_form.tpl"}
{/capture}

{/capture}

{include file="common/mainbox.tpl"
    title=__("vendor_payouts")
    content=$smarty.capture.mainbox
    sidebar=$smarty.capture.sidebar
    select_languages=true
} 