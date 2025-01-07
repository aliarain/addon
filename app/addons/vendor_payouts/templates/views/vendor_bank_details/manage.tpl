{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="manage_bank_details_form" class="form-horizontal form-edit">

{if $bank_details}
    <div class="table-responsive-wrapper">
        <table class="table table-middle table-responsive">
            <thead>
                <tr>
                    <th width="1%">
                        {include file="common/check_items.tpl"}
                    </th>
                    <th width="20%">{__("bank_name")}</th>
                    <th width="20%">{__("account_holder")}</th>
                    <th width="15%">{__("swift_code")}</th>
                    <th width="15%">{__("status")}</th>
                    <th width="15%">{__("verification")}</th>
                    <th width="10%">&nbsp;</th>
                </tr>
            </thead>
            {foreach from=$bank_details item=detail}
                <tr class="cm-row-status-{$detail.status|lower}">
                    <td>
                        <input type="checkbox" name="detail_ids[]" value="{$detail.detail_id}" class="cm-item" />
                    </td>
                    <td data-th="{__("bank_name")}">
                        <a href="{"vendor_bank_details.update?detail_id=`$detail.detail_id`"|fn_url}">{$detail.bank_name}</a>
                    </td>
                    <td data-th="{__("account_holder")}">{$detail.account_holder}</td>
                    <td data-th="{__("swift_code")}">{$detail.swift_code}</td>
                    <td data-th="{__("status")}">
                        <span class="label label-{if $detail.status == "A"}success{elseif $detail.status == "P"}warning{else}danger{/if}">
                            {$detail.status_text}
                        </span>
                    </td>
                    <td data-th="{__("verification")}">
                        {if $detail.verified_at}
                            {$detail.verified_at|date_format:"`$settings.Appearance.date_format`"}
                        {else}
                            {__("pending")}
                        {/if}
                    </td>
                    <td class="right nowrap" data-th="{__("tools")}">
                        {capture name="tools_list"}
                            <li>{btn type="list" text=__("edit") href="vendor_bank_details.update?detail_id=`$detail.detail_id`"}</li>
                            {if $detail.status != "A"}
                                <li>{btn type="list" class="cm-confirm" text=__("delete") href="vendor_bank_details.delete?detail_id=`$detail.detail_id`" method="POST"}</li>
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

</form>

{capture name="adv_buttons"}
    {include file="common/tools.tpl" tool_href="vendor_bank_details.add" prefix="top" title=__("add_bank_details") hide_tools=true icon="icon-plus"}
{/capture}

{/capture}

{include file="common/mainbox.tpl" 
    title=__("bank_details")
    content=$smarty.capture.mainbox 
    adv_buttons=$smarty.capture.adv_buttons
    select_languages=true
} 