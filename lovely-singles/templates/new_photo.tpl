<table id="new_gallery" align="center" width="95%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
		<table id="new_gallery_table" align="center" cellspacing="1" cellpadding="0" border="0">
		<tr>
			<th>Photo Profile</th>
			<th>Username</th>
			<th>FSK18</th>
			<th>New Photo Type</th>
			<th colspan="2">Action</th>
		</tr>
		{if $numPhotoData > 0}
			{section name=i loop=$PhotoData}
				<form id="frm_approvephoto" name="frm_approvephoto" method="post" action="./?action=photoapprove">
				<tr>
					<td width="20%" align="center">
						{if $PhotoData[i].picturepath neq ""}
							<div id="photo_1" align="center">
								<div class="photo_green">									
									<a href="thumbs/{$PhotoData[0].picturepath}" rel="lightbox">
										<img src="thumbs/{$PhotoData[i].picturepath}" border="0">
									</a>				
								</div>
								<div>
									<table cellspacing="0" cellpadding="0" border="0">										
									<tr>
										<td>
											<input type="hidden" id="EmailChatID" name="EmailChatID" value="{$PhotoData[i].userid}">
											<input type="hidden" id="siteid" name="siteid" value="{$PhotoData[i].siteid}">
											<input type="hidden" id="ch_photo[0]" name="ch_photo[0]" value="{$PhotoData[i].id}">									
											{if $PhotoData[i].fsk18tmp eq "y"}
												<input type="checkbox" id="ch_fsk18[]" name="ch_fsk18[]" value="{$PhotoData[i].id}" checked="checked"> FSK18?												
											{else}
												<input type="checkbox" id="ch_fsk18[]" name="ch_fsk18[]" value="{$PhotoData[i].id}"> FSK18?											
											{/if}
										</td>
									</tr>								
									</table>
								</div>
							</div>
						{else}
							<div id="photo_1" align="center">
								<div class="photo_green">									
										<table width="100%" height="100" border="0">
										<tr>
											<td align="center">No Photo Profile</td>
										</tr>
										</table>								
										
								</div>								
							</div>
						{/if}
					</td>
					<td width="15%">{$PhotoData[i].username}</td>
					<td width="10%">{$PhotoData[i].fsk18}</td>
					<td width="20%">{find_photo_type person_id=$PhotoData[i].userid site=$PhotoData[i].siteid}</td>					
					<td width="12%">
						{if $PhotoData[i].photoalbume eq "y"}
							<a href="./?action=new_photo_details&EmailChatID={$PhotoData[i].userid}">Photo Album</a>						
						{/if}
					</td>
					<td width="13%">
						{if $PhotoData[i].picturepath neq ""}
							<input type="submit" id="approve" name="approve" value="Approve" class="mini_approve_blt">
							<input type="submit" id="denine" name="denine" value="Deny" class="mini_denine_blt">
						{/if}
					</td>
				</tr>	
				</form>
			{/section}
		{else}
			<tr>
				<td colspan="8">No Data.</td>
			</tr>
		{/if}
		</table>
	</td>
</tr>
<tr>
	<td align="center">
		{paginate_prev} {paginate_middle format="page" prefix=" " suffix=" " link_prefix=" " link_suffix=" "} {paginate_next}
	</td>
</tr>
</table>