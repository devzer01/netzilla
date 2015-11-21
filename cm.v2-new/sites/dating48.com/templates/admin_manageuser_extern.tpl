<!-- {$smarty.template} -->
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" celpadding="0">
	<tr>
		<td align="center">
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="26" background="images/bg_sex.jpg">
					<table align="center" width="156" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td width="156" height="26" align="center" background="images/bg_center.gif" class="text12black">{#MANAGE_USER#}</td>
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
<tr>
	<td align="center" valign="top">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>
				<table width="90%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table  border="0" align="right" cellpadding="2" cellspacing="0">
						<tr>
							<td><a href="?action=admin_adduser" style="text-decoration: none"><img src="images/icon/b_insrow.png" width="16" height="16" border="0">{#Add_New_User#}</a></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table width="100%"  border="0" cellspacing="1" cellpadding="2">
						<tr bgcolor="#66CCFF" height="20px">
							<td width="22" align="center">
                            {if $smarty.get.order eq ''}
								{if $smarty.get.type eq 'asc'}
									<a href="?action=admin_manageuser_extern&order=&type=desc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist"><img src="images/s_desc.png" border="0"></a>
								{else}
									<a href="?action=admin_manageuser_extern&order=&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist"><img src="images/s_asc.png" border="0"></a>
								{/if}
							{else}
								<a href="?action=admin_manageuser_extern&order=&type=desc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">#</a>
							{/if}
							</td>
							<td>Type</td>
							<td align="center">
							{if $smarty.get.order eq 'name'}
								{if $smarty.get.type eq 'desc'}
									<a href="?action=admin_manageuser_extern&order=name&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#USERNAME#}</a> <img src="images/s_desc.png">
								{else}
									<a href="?action=admin_manageuser_extern&order=name&type=desc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#USERNAME#}</a> <img src="images/s_asc.png">
								{/if}
							{else}
								<a href="?action=admin_manageuser_extern&order=name&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#USERNAME#}</a>
							{/if}
							</td>

							<td align="center" width="100">
							{if $smarty.get.order eq 'registred'}
								{if $smarty.get.type eq 'desc'}
									<a href="?action=admin_manageuser_extern&order=registred&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">Anmeldung</a> <img src="images/s_desc.png">
								{else}
									<a href="?action=admin_manageuser_extern&order=registred&type=desc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">Anmeldung</a> <img src="images/s_asc.png">
								{/if}
							{else}
								<a href="?action=admin_manageuser_extern&order=registred&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">Anmeldung</a>
							{/if}
							</td>							

							<td align="center" width="100">
							{if $smarty.get.order eq 'city'}
								{if $smarty.get.type eq 'desc'}
									<a href="?action=admin_manageuser_extern&order=city&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#City#}</a> <img src="images/s_desc.png">
								{else}
									<a href="?action=admin_manageuser_extern&order=city&type=desc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#City#}</a> <img src="images/s_asc.png">
								{/if}
							{else}
								<a href="?action=admin_manageuser_extern&order=city&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#City#}</a>
							{/if}
							</td>
<!--			      <td align="center" width="100">
			      {if $smarty.get.order eq 'state'}
				{if $smarty.get.type eq 'desc'}
					<a href="?action=admin_manageuser&order=state&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#State#}</a> <img src="images/s_desc.png">
				{else}
					<a href="?action=admin_manageuser&order=state&type=desc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#State#}</a> <img src="images/s_asc.png">
				{/if}
			      {else}
					<a href="?action=admin_manageuser&order=state&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#State#}</a>
			      {/if}
			      </td> -->
			      <td align="center" width="100">
			      {if $smarty.get.order eq 'country'}
				{if $smarty.get.type eq 'desc'}
					<a href="?action=admin_manageuser_extern&order=country&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#Country#}</a> <img src="images/s_desc.png">
				{else}
					<a href="?action=admin_manageuser_extern&order=country&type=desc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#Country#}</a> <img src="images/s_asc.png">
				{/if}
			      {else}
					<a href="?action=admin_manageuser_extern&order=country&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">{#Country#}</a>
			      {/if}
			      </td>
					<td align="center" width="80">
						{if $smarty.get.order eq 'flag'}
				{if $smarty.get.type eq 'desc'}
					<a href="?action=admin_manageuser_extern&order=flag&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">Bearb.</a> <img src="images/s_desc.png">
				{else}
					<a href="?action=admin_manageuser_extern&order=flag&type=desc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">Bearb.</a> <img src="images/s_asc.png">
				{/if}
			      {else}
					<a href="?action=admin_manageuser_extern&order=flag&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">Bearb.</a>
			      {/if}
					</td>
			
			      <td align="center" width="100">        
			      {if $smarty.get.order eq 'mobileno'}
					{if $smarty.get.type eq 'desc'}
						<a href="?action=admin_manageuser_extern&order=mobileno&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">Handynr.</a> <img src="images/s_desc.png">
					{else}
						<a href="?action=admin_manageuser_extern&order=mobileno&type=desc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">Handynr.</a> <img src="images/s_asc.png">
					{/if}
			      {else}
					<a href="?action=admin_manageuser_extern&order=mobileno&type=asc&g={$smarty.get.g}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="linklist">Handynr.</a>
			      {/if}
			      </td>					

					<td align="center" width="80">{#Edit#}</td>
					<td align="center" width="80">{#Delete#}</td>
                            </tr>
						  {foreach key=key from=$userrec item=userdata}
						  <tr  bgcolor="{cycle values="#eeeeee,#d0d0d0"}">
							<td>{if $userdata.picturepath ne ''}<img src="images/has_pic.jpg">{/if}</td>
							<form method="POST" action="">
							<td bgcolor="#EEEEEE">
								{*if $userdata.type eq 2}
								<img src="images/gold.gif" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}><br>
								<img src="images/gold.gif" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}><img src="images/gold.gif" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}>
								{elseif $userdata.type eq 3}
								<img src="images/silber.gif" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}><img src="images/silber.gif" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}>
								{elseif $userdata.type eq 4}
								<img src="images/bronze.gif" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}>
								{else}
									<strong>A</strong>
								{/if*}

								<span id="type_first_{$userdata.user_id}">
								<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td>{$userdata.type}
									<a href="javascript:void(0)" onclick="$('type_span_{$userdata.user_id}').style.display = 'block'; $('type_first_{$userdata.user_id}').style.display = 'none'" class="link">{$text_edit}
									{if $userdata.type eq 2}
									<img src="images/gold.gif" border="0" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}><img src="images/gold.gif" border="0" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}><img src="images/gold.gif" border="0" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}>
									{elseif $userdata.type eq 3}
									<img src="images/silber.gif" border="0" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}><img src="images/silber.gif" border="0" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}>
									{elseif $userdata.type eq 4}
									<img src="images/bronze.gif" border="0" width="15"{if $userdata.end_date} alt="Gültig bis: {$userdata.end_date}"{/if}>
									{else}
										<strong>A</strong>
									{/if}
									</a>
									</td>
								</tr>
								</table>
								</span>
								<span id="type_span_{$userdata.user_id}" style="display: none">
								<select name="type">
									{html_options options=$type_box selected=$userdata.type}
								</select>
								<input name="id" type="hidden" value="{$userdata.user_id}">
								<input name="change_type" type="submit" value="OK">
								</span>
							</td>
							</form>
							<td><div align="left">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="linklist1">{$userdata.username}</a></div></td>
							<td width="100px">{$userdata.registred}</td>
							<td width="100px">{$userdata.city}</td>
