<script>
{literal}
jQuery(document).ready(function(){
	jQuery('#all:checkbox').change(function(){
		if(jQuery(this).attr("checked"))
			jQuery('input:checkbox').attr('checked','checked');
		else
			jQuery('input:checkbox').removeAttr('checked');
	})
})

function loadAddBonusBox()
{
	var url = "?action=admin_manage_bonus_popup";
	jQuery("#dialog").load(url).dialog({ width: 550 });
}

function submitAddBonusForm(amount, email_subject_text, email_body_text, sms_body_text)
{
	jQuery('#coins').val(amount);
	jQuery('#email_subject').val(email_subject_text);
	jQuery('#email_body').val(email_body_text);
	jQuery('#sms_body').val(sms_body_text);
	jQuery.ajax({ type: "POST", url: "", data: jQuery("#addBonusForm").serialize(), success:(function(result){if(result){alert(result);} if(result=="Finished.") jQuery('#dialog').dialog('close'); else showAddBonusButton();}) });
}

function hideAddBonusButton()
{
	jQuery("#add_bonus_button").hide();
	jQuery("#add_bonus_info").text("Please wait...");
}

function showAddBonusButton()
{
	jQuery("#add_bonus_button").show();
	jQuery("#add_bonus_info").text("");
}
{/literal}
</script>

<div class="result-box">
	<h1>{#MANAGE_BONUS#}</h1>
	<form action="" id="addBonusForm" method="post"/>
	<input type="hidden" name="coins" id="coins"/>
	<input type="hidden" name="email_subject" id="email_subject"/>
	<input type="hidden" name="email_body" id="email_body"/>
	<input type="hidden" name="send_via_sms" id="send_via_sms" value="1"/>
	<input type="hidden" name="sms_body" id="sms_body"/>
	{if (count($userrec)>0)}
	<h1>Top {$limit_number}</h1>
	<div class="result-box-inside-nobg">
		<div style="color: red"><a href="#" style="color: white" onclick="loadAddBonusBox()">Add bonus</a></div>
		<table width="100%"  border="0">
			<tr bgcolor="#b6b6b6" height="28px">
				<td align="center" width="20" class="text-title"><input type="checkbox" id="all"/></td>
				<td align="center" class="text-title">Username</td>			
				<td align="center" width="160" class="text-title">Registered On</td>				
				<td align="center" width="120" class="text-title">City</td>			
				<td align="center" width="70" class="text-title">Country</td>				
				<td align="center" width="90" class="text-title">Spent Coins</td>
				<td align="center" width="90" class="text-title">Balance</td>				
			</tr>

			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="#663333,#996666"}">
				<td align="center"><input type="checkbox" name="username[]" value="{$userdata.username}"/></td>
				<td align="center">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="link">{$userdata.username}</a></td>
				<td align="center">{$userdata.registred}</td>
				<td align="center">{$userdata.city}</td>
				<td width="20px"  align="center">
					{if $userdata.country eq "Germany"}
						DE
					{elseif $userdata.country eq "Switzerland"}
						CH
					{elseif $userdata.country eq "Austria"}
						AT
					{elseif $userdata.country eq "United Kingdom"}
						UK
					{elseif $userdata.country eq "Belgium"}
						BE
					{/if}
				</td>
				<td align="center">{$userdata.spent_coin|number_format:0:".":","}</td>	
				<td align="center">{$userdata.remain_coin|number_format:0:".":","}</td>
			</tr>
			{/foreach}
		</table>
	</div>
	{/if}

	{if (count($search_result)>0)}
	<h1>Search result</h1>
	<div class="result-box-inside-nobg">
		<div style="color: red"><a href="#" style="color: white" onclick="loadAddBonusBox()">Add bonus</a></div>
		<table width="100%"  border="0">
			<tr bgcolor="#b6b6b6" height="28px">
				<td align="center" width="20" class="text-title"><input type="checkbox" id="all"/></td>
				<td align="center" class="text-title">Username</td>
				<td align="center" class="text-title">Registered On</td>
				<td align="center" class="text-title">City</td>
				<td align="center" class="text-title">Country</td>
				<td align="center" class="text-title">Spent Coins</td>
				<td align="center" class="text-title">Balance</td>
			</tr>

			{foreach key=key from=$search_result item=userdata}
			<tr  bgcolor="{cycle values="#663333,#996666"}">
				<td align="center"><input type="checkbox" name="username[]" value="{$userdata.username}"/></td>
				<td align="center">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="link">{$userdata.username}</a></td>
				<td align="center">{$userdata.registred}</td>
				<td align="center">{$userdata.city}</td>
				<td width="20px"  align="center">
					{if $userdata.country eq Germany}
					DE
					{elseif $userdata.country eq Switzerland}
					CH
					{elseif $userdata.country eq Austria}
					AT
					{elseif $userdata.country eq "United Kingdom"}
					UK
					{elseif $userdata.country eq "Belgium"}
					BE
					{/if}
				</td>
				<td align="center">{$userdata.spent_coin|number_format:0:".":","}</td>	
				<td align="center">{$userdata.remain_coin|number_format:0:".":","}</td>
			</tr>
			{/foreach}
		</table>
	</div>
	<div class="page" style="float: left; width: auto; padding-left: 20px;">
		<select name="per_page" onchange="document.cookie='users_per_page='+jQuery(this).val(); location.reload();">
			<option value="20"{if $smarty.cookies.users_per_page eq 20} selected="selected"{/if}>20</option>
			<option value="50"{if $smarty.cookies.users_per_page eq 50} selected="selected"{/if}>50</option>
			<option value="100"{if $smarty.cookies.users_per_page eq 100} selected="selected"{/if}>100</option>
			<option value="200"{if $smarty.cookies.users_per_page eq 200} selected="selected"{/if}>200</option>
			<option value="500"{if $smarty.cookies.users_per_page eq 500} selected="selected"{/if}>500</option>
			<option value="1000"{if $smarty.cookies.users_per_page eq 1000} selected="selected"{/if}>1000</option>
			<option value="2000"{if $smarty.cookies.users_per_page eq 2000} selected="selected"{/if}>2000</option>
		</select>
	</div>
	<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
	{/if}
	</form>
</div>
<div id="dialog" title="Amount"></div>