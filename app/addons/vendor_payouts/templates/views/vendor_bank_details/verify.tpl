{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="verify_bank_details_form" class="form-horizontal form-edit">
<input type="hidden" name="detail_id" value="{$bank_detail.detail_id}" />

<div class="control-group">
    <label class="control-label">{__("vendor")}:</label>
    <div class="controls">
        <p class="text-info">{$bank_detail.company_name}</p>
    </div>
</div>

<div class="control-group">
    <label class="control-label">{__("bank_name")}:</label>
    <div class="controls">
        <p>{$bank_detail.bank_name}</p>
    </div>
</div>

<div class="control-group">
    <label class="control-label">{__("account_holder")}:</label>
    <div class="controls">
        <p>{$bank_detail.account_holder}</p>
    </div>
</div>

<div class="control-group">
    <label class="control-label">{__("swift_code")}:</label>
    <div class="controls">
        <p>{$bank_detail.swift_code}</p>
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required">{__("verification_status")}:</label>
    <div class="controls">
        <select name="bank_detail[status]" class="input-large">
            <option value="A" {if $bank_detail.status == "A"}selected="selected"{/if}>{__("approved")}</option>
            <option value="P" {if $bank_detail.status == "P"}selected="selected"{/if}>{__("pending")}</option>
            <option value="R" {if $bank_detail.status == "R"}selected="selected"{/if}>{__("rejected")}</option>
        </select>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="verification_notes">{__("verification_notes")}:</label>
    <div class="controls">
        <textarea id="verification_notes" name="bank_detail[verification_notes]" cols="55" rows="4" class="input-large">{$bank_detail.verification_notes}</textarea>
    </div>
</div>

{capture name="buttons"}
    {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_bank_details.verify]" but_text=__("save")}
{/capture}

</form>

{/capture}

{include file="common/mainbox.tpl"
    title=__("verify_bank_details")
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
} 