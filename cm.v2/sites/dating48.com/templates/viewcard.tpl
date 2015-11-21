<!-- {$smarty.template} -->
<div class="result-box">
<h1>::{#View_Card#}::</h1>
<div class="result-box-inside">

<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td height="30px"></td>
	</tr>	

	<tr>
		<td height="25px"><table  border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><img src="{$message.image}"border="0"></td>
          </tr>
        </table>	  </td>
	</tr>
	<tr>
		<td>
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
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

		</table>
		</td>
	</tr>
	<tr>
		<td height="30px"></td>
	</tr>
	<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<form id="view_message_form" name="view_message_form" method="post" action="">

			</form>
		<tr>
		<td height="30px"></td>
	</tr>		
			
		</table>	
		</td>
	</tr>
</table>

</div>
</div>