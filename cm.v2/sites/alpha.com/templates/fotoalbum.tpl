<!-- {$smarty.template} -->
<script>
{literal}
function deletePhoto(id, approval)
{
	if(confirm('Are you sure to delete this photo?'))
	{
		jQuery.post("ajaxRequest.php",{"action": "deletePhoto", "fotoid": id, "approval": approval}, function(data){getPage('?action=fotoalbum', 'contentDiv');});
	}
	return false;
}
{/literal}
</script>

<h1 class="title">{#Foto_Album#}</h1>
{if $smarty.get.action eq "fotoalbum"}
<div style="padding:20px;">
    {if $total >= 10}
        <strong>{#Full_Fotoalbum#}</strong><br />
    {else}
		{if $text neq ""}<br />
			{$text}<br/>
		{/if}
		{#Images_policy#}<br />
		<form id="upload_foto_form" name="upload_foto_form" method="post" action="?action=fotoalbum" enctype="multipart/form-data" style="padding:20px 40px 10px 40px;">
            <input type="file" id="upload_file" name="upload_file"/><br />
        </form>
		<a href="#" onclick="jQuery('#upload_foto_form').submit(); return false;" class="btn-register" style="margin-top:10px;">Upload</a>
		<br class="clear" />
	{/if}
</div>
{/if}

{if count($fotoalbum)}
<ul class="container-profile-icon">
{foreach from=$fotoalbum item=item name="fotoalbum"}
<li>
	<a href="thumbnails.php?file={$item.picturepath}" class="profile-icon lightview {if $item.approval} approval{/if}" rel='gallery[mygallery]'></a>
	<img src="thumbnails.php?file={$item.picturepath}&w=110&h=92" width="110" height="92" class="profile-img"/>
    <a href="#" class="quick-icon q-photo q-del" onclick="return deletePhoto({$item.id}{if $item.approval}, 'APPROVAL'{/if})"><span>Delete</span></a>
</li> 
{/foreach} 
</ul>
{/if}
</div>