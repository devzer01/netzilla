<div class="result-box">
<h1>Payment made</h1>
<div class="result-box-inside">
<form id="sendmessage_form" name="sendmessage_form" action="?action=sendSMS" method="post">

<table align="center" border="0" cellpadding="0" cellspacing="0" width="80%">
	<tr>
		<td height="35px"></td>
	</tr>	
	
	{if $gp_status}
	<tr>
		<td colspan="2" class="text14red">The GiroPay payment was not successful!</td>
	</tr>
	<tr>
		<td colspan="2" class="text14red">Error message: {$gp_status}!</td>
	</tr>
   {else}
	<tr>
		<td colspan="2" class="text14red">The payment was not successful!</td>
	</tr>
   {/if}
	<tr>
		<td height="35px"></td>
	</tr>
	<tr>
		<td colspan="2" class="text14grey">You will now be automatically redirected back to the membership page.</td>
	</tr>
	<tr>
		<td height="30px"></td>
	</tr>
	<tr>
		<td colspan="2"><META http-equiv='refresh' content='3;URL=http://www.lovely-singles.com/?action=membership'></td>
	</tr>
	<tr>
		<td height="10px"></td>
	</tr>

</table>
</form>
</div>
</div>