<!--							<td width="100px">{$userdata.state}</td>  -->
							{if $userdata.country eq Deutschland}
								<td width="20px">DE</td>				
							{elseif $userdata.country eq Schweiz}
								<td width="20px">CH</td>
							{else}
								<td width="20px">AT</td>							
							{/if}							
							{if $userdata.flag == 1}
								<td align="center">Ja</td>
							{else}
								<td align="center">Nein</td>
							{/if}							
							{if $userdata.fake == 1}
								<td align="center">---</td>
							{else}
								<td>{$userdata.mobileno}</td>
							{/if}
						    <td width="80">
							  <div align="center">
							  <a href="?action=editprofileextern&user={$userdata.username}&proc=edit&from=admin">
							  <img src="images/icon/b_edit.png" width="16" height="16" border="0"></a> </div></td>
						  <td width="80">
							  <div align="center">
							  {if $userdata.status != 1}
							  <a href="?action=admin_manageuser_extern&user={$userdata.username}&proc=del&page={$smarty.get.page}" onclick="return confirm(confirm_delete_box)">
							  <img src="images/icon/b_drop.png" width="16" height="16" border="0">
							  </a>
							  {else}
							  <img src="images/icon/b_drop_disable.png" width="16" height="16">
							  {/if}
							  </div></td>
						</tr>
						  {/foreach}
                          </table></td>
                        </tr>
			<tr>
				<td height="5px"></td>
			</tr>
                        <tr>
                          <td align="left">{paginate_prev class="linklist"} {paginate_middle class="linklist"} {paginate_next class="linklist"}&nbsp;</td>
                        </tr>
                  </table></td></tr> 
			</table>
		</td>
	</tr>
	<tr id="trList" style="display:{'none'} ">
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td height="20px"></td>
	</tr>
</table>