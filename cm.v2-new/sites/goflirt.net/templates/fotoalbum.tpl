<h1 class="title">{#Foto_Album#}</h1>
{if $smarty.get.action eq "fotoalbum"}
<div style="padding:20px;">
    {if $total >= $smarty.const.MAX_PHOTOS}
        <strong>{#Full_Fotoalbum#}</strong><br />
    {else}
		{if $text neq ""}<br />
			{$text}<br/>
		{/if}
		{#Images_policy#}<br />
		<form id="upload_foto_form" name="upload_foto_form" method="post" action="?action=fotoalbum" enctype="multipart/form-data" style="padding:10px; float:left;">
            <input type="file" id="upload_file" name="upload_file"/ style="float:left;">
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
	<a href="thumbnails.php?file={$item.picturepath}" class="fancybox profile-icon {if $item.approval} approval{/if}" data-fancybox-group="gallery"></a>
	<img src="thumbnails.php?file={$item.picturepath}&w=110&h=92" width="110" height="92" class="profile-img"/>
    <a href="#" class="quick-icon q-photo q-del" onclick="return deletePhoto({$item.id}{if $item.approval}, 'APPROVAL'{/if})"><span>Delete</span></a>
</li> 
{/foreach} 
</ul>
{/if}
</div>