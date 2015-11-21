<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
{***************************** Start include top menu ********************************}
{include file="top.tpl"}
{******************************* End include top menu ********************************}
<body class="lonely_heart_view">
		<!--<h1>{#Lonely_Heart_Ads#}</h1>
		<div></div>-->
			<strong>{#Target_group#}:</strong>&nbsp;&nbsp;{$lonelyheart.target}
			<br /><strong>{#Category#}:</strong>&nbsp;&nbsp;{$lonelyheart.category}
			<br /><strong>{#Headline#}:</strong>&nbsp;&nbsp;{$lonelyheart.headline|wordwrap:60:"<br />":true}
			<br /><strong>{#Text#}:</strong>&nbsp;&nbsp;{$lonelyheart.text|stripslashes|wordwrap:60:"<br />":true}
		
		<!--<div style="text-align:right; float:right">
			{if $smarty.get.action eq "lonely_heart_ads"}
				<input id="edit_button" name="edit_button" class="button" type="button" onclick="window.opener.location='?action=lonely_heart_ads&do=edit&lonelyid={$smarty.get.lonelyid}'; window.close();" value="{#EDIT#}" />
			{/if}
		</div>-->
</body>
</html>