{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="create_payout_form" class="form-horizontal form-edit">

<div class="control-group">
    <label class="control-label cm-required" for="vendor_id">{__("vendor")}:</label>
    <div class="controls">
        {include file="views/companies/components/picker/picker.tpl"
            input_name="payout_data[vendor_id]"
            item_ids=[$payout_data.vendor_id]
            show_advanced=false
        }
    </div>
</div>

<div class="control-group">
    <label class="control-label cm-required" for="amount">{__("amount")}:</label>
    <div class="controls">
        <input type="text" name="payout_data[amount]" id="amount" value="{$payout_data.amount}" class="input-large" />
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="notes">{__("notes")}:</label>
    <div class="controls">
        <textarea name="payout_data[notes]" id="notes" cols="55" rows="4" class="input-large">{$payout_data.notes}</textarea>
    </div>
</div>

{capture name="buttons"}
    {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_payouts.create]" but_text=__("create")}
{/capture}

</form>

{/capture}

{include file="common/mainbox.tpl"
    title=__("create_payout")
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
} 