<script>
{literal}
var running = false;
var totalToSend = 0;
var totalSent = 0;

jQuery(document).ready(function(){
	jQuery('#all').change(function() {
		var checkboxes = jQuery('input:checkbox');
		if(jQuery(this).is(':checked')) {
			checkboxes.prop('checked', true);
		} else {
			checkboxes.prop('checked', false);
		}
	});
})

function loadAddBonusBox()
{
	var url = "?action=admin_manage_bonus_popup";
	jQuery("#dialog").load(url).dialog({ width: 550 });
}

function submitAddBonusForm(amount, email_subject_text, email_body_text, sms_body_text)
{
	/**************************
	* iPokz
	***************************/
	totalUsers = jQuery("input[name^=username]:checked").length;
	
	if(totalUsers != 0){
		var username = [];
		jQuery("input[name^=username]:checked").each(function(){
			username.push(jQuery(this).val());
		});
		jQuery('#all').prop('checked', false);
		jQuery("input[name^=username]:checked").prop('checked', false);
		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: "",
			data: { 
				'version': 2,
				'coins': amount,
				'email_subject': email_subject_text,
				'sms_body': sms_body_text,
				'email_body': email_body_text,
				'send_via_sms': 1,
				'username[]': username
			},
			success: (function(data){
				alert(data.resulttext);
			})
		});
		
	} else {
		alert("Please select user(s) before add Bonus");
	}
	showAddBonusButton();
	
	
	/************************************************************************************/
	/*if(running == false)
	{
		jQuery('#send_result').val("");
		running = true;
		totalToSend = jQuery('*[name=\"username[]\"]:checked').length;;
		totalSent = 0;
	}

	jQuery('#coins').val(amount);
	jQuery('#email_subject').val(email_subject_text);
	jQuery('#email_body').val(email_body_text);
	jQuery('#sms_body').val(sms_body_text);

	var username = jQuery('*[name=\"username[]\"]:checked:first');
	if(typeof username.val() !== "undefined")
	{
		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: "",
			data: "coins="+jQuery('#coins').val()+"&email_subject="+jQuery('#email_subject').val()+"&email_body="+jQuery('#email_body').val()+"&sms_body="+jQuery('#sms_body').val()+"&send_via_sms=1&username="+username.val(),
			success: (function(data){
				if(data.result==0)
				{
					alert(data.resulttext);
				}
				else
				{
					//jQuery("#dialog").dialog('close');
					totalSent++;
					jQuery("#send_result").val(jQuery("#send_result").val()+"["+totalSent+"/"+totalToSend+"] "+data.resulttext+"\n");
					var obj = jQuery('#send_result');
					obj.scrollTop(obj[0].scrollHeight - obj.height());
					username.removeAttr('checked');
					submitAddBonusForm(jQuery('#coins').val(), jQuery('#email_subject').val(), jQuery('#email_body').val(), jQuery('#sms_body').val());
				}
			})
		});
	}
	else
	{
		showAddBonusButton();
		running = false;
	}
	jQuery('#all').removeAttr('checked');*/
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
	<h1 class="admin-title">{#MANAGE_BONUS#}</h1>
	<form action="" id="addBonusForm" method="post"/>
	<input type="hidden" name="coins" id="coins"/>
	<input type="hidden" name="email_subject" id="email_subject"/>
	<input type="hidden" name="email_body" id="email_body"/>
	<input type="hidden" name="send_via_sms" id="send_via_sms" value="1"/>
	<input type="hidden" name="sms_body" id="sms_body"/>
	{if (count($userrec)>0)}
	<h1>Top {$limit_number}</h1>
	<div class="result-box-inside">
		<div style="color: red"><a href="#" class="admin-link" onclick="loadAddBonusBox()">Add bonus</a></div>
		<table width="100%"  border="0" cellpadding="10" cellspacing="1">
			<tr bgcolor="#2d2d2d" height="28px">
				<td align="center" width="20" class="text-title"><input type="checkbox" id="all"/></td>
				<td align="center" class="text-title"><a>Username</a></td>			
				<td align="center" width="160" class="text-title"><a>Registered On</a></td>				
				<td align="center" width="120" class="text-title"><a>City</a></td>			
				<td align="center" width="70" class="text-title"><a>Country</a></td>				
				<td align="center" width="90" class="text-title"><a>Spent Coins</a></td>
				<td align="center" width="90" class="text-title"><a>Balance</a></td>				
			</tr>

			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="#ccb691,#fde6be"}">
				<td align="center"><input type="checkbox" name="username[]" value="{$userdata.username}"/></td>
				<td align="center">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="admin-link">{$userdata.username}</a></td>
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
		<div style="color: red"><a href="#" onclick="loadAddBonusBox()" class="admin-link">Add bonus</a></div>
		<table width="100%"  border="0">
			<tr bgcolor="#2d2d2d" height="28px">
				<td align="center" width="20" class="text-title"><input type="checkbox" id="all"/></td>
				<td align="center" class="text-title"><a>Username</a></td>
				<td align="center" class="text-title"><a>Registered On</a></td>
				<td align="center" class="text-title"><a>City</a></td>
				<td align="center" class="text-title"><a>Country</a></td>
				<td align="center" class="text-title"><a>Spent Coins</a></td>
				<td align="center" class="text-title"><a>Balance</a></td>
			</tr>

			{foreach key=key from=$search_result item=userdata}
			<tr  bgcolor="{cycle values="#ccb691,#fde6be"}">
				<td align="center"><input type="checkbox" name="username[]" value="{$userdata.username}"/></td>
				<td align="center">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="admin-link">{$userdata.username}</a></td>
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
	<div class="page">{paginate_prev class="pre-pager"} {paginate_middle class="num-pager"} {paginate_next class="next-pager"}</div>
	{/if}
	</form>
</div>
<div id="dialog" title="Amount"></div>