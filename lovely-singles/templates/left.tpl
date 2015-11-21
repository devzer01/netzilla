<table width="154" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="26" align="center" background="images/bg_mem.gif" class="text12black">
		{if $smarty.session.sess_username neq ""}
			{#MENU#}
		{else}
			{#MEMEBER_LOGIN#}
		{/if}
		</td>
	</tr>
	<tr>
		<td  valign="top">
		<table width="154px" border="0" cellpadding="0" cellspacing="0" background="images/mem_c.jpg">
			<tr>
				<td width="18"><img src="images/mem_top_l.gif" width="18" height="6"></td>
				<td background="images/mem_top_c.gif"></td>
				<td width="10"><img src="images/mem_top_r.gif" width="10" height="6"></td>
			</tr>
			<tr>
				<td width="18" background="images/mem_c_l.gif">&nbsp;</td>
				<td valign="top" bgcolor="#9AC6DD">
				<table width="154px" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td>
						<div id="loginBox">
						{if $smarty.session.sess_username neq ""}
							{if $smarty.session.sess_mem==1}
								{include file="left-membership_islogged.tpl"}
							{else}
								{include file="left-membertest_islogged.tpl"}
							{/if}
						{else}
							{include file="left-notlogged.tpl"}
						{/if}  
						</div>
						</td>
					</tr> 
			  </table>			  </td>
				<td width="10" background="images/mem_c_r.gif">&nbsp;</td>
			</tr> 
		</table>
		</td>
	</tr>
</table>