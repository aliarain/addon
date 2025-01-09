{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="bank_details_form" class="form-horizontal form-edit" enctype="multipart/form-data">
<input type="hidden" name="bank_detail_id" value="{$bank_detail.bank_detail_id}" />

{include file="common/subheader.tpl" title=__("vendor_bank_details")}

<div class="control-group">
    <h4 class="subheader">{__("required_bank_information")}</h4>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="account_holder">{__("account_holder_name")}:</label>
    <div class="controls">
        <input type="text" name="bank_detail[account_holder]" id="account_holder" value="{$bank_detail.account_holder}" class="input-large" />
        <p class="muted description">{__("account_holder_name_tooltip")}</p>
    </div>
</div>

// ... rest of the form fields ...

{capture name="buttons"}
    {include file="buttons/save_cancel.tpl" 
        but_name="dispatch[vendor_payouts.update]" 
        but_role="submit-link" 
        but_target_form="bank_details_form" 
        but_text=__("save_bank_details")
        save=true}
{/capture}

</form>

{/capture}

{include file="common/mainbox.tpl"
    title=__("vendor_bank_account_setup")
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
} 