<!-- {$smarty.template} -->
{*
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
	<td align="center" background="images/bgcenter.gif" class="text12whitebold" ><img src="images/xx.gif" /></td>
	<td background="images/bgr.gif" width="12" height="24"></td>
</tr>
</table>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="30px"></td>
	</tr>
	<tr>
		<td>
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr><td align="center" class="text14red"><b>{$text}</b><br><br> </td></tr>
			<tr><td height="15px"></td></tr>
			<tr><td align="center" class="text14grey">Am besten also gleich deine Mitgliedschaft aufwerten und dann auch diese Funktion nutzen!</td></tr>
			<tr><td height="10px"></td></tr>					
			<tr><td align="center" class="text14grey">
			{if !$payment}
			{include file="payment.tpl"}
			{/if}
			</td></tr>
		</table>
		</td>
	</tr>
</table>
*}
<div class="result-box">
	<h1>{$text}</h1>
	<div class="result-box-inside">
		<div align="center" style="width:680px;">
        	Am besten also gleich deine Mitgliedschaft aufwerten und dann auch diese Funktion nutzen!
        	{if !$payment}
			{include file="payment.tpl"}
			{/if}
        </div>
	</div>
</div>