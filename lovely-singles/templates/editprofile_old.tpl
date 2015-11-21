<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="26" background="images/bg_sex.jpg">
		<table align="center" width="156" border="0" cellspacing="0" cellpadding="0">
			<tr>
            <td width="156" height="26" align="center" background="images/bg_center.gif" class="text12black">{#Edit_Profile#}</td>
          </tr>
		</table>
		</td>
	</tr>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="80%">
	<tr>
		<td height="20px"></td>
	</tr>
	<tr>
		<td>
		{literal}
		<script language="javascript" type="text/javascript">
			window.onload = function()
			{
				country_select = '{/literal}{$save.country}{literal}';
				state_select = '{/literal}{$save.state}{literal}';
				city_select = '{/literal}{$save.city}{literal}';
				ajaxRequest('loadOptionCountry', '', '', 'loadOptionCountry1', 'reportError');
				getNumDate('date', $('month').options[$('month').selectedIndex].value, $('year').options[$('year').selectedIndex].value);
				InitFlashObj();
			};
		</script>
		{/literal}
		
		<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<form id="editProfile" name="editProfile" method="post" action="">
			<tr>
				<td align="left" colspan="2" height="5px"></td>
			</tr>
			<tr>
				<td align="left">{#USERNAME#}:</td>
				<td align="left">{$save.username}</td>
			</tr>
			<tr>
				<td align="left">{#Type#}:</td>
				<td align="left">
				{if $save.type eq 1}
					{#Administrator#}
				{elseif $save.type eq 2}
					{#Membership_gold#}
				{elseif $save.type eq 3}
					{#Membership_silver#}
				{elseif $save.type eq 4}
					{#Membership_bronze#}
				{elseif $save.type eq 5}
					{#Test_Membership#}
				{/if}
				</td>
			</tr>
			<tr>
				<td align="left">{#Email#}:</td>
				<!--<td align="left">{$save.email}</a>-->
				<td align="left"><input id="email" name="email" type="email" value="{$save.email}" style="width:150px" /> <a id="passwordtip2" href="javascript:" title="Die Email muss das Format Name@Provider.Domain haben" class="check">?</a></td>
				</td>
			</tr>
			<tr>
				<td align="left">{#Confirm#}-{#Email#}:</td>
				<!--<td align="left">{$save.email}</a>-->
				<td align="left"><input id="confirm_email" name="confirm_email" type="email" value="{$save.email}" style="width:150px" /> <a id="passwordtip2" href="javascript:" title="Die Eingabe muss identisch zu deiner Emailadresse sein!" class="check">?</a></td>
				</td>
			</tr>			
			<tr>
				<td align="left" >{#PASSWORD#}:</td>
				<td align="left"><input id="password" name="password" type="password" value="{$save.password}" style="width:150px" /> <a id="passwordtip" href="javascript:" title="Dein Password muss mindestens 6 Zeichen lang sein!" class="check">?</a></td>
			</tr>
			<tr>
				<td align="left">{#Confirm#}-{#PASSWORD#}:</td>
				<td align="left"><input id="confirm_password" name="confirm_password" type="password" value="{$save.password}" style="width:150px" /> <a id="passwordtip2" href="javascript:" title="Die Eingabe muss identisch zu deinem Passwort sein!" class="check">?</a></td>
			</tr>			
			<tr>
				<td align="left">{#Gender#}:</td>
				<td align="left">{html_radios id="gender" name="gender" options=$gender selected=$save.gender}</td>
			</tr>
			<tr>
				<td align="left" >{#Birthday#}:</td>
				<td align="left">
				{html_options id="date" name="date" options=$date selected=$save.date}
				{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month} 
				{html_options id="year" name="year" options=$year onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year}
				</td>
			</tr>
			<tr>
				<td align="left">{#Country#}:</td>
				<td align="left">{html_options id="country" name="country" options=$country selected=$save.country style="width:155px" onchange="loadOptionState(this.options[this.selectedIndex].value, '');loadOptionCity(0, '')"}</td>
			</tr>
			<tr>
				<td align="left">{#State#}:</td>
				<td align="left">{html_options id="state" name="state" options=$state selected=$save.state style="width:155px" onchange="loadOptionCity(this.options[this.selectedIndex].value, '')"}</td>
			</tr>
			<tr>
				<td align="left">{#City#}:</td>
				<td align="left">{html_options id="city" name="city" options=$city selected=$save.city style="width:155px"}</td>
			</tr>
			<tr>
				<td align="left">{#Area#}:</td>
				<td align="left"><input id="area" name="area" type="text" value="{$save.area}" style="width:150px" /></td>
			</tr>
			<tr>
				<td>{#Height#} ({#cm#}):</td>
				<td><input id="height" name="height" type="text" value="{$save.height}" style="width:150px" /></td>
			</tr>
			<tr>
				<td>{#Weight#} ({#kg#}):</td>
				<td><input id="weight" name="weight" type="text" value="{$save.weight}" style="width:150px" /></td>
			</tr>
			<tr>
				<td>{#Appearance#}:</td>
				<td>{html_options id="appearance" name="appearance" options=$appearance selected=$save.appearance style="width:155px"}</td>
			</tr>
			<tr>
				<td>{#Eyes_Color#}:</td>
				<td>{html_options id="eyescolor" name="eyescolor" options=$eyescolor selected=$save.eyescolor style="width:155px"}</td>
			</tr>
			<tr>
				<td>{#Hair_Color#}:</td>
				<td>{html_options id="haircolor" name="haircolor" options=$haircolor selected=$save.haircolor style="width:155px"}</td>
			</tr>
			<tr>
				<td>{#Hair_Length#}:</td>
				<td>{html_options id="hairlength" name="hairlength" options=$hairlength selected=$save.hairlength style="width:155px"}</td>
			</tr>
			<tr>
				<td>{#Beard#}:</td>
				<td>{html_options id="beard" name="beard" options=$beard selected=$save.beard style="width:155px"}</td>
			</tr>
			<tr>
				<td>{#Zodiac#}:</td>
				<td>{html_options id="zodiac" name="zodiac" options=$zodiac selected=$save.zodiac style="width:155px"}</td>
			</tr>
			<tr>
				<td>{#Civil_status#}:</td>
				<td>{html_options id="civilstatus" name="civilstatus" options=$status selected=$save.civilstatus style="width:155px"}</td>
			</tr>
			<tr>
				<td>{#Sexuality#}:</td>
				<td>{html_options id="sexuality" name="sexuality" options=$sexuality selected=$save.sexuality style="width:155px"}</td>
			</tr>
			<tr>
				<td>{#Tattos#}:</td>
				<td>{html_radios id="tattos" name="tattos" options=$yesno selected=$save.tattos}</td>
			</tr>
			<tr>
				<td>{#Smoking#}:</td>
				<td>{html_radios id="smoking" name="smoking" options=$yesno selected=$save.smoking}</td>
			</tr>
			<tr>
				<td>{#Glasses#}:</td>
				<td>{html_radios id="glasses" name="glasses" options=$yesno selected=$save.glasses}</td>
			</tr>
			<!--<tr>
				<td>{#Handicapped#}:</td>
				<td>{html_radios id="handicapped" name="handicapped" options=$yesno selected=$save.handicapped}</td>
			</tr>-->
			<tr>
				<td>{#Piercings#}:</td>
				<td>{html_radios id="piercings" name="piercings" options=$yesno selected=$save.piercings}</td>
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
				<td align="left" colspan="2"><b>{#Prefenrence#}</b></td>
			</tr>
			<tr>
				<td>{#Cybersex#}:</td>
				<td>{html_radios id="cybersex" name="cybersex" options=$yesno selected=$save.cybersex}</td>
			</tr>
			<tr>
				<td>{#Picture_Swapping#}:</td>
				<td>{html_radios id="picture_swapping" name="picture_swapping" options=$yesno selected=$save.picture_swapping}</td>
			</tr>
			<tr>
				<td>{#Live_dating#}:</td>
				<td>{html_radios id="live_dating" name="live_dating" options=$yesno selected=$save.live_dating}</td>
			</tr>
			<tr>
				<td>{#Role_playing#}:</td>
				<td>{html_radios id="role_playing" name="role_playing" options=$yesno selected=$save.role_playing}</td>
			</tr>
			<tr>
				<td>{#sm#}:</td>
				<td>{html_radios id="s_m" name="s_m" options=$yesno selected=$save.s_m}</td>
			</tr>
			<tr>
				<td>{#Partner_exchange#}:</td>
				<td>{html_radios id="partner_exchange" name="partner_exchange" options=$yesno selected=$save.partner_exchange}</td>
			</tr>
			<tr>
				<td>{#Voyeurism#}:</td>
				<td>{html_radios id="voyeurism" name="voyeurism" options=$yesno selected=$save.voyeurism}</td>
			</tr>
			<tr>
				<td>{#Your#} {#Description#}:</td>
				<td><textarea id="description" name="description" columns="20" rows="12" style="width:350px">{$save.description}</textarea></td>
			</tr>
			<tr>
				<td colspan="2" height="20px">
			</tr>
			<tr>
				<td align="center" colspan="2"><input name="submit" type="image" src="images/senden_bt.gif" value="{#SUBMIT#}" onclick="return callNullEditprofile();"> 
				<input name="back" type="image" src="images/zurueck_bt.gif" onclick="parent.location='{$smarty.server.HTTP_REFERER}';" value="{#BACK#}"></td>
			</tr>
			</form>
		</table>
		</td>
	</tr>
	<tr>
		<td height="20px"></td>
	</tr>
	<tr>
		<td>
		<fieldset>
		<legend>Lade dein Profilbild hoch</legend>
		<table border="0" cellpadding="4" cellspacing="4" width="100%">
			<tr>
				<td valign="top" width="35%" align="right">Foto auswählen:</td>
				<td>
				<div align="left" id="FlashUpload" style="height:1px">
				<OBJECT id="FlashFilesUpload" codeBase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="450px" height="10px" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" VIEWASTEXT>
					<PARAM NAME="FlashVars" VALUE="uploadUrl=upload.php&useExternalInterface=Yes">
					<PARAM NAME="BGColor" VALUE="#F8F6E6">
					<PARAM NAME="Movie" VALUE="ElementITMultiPowUpload1.6.swf">
					<PARAM NAME="Src" VALUE="ElementITMultiPowUpload1.6.swf">
					<PARAM NAME="WMode" VALUE="Window">
					<PARAM NAME="Play" VALUE="-1">
					<PARAM NAME="Loop" VALUE="-1">
					<PARAM NAME="Quality" VALUE="High">
					<PARAM NAME="SAlign" VALUE="">
					<PARAM NAME="Menu" VALUE="-1">
					<PARAM NAME="Base" VALUE="">
					<PARAM NAME="AllowScriptAccess" VALUE="always">
					<PARAM NAME="Scale" VALUE="ShowAll">
					<PARAM NAME="DeviceFont" VALUE="0">
					<PARAM NAME="EmbedMovie" VALUE="0">	    
					<PARAM NAME="SWRemote" VALUE="">
					<PARAM NAME="MovieData" VALUE="">
					<PARAM NAME="SeamlessTabbing" VALUE="1">
					<PARAM NAME="Profile" VALUE="0">
					<PARAM NAME="ProfileAddress" VALUE="">
					<PARAM NAME="ProfilePort" VALUE="0">
					<embed bgcolor="#F8F6E6" id="EmbedFlashFilesUpload" src="ElementITMultiPowUpload1.6.swf" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"	type="application/x-shockwave-flash" width="450px" height="10px" flashvars="uploadUrl=upload.php&useExternalInterface=Yes">
					</embed>
				  </OBJECT>
				 </div>

				 <div align="left" id="JSUpload" style="visibility: visible;">
				  <select id="fileslist" name="fileslist" style="height: 25px; width: 300px;" multiple></select> <input type="image" src="images/search_bt.gif" value="{#BROWSE_FILES#}" name="flashInfoButton" id="flashInfoButton" onClick="browsefiles();" /> 
				  	
				  	<table width="260px" height="15px" cellpadding="0" cellspacing="0">		
						<tr>
							<td>&nbsp;</td>
						</tr>
					</table>					
				  	
				  	<table style="border:solid 1px #000033;" width="260px" height="15px" cellpadding="0" cellspacing="0">		
						<tr>
							<td>
								<table id="rowProgress" bgcolor="#0033ff" width="1px" height="15px" cellpadding="0" cellspacing="0">
									<tr>
										<td>&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<input type="image" src="images/upload_bt.gif" value="{#UPLOAD#}" name="flashInfoButton" onClick="upload()" />
				</div> 
				</td>
			</tr>			
		</table>
		</fieldset>
		</td>
	</tr>
	<tr>
		<td height="25px"></td>
	</tr>
</table>