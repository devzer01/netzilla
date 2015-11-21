<!-- {$smarty.template} -->
<table width="610" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
		<table width="610" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
			</tr>
			<tr>
				<td valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td valign="top" >
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						{if $smarty.get.action eq 'admin_manageuser_extern'}
							<tr>
								<td width="610" rowspan="2" valign="top">
								<div>
									{include file='admin_manageuser_extern.tpl'}
								</div>								
								</td>
							</tr>
						{/if}
					  </table>					  
					  </td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
