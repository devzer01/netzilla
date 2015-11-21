{literal}
<script language="javascript" type="text/javascript">
window.onload = function(){
	ajaxRequest('loadOptionCountry', '', '', 'loadOptionCountry', 'reportError');
};
</script>
{/literal}
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="26"  background="images/bg_sex.jpg">
		<div align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
					<td align="center" background="images/bgcenter.gif" class="text14whitebold" ><img src="images/xx.gif" />
					::{#Upgrade_to_Full_Membership#}::</td>
					<td background="images/bgr.gif" width="12" height="24"></td>
				 </tr>
		</table>
		</div>
		</td>
	</tr>
</table>
<table align="center" border="0" cellspacing="0" cellpadding="0" width="90%">
	<tr>
		<td height="20px"></td>
	</tr>
	<tr>
		<td>
		<table align="center" border="0" cellspacing="5" cellpadding="0" width="100%">
			<form id="upgrade_form" name="upgrade_form" method="post" action="">
			<tr>
				<td align="left" width="">{#USERNAME#}:</td>
				<td align="left">{$smarty.session.sess_username}</td>
			</tr>
			<tr>
				<td align="left">{#Country#}:</td>
				<td align="left"><select id="country" name="country" style="width:155px" onchange="loadOptionState(this.options[this.selectedIndex].value, '');loadOptionCity(0, '')"></select></td>
			</tr>
			<tr>
			  	<td align="left">{#State#}:</td>
				<td align="left"><select id="state" name="state" style="width:155px" onchange="loadOptionCity(this.options[this.selectedIndex].value, '')"></select></td>
			</tr>
			<tr>
			  <td align="left">{#City#}:</td>
				<td align="left"><select id="city" name="city" style="width:155px"></select></td>
			</tr>
			<tr>
			  <td align="left">{#Area#}:</td>
				<td align="left"><input id="area" name="area" type="text" style="width:150px" /></td>
			</tr>
			<tr>
			  <td>{#Height#} ({#Cm#}) :</td>
				<td><input id="height" name="height" type="text" style="width:150px" /></td>
			</tr>
			<tr>
			  <td>{#Weight#} ({#Kg#}) :</td>
				<td><input id="weight" name="weight" type="text" style="width:150px" /></td>
			</tr>
			<tr>
			  <td>{#Appearance#}:</td>
				<td>{html_options id="appearance" name="appearance" options=$appearance style="width:155px"}</td>
			</tr>
			<tr>
			  <td>{#Eyes_Color#}:</td>
				<td>{html_options id="eyescolor" name="eyescolor" options=$eyescolor style="width:155px"}</td>
			</tr>
			<tr>
			  <td>{#Hair_Color#}:</td>
				<td>{html_options id="haircolor" name="haircolor" options=$haircolor style="width:155px"}</td>
			</tr>
			<tr>
			  <td>{#Hair_Length#}:</td>
				<td>{html_options id="hairlength" name="hairlength" options=$hairlength style="width:155px"}</td>
			</tr>
			<tr>
			  <td>{#Beard#}:</td>
				<td>{html_options id="beard" name="beard" options=$beard style="width:155px"}</td>
			</tr>
			<tr>
			  <td>{#Zodiac#}:</td>
				<td>{html_options id="zodiac" name="zodiac" options=$zodiac style="width:155px"}</td>
			</tr>
			<tr>
			  <td>{#Sexuality#}:</td>
				<td>{html_options id="sexuality" name="sexuality" options=$sexuality style="width:155px"}</td>
			</tr>
			<tr>
			  <td>{#Tattos#}:</td>
				<td>{html_radios id="tattos" name="tattos" options=$yesno}</td>
			</tr>
			<tr>
			  <td>{#Smoking#}:</td>
				<td>{html_radios id="smoking" name="smoking" options=$yesno}</td>
			</tr>
			<tr>
			  <td>{#Glasses#}:</td>
				<td>{html_radios id="glasses" name="glasses" options=$yesno}</td>
			</tr>
			<!--<tr>
			  <td>{#Handicapped#}:</td>
				<td>{html_radios id="handicapped" name="handicapped" options=$yesno}</td>
			</tr>-->
			<tr>
			  <td>{#Piercings#}:</td>
				<td>{html_radios id="piercings" name="piercings" options=$yesno}</td>
			</tr>
			<tr>
				<td align="left" colspan="2"><b>{#Prefenrence#}</b></td>
			</tr>
			<tr>
			  <td>{#Cybersex#}:</td>
				<td>{html_radios id="cybersex" name="cybersex" options=$yesno}</td>
			</tr>
			<tr>
			  <td>{#Picture_Swapping#}:</td>
				<td>{html_radios id="picture_swapping" name="picture_swapping" options=$yesno}</td>
			</tr>
			<tr>
			  <td>{#Live_dating#}:</td>
				<td>{html_radios id="live_dating" name="live_dating" options=$yesno}</td>
			</tr>
			<tr>
			  <td>{#Role_playing#}:</td>
				<td>{html_radios id="role_playing" name="role_playing" options=$yesno}</td>
			</tr>
			<tr>
			  <td>{#sm#}:</td>
				<td>{html_radios id="s_m" name="s_m" options=$yesno}</td>
			</tr>
			<tr>
			  <td>{#Partner_exchange#}:</td>
				<td>{html_radios id="partner_exchange" name="partner_exchange" options=$yesno}</td>
			</tr>
			<tr>
			  <td>{#Voyeurism#}:</td>
				<td>{html_radios id="voyeurism" name="voyeurism" options=$yesno}</td>
			</tr>
			<tr>
				<td>{#Your#} {#Description#}</td>
				<td><textarea id="description" name="description"></textarea></td>
			</tr>
			<tr>
				<td colspan="2" height="20px">
			</tr>
			<tr>
				<td align="center" colspan="2"><input name="submit" type="submit" value="{#SUBMIT#}"onclick="return callNullUpgrade()"> 
				<input name="back" type="button" onclick="parent.location='{$smarty.server.HTTP_REFERER}';" value="{#BACK#}"></td>
			</tr>
			</form>
		</table>
		</td>
	</tr>
	<tr>
		<td height="20px"></td>
	</tr>
</table>