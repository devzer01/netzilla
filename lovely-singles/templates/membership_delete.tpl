<div class="result-box">
	<h1>{#MEMBERSHIP#}</h1>
	<div class="result-box-inside">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr><td height=15>&nbsp;</td></tr>

		{if $smarty.session.sess_permission eq "4"}
		<tr><td class="text14grey" align="center">Möchtest du deine KOSTENLOSE Mitgliedschaft bei Herzoase tatsächlich beenden?</td></tr>
		{else}
		<tr><td class="text14grey" align="center">Möchtest du deine Mitgliedschaft bei Herzoase tatsächlich beenden?</td></tr>
		{/if}

		<tr><td height=35>&nbsp;</td></tr>
		<form action="" method="post">
		<tr align="center">
			<td>
				<input type="submit" name="delete_button" value="Ja" onclick="setVisibility('delete_message_tr','', 'visible')" class="button">
				<input type="button" value="Nein" onclick="location = '.'" class="button">
			</td>
		</tr>
		<tr><td height=35>&nbsp;</td></tr>

			<tr id="delete_message_tr" style="display: none;">
				<td class="text14grey" align="center">
					Die Löschung des Profils kann bis zu 2 Wochen dauern. Die Kündigung eines evtl. Abonnements wird mit dem heutigen Datum akzeptiert.
				</td>
			</tr>
		</form>
		</table>
	</div>
</div>