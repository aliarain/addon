{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="vendor_bank_details_form">

{include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id="pagination_contents_bank_details"}

{if $bank_details}
<div class="table-responsive-wrapper">
    <table class="table table-middle table-responsive">
    <thead>
        <tr>
            <th>{__("bank_name")}</th>
            <th>{__("account_holder")}</th>
            <th>{__("swift_code")}</th>
            <th>{__("status")}</th>
            <th>{__("created_at")}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    {foreach from=$bank_details item=detail}
        <tr>
            <td data-th="{__("bank_name")}">{$detail.bank_name}</td>
            <td data-th="{__("account_holder")}">{$detail.account_holder}</td>
            <td data-th="{__("swift_code")}">{$detail.swift_code}</td>
            <td data-th="{__("status")}">
                <span class="label label-{if $detail.status == "A"}success{elseif $detail.status == "P"}warning{else}danger{/if}">
                    {if $detail.status == "A"}{__("approved")}
                    {elseif $detail.status == "P"}{__("pending")}
                    {else}{__("rejected")}{/if}
                </span>
            </td>
            <td data-th="{__("created_at")}">{$detail.created_at|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
            <td class="right nowrap" data-th="{__("tools")}">
                {capture name="tools_list"}
                    <li>{btn type="list" text=__("edit") href="vendor_bank_details.update?detail_id=`$detail.detail_id`"}</li>
                    {if $detail.status == "P"}
                        <li>{btn type="list" text=__("delete") class="cm-confirm" href="vendor_bank_details.delete?detail_id=`$detail.detail_id`" method="POST"}</li>
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

{include file="common/pagination.tpl" div_id="pagination_contents_bank_details"}

</form>

{/capture}

{capture name="adv_buttons"}
    {include file="common/tools.tpl" tool_href="vendor_bank_details.update" prefix="top" title=__("add_bank_details") hide_tools=true icon="icon-plus"}
{/capture}

{include file="common/mainbox.tpl" 
    title=__("vendor_bank_details")
    content=$smarty.capture.mainbox
    adv_buttons=$smarty.capture.adv_buttons
}