<div class="result-box">
	<h1>{#Foto_Album#}</h1>
	<div class="result-box-inside-nobg">
		<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td align="center">
					{foreach from=$fotoalbum item=item name="fotoalbum"}
					<div class="cardlist">
					<a href="thumbnails.php?file={$item.picturepath}" class='lightview' rel='gallery[mygallery]' title=""><img src="thumbnails.php?file={$item.picturepath}&w=112" width="112"/></a><br/>
					{if $smarty.get.action eq "fotoalbum"}
					<form id="form_pic{$smarty.foreach.fotoalbum.index+1}" name="form_pic{$smarty.foreach.fotoalbum.index+1}" method="post" action="">
					<input type="hidden" id="fotoid" name="fotoid" value="{$item.id}" />
					<input type="hidden" id="delete_button" name="delete_button" value="delete" />
					<a href="#" onclick="if(confirm('You would like to really delete the selected photo?')) form_pic{$smarty.foreach.fotoalbum.index+1}.submit();" class="cardbutton">{#delete#}</a>
					</form>
					{/if}
					</div>
					{/foreach}
			</td>
		</tr>
		</table>

		{if $smarty.get.action eq "fotoalbum"}
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
			<tr>
				<td height="30px"></td>
			</tr>
			<tr>
				<td align="center">
				{if $total >= 10}
					{#Full_Fotoalbum#}
				{else}
					<form id="upload_foto_form" name="upload_foto_form" method="post" action="" enctype="multipart/form-data">
					<table border="0" cellpadding="0" cellspacing="0" width="90%">
					<tr>
						<td width="100%">
							{#Upload_your_picture#} <input type="file" id="upload_file" name="upload_file"/> 
						</td>
					</tr>
					<tr><td height="10"></td></tr>
					<tr>
						<td width="100%">
							<input id="upload_button" name="upload_button" type="submit" value="Upload" class="button" />
							{if $text neq ""}
							<br /><br />
							<div><font color="#CC3333"><b>{$text}</b></font></div>
							{/if}
						</td>
					</tr>
					</table>
					</form>
				{/if}
				</td>
			</tr>
			<tr>
				<td height="20px"></td>
			</tr>
		</table>
		{/if}
	</div>
</div>