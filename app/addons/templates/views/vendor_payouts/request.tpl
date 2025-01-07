{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="payout_request_form" class="form-horizontal">

<div class="control-group">
    <label class="control-label" for="payout_amount">{__("amount")}:</label>
    <div class="controls">
        <input type="text" name="payout_data[amount]" id="payout_amount" value="{$payout_data.amount|default:$minimum_payout}" class="input-large" />
        <p class="muted">{__("minimum_payout_amount")}: {include file="common/price.tpl" value=$minimum_payout}</p>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="bank_details_id">{__("bank_account")}:</label>
    <div class="controls">
        <select name="payout_data[bank_details_id]" id="bank_details_id">
            {foreach from=$bank_details item="bank"}
                <option value="{$bank.detail_id}" {if $bank.is_default == "Y"}selected="selected"{/if}>
                    {$bank.bank_name} - {$bank.account_holder} ({$bank.account_number})
                </option>
            {/foreach}
        </select>
        <p class="muted">
            <a href="{"vendor_bank_details.update"|fn_url}">{__("add_new_bank_account")}</a>
        </p>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="notes">{__("notes")}:</label>
    <div class="controls">
        <textarea name="payout_data[notes]" id="notes" cols="55" rows="4"></textarea>
    </div>
</div>

<div class="buttons-container">
    {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_payouts.request]" but_text=__("request_payout")}
</div>

</form>

{/capture}

{include file="common/mainbox.tpl" 
    title=__("request_payout")
    content=$smarty.capture.mainbox
} 