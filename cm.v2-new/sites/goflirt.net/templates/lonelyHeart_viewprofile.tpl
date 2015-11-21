<!-- {$smarty.template} -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
{include file="top.tpl"}
<body>
<h2>{$profile.username|regex_replace:"/@.*/":""}</h2>
<div class="result-box">
	
	<div class="result-box-inside-nobg">
		{if $profile.picturepath|count_characters < 2} 
			<div align="center"><img src="{$thumbpath}default.jpg" border="0" width="90" height="103" class="listimg" alt="" /></div>
		{else} 
			<div align="center"><img src="{$thumbpath}{$profile.picturepath}" border="0" width="90" height="103" class="listimg" alt="" /></div>
		{/if}
		<br />
		<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td width="50%" valign="top">
                <table width="100%" align="left" cellpadding="2" cellspacing="1" border="0">
                <tr bgcolor="#b6b6b6" height="28px">
                	<td colspan="2" align="center" class="text-title">{#Profile#}</td>
                </tr>
                <tr height="22px">									
                    <td width="52%" align="left"   bgcolor="#996666" style="padding-left:10px;">{#USERNAME#}:</td>
                    <td width="48%" align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.username|regex_replace:"/@.*/":""}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Gender#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.gender}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Birthday#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{if $profile.birthday eq "0000-00-00"}No entry{else}{$profile.birthday}{/if}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Country#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.country}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#City#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.city}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Height#} ({#Cm#}) :</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.height}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Weight#} ({#Kg#}) :</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.weight}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Appearance#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.appearance}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Eyes_Color#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.eyescolor}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Hair_Color#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.haircolor}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Hair_Length#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.hairlength}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Beard#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.beard}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Zodiac#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;" style="padding-left:10px;">{$profile.zodiac}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Civil_status#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.civilstatus}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Sexuality#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.sexuality}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Tattos#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.tattos}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Smoking#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.smoking}</td>
                </tr>
                <tr height="22px">									
                    <td align="left"   bgcolor="#996666" style="padding-left:10px;">{#Glasses#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.glasses}</td>
                </tr>									
                <tr height="22px">									
                    <td align="left"  bgcolor="#996666" style="padding-left:10px;">{#Piercings#}:</td>
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{$profile.piercings}</td>
                </tr>
                </table>
            </td>
            <td width="10" valign="top">&nbsp;</td>
            <td width="50%" valign="top">
                <table width="100%" align="left" cellpadding="2" cellspacing="1" border="0" >
                <tr bgcolor="#b6b6b6" height="28px">
                	<td colspan="2" align="center" class="text-title">{#Yourre#} {#looking_for#}</td>
                </tr>
                <tr height="22px">
                    <td width="75%" align="left" bgcolor="#996666" style="padding-left:10px;">{#Men#}:</td>
                    <td width="25%" bgcolor="#996666" style="padding-left:10px;">{$profile.lookmen}</td>
                </tr>
                <tr height="22px">								
                    <td align="left" bgcolor="#996666" style="padding-left:10px;">{#Women#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.lookwomen}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Pairs#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.lookpairs}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Min#} {#Age#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.minage}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Max#} {#Age#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.maxage}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Relationship#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.relationship}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#One_Night_Stand#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.onenightstand}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Affair#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.affair}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Friendship_and_more#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.friendship}</td>								
                </tr>
                </table>
				<br />
                <table width="100%" align="left" cellpadding="2" cellspacing="1" border="0">
                <tr bgcolor="#b6b6b6" height="28px">
                	<td colspan="2" align="center" class="text-title">{#Prefenrence#}</td>
                </tr>
                <tr height="22px">								
                    <td width="75%" align="left" style="padding-left:10px;" bgcolor="#996666">{#Cybersex#}:</td>
                    <td width="25%" bgcolor="#996666" style="padding-left:10px;">{$profile.cybersex}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Picture_Swapping#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.picture_swapping}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Live_dating#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.live_dating}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Role_playing#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.role_playing}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#sm#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.s_m}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Partner_exchange#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.partner_exchange}</td>								
                </tr>
                <tr height="22px">								
                    <td align="left" style="padding-left:10px;" bgcolor="#996666">{#Voyeurism#}:</td>
                    <td bgcolor="#996666" style="padding-left:10px;">{$profile.voyeurism}</td>							
                </tr>			
                </table>
            </td>
		</tr>
        <tr>
            <td colspan="3" height="15">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3" height="15" border="0">
                <table width="100%" align="left" cellpadding="2" cellspacing="1" border="0">						
                <tr height="22px">
                    <td align="left" style="padding-left:10px;" bgcolor="#996666" valign="top">{#Description#}:</td>
                    <td align="left" width="600px;" bgcolor="#996666" style="padding-left:10px;"><div style="display:block; width:590px; word-wrap: break-word;">{$profile.description}</div></td>
                </tr>
                </table>
            </td>
        </tr>				
		</table>
        <br />
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>			
			<td align="center">
				<input id="addfavorite_button" name="addfavorite_button" type="button" value="{#Add_to_Favorite#}" onclick="ajaxRequest('addFavorite', 'username={$profile.username}', '', addFavorite, '')" class="button">		
			</td>
        </tr>											
        </table>
	</div>
</div>
</body>
</html>