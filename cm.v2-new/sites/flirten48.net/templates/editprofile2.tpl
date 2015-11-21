<!-- {$smarty.template} -->
{literal}
<script type="text/javascript" language="javascript">
function removePic()
{
	{/literal}
	{if $smarty.session.sess_admin eq '1' and $smarty.get.from eq 'admin'}
	parent.location = '?action=editprofile&type=removepic&user={$smarty.get.user}&proc={$smarty.get.proc}';
	{else}
	parent.location = '?action=editprofile&type=removepic';
	{/if}
	{literal}
}
function updateEdit_datetime()
{
	ajaxRequest('updateEdit_datetime', 'username={/literal}{$save.username}{literal}', '', 'updateLoop', 'reportError');
}

function updateLoop(originalRequest)
{
	if(originalRequest.status == 200)
		setTimeout('updateEdit_datetime()', 30000);
}

updateEdit_datetime();
</script>
{/literal}

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
	<td align="center" background="images/bgcenter.gif" class="text12whitebold" ><img src="images/xx.gif" />:: {#Edit_Profile#} ::</td>
	<td background="images/bgr.gif" width="12" height="24"></td>
</tr>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="80%">
  <tr>
    <td height="20px"></td>
  </tr>
  <tr>
    <td align="left"><table border="0" cellpadding="5" cellspacing="0" width="100%">
      <form id="editProfile" enctype="multipart/form-data" name="editProfile" method="post" action="">
        <tr>
          <td align="left" colspan="3" height="5px"></td>
        </tr>
        <tr>
          <td align="left">{#USERNAME#}:</td>
          <td align="left">{$save.username}</td>
          <td align="center" rowspan="6" width="105px" valign="top"><table align="center" boder="0" cellpadding="0" cellspacing="0" height="120px" width="105px" style="background-color:#000000; boder:solid 1px">
            <tr>
              <td align="center"> {if $save.picturepath neq ''}
                <div id="my_pic"><img border=1 src="thumbs/{$save.picturepath}" height="120px" width="105px" /></div>
                <div align="center"><a href="javascript:if(confirm('Are you sure to remove foto?')) removePic(); " class="linkwhite">{#remove_pic#}</a></div>
                {else}
                <div id="my_pic"><img border=1 src="thumbs/default.jpg" /></div>
                {/if} </td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td align="left">{#Type#}:</td>
          <td align="left"> {if $save.type eq 1}
            {#Administrator#}
            {elseif $save.type eq 2}
            {#Membership_gold#}
            {elseif $save.type eq 3}
            {#Membership_silver#}
            {elseif $save.type eq 4}
            {#Membership_bronze#}
            {elseif $save.type eq 5}
            {#Test_Membership#}
            {/if} </td>
        </tr>
        <tr>
          <td align="left">{#Email#}:</td>
          <td align="left"><input id="email" name="email" type="email" value="{$save.email}" style="width:150px" class="input"/>
              <a id="passwordtip2" href="javascript:" title="Die Email muss das Format Name@Provider.Domain haben" class="check">?</a></td>
        </tr>
      </form>
    </table></td>
  </tr>
  <tr>
    <td align="left">{#Confirm#}-{#Email#}:</td>
    <td align="left"><input id="confirm_email" name="confirm_email" type="email" value="{$save.email}" style="width:150px" class="input"/>
      <a id="passwordtip2" href="javascript:" title="Die Eingabe muss identisch zu deiner Emailadresse sein!" class="check">?</a></td>
  </tr>
  <tr>
    <td align="left" >{#PASSWORD#}:</td>
    <td align="left"><input id="password" name="password" type="password" value="{$save.password}" style="width:150px" class="input"/>
      <a id="passwordtip" href="javascript:" title="Dein Password muss mindestens 6 Zeichen lang sein!" class="check">?</a></td>
  </tr>
  <tr>
    <td align="left">{#Confirm#}-{#PASSWORD#}:</td>
    <td align="left"><input id="confirm_password" name="confirm_password" type="password" value="{$save.password}" style="width:150px" class="input"/>
      <a id="passwordtip2" href="javascript:" title="Die Eingabe muss identisch zu deinem Passwort sein!" class="check">?</a></td>
  </tr>
  <tr>
    <td align="left">{#Gender#}:</td>
    <td align="left">{html_radios id="gender" name="gender" options=$gender selected=$save.gender}</td>
  </tr>
  <tr>
    <td align="left" >{#Birthday#}:</td>
    <td align="left" colspan="2"> {html_options id="date" name="date" options=$date selected=$save.date}
      {html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month} 
      {html_options id="year" name="year" options=$year onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year} </td>
  </tr>
  <tr>
    <td align="left">{#Country#}:</td>
    <td align="left" colspan="2">{html_options id="country" name="country" options=$country selected=$save.country style="width:155px" onchange="loadOptionState('state', this.options[this.selectedIndex].value, '');loadOptionCity('city', 0, '')"}</td>
  </tr>
  <tr>
    <td align="left">{#State#}:</td>
    <td align="left" colspan="2">{html_options id="state" name="state" options=$state selected=$save.state style="width:155px" onchange="loadOptionCity('city', this.options[this.selectedIndex].value, '')"}</td>
  </tr>
  <tr>
    <td align="left">{#City#}:</td>
    <td align="left" colspan="2">{html_options id="city" name="city" options=$city selected=$save.city style="width:155px"}</td>
  </tr>
  {literal}
  <script language="javascript" type="text/javascript">
				country_select = '{/literal}{$save.country}{literal}';
				state_select = '{/literal}{$save.state}{literal}';
				city_select = '{/literal}{$save.city}{literal}';
				fileExtension = '{/literal}{$config_image}{literal}';
				fileDescription = 'Images ({/literal}{$config_image}{literal})';
				ajaxRequest('loadOptionCountry', '', '', 'loadOptionCountry1', 'reportError');
				getNumDate('date', $('month').options[$('month').selectedIndex].value, $('year').options[$('year').selectedIndex].value);
				InitFlashObj();
			</script>
  {/literal}
  <tr>
    <td align="left">{#Area#}:</td>
    <td align="left" colspan="2"><input id="area" name="area" type="text" value="{$save.area}" style="width:150px" class="input"/></td>
  </tr>
  <tr>
    <td>{#Height#}:</td>
    <td colspan="2">{html_options id="height" name="height" options=$height selected=$save.height style="width:155px" onchange="checkNullSelectOption(this)}</td>
  </tr>
  <tr>
    <td>{#Weight#}:</td>
    <td colspan="2">{html_options id="weight" name="weight" options=$weight selected=$save.weight style="width:155px" onchange="checkNullSelectOption(this)}</td>
  </tr>
  <tr>
    <td>{#Appearance#}:</td>
    <td colspan="2">{html_options id="appearance" name="appearance" options=$appearance selected=$save.appearance style="width:155px"}</td>
  </tr>
  <tr>
    <td>{#Eyes_Color#}:</td>
    <td colspan="2">{html_options id="eyescolor" name="eyescolor" options=$eyescolor selected=$save.eyescolor style="width:155px"}</td>
  </tr>
  <tr>
    <td>{#Hair_Color#}:</td>
    <td colspan="2">{html_options id="haircolor" name="haircolor" options=$haircolor selected=$save.haircolor style="width:155px"}</td>
  </tr>
  <tr>
    <td>{#Hair_Length#}:</td>
    <td colspan="2">{html_options id="hairlength" name="hairlength" options=$hairlength selected=$save.hairlength style="width:155px"}</td>
  </tr>
  <tr>
    <td>{#Beard#}:</td>
    <td colspan="2">{html_options id="beard" name="beard" options=$beard selected=$save.beard style="width:155px"}</td>
  </tr>
  <tr>
    <td>{#Zodiac#}:</td>
    <td colspan="2">{html_options id="zodiac" name="zodiac" options=$zodiac selected=$save.zodiac style="width:155px"}</td>
  </tr>
  <tr>
    <td>{#Civil_status#}:</td>
    <td colspan="2">{html_options id="civilstatus" name="civilstatus" options=$status selected=$save.civilstatus style="width:155px"}</td>
  </tr>
  <tr>
    <td>{#Sexuality#}:</td>
    <td colspan="2">{html_options id="sexuality" name="sexuality" options=$sexuality selected=$save.sexuality style="width:155px"}</td>
  </tr>
  <tr>
    <td>{#Tattos#}:</td>
    <td colspan="2">{html_radios id="tattos" name="tattos" options=$yesno selected=$save.tattos}</td>
  </tr>
  <tr>
    <td>{#Smoking#}:</td>
    <td colspan="2">{html_radios id="smoking" name="smoking" options=$yesno selected=$save.smoking}</td>
  </tr>
  <tr>
    <td>{#Glasses#}:</td>
    <td colspan="2">{html_radios id="glasses" name="glasses" options=$yesno selected=$save.glasses}</td>
  </tr>
  <tr>
    <td>{#Handicapped#}:</td>
    <td>{html_radios id="handicapped" name="handicapped" options=$yesno selected=$save.handicapped}</td>
  </tr>
  <tr>
    <td>{#Piercings#}:</td>
    <td colspan="2">{html_radios id="piercings" name="piercings" options=$yesno selected=$save.piercings}</td>
  </tr>
  <tr>
    <td align="left" colspan="3"><b>{#Yourre#} {#looking_for#}</b></td>
  </tr>
  <tr>
    <td>{#Men#}:</td>
    <td colspan="2">{html_radios id="lookmen" name="lookmen" options=$yesno selected=$save.lookmen}</td>
  </tr>
  <tr>
    <td>{#Women#}:</td>
    <td colspan="2">{html_radios id="lookwomen" name="lookwomen" options=$yesno selected=$save.lookwomen}</td>
  </tr>
  <tr>
    <td>{#Pairs#}:</td>
    <td colspan="2">{html_radios id="lookpairs" name="lookpairs" options=$yesno selected=$save.lookpairs}</td>
  </tr>
  <tr>
    <td>{#Min#} {#Age#}:</td>
    <td colspan="2">{html_options id="minage" name="minage" options=$age onchange="ageRange('minage', 'maxage')" selected=$save.minage}</td>
  </tr>
  <tr>
    <td>{#Max#} {#Age#}:</td>
    <td colspan="2">{html_options id="maxage" name="maxage" options=$age selected=$save.maxage}</td>
  </tr>
  <tr>
    <td>{#Relationship#}:</td>
    <td colspan="2">{html_radios id="relationship" name="relationship" options=$yesno selected=$save.relationship}</td>
  </tr>
  <tr>
    <td>{#One_Night_Stand#}:</td>
    <td colspan="2">{html_radios id="onenightstand" name="onenightstand" options=$yesno selected=$save.onenightstand}</td>
  </tr>
  <tr>
    <td>{#Affair#}:</td>
    <td colspan="2">{html_radios id=""affair name="affair" options=$yesno selected=$save.affair}</td>
  </tr>
  <tr>
    <td>{#Friendship_and_more#}:</td>
    <td colspan="2">{html_radios id="friendship" name="friendship" options=$yesno selected=$save.friendship}</td>
  </tr>
  <tr>
    <td align="left" colspan="3"><b>{#Prefenrence#}</b></td>
  </tr>
  <tr>
    <td>{#Cybersex#}:</td>
    <td colspan="2">{html_radios id="cybersex" name="cybersex" options=$yesno selected=$save.cybersex}</td>
  </tr>
  <tr>
    <td>{#Picture_Swapping#}:</td>
    <td colspan="2">{html_radios id="picture_swapping" name="picture_swapping" options=$yesno selected=$save.picture_swapping}</td>
  </tr>
  <tr>
    <td>{#Live_dating#}:</td>
    <td colspan="2">{html_radios id="live_dating" name="live_dating" options=$yesno selected=$save.live_dating}</td>
  </tr>
  <tr>
    <td>{#Role_playing#}:</td>
    <td colspan="2">{html_radios id="role_playing" name="role_playing" options=$yesno selected=$save.role_playing}</td>
  </tr>
  <tr>
    <td>{#sm#}:</td>
    <td colspan="2">{html_radios id="s_m" name="s_m" options=$yesno selected=$save.s_m}</td>
  </tr>
  <tr>
    <td>{#Partner_exchange#}:</td>
    <td colspan="2">{html_radios id="partner_exchange" name="partner_exchange" options=$yesno selected=$save.partner_exchange}</td>
  </tr>
  <tr>
    <td>{#Voyeurism#}:</td>
    <td colspan="2">{html_radios id="voyeurism" name="voyeurism" options=$yesno selected=$save.voyeurism}</td>
  </tr>
  <tr>
    <td>{#Your#} {#Description#}:</td>
    <td colspan="2"><textarea id="description" name="description" columns="20" rows="12" style="width:350px" class="textarea">{$save.description}</textarea></td>
  </tr>
  {if $smarty.session.sess_admin eq '1' and $smarty.get.from eq 'admin'}
  <tr>
    <td>Foto auswählen:</td>
    <td colspan="2"><input type="text" id="picturepath" name="picturepath" value="{$save.picturepath}" style="background-color:#db9ced;" readonly />
        <input name="button" type="button" onclick="window.open('?action=image_dir', 'popup', 'location=0,status=1,scrollbars=1,width=500,height=500,resizable=1')" value="Browse" />
    </td>
  </tr>
  {else}
  <tr>
    <td>Foto auswählen:</td>
    <td colspan="2"><input name="upload_pic" type="hidden" value="yes" />
        <input name="picturepath" type="file" id="picturepath" style="width:350px; background-color:#db9ced;"/>
    </td>
  </tr>
  {/if}
  <tr>
    <td colspan="2" height="20px"></td>
  </tr>
  <tr>
    <td></td>
    <td align="left" colspan="2"><table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left" colspan="3"><input name="submit" type="image" src="images/{$smarty.session.lang}/senden_bt.gif" onclick="return callNullEditprofile()" />
            <a href="{if ($smarty.session.sess_admin eq '1') and ($smarty.get.from eq 'admin')}{$smarty.session.SmartyPaginate.default.url}{else}index.php{/if}"><img name="back" src="images/{$smarty.session.lang}/zurueck_bt.gif" border="0" /></a> </td>
      </tr>
    </table></td>
  </tr>
</table>
<script>
	var picturepath_obj = document.getElementById('picturepath');
</script>
