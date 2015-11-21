<!-- {$smarty.template} -->
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="26" background="images/bg_sex.jpg">
					<table align="center" width="156" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td width="156" height="26" align="center" background="images/bg_center.gif" class="text12black">Mitglieder Zahlungen</td>
					  </tr>
					</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<form action="" method="post">
<table border="0" cellpadding="5" cellspacing="0" align="center">
<tr>
	<td align="left" colspan="3" height="5px"></td>
</tr>
{if $text}
<tr>
	<td align="center" colspan="3"><strong style="color: red; font-size: 14px">{$text}</string><td>
</tr>
<tr>
	<td align="left" colspan="3" height="5px"></td>
</tr>
{/if}
<tr>
	<td align="left" class="text14grey">Nickname:</td>
	<td align="left" width="320" colspan="2">
	<input name="username" type="text" id="username" style="width:150px" value="{$save.username}" maxlength="20" readonly/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">Handy Nummer:</td>
	<td align="left" width="320" colspan="2">
	<input name="mobileno" type="text" id="mobileno" style="width:150px" value="{$save.mobileno}" maxlength="20"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">Bisheriges Abo:</td>
	<td align="left"  width="320" colspan="2">
	{*<input name="old_type" type="text" id="old_type" style="width:50px" value="{$save.old_type}" maxlength="1"/>*}
	<input type="radio" name="old_type" value="2" {if $save.old_type eq '2'}checked="checked"{/if}> VIP
	<input type="radio" name="old_type" value="3" {if $save.old_type eq '3'}checked="checked"{/if}> Premium
	<input type="radio" name="old_type" value="4" {if $save.old_type eq '4'}checked="checked"{/if}> Standard
	</td>
	{*<td align="left" class="text12grey">2: VIP, 3: Premium, 4: Standard</td>*}
</tr>
<!--
<tr>
	<td align="left" class="text14grey">Neues Abo:</td>
	<td align="left"  width="320" colspan="2">
	{*<input name="new_type" type="text" id="new_type" style="width:50px" value="{$save.new_type}" maxlength="1"/> *}
	<input type="radio" name="new_type" value="2" {if $save.new_type eq '2'}checked="checked"{/if}> VIP
	<input type="radio" name="new_type" value="3" {if $save.new_type eq '3'}checked="checked"{/if}> Premium
	<input type="radio" name="new_type" value="4" {if $save.new_type eq '4'}checked="checked"{/if}> Standard
	</td>
	{*<td align="left" class="text12grey">2: VIP, 3: Premium, 4: Standard</td>*}
</tr>
-->
<tr>
	<td align="left" class="text14grey">Bisheriges Abo-Ende:</td>
	<td align="left" width="320" colspan="2">
	<input name="old_paid_until" type="text" id="old_paid_until" style="width:150px" value="{$save.old_paid_until}" maxlength="30"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">Neues Abo-Ende:</td>
	<td align="left" width="320" colspan="2">
	<input name="new_paid_until" type="text" id="new_paid_until" style="width:150px" value="{$save.new_paid_until}" maxlength="30"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">Bezahlt per:</td>
	<td align="left"  width="320" colspan="2">
	{*<input name="paid_via" type="text" id="paid_via" style="width:50px" value="{$save.paid_via}" maxlength="1"/>*}
	<input type="radio" name="paid_via" value="1" {if $save.paid_via eq '1'}checked="checked"{/if}> Kreditkarte
	<input type="radio" name="paid_via" value="2" {if $save.paid_via eq '2'}checked="checked"{/if}> Paypal
	<input type="radio" name="paid_via" value="3" {if $save.paid_via eq '3'}checked="checked"{/if}> Überweisung
	<input type="radio" name="paid_via" value="4" {if $save.paid_via eq '4'}checked="checked"{/if}> ELV
	</td>
	{*<td align="left" class="text12grey">1: Kreditkarte, 2: Paypal, 3: Überweisung, 4: ELV</td>*}
