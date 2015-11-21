{literal}
<script language="javascript" type="text/javascript">
ajaxRequest("loadOptionCountry", "", "", "loadOptionCountry", "reportError");

var Opt = Array();
function clickInOpt(obj){
	Opt[obj.name] = obj.value; 
}
function clickOutOpt(obj){
	Opt[obj.name] = ""; 
	obj.checked=false;
}
function chkOpt(obj){
 if(obj.value==Opt[obj.name]) clickOutOpt(obj); 
 else clickInOpt(obj); 
}
</script>
{/literal}
<div class="result-box">
<h1>{#ADV_SEARCH#}</h1>
<div class="result-box-inside-nobg">

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="20px"></td>
	</tr>
	<form name="adv_search" method="post" action="index.php?action=show_advsearch&search={$smarty.request.search}">
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="4" cellspacing="0" width="300">
							<tr height="22">
								<td align="left">{#Nickname#}:</td>
								<td align="left"><input type="text" id="q_nickname" name="q_nickname" style="width:135px" class="input"/></td>
							</tr>
							<tr height="22">
								<td align="left">{#Min#} {#Age#}:</td>
								<td align="left">
									<select id="minage" name="minage">
										<option value=""> Alter  </option>
										{html_options options=$age onchange="ageRange('minage', 'maxage')"} 
									</select>
								</td>
							</tr>
							<tr height="22">
								<td align="left">{#Country#}:</td>
								<td align="left"><select id="country" name="country" style="width:135px" onchange="loadOptionState('state', this.options[this.selectedIndex].value, '');loadOptionCity('city', 0, '')"></select></td>
							</tr>
							<tr height="22">
								<td align="left">{#State#}:</td>
								<td align="left"><select id="state" name="state" style="width:135px" onchange="loadOptionCity('city', this.options[this.selectedIndex].value, '')"></select></td>
							</tr>
							<tr height="22">
								<td align="left">{#City#}:</td>
								<td align="left"><select id="city" name="city" style="width:135px"></select></td>
							</tr>
							<tr height="22">
								<td align="left">{#Zodiac#}:</td>
								<td align="left">{html_options id="zodiac" name="zodiac" options=$zodiac  style="width:135px"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Civil_status#}:</td>
								<td align="left">{html_options id="civilstatus" name="civilstatus" options=$status  style="width:135px"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Men#}:</td>
								<td align="left">{html_radios id="lookmen" name="lookmen" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Women#}:</td>
								<td align="left">{html_radios id="lookwomen" name="lookwomen" options=$yesno onclick="chkOpt(this)" }</td>
							</tr>
							<tr height="22">
								<td align="left">{#Smoking#}:</td>
								<td align="left">{html_radios id="smoking" name="smoking" options=$yesno  onclick="chkOpt(this)" }</td>
							</tr>
							<tr height="22">
								<td align="left">{#Relationship#}:</td>
								<td align="left">{html_radios id="relationship" name="relationship" options=$yesno onclick="chkOpt(this)" }</td>
							</tr>
							<tr height="22">
								<td align="left">{#One_Night_Stand#}:</td>
								<td align="left">{html_radios id="onenightstand" name="onenightstand" options=$yesno onclick="chkOpt(this)" }</td>
							</tr>
							<tr height="22">
								<td align="left">{#Picture_Swapping#}:</td>
								<td align="left">{html_radios id="picture_swapping" name="picture_swapping" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Live_dating#}:</td>
								<td align="left">{html_radios id="live_dating" name="live_dating" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Role_playing#}:</td>
								<td align="left">{html_radios id="role_playing" name="role_playing" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#sm#}:</td>
								<td align="left">{html_radios id="s_m" name="s_m" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
						</table>
					</td>
				  <td>&nbsp;</td>
					<td align="center" valign="top">
						<table border="0" cellpadding="4" cellspacing="0" width="100%">
							<tr height="22">
								<td align="left">{#Gender#}:</td>
								<td align="left">{html_radios id="gender" name="gender" options=$gender}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Max#} {#Age#}:</td>
								<td align="left">
									<select  id="maxage" name="maxage">
										<option value=""> Alter </option>
										{html_options options=$age }
									</select>
								</td>
							</tr>
							<tr height="22">
								<td align="left">{#Appearance#}:</td>
								<td align="left">{html_options id="appearance" name="appearance" options=$appearance selected=$appearance style="width:135px"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Eyes_Color#}:</td>
								<td align="left">{html_options id="eyescolor" name="eyescolor" options=$eyescolor  style="width:135px"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Hair_Color#}:</td>
								<td align="left">{html_options id="haircolor" name="haircolor" options=$haircolor style="width:135px"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Beard#}:</td>
								<td align="left">{html_options id="beard" name="beard" options=$beard  style="width:135px"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Sexuality#}:</td>
								<td align="left">{html_options id="sexuality" name="sexuality" options=$sexuality  style="width:135px"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Tattos#}:</td>
								<td align="left">{html_radios id="tattos" name="tattos" options=$yesno  onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Pairs#}:</td>
								<td align="left">{html_radios id="lookpairs" name="lookpairs" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Glasses#}:</td>
								<td align="left">{html_radios id="glasses" name="glasses" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Piercings#}:</td>
								<td align="left">{html_radios id="piercings" name="piercings" options=$yesno onclick="chkOpt(this)" }</td>
							</tr>
							<tr height="22">
								<td align="left">{#Affair#}:</td>
								<td align="left">{html_radios id=""affair name="affair" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Friendship_and_more#}:</td>
								<td align="left">{html_radios id="friendship" name="friendship" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Cybersex#}:</td>
								<td align="left">{html_radios id="cybersex" name="cybersex" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Partner_exchange#}:</td>
								<td align="left">{html_radios id="partner_exchange" name="partner_exchange" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
							<tr height="22">
								<td align="left">{#Voyeurism#}:</td>
								<td align="left">{html_radios id="voyeurism" name="voyeurism" options=$yesno onclick="chkOpt(this)"}</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="8"></td></tr>
				<tr>
					<td colspan="3" align="center">					
						<br>
						<input name="submit" type="submit" value="{#SEARCH#}" onclick="return callNullEditprofile()" class="button">
						<br>
						<br>				
					</td>
				</tr>
			</table>
		</td>
	</tr>
	</form>
</table>

</div>
</div>