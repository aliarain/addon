{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="bank_details_form" class="form-horizontal form-edit" enctype="multipart/form-data">
<input type="hidden" name="bank_detail_id" value="{$bank_detail.bank_detail_id}" />

{include file="common/subheader.tpl" title=__("bank_account_details")}

<div class="control-group">
    <label class="control-label cm-required" for="account_holder">{__("account_holder")}:</label>
    <div class="controls">
        <input type="text" name="bank_detail[account_holder]" id="account_holder" value="{$bank_detail.account_holder}" class="input-large" />
        <p class="muted description">{__("account_holder_tooltip")}</p>
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="account_number">{__("account_number")}:</label>
    <div class="controls">
        <input type="text" name="bank_detail[account_number]" id="account_number" value="{$bank_detail.account_number}" class="input-large" />
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="routing_number">{__("routing_number")}:</label>
    <div class="controls">
        <input type="text" name="bank_detail[routing_number]" id="routing_number" value="{$bank_detail.routing_number}" class="input-large" />
        <p class="muted description">{__("routing_number_tooltip")}</p>
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="bank_name">{__("bank_name")}:</label>
    <div class="controls">
        <input type="text" name="bank_detail[bank_name]" id="bank_name" value="{$bank_detail.bank_name}" class="input-large" />
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="account_type">{__("account_type")}:</label>
    <div class="controls">
        <select name="bank_detail[account_type]" id="account_type">
            <option value="checking" {if $bank_detail.account_type == "checking"}selected="selected"{/if}>{__("checking")}</option>
            <option value="savings" {if $bank_detail.account_type == "savings"}selected="selected"{/if}>{__("savings")}</option>
            <option value="current" {if $bank_detail.account_type == "current"}selected="selected"{/if}>{__("current")}</option>
        </select>
    </div>
</div>

{include file="common/subheader.tpl" title=__("additional_details")}

<div class="control-group">
    <label class="control-label" for="iban">{__("iban")}:</label>
    <div class="controls">
        <input type="text" name="bank_detail[iban]" id="iban" value="{$bank_detail.iban}" class="input-large" />
        <p class="muted description">{__("iban_tooltip")}</p>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="branch_name">{__("branch_name")}:</label>
    <div class="controls">
        <input type="text" name="bank_detail[branch_name]" id="branch_name" value="{$bank_detail.branch_name}" class="input-large" />
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="branch_address">{__("branch_address")}:</label>
    <div class="controls">
        <textarea name="bank_detail[branch_address]" id="branch_address" class="input-large">{$bank_detail.branch_address}</textarea>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="account_currency">{__("account_currency")}:</label>
    <div class="controls">
        <select name="bank_detail[currency_code]" id="account_currency">
            {foreach from=$currencies item="currency"}
                <option value="{$currency.currency_code}" {if $bank_detail.currency_code == $currency.currency_code}selected="selected"{/if}>{$currency.description}</option>
            {/foreach}
        </select>
    </div>
</div>

{include file="common/subheader.tpl" title=__("verification_documents")}

<div class="control-group">
    <label class="control-label">{__("upload_documents")}:</label>
    <div class="controls">
        {include file="common/fileuploader.tpl" var_name="bank_documents[]" multiupload=true}
        <p class="muted description">{__("document_requirements")}</p>
    </div>
</div>

{capture name="buttons"}
    {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_bank.update]" but_role="submit-link" but_target_form="bank_details_form" save=true}
{/capture}

</form>

{/capture}

{include file="common/mainbox.tpl"
    title=__("bank_account_details")
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
} 