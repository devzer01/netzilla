<!-- {$smarty.template} -->
<script type='text/javascript'>
{literal}
function deletePhoto(id, approval)
{
	if(confirm('Bist du sicher, dass du dieses Foto entfernen willst?'))
	{
		jQuery.post("ajaxRequest.php",{"action": "deletePhoto", "fotoid": id, "approval": approval}, function(data){getPage('?action=fotoalbum', 'contentDiv');});
	}
	return false;
}
{/literal}
</script>

	<div class="container-favoriten">
        <h1 class="favoriten-title">Fotoalbum</h1>
        <div class="upload-file-foto">
        {if $smarty.get.action eq "fotoalbum"}
		    {if $total >= 10}
		        <strong>{#Full_Fotoalbum#}</strong><br />
		    {else}
				<form id="upload_foto_form" name="upload_foto_form" method="post" action="?action=fotoalbum" enctype="multipart/form-data" 
                style="display:block;">
		        	<div style="float:left;">
                    <strong>{#Upload_your_picture#}</strong>
		            <input type="file" id="upload_file" name="upload_file" style="width:180px;"/>
                    </div>
		        	<a href="#" id="" onclick="jQuery('#upload_foto_form').submit(); return false;" value="" class="btn-search">Upload</a>
		        	{if $text neq ""}<br />
		        		{$text}
		        	{/if}
		            <br class="clear" />
		        </form>
			{/if}
		{/if}
          
*Hochgeladene Bilder dürfen nur dich zeigen, Bilder von anderen Personen, von Personen unter 18 Jahren oder mit jedem anderen Inhalt werden entfernt.
		</div>
            <ul class="container-profile-list">
            	
            	{foreach from=$fotoalbum item=item name="fotoalbum"}
				<li>
                	<div>
                		{if $item.approval}
                			<img src="images/cm-theme/approve.png" width="141" height="170" style=" z-index:2; "/>
                		{/if}
                		<img src="thumbnails.php?file={$item.picturepath}&w=141&h=170" width="141" height="170" style=" z-index:1; "/>
                		{if $item.approval} 
                			<img src="images/cm-theme/profile-list-bg.png" width="145" height="145" style=" z-index:3; " />
                		{else}
	                		<a href="thumbnails.php?file={$item.picturepath}" class="fancybox" rel="group">
								<img src="images/cm-theme/profile-list-bg.png" width="145" height="145" style=" z-index:3; " />
	                		</a>
	                	{/if}
                	</div>
                	<a href="#" class="btn-del" onclick="return deletePhoto({$item.id}{if $item.approval}, 'APPROVAL'{/if})"><span>Delete</span></a>
                </li>
				{/foreach}
			</ul>
            <!-- -->

            <!-- -->
            <br class="clear" />
        </div>