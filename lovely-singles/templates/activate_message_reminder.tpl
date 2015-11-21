<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{#TITLE#}</title>
</head>
<body>
<table width="800" border="0" cellpadding="0" cellspacing="0">
<tr>
<td><img src="{$url_web}images/mail-header.jpg" width="800" height="205" /></td>
</tr>
<tr>
<td style="border:1px; border-style:solid; border-color:#696969; background:#b6b6b6;">
<table width="782" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="30" style="font-family:tahoma, Helvetica, sans-serif; font-size:14px; color:#b83232; font-weight:bold;">{#Welcome#} {$username},</td>
  </tr>
</table>
<table width="782" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
  <td style="border:1px; border-style:solid; border-color:#696969; background:#7e190d;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px; color:#ffffff; font-weight:bold; font-size:14px;">
    Du hast dich vor kurzem auf unserer Flirtseite Lovely-Singles.com registriert. <br/>
	Bitte bestätige noch schnell deine Emailadresse, damit du gleich los flirten kannst! <br/>
	Drücke dafür bitte den nachfolgenden Link:<br /><br />
  <a href="{$url_web}?action=activate&username={$username}&password={$password}&code={$code}" style="color:#FFFFFF;text-decoration:underline; font-size:14px;">{#Activate#}</a><br />
  <br />Weiterhin senden wir dir hier nochmal deinen Login-Namen sowie dein Passwort. Bitte speicher diese Email an einem sicheren Ort oder schreibe dir die Daten auf!<br />
  <br /><b>{#USERNAME#}:</b>&nbsp;&nbsp;{$username}
  <br /><b>{#PASSWORD#}:</b>&nbsp;&nbsp;{$password}<br />
  <br />Viel Spaß & Flirterfolg auf Lovely-Singles.com!<br /> 
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
</body>
</html>