{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="bank_details_form" class="form-horizontal form-edit" enctype="multipart/form-data">
<input type="hidden" name="bank_detail_id" value="{$bank_detail.bank_detail_id}" />

{include file="common/subheader.tpl" title=__("bank_account_details")}

<div class="control-group">
    <h4 class="subheader">{__("required_bank_information")}</h4>
</div>

{* Rest of your existing form fields *}

{capture name="buttons"}
    {include file="buttons/save_cancel.tpl" 
        but_name="dispatch[vendor_payouts.update_bank_details]" 
        but_role="submit-link" 
        but_target_form="bank_details_form" 
        but_text=__("save_bank_details")
        save=true}
{/capture}

</form>

{/capture}

{include file="common/mainbox.tpl"
    title=__("bank_account_setup")
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
} 