<!-- {$smarty.template} -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
	<td align="center" background="images/bgcenter.gif" class="text12whitebold" ><img src="images/xx.gif" />:: Cart ::</td>
	<td background="images/bgr.gif" width="12" height="24"></td>
</tr>
<tr><td height="20"></td></tr>
</table>

{if $smarty.get.redirect eq '1'}
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td  align="center" class="text12black">
		You're redirecting to our payment system...<br/>
		<a href="http://payment.raturo.de/?tid={$tid}">Click here for redirect.</a>
	</td>
  </tr>
</table>

<SCRIPT language="JavaScript">
<!--
//setTimeout("location.href = 'http://payment.raturo.de/?tid={$tid}';",1500);
//-->
</SCRIPT>
{else}
CART HERE
{/if}