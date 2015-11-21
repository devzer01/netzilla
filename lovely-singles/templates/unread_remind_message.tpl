<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{#unread_remind_message_title#}</title>
</head>

<table width="800" border="0" cellpadding="0" cellspacing="0">
<tr>
<td><img src="{$url_web}images/mail-header.jpg" width="800" height="205" /></td>
</tr>
<tr>
<td style="border:1px; border-style:solid; border-color:#696969; background:#b6b6b6;">
<table width="782" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" style="font-family:tahoma, Helvetica, sans-serif; font-size:14px; color:#b83232; font-weight:bold;">{#unread_remind_message_subtitle#} {$username},</td>
  </tr>
</table>
<table width="782" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
  <td style="border:1px; border-style:solid; border-color:#696969; background:#7e190d;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px; color:#ffffff; font-weight:bold; font-size:14px;">
    {#unread_remind_message_content#|replace:'[PROFILE_NAME]':$sentfrom|replace:'[WEBSITE_URL]':$url_web} <a href="{$url_web}?action=activate&username={$username}&password={$password}&code={$code}&url_action={$url_action}" target="_blank" style="color:#FFFFFF;text-decoration:underline; font-size:14px;">{#unread_remind_message_link#}</a>
  </td>
  <td width="95" align="right" valign="bottom"><img src="{$url_web}images/mail-right.jpg" width="85" height="120" /></td>
  </tr>
  </table></td>
</tr>
</table>
<table width="782" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="10"></td>
  </tr>
</table>
</td>
</tr>
</table>

<!--<div style="background:url({$url_web}images/bg.jpg) top center repeat-x;background:#2d2d2d;line-height:1em;color:#000000;width:800px;">
    <div style="background:url({$url_web}images/header.jpg) center top no-repeat;height:195px;width:100%;">
        <div>
            <div style="display:block;float:left;height:133px;width:auto;">
            	<img src="{$url_web}images/logo.png" />
			</div>
        </div>
    </div>
    <div style="height: auto;width:800px;margin:0 auto;margin-top:10px;">
        <div style="display:block;width:798px;height:auto;background:#b6b6b6;border:1px solid #696969;-moz-border-radius: 5px; -webkit-border-radius: 5px;margin-top:10px;">
            <h1 style="display:block;width:800px;line-height:30px;font-size:11px;text-indent:15px;color:#b83232;font-weight:bold;">{#Welcome#} {$username},</h1>
            <div style="background: url({$url_web}images/newbox-bg.jpg) bottom right no-repeat #7e190d;display:block;width:auto;overflow:auto;height:auto;margin:0 8px 8px 8px;padding:8px;border:1px solid #696969;-moz-border-radius: 5px;-webkit-border-radius: 5px;color:#ffffff;">
                <div style="display:block;text-align:left;line-height:20px;margin-top:5px;margin-left:10px;color:#ffffff;font-weight:bold;">
                    <br />{#activate_message_title#}
                    <br /><br /><a href="{$url_web}?action=activate&username={$username}&password={$password}&code={$code}" style="color:#FFFFFF;text-decoration:underline;">{#Activate#}</a>
                    <br />{#activate_message_title2#}
                    <br /><b>{#USERNAME#}:</b>&nbsp;&nbsp;{$username}
                    <br /><b>{#PASSWORD#}:</b>&nbsp;&nbsp;{$password}
                    <br />{#activate_message_title3#}
                    <br /> 
                </div>
            </div>
        </div>
	</div>
</div>-->