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
<div style=" width:1020px; height:auto; padding:0 -webkit-border-bottom-right-radius: 15px; -webkit-border-bottom-left-radius: 15px; -moz-border-radius-bottomright: 15px;-moz-border-radius-bottomleft: 15px; border-bottom-right-radius: 15px; border-bottom-left-radius: 15px; background:url(images/cm-theme/bg-box-content.png); float:left;">
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
        <div class="img-profile"><img src="thumbnails.php?file={$item.picturepath}&w=112&h=113" width="112" height="113"/></div>
    </div>
    </a>
    <div class="container-quick-icon-foto">
    <a href="#" class="quick-icon-left del-icon" title="Delete" onclick="return deletePhoto({$item.id}{if $item.approval}, 'APPROVAL'{/if})"></a>
    </div>
</li> 
{/foreach} 
</ul>
{/if}
</div></div>