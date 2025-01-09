{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="bank_details_form" class="form-horizontal form-edit" enctype="multipart/form-data">
<input type="hidden" name="bank_detail_id" value="{$bank_detail.bank_detail_id}" />

{include file="common/subheader.tpl" title=__("bank_account_details")}

<div class="control-group">
    <label class="control-label cm-required" for="account_holder">{__("account_holder_name")}:</label>
    <div class="controls">
        <input type="text" name="bank_detail[account_holder]" id="account_holder" value="{$bank_detail.account_holder}" class="input-large" />
    </div>
</div>

{* Add other form fields *}

{capture name="buttons"}
    {include file="buttons/save_cancel.tpl" 
        but_name="dispatch[zatesystem_vendor_payout.update_bank_details]"
        but_role="submit-link" 
        but_target_form="bank_details_form" 
        but_text=__("save")
        save=true}
{/capture}

</form>

{/capture}

{include file="common/mainbox.tpl"
    title=__("vendor_bank_details")
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
} 