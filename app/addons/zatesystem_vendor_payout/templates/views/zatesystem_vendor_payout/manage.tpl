{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="manage_payouts_form">

{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id="pagination_contents_payouts"}

{if $payouts}
<div class="table-responsive-wrapper">
    <table class="table table-middle table-responsive">
        <thead>
            <tr>
                <th>{__("vendor")}</th>
                <th>{__("amount")}</th>
                <th>{__("date")}</th>
                <th>{__("status")}</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        {foreach from=$payouts item="payout"}
        <tr>
            <td>{$payout.company_name}</td>
            <td>{include file="common/price.tpl" value=$payout.payout_amount}</td>
            <td>{$payout.created_at|date_format:"`$settings.Appearance.date_format`"}</td>
            <td>{$payout.status}</td>
            <td class="right">
                {include file="buttons/button.tpl" 
                    but_href="zatesystem_vendor_payout.details?payout_id=`$payout.payout_id`"
                    but_text=__("details")
                    but_role="action"}
            </td>
        </tr>
        {/foreach}
    </table>
</div>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

</form>

{/capture}

{capture name="adv_buttons"}
    {include file="common/tools.tpl" 
        tool_href="zatesystem_vendor_payout.add"
        prefix="top"
        title=__("add_payout")
        icon="icon-plus"}
{/capture}

{include file="common/mainbox.tpl"
    title=__("vendor_payouts")
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
    adv_buttons=$smarty.capture.adv_buttons
} 