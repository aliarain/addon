{capture name="mainbox"}

<div class="row-fluid">
    <div class="span6">
        <div class="well">
            <h3>{__("payout_details")}</h3>
            <dl class="dl-horizontal">
                <dt>{__("payout_amount")}:</dt>
                <dd>{include file="common/price.tpl" value=$payout.payout_amount}</dd>
                
                <dt>{__("status")}:</dt>
                <dd>
                    <span class="label label-{if $payout.status == "P"}info{elseif $payout.status == "C"}success{else}danger{/if}">
                        {if $payout.status == "P"}{__("processing")}
                        {elseif $payout.status == "C"}{__("completed")}
                        {else}{__("failed")}{/if}
                    </span>
                </dd>
                
                <dt>{__("date")}:</dt>
                <dd>{$payout.created_at|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</dd>
                
                {if $payout.completed_at}
                    <dt>{__("completed_at")}:</dt>
                    <dd>{$payout.completed_at|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</dd>
                {/if}
            </dl>
        </div>
    </div>
    
    <div class="span6">
        <div class="well">
            <h3>{__("bank_details")}</h3>
            <dl class="dl-horizontal">
                <dt>{__("bank_name")}:</dt>
                <dd>{$payout.bank_details.bank_name}</dd>
                
                <dt>{__("account_holder")}:</dt>
                <dd>{$payout.bank_details.account_holder}</dd>
                
                <dt>{__("account_number")}:</dt>
                <dd>{$payout.bank_details.account_number}</dd>
                
                <dt>{__("swift_code")}:</dt>
                <dd>{$payout.bank_details.swift_code}</dd>
                
                {if $payout.bank_details.iban}
                    <dt>{__("iban")}:</dt>
                    <dd>{$payout.bank_details.iban}</dd>
                {/if}
                
                <dt>{__("branch_name")}:</dt>
                <dd>{$payout.bank_details.branch_name}</dd>
                
                <dt>{__("bank_address")}:</dt>
                <dd>{$payout.bank_details.bank_address}</dd>
            </dl>
        </div>
    </div>
</div>

{if $payout.notes}
<div class="row-fluid">
    <div class="span12">
        <div class="well">
            <h3>{__("notes")}</h3>
            <p>{$payout.notes}</p>
        </div>
    </div>
</div>
{/if}

{/capture}

{capture name="buttons"}
    {capture name="tools_list"}
        {if $payout.status == "P" && $auth.user_type == "A"}
            <li>{btn type="list" text=__("mark_as_completed") href="vendor_payouts.complete?payout_id=`$payout.payout_id`" class="cm-confirm" method="POST"}</li>
            <li>{btn type="list" text=__("mark_as_failed") href="vendor_payouts.fail?payout_id=`$payout.payout_id`" class="cm-confirm" method="POST"}</li>
        {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_list}
{/capture}

{include file="common/mainbox.tpl"
    title="{__("payout")} #{$payout.payout_id}"
    content=$smarty.capture.mainbox
    buttons=$smarty.capture.buttons
}