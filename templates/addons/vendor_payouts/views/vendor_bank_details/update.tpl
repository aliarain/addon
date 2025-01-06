{if $bank_detail}
    {assign var="id" value=$bank_detail.detail_id}
{else}
    {assign var="id" value=0}
{/if}

{capture name="mainbox"}

<form action="{""|fn_url}" method="post" class="form-horizontal form-edit" name="bank_details_form">
<input type="hidden" name="detail_id" value="{$id}" />

{capture name="tabsbox"}
<div id="content_general">
    <fieldset>
        <div class="control-group">
            <label class="control-label cm-required" for="bank_name">{__("bank_name")}:</label>
            <div class="controls">
                <input type="text" name="bank_data[bank_name]" id="bank_name" value="{$bank_detail.bank_name}" size="32" maxlength="255" class="input-large" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label cm-required" for="account_holder">{__("account_holder")}:</label>
            <div class="controls">
                <input type="text" name="bank_data[account_holder]" id="account_holder" value="{$bank_detail.account_holder}" size="32" maxlength="255" class="input-large" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label cm-required" for="account_number">{__("account_number")}:</label>
            <div class="controls">
                <input type="text" name="bank_data[account_number]" id="account_number" value="{$bank_detail.account_number}" size="32" maxlength="255" class="input-large" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label cm-required" for="swift_code">{__("swift_code")}:</label>
            <div class="controls">
                <input type="text" name="bank_data[swift_code]" id="swift_code" value="{$bank_detail.swift_code}" size="32" maxlength="50" class="input-large" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="iban">{__("iban")}:</label>
            <div class="controls">
                <input type="text" name="bank_data[iban]" id="iban" value="{$bank_detail.iban}" size="32" maxlength="50" class="input-large" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label cm-required" for="branch_name">{__("branch_name")}:</label>
            <div class="controls">
                <input type="text" name="bank_data[branch_name]" id="branch_name" value="{$bank_detail.branch_name}" size="32" maxlength="255" class="input-large" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label cm-required" for="bank_address">{__("bank_address")}:</label>
            <div class="controls">
                <textarea name="bank_data[bank_address]" id="bank_address" cols="32" rows="4" class="input-large">{$bank_detail.bank_address}</textarea>
            </div>
        </div>

        {if $bank_detail.status}
        <div class="control-group">
            <label class="control-label">{__("status")}:</label>
            <div class="controls">
                <span class="label label-{if $bank_detail.status == "A"}success{elseif $bank_detail.status == "P"}warning{else}danger{/if}">
                    {if $bank_detail.status == "A"}{__("approved")}
                    {elseif $bank_detail.status == "P"}{__("pending")}
                    {else}{__("rejected")}{/if}
                </span>
                {if $bank_detail.verification_notes}
                    <p class="muted">{$bank_detail.verification_notes}</p>
                {/if}
            </div>
        </div>
        {/if}
    </fieldset>
</div>
{/capture}

{include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab=$smarty.request.selected_section track=true}

<div class="buttons-container">
    {include file="buttons/save_cancel.tpl" but_name="dispatch[vendor_bank_details.update]" but_role="submit-link" but_target_form="bank_details_form" save=$id}
</div>

</form>

{/capture}

{include file="common/mainbox.tpl"
    title=($id) ? $bank_detail.bank_name : __("new_bank_details")
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
}