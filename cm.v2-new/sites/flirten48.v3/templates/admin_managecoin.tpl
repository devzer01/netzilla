<!-- {$smarty.template} -->
<h1 class="admin-title">{#MANAGE_COIN#}</h1>


<form method="POST" action="" name="coinform" id="coinform">
<table width="100%"  border="0" cellpadding="5" cellspacing="5">
<tr>
<td align="left" width="100px">Free coins : </td>
<td><input type="text" name="freecoins" size="10" value="{$managecoin.0.freecoins}"> (Maximum 100 coins)</td>
</tr>
<tr>
<td align="left" width="100px">Free coins after verify mobile phone : </td>
<td><input type="text" name="coinVerifyMobile" size="10" value="{$managecoin.0.coinVeryfyMobile}"> (Maximum 100 coins)</td>
</tr>
<tr>
<td align="left" colspan="2"><br/>How much coin(s) will be charged for these services?</td>
</tr>
<tr>
<td align="left" width="100px">SMS : </td>
<td><input type="text" name="coinsms" size="10" value="{$managecoin.0.coin_sms}"></td>
</tr>
<tr>
<td aligh="left">EMAIL (PM) : </td>
<td><input type="text" name="coinemail" size="10" value="{$managecoin.0.coin_email}"></strong></td>
</tr>
<tr>
<td colspan="2" align="right"><a href="#" onclick="$('coinform').submit()" class="butregisin">Save</a></td>
</tr>
</table>
</form>
