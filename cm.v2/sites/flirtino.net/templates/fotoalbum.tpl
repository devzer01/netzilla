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

<h1>{#Foto_Album#}</h1>
{if $smarty.get.action eq "fotoalbum"}
<div style="margin:0 20px 20px 20px;">
    {if $total >= 10}
		{#Full_Fotoalbum#}<br class="clear" />
    {else}
		{if $text neq ""}
			{$text}<br class="clear" />
		{/if}
		{#Images_policy#}<br class="clear" />
		<form id="upload_foto_form" name="upload_foto_form" method="post" action="?action=fotoalbum" enctype="multipart/form-data" style="padding:10px; float:left;">
            <input type="file" id="upload_file" name="upload_file"/ style="float:left;">
        </form>
		<a href="#" onclick="jQuery('#upload_foto_form').submit(); return false;" class="btn-login" style="margin-top:10px;">Upload</a>
		<br class="clear" />
	{/if}
</div>
{/if}

{if count($fotoalbum)}
<ul class="container-profile-list photoalbum">
{foreach from=$fotoalbum item=item name="fotoalbum"}
	<li><a href="thumbnails.php?file={$item.picturepath}" class="fancybox profile-boder {if $item.approval} approval{/if}"></a><img src="thumbnails.php?file={$item.picturepath}&w=108&h=108" width="108" height="108" class="profile-img"/>
	<a href="#" class="q-icon q-right q-del" onclick="return deletePhoto({$item.id}{if $item.approval}, 'APPROVAL'{/if})"><span>del</span></a>
	</li>
{/foreach} 
</ul>
{/if}
</div>