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
<h5 class="title">{#Foto_Album#}</h5>

	{if $smarty.get.action eq "fotoalbum"}
    {if $total >= 10}
        <strong>{#Full_Fotoalbum#}</strong><br />
    {else}
		<form id="upload_foto_form" name="upload_foto_form" method="post" action="?action=fotoalbum" enctype="multipart/form-data" style="padding:20px 40px 10px 40px;">
        	<strong>{#Upload_your_picture#}</strong>
            <input type="file" id="upload_file" name="upload_file"/><br />
            {#Images_policy#}<br />
        	<a href="#" id="" onclick="jQuery('#upload_foto_form').submit(); return false;" value="" class="btn-yellow-left">Upload</a>
        	{if $text neq ""}<br />
        		{$text}
        	{/if}
            <br class="clear" />
            <!--<a href="http://blueimp.github.com/jQuery-File-Upload/" style="color:#F00; font-size:16px;">Sample</a> -->
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
<ul id="container-photoalbum-list" style="float:left;">
{foreach from=$fotoalbum item=item name="fotoalbum"}
<li>
    <a href="thumbnails.php?file={$item.picturepath}" class='lightview' rel='gallery[mygallery]'>
    <div class="profile-list">
        <div class="boder-profile-img">
		{if $item.approval}
		<!-- watermark -->
		<img src="images/cm-theme/wait.png" width="120" height="121"/>
		{else}
		<img src="images/cm-theme/profile-boder-img.png" width="120" height="121"/>
		{/if}
		</div>
        <div class="img-profile"><img src="thumbnails.php?file={$item.picturepath}&w=97&h=98" width="97" height="98" class="foto-image" style="top:5px; left:5px; position:relative;"/></div>
    </div>
    </a>
    <div class="container-quick-icon-foto">
    <a href="#" class="quick-icon-left del-icon" title="Delete" onclick="return deletePhoto({$item.id}{if $item.approval}, 'APPROVAL'{/if})"></a>
    </div>
</li> 
{/foreach} 
</ul>
{/if}
</div>