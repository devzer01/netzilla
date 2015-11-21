<!-- {$smarty.template} -->
<table id="new_photo_details" width="95%" align="center" cellspacing="1" cellpadding="0" border="0">
<form id="frm_photo_approve" name="frm_photo_approve" method="post" action="">
<tr>
	<td width="13%" align="center">Username : {$PhotoAlbumDetails[0].username}</td>
	<td width="75%">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			{if $numPhotoAlbumDetails neq 0}
				<td>				
					{if $numPhotoAlbumDetails neq 0}
						{section name=photoalbumIndex loop=$PhotoAlbumDetails}
						{assign var=countNumber value=$countValue+1}
							<div id="photo_2">
								<div class="photo_red">
									<p>
										<a href="thumbs/{$PhotoAlbumDetails[photoalbumIndex].picturepath}" rel="lightbox">
											<img src="thumbs/{$PhotoAlbumDetails[photoalbumIndex].picturepath}" width="120" height="90" border="0">
										</a>
									</p>
								</div>	
								
								<table cellspacing="0" cellpadding="0" border="0">
								<tr>
									<td width="3">
										{if $PhotoAlbumDetails[photoalbumIndex].fsk18 eq "y"}
											<input type="checkbox" id="ch_fsk18[]" name="ch_fsk18[]" value="{$PhotoAlbumDetails[photoalbumIndex].id}" checked="checked">									
										{else}
											<input type="checkbox" id="ch_fsk18[]" name="ch_fsk18[]" value="{$PhotoAlbumDetails[photoalbumIndex].id}">
										{/if}
									</td>
									<td width="10">FSK18?</td>
								</tr>
								<tr>
									<td width="3"><input type="checkbox" id="ch_photo[]" name="ch_photo[]" value="{$PhotoAlbumDetails[photoalbumIndex].id}"></td>
									<td width="10">approve/deny?</td>
								</tr>
								</table>							
							</div>				
						{/section}
					{/if}
								
				
				</td>
			{else}	
				<td align="center">No PHOTO Upload</td>
			{/if}
		</tr>
		</table>
	</td>
	<td width="7%">
		<input type="submit" id="approve" name="approve" value="Approve" class="approve_blt">
		<input type="submit" id="deny" name="deny" value="Deny" class="denine_blt">
	</td>
</tr>
</form>
</table>