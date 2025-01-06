{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="vendor_payouts_form">

{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id="pagination_contents_payouts"}

{if $payouts}
<div class="table-responsive-wrapper">
    <table class="table table-middle table-responsive">
    <thead>
        <tr>
            <th>{__("payout_amount")}</th>
            <th>{__("bank_details")}</th>
            <th>{__("status")}</th>
            <th>{__("date")}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    {foreach from=$payouts item=payout}
        <tr>
            <td data-th="{__("payout_amount")}">{include file="common/price.tpl" value=$payout.payout_amount}</td>
            <td data-th="{__("bank_details")}">{$payout.bank_details.bank_name} - {$payout.bank_details.account_holder}</td>
            <td data-th="{__("status")}">
                <span class="label label-{if $payout.status == "P"}info{elseif $payout.status == "C"}success{else}danger{/if}">
                    {if $payout.status == "P"}{__("processing")}
                    {elseif $payout.status == "C"}{__("completed")}
                    {else}{__("failed")}{/if}
                </span>
            </td>
            <td data-th="{__("date")}">{$payout.created_at|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
            <td class="right nowrap" data-th="{__("tools")}">
                {capture name="tools_list"}
                    <li>{btn type="list" text=__("view") href="vendor_payouts.details?payout_id=`$payout.payout_id`"}</li>
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

{/capture}

{include file="common/mainbox.tpl" 
    title=__("vendor_payouts")
    content=$smarty.capture.mainbox
}