<!-- {$smarty.template} -->
{literal}
<script language="javascript" type="text/javascript">
window.onload = function(){
	getNumDate('date', $('month').options[$('month').selectedIndex].value, $('year').options[$('year').selectedIndex].value);
};
</script>
{/literal}
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="26"  background="images/bg_sex.jpg">
		<div align="center">
		<table width="156"  border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="156" height="26" align="center" background="images/bg_center.gif" class="text12black">{#Register#}<br />{#Test_Membership#}</td>
			</tr>
		</table>
		</div>
		</td>
	</tr>
</table>
<table align="center" border="0" cellpadding="5" cellspacing="0" width="90%">
	<form id="register_test_form" name="register_test_form" method="post" action=""> 
	<tr>
		<td align="center" colspan="2" height="30px" valign="middle"><font color="#FF0000"><b>{$text}</b></font></td>
	</tr>
	<tr>
	  <td align="left">{#USERNAME#}:</td>
		<td align="left">
		<input id="username" name="username" type="text" value="{$save.username}" style="width:150px" maxlength="9" />
		<a id="usernametip" href="javascript:void(0)" title="Please input 6-9 characters." class="check">?</a> <a href="javascript: void(0)" onclick="checkUsername('username')" class="check">{#Check_username#}</a> </td>
	</tr>
	<tr>
	  <td align="left">{#Email#}:</td>
		<td align="left">
		<input id="email" name="email" type="text" value="{$save.email}" style="width:150px" />
		<a href="javascript: void(0)" onclick="if(checkNull($('email').value) && checkFormEmail($('email').value))ajaxRequest('isEmail', 'email='+$('email').value, '', 'isUsername', 'reportError')" class="check">{#Check_mail#}</a> </td>
	</tr>
	<tr>
	  <td align="left">{#Gender#}:</td>
		<td align="left">{html_radios id="gender" name="gender" options=$gender selected=$save.gender}</td>
	</tr>
	<tr>
	  <td align="left" >{#Birthday#}:</td>
		<td align="left"> {html_options id="date" name="date" options=$date selected=$save.date} {html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month} {html_options id="year" name="year" options=$year onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year} </td>
	</tr>
	<tr>
		<td>{#Civil_status#}:</td>
		<td>{html_options id="civilstatus" name="civilstatus" options=$status selected=$save.civilstatus style="width:155px"}</td>
	</tr>
	<tr>
	  <td>{#Min#} {#Age#}:</td>
		<td>{html_options id="minage" name="minage" options=$age onchange="ageRange('minage', 'maxage')" selected=$save.minage}</td>
	</tr>
	<tr>
	  <td>{#Max#} {#Age#}:</td>
		<td>{html_options id="maxage" name="maxage" options=$age selected=$save.maxage}</td>
	</tr>
	<tr>
	  <td>{#Relationship#}:</td>
		<td>{html_radios id="relationship" name="relationship" options=$yesno selected=$save.relationship}</td>
	</tr>
	<tr>
	  <td>{#One_Night_Stand#}:</td>
		<td>{html_radios id="onenightstand" name="onenightstand" options=$yesno selected=$save.onenightstand}</td>
	</tr>
	<tr>
	  <td>{#Affair#}:</td>
		<td>{html_radios id=""affair name="affair" options=$yesno selected=$save.affair}</td>
	</tr>
	<tr>
	  <td>{#Friendship_and_more#}:</td>
		<td>{html_radios id="friendship" name="friendship" options=$yesno selected=$save.friendship}</td>
	</tr>
	<tr>
		<td align="left" colspan="2"><b>{#Yourre#} {#looking_for#}</b></td>
	</tr>
	<tr>
	  <td>{#Men#}:</td>
		<td>{html_radios id="lookmen" name="lookmen" options=$yesno selected=$save.lookmen}</td>
	</tr>
	<tr>
	  <td>{#Women#}:</td>
		<td>{html_radios id="lookwomen" name="lookwomen" options=$yesno selected=$save.lookwomen}</td>
	</tr>
	<tr>
	  <td>{#Pairs#}:</td>
		<td>{html_radios id="lookpairs" name="lookpairs" options=$yesno selected=$save.lookpairs}</td>
	</tr>
	<tr>
		<td align="left" colspan="2" height="20px"></td>
	</tr>
	<tr>
		<td align="center" colspan="2" height="5px">
		<input name="submit" type="submit" onClick="return checkNullMemberTest()" value="{#SUBMIT#}" />		  
		<input name="button" type="button" onclick="parent.location=''" value="{#CANCEL#}" /></td>
	</tr>
	<tr>
		<td colspan="2" height="20px"></td>
	</tr>
	</form>
</table>
