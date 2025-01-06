{capture name="section"}
<div class="sidebar-row">
    <h6>{__("search")}</h6>
    <form action="{""|fn_url}" name="payout_search_form" method="get">
        <div class="sidebar-field">
            <label for="payout_id">{__("id")}</label>
            <input type="text" name="payout_id" id="payout_id" value="{$search.payout_id}" size="15"/>
        </div>

        <div class="sidebar-field">
            <label for="amount_from">{__("amount")}</label>
            <input type="text" name="amount_from" id="amount_from" value="{$search.amount_from}" size="7" class="input-small" /> - 
            <input type="text" name="amount_to" value="{$search.amount_to}" size="7" class="input-small" />
        </div>

        <div class="sidebar-field">
            <label for="status">{__("status")}</label>
            <select name="status" id="status">
                <option value="">{__("all")}</option>
                <option value="P" {if $search.status == "P"}selected="selected"{/if}>{__("processing")}</option>
                <option value="C" {if $search.status == "C"}selected="selected"{/if}>{__("completed")}</option>
                <option value="F" {if $search.status == "F"}selected="selected"{/if}>{__("failed")}</option>
            </select>
        </div>

        <div class="sidebar-field">
            <label for="time_from">{__("period")}</label>
            {include file="common/calendar.tpl" 
                date_id="time_from" 
                date_name="time_from" 
                date_val=$search.time_from 
                start_year=$settings.Company.company_start_year}
            {include file="common/calendar.tpl" 
                date_id="time_to" 
                date_name="time_to" 
                date_val=$search.time_to 
                start_year=$settings.Company.company_start_year}
        </div>

        {if $auth.user_type == "A"}
        <div class="sidebar-field">
            <label for="vendor">{__("vendor")}</label>
            {include file="views/companies/components/picker/picker.tpl"
                input_name="vendor_id"
                item_ids=[$search.vendor_id]
                show_advanced=false
                type="selection"
                close_on_select=true
                view_mode="single_button"
            }
        </div>
        {/if}

        <div class="sidebar-field">
            <input class="btn" type="submit" name="dispatch[vendor_payouts.manage]" value="{__("search")}">
        </div>
    </form>
</div>
{/capture}

{include file="common/section.tpl" section_content=$smarty.capture.section}