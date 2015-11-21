<div class="result-box">
<h1>{#View_Card#}</h1>
<div class="result-box-inside">
<div align="center" style="margin-top:10px;"><img src="{$message.image}" style=" width:400px; border:4px solid #FFFFFF;"></div>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center">
		 <table width="400"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
			  <td colspan="2" valign="top">&nbsp;</td>
		  </tr>
			<tr>
			<tr>
				<td valign="top" class="text14black"><b>{#From#}:</b></td>
				<td class="text14grey">{$message.username}</td>
			</tr>
			<tr>
				<td valign="top" class="text14black"><b>{#Datetime#}:</b></td>
				<td class="text14grey">{$message.datetime}</td>
			</tr>
			<tr>
			<td valign="top" class="text14black"><b>{#Subject#}:</b></td>
				<td class="text14grey">
				{if $smarty.get.action eq 'viewcard'}
				{#HPB#}
				{elseif $smarty.get.action eq 'viewcard_mail'}
				{$message.subject}
				{/if}
				</td>
			</tr>
			<tr>
				<td valign="top" class="text14black"><b>{#Message#}:</b></td>
				<td class="text14grey">{$message.message|wordwrap:80:"<br />":true}</td>
			</tr>
		<form id="view_message_form" name="view_message_form" method="post" action="">

			</form>
		</table>
		</td>
	</tr>
</table>

</div>
</div>