<!-- {$smarty.template} -->
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="26" background="images/bg_sex.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
					<td align="center" background="images/bgcenter.gif" class="text14whitebold" ><img src="images/xx.gif" />
					::{#Lonely_Heart_Ads#}::</td>
					<td background="images/bgr.gif" width="12" height="24"></td>
				 </tr>
		</table>
		</td>
	</tr>
</table> 
<table width="610" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td width="610" valign="top">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  {if $lonelyheart.category != ''}
				<tr>
				  <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<tr>
				  <td>
				  <table width="607" height="101" border="0" cellpadding="0" cellspacing="0">
				  <tr>
					<td width="10"><img src="images/pic_top_l.gif" width="10" height="6" /></td>
					<td background="images/pic_top_c.gif"></td>
					<td width="10"><img src="images/pic_top_r.gif" width="10" height="6" /></td>
				  </tr>
				  <tr>
					<td width="10" height="101" background="images/p_c_l.gif"></td>
					<td height="101" align="center" valign="middle" bgcolor="#ECF0F1" class="text12black">
					<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0"> 
						<tr>
							<td align="center" colspan="2" height="10px"></td>
						</tr>
						<tr>
							<td width="50%">{#Target_group#}:</td>
							<td width="50%"><div align="left">{$lonelyheart.target}</div></td>
						</tr>
						<tr>
							<td width="50%">{#Category#}:</td>
							<td width="50%"><div align="left">{$lonelyheart.category}</div></td>
						</tr>
						<tr>
							<td width="50%">Headline:</td>
							<td width="50%"><div align="left">{$lonelyheart.headline}</div></td>
						</tr>
						<tr>
							<td width="50%">{#Text#}:</td>
							<td width="50%"><div align="left">{$lonelyheart.text}</div></td>
						</tr>
						<tr>
							<td align="center" colspan="2" height="10px"></td>
						</tr>
						<tr>
							<td align="center" colspan="2"><input id="back_button" name="back_button" type="button" onclick="parent.location='{$smarty.server.HTTP_REFERER}';" value="{#BACK#}">
							</td>
						</tr>
					</table>					</td>
					<td width="10" height="101" background="images/p_c_r.gif"></td>
				  </tr>
				  <tr>
					<td width="10"><img src="images/p_foot_l.jpg" width="10" height="5" /></td>
					<td background="images/p_foot_c.jpg"></td>
					<td width="10"><img src="images/p_foot_r.jpg" width="10" height="5" /></td>
				  </tr>
				</table>
				  </td>
				</tr>
            	<tr> 
			</tr> 
          </table></td>
        </tr>
		{else}
        <tr>
          <td>
		  <table width="607" height="101" border="0" cellpadding="0" cellspacing="0">
		  <tr>
			<td width="10"><img src="images/pic_top_l.gif" width="10" height="6" /></td>
			<td background="images/pic_top_c.gif"></td>
			<td width="10"><img src="images/pic_top_r.gif" width="10" height="6" /></td>
		  </tr>
		  <tr>
			<td width="10" height="101" background="images/p_c_l.gif"></td>
			<td height="101" align="center" valign="middle" bgcolor="#ECF0F1" class="text12black"> {#Have_no_data_yet#} <a href="#" onclick="parent.location='{$smarty.server.HTTP_REFERER}';">{#BACK#}</a></td>
			<td width="10" height="101" background="images/p_c_r.gif"></td>
		  </tr>
		  <tr>
			<td width="10"><img src="images/p_foot_l.jpg" width="10" height="5" /></td>
			<td background="images/p_foot_c.jpg"></td>
			<td width="10"><img src="images/p_foot_r.jpg" width="10" height="5" /></td>
		  </tr>
		</table>
		  </td>
        </tr>
		{/if}
      </table>
	</td>
  </tr>
</table>