</tr>
<tr>
	<td align="left" class="text14grey">Name:</td>
	<td align="left" width="320" colspan="2">
	<input name="real_name" type="text" id="real_name" style="width:150px" value="{$save.real_name}" maxlength="30"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">Strasse:</td>
	<td align="left" width="320" colspan="2">
	<input name="real_street" type="text" id="real_street" style="width:150px" value="{$save.real_street}" maxlength="30"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">PLZ:</td>
	<td align="left" width="320" colspan="2">
	<input name="real_plz" type="text" id="real_plz" style="width:150px" value="{$save.real_plz}" maxlength="30"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">Stadt:</td>
	<td align="left" width="320" colspan="2">
	<input name="real_city" type="text" id="real_city" style="width:150px" value="{$save.real_city}" maxlength="30"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">IP Adresse:</td>
	<td align="left" width="320" colspan="2">
	<input name="ip_address" type="text" id="ip_address" style="width:150px" value="{$save.ip_address}" maxlength="30" disabled/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">Bezahlt am:</td>
	<td align="left" width="320" colspan="2">
	<input name="payday" type="text" id="payday" style="width:150px" value="{$save.payday}" maxlength="30"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">Zahlvorgang abgeschlossen:</td>
	<td align="left"  width="320" colspan="2">
	{*<input name="payment_complete" type="text" id="payment_complete" style="width:50px" value="{$save.payment_complete}" maxlength="1"/>*}
	<input type="radio" name="payment_complete" value="0" {if $save.payment_complete eq '0'}checked="checked"{/if}> Nein
	<input type="radio" name="payment_complete" value="1" {if $save.payment_complete eq '1'}checked="checked"{/if}> Ja
	</td>
	{*<td align="left" class="text12grey">0: Nein, 1: Ja</td>*}
</tr>
<tr>
	<td align="left" class="text14grey">Widerspruch eingelegt:</td>
	<td align="left"  width="320" colspan="2">
	{*<input name="recall" type="text" id="recall" style="width:50px" value="{$save.recall}" maxlength="1"/>*}
	<input type="radio" name="recall" value="0" {if $save.recall eq '0'}checked="checked"{/if}> Nein
	<input type="radio" name="recall" value="1" {if $save.recall eq '1'}checked="checked"{/if}> Ja
	</td>
	{*<td align="left" class="text12grey">0: Nein, 1: Ja</td>*}
</tr>
<tr>
	<td align="left" class="text14grey">Betrag:</td>
	<td align="left" width="175">
	<input name="sum_paid" type="text" id="sum_paid" style="width:50px" value="{$save.sum_paid}" maxlength="3"/> EURO
	</td>
	{*<td align="left" class="text12grey">EURO</td>*}
</tr>
<tr>
	<td align="left" class="text14grey">Name der Bank</td>
	<td align="left" width="320" colspan="2">
	<input name="bank_name" type="text" id="bank_name" style="width:150px" value="{$save.bank_name}" maxlength="30"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">BLZ:</td>
	<td align="left" width="320" colspan="2">
	<input name="bank_blz" type="text" id="bank_blz" style="width:150px" value="{$save.bank_blz}" maxlength="30"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">Kontonummer:</td>
	<td align="left" width="320" colspan="2">
	<input name="bank_account" type="text" id="bank_account" style="width:150px" value="{$save.bank_account}" maxlength="30"/> 
	</td>
</tr>
<tr>
	<td align="left" class="text14grey">Gehört zu Datensatz:</td>
	<td align="left" width="320" colspan="2">
	<input name="copy_from" type="text" id="copy_from" style="width:150px" value="{if $smarty.get.action eq "admin_paid_copy"}
{$save.ID}{else}{$save.copy_from}{/if}" maxlength="30" readonly/> 
	</td>
</tr>
<tr>
	<td align="left" colspan="3" height="20px"></td>
</tr>
<tr>
		<td align="center" colspan="3" height="5px">
		<input type="hidden" name="return_url" value='{$smarty.server.HTTP_REFERER}' /> 
		<input type="button" onclick="parent.location='{$smarty.server.HTTP_REFERER}'" value="{#CANCEL#}" />
		{if $smarty.get.action eq "admin_paid_copy"}
<!--		<input type="submit" name="copy" value="Datensatz anlegen" /></td>      -->
		{else}
		<input type="submit" name="send" value="Senden" /></td>
		{/if}
</tr>
</table>
</form>