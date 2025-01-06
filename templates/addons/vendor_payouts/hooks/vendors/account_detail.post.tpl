{if $runtime.company_id}
<div class="control-group">
    <label class="control-label">{__("bank_details")}:</label>
    <div class="controls">
        {if $bank_details}
            <table class="table table-middle table-condensed">
                <thead>
                    <tr>
                        <th>{__("bank_name")}</th>
                        <th>{__("status")}</th>
                        <th>{__("actions")}</th>
                    </tr>
                </thead>
                {foreach from=$bank_details item=detail}
                    <tr>
                        <td>{$detail.bank_name}</td>
                        <td>
                            <span class="label label-{if $detail.status == "A"}success{elseif $detail.status == "P"}warning{else}danger{/if}">
                                {if $detail.status == "A"}{__("approved")}
                                {elseif $detail.status == "P"}{__("pending")}
                                {else}{__("rejected")}{/if}
                            </span>
                        </td>
                        <td>
                            <a href="{"vendor_bank_details.update?detail_id=`$detail.detail_id`"|fn_url}" class="btn btn-small">{__("edit")}</a>
                        </td>
                    </tr>
                {/foreach}
            </table>
            <p>
                <a href="{"vendor_bank_details.update"|fn_url}" class="btn btn-primary">{__("add_new_bank_details")}</a>
            </p>
        {else}
            <p class="muted">{__("no_bank_details_available")}</p>
            <p>
                <a href="{"vendor_bank_details.update"|fn_url}" class="btn btn-primary">{__("add_bank_details")}</a>
            </p>
        {/if}
    </div>
</div>
{/if}