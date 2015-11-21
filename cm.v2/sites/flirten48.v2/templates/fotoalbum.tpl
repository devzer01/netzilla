<!-- {$smarty.template} -->
<script>
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
<div id="container-photo-gallery">
	<div class="title">
    	<div class="title-left"></div><h1>{#Foto_Album#}</h1><div class="title-right"></div>
    </div>

	{if $smarty.get.action eq "fotoalbum"}
    {if $total >= 10}
        <strong>{#Full_Fotoalbum#}</strong><br />
    {else}
		<form id="upload_foto_form" name="upload_foto_form" method="post" action="?action=fotoalbum" enctype="multipart/form-data">
        	<div class="text-upload"><strong>{#Upload_your_picture#}</strong>
            <input type="file" id="upload_file" name="upload_file"/><br />
            {#Images_policy#}<br />
        	<a href="#" id="" onclick="jQuery('#upload_foto_form').submit(); return false;" value="" class="btn-upload">Upload</a>
        	{if $text neq ""}<br />
        		{$text}
        	{/if}
            <br class="clear" />
            <!--<a href="http://blueimp.github.com/jQuery-File-Upload/" style="color:#F00; font-size:16px;">Sample</a> -->
            </div>
        </form>
	{/if}
{/if}


{if count($fotoalbum)}
{literal}
<style>
#container-photoalbum-list{ margin:10px 0 10px 0 !important;}
#container-photoalbum-list li{margin-left:26px !important; margin-top:10px !important;}
</style>
{/literal}
<ul id="container-photoalbum-list" class="container-photo" style="float:left;">
{foreach from=$fotoalbum item=item name="fotoalbum"}
<li>

	<img src="thumbnails.php?file={$item.picturepath}&w=102&h=102" width="102" height="102" />
    <a href="thumbnails.php?file={$item.picturepath}" class="link-profile lightview{if $item.approval} link-profile-approval{/if}" rel='gallery[mygallery]'></a>
    <a href="#" class="q-left q-del" onclick="return deletePhoto({$item.id}{if $item.approval}, 'APPROVAL'{/if})"></a>

</li> 
{/foreach} 
</ul>
{/if}
</div>