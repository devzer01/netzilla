<div class="result-box">
<h1>{#MANAGE_COIN#}</h1>
<div class="result-box-inside-nobg">

<br />
<form method="POST" action="" name="coinform" id="coinform">
<table width="100%"  border="0">
<tr>
<td align="left" width="100px">Free coins : </td>
<td><input type="text" name="freecoins" size="10" value="{$managecoin.0.freecoins}"></td>
</tr>
<tr>
<td align="left" width="100px">Free coins after verified mobile phone : </td>
<td><input type="text" name="coinVerifyMobile" size="10" value="{$managecoin.0.coinVeryfyMobile}"> (Maximum 100 coins)</td>
</tr>
<tr>
<td align="left" colspan="2">How much coin(s) will be charged for these services?<br/><br/></td>
</tr>
<tr>
<td align="left" width="100px">SMS : </td>
<td><input type="text" name="coinsms" size="10" value="{$managecoin.0.coin_sms}"></td>
</tr>
<tr>
<td aligh="left">EMAIL (PM) : </td>
<td><input type="text" name="coinemail" size="10" value="{$managecoin.0.coin_email}"></td>
</tr>
<tr>
<td colspan="2" align="right"><a href="#" onclick="$('coinform').submit()" class="butregisin">Save</a></td>
</tr>
</table>
</form>

</div>

<!--<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>-->
</div>