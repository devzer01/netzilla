<div class="container-metropopup">
	<!--<div class="metropopup-content">
		<form id="attachment-coins-form" name="attachment-coins-form" method="post" action="" class="formfield">
		{#how_many_coins_send#}<br/>
		<input type="text" name="coins" style="width:300px;" class="formfield_01" value="{$smarty.const.COIN_EMAIL}">
		<a href="#" onclick="addAttactmentCoins(); return false" class="btn-popup">{#SEND#}</a>
		</form>
	</div>
	<br class="clear" /> -->
    <div class="container-select-coins">
    <h2>{#how_many_coins_send#}</h2>
    
<table width="auto" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td align="center" valign="middle">
<ul class="container-attachments-coins">
<li><a href="#" onclick="addAttactmentCoins(30); return false"><span>30</span></a></li>
<li><a href="#" onclick="addAttactmentCoins(100); return false"><span>100</span></a></li>
</ul>
</td>
</tr>
</table>

        <br class="clear" />
    </div>
	</div>
</div>