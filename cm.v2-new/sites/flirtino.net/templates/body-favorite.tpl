<!-- {$smarty.template} -->
<div align="center">
	<table width="80%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="110" valign="top">
			<table width="100%"  border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td colspan="2" class="left_td">
						{#Name#}:<strong class="text10blackbold"> {$datas[newmen].username|regex_replace:"/@.*/":""}</strong>
					</td>
				</tr>
				<tr valign="top">
					<td height="9" colspan="2"></td>
				</tr>
				<tr> 
					<td width="50%" class="left_td">
						{#Area#}: <strong class="text10blackbold"> {$datas[newmen].area} </strong>
					</td>
					{assign var="city" value=$datas[newmen].city} 
					<td class="left_td">
						{#City#}: <strong class="text10blackbold">{$city}</strong>
					</td>
				</tr>
				<tr valign="top">
					<td height="9" colspan="2"></td>
				</tr>
				{assign var="thisY" value=$datas[newmen].birthday|date_format:"%Y"}  
				{assign var="Age" value="`$year-$thisY`"}
				<tr valign="top">
					<td class="left_td">
						{#Age#}: <strong class="text10blackbold"> {$Age} {#Year#} </strong>
					</td>
					<td class="left_td">
						{#Civil_status#}: <strong class="text10blackbold"> {$datas[newmen].civilstatus} </strong>
					</td>
				</tr>
				<tr valign="top">
					<td height="9" colspan="2"></td>
				</tr>
				<tr valign="top">
					<td width="36%" class="left_td">
						{#Height#}: <strong class="text10blackbold">{$datas[newmen].height}</strong>
					</td>
					<td width="70%" class="left_td">
						{#Appearance#}: <strong class="text10blackbold">{$datas[newmen].appearance}</strong>
					</td>
				</tr>
				<tr valign="top">
					<td height="9" colspan="2"></td>
				</tr>
				<tr valign="top">
					<td height="35" colspan="2" class="left_td">
						{#Description#}: <strong class="text10blackbold"> {$datas[newmen].description|nl2br|truncate:40:"...":true|stripslashes}</strong>
					</td>
				</tr>
			</table>
			</td>
				<td width="30%">
				<div align="right">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td width="100%">							    
						<table width="78" border="1" align="right" cellpadding="0" cellspacing="0" bordercolor="#CC99CC">
							<tr>
								<td width="78" height="98" bgcolor="#FFFFFF"align="right"> 
								{if $datas[newmen].picturepath != ''}
								<img src="thumbnails.php?file={$datas[newmen].picturepath}&w=90&h=103" width="90" height="103"> 
								{else}
								<img src="thumbs/default.jpg" >
								{/if}
								</td>
							</tr>
						</table>
						</td>
						<td><img src="images/dot.gif" width="17" height="1" border="0"></td>			        
					</tr>
				</table>
				</div>
				</td> 
			</tr>
			<tr>
				<td colspan="2">
				<table width="100%"  border="0" cellspacing="0" cellpadding="2">
				<tr>
				<td colspan="2" height="9"></td>
				</tr>
				<tr>
				{if $smarty.session.sess_username != ''}
					<td colspan="2" >
					<table width="100%"  border="0" cellspacing="0" cellpadding="2">
						<tr>
							<td width="25%">
							<div align="center">
							  <table width="110" height="15" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="110" height="15" align="center" background="images/bgfavor.gif"><a href="#" class="link" onclick="if(confirm(confirm_delete_box)) goUrl('?action=favorite&do=del&searchChar={$smarty.get.searchChar}&username={$datas[newmen].username}');">{#Remove#}</a></td>
                                </tr>
                              </table>
							</div>							</td>
						
							<td >&nbsp;</td>
							<td width="25%">
							<table width="110" height="15" border="0" cellpadding="0" cellspacing="0">
								<tr>
								<td width="110" height="15" align="center" background="images/bgfavor.gif">
								{ if $smarty.session.sess_mem=='1'}
									<a href="?action=viewprofile&username={$datas[newmen].username}" class="link">{#Member_profile#}</a>
								{else}
									<a href="#" class="link" onClick="return GB_show('Nur für Mitglieder', 'alert.php', 170, 420)">{#Member_profile#}</a>
								{/if}</td>
								</tr>
							</table>							</td> 
							<td >&nbsp;</td>
							<td width="25%">
							<div align="center">
								<table width="110" height="15" border="0" cellpadding="0" cellspacing="0">
								<tr>
								<td width="110" height="15" align="center" background="images/bgfavor.gif">
								{ if $smarty.session.sess_mem=='1'}
										<a href="?action=lonely_heart_ads_view&username={$datas[newmen].username}" class="link">{#Member_ADs#}</a>
										{else}
										<a href="#" class="link" onClick="return GB_show('Nur für Mitglieder', 'alert.php', 170, 420)">{#Member_ADs#}</a>
								{/if}	</td>
								</tr>
								</table>	
								
							</div>							
							</td>
							<td >&nbsp;</td>
							<td width="25%">
							<table width="110" height="15" border="0" cellpadding="0" cellspacing="0">
								<tr>
								<td width="110" height="15" align="center" background="images/bgfavor.gif">
								{ if $smarty.session.sess_mem=='1'}
									<a href="?action=mymessage&type=writemessage&username={$datas[newmen].username}" class="link">{#Mail_to#}</a>
									{else}
									<a href="#" class="link" onClick="return GB_show('Nur für Mitglieder', 'alert.php', 170, 420)">{#Mail_to#}</a>
								{/if}		</td>
								</tr>
								</table>	
							</td>
						</tr>
					</table>
					</td>
				{/if}						  
				</tr>
			</table>
			</td>
		</tr>
	</table>
</div>

