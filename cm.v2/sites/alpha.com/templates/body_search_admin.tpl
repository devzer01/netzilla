<!-- {$smarty.template} -->
<table width="98%" height="100%" border="0" cellpadding="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="26" background="images/bg_sex.jpg">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
					<td align="center" background="images/bgcenter.gif" class="text14whitebold" ><img src="images/xx.gif" />
					::{#Manage_user#}::</td>
					<td background="images/bgr.gif" width="12" height="24"></td>
				 	</tr>
					</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" celpadding="0">
	<tr>
		<td height="20px"></td>
	</tr>
	 {if $userrec[0] != ''}
	<tr>
		<td align="center" valign="top">
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					  <td>
						<table width="90%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
						<td>
						<table  border="0" align="right" cellpadding="2" cellspacing="0" style="cursor:pointer; " onClick="window.location.href='?action=register&type=membership&from=admin'">
					 
					  <tr>
						<td><img src="images/icon/b_insrow.png" width="16" height="16"></td>
						<td> {#Add_New_User#} </td>
					  </tr>
					</table>
					</td>
				  </tr>
			<tr>
                          		<td>
                           		 <table width="100%"  border="0" cellspacing="1" cellpadding="2">
                            
                            <tr bgcolor="#66CCFF" height="20px">
                              <td align="center">
			      {if $smarty.get.order eq ''}
				{if $smarty.get.type eq 'desc'}
					<a href="?action=admin_manageuser&order=&type=asc&next={$smarty.get.next}" class="linklist">{#USERNAME#}</a> <img src="images/s_desc.png">
				{else}
					<a href="?action=admin_manageuser&order=&type=desc&next={$smarty.get.next}" class="linklist">{#USERNAME#}</a> <img src="images/s_asc.png">
				{/if}
			      {else}
					<a href="?action=admin_manageuser&order=&type=asc&next={$smarty.get.next}" class="linklist">{#USERNAME#}</a>
			      {/if}
			      </td>
			      <td align="center" width="100">
			      {if $smarty.get.order eq 'city'}
				{if $smarty.get.type eq 'desc'}
					<a href="?action=admin_manageuser&order=city&type=asc&next={$smarty.get.next}" class="linklist">{#City#}</a> <img src="images/s_desc.png">
				{else}
					<a href="?action=admin_manageuser&order=city&type=desc&next={$smarty.get.next}" class="linklist">{#City#}</a> <img src="images/s_asc.png">
				{/if}
			      {else}
					<a href="?action=admin_manageuser&order=city&type=asc&next={$smarty.get.next}" class="linklist">{#City#}</a>
			      {/if}
			      </td>
			      <td align="center" width="100">
			      {if $smarty.get.order eq 'state'}
				{if $smarty.get.type eq 'desc'}
					<a href="?action=admin_manageuser&order=state&type=asc&next={$smarty.get.next}" class="linklist">{#State#}</a> <img src="images/s_desc.png">
				{else}
					<a href="?action=admin_manageuser&order=state&type=desc&next={$smarty.get.next}" class="linklist">{#State#}</a> <img src="images/s_asc.png">
				{/if}
			      {else}
					<a href="?action=admin_manageuser&order=state&type=asc&next={$smarty.get.next}" class="linklist">{#State#}</a>
			      {/if}
			      </td>
			      <td align="center" width="100">
			      {if $smarty.get.order eq 'country'}
				{if $smarty.get.type eq 'desc'}
					<a href="?action=admin_manageuser&order=country&type=asc&next={$smarty.get.next}" class="linklist">{#Country#}</a> <img src="images/s_desc.png">
				{else}
					<a href="?action=admin_manageuser&order=country&type=desc&next={$smarty.get.next}" class="linklist">{#Country#}</a> <img src="images/s_asc.png">
				{/if}
			      {else}
					<a href="?action=admin_manageuser&order=country&type=asc&next={$smarty.get.next}" class="linklist">{#Country#}</a>
			      {/if}
			      </td>
                              <td align="center" width="80">{#Edit#}</td>
                              <td align="center" width="80">{#Delete#}</td>
                            </tr>
                 
						  {foreach key=key from=$userrec item=userdata}
						  <tr  bgcolor="{cycle values="#eeeeee,#d0d0d0"}">
							<td><div align="left">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="linklist">{$userdata.username}</a></div></td>
							<td width="100px">{$userdata.city}</td>
							<td width="100px">{$userdata.state}</td>
							<td width="100px">{$userdata.country}</td>
						    <td width="80">
							  <div align="center">
							  <a href="?action=editprofile&user={$userdata.username}&proc=edit&from=admin">
							  <img src="images/icon/b_edit.png" width="16" height="16" border="0"></a> </div></td>
						  <td width="80">
							  <div align="center">
							  {if $userdata.status != 1}
							  <a href="?action=admin_manageuser&user={$userdata.username}&proc=del&page={$smarty.get.page}" onclick="return confirm(confirm_delete_box)">
							  <img src="images/icon/b_drop.png" width="16" height="16" border="0">
							  </a>
							  {else}
							  <img src="images/icon/b_drop_disable.png" width="16" height="16">
							  {/if}
							  </div></td>
						</tr>
						  {/foreach}
                          </table>
                          </td>
                        </tr>
                 
			<tr>
				<td height="5px"></td>
			</tr>
			
                        <tr>
                          <td align="left">{paginate_prev class="linklist"} {paginate_middle class="linklist"} {paginate_next class="linklist"}&nbsp;</td>
                        </tr>
                        
                  </table>
                  
                  </td>
                  </tr>
	</table>
</td>
</tr>
	<tr id="trList" style="display:{'none'} ">
		<td>&nbsp;</td>
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
					<td height="101" align="center" valign="middle" bgcolor="#ECF0F1" class="text12black">No User Found</td>
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
	<tr>
		<td height="20px"></td>
	</tr>
</table>
