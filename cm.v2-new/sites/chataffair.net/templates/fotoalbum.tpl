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
<h1 class="title">{#Foto_Album#}</h1>

	{if $smarty.get.action eq "fotoalbum"}
    {if $total >= 10}
        <strong>{#Full_Fotoalbum#}</strong><br />
    {else}
		<form id="upload_foto_form" name="upload_foto_form" method="post" action="?action=fotoalbum" enctype="multipart/form-data" style="padding:20px 40px 10px 40px;">
        	<strong>{#Upload_your_picture#}</strong>
            <input type="file" id="upload_file" name="upload_file"/><br />
            {#Images_policy#}<br />
        	<a href="#" id="" onclick="jQuery('#upload_foto_form').submit(); return false;" value="" class="btn-submit">Upload</a>
        	{if $text neq ""}<br />
        		{$text}
        	{/if}
            <br class="clear" />
            <!--<a href="http://blueimp.github.com/jQuery-File-Upload/" style="color:#F00; font-size:16px;">Sample</a> -->
        </form>
	{/if}
{/if}

{if count($fotoalbum)}
<div id='newest-result-container' class="image_grid portfolio_4col">
<ul id="list" class="portfolio_list da-thumbs">
{foreach from=$fotoalbum item=item name="fotoalbum"}
<li>
{if $item.approval}
<!-- watermark -->
<div style="position:absolute; z-index:5;"><img src="images/cm-theme/wait.png" width="169" height="168" /></div>
{else}
{/if}

<img src="thumbnails.php?file={$item.picturepath}&w=169&h=168" width="169" height="168"/>

<article class="da-animate da-slideFromRight" style="display: block; z-index:6">
<div style="margin-top:65px;"></div>
{if $item.approval}
<span class="del"><a href="javascript:void(0);" class="quick-icon-right del-icon-g" title="{#Delete#}" onclick="return deletePhoto({$item.id}{if $item.approval}, 'APPROVAL'{/if})"></a></span>
{else}
<span class="del"><a href="javascript:void(0);" class="quick-icon-right del-icon-g" title="{#Delete#}" onclick="return deletePhoto({$item.id}{if $item.approval}, 'APPROVAL'{/if})"></a></span>
<span class="view-foto"><a href="thumbnails.php?file={$item.picturepath}" class="quick-icon-right del-icon-g lightview" title="View" rel='gallery[mygallery]'></a></span>
{/if}



</article>
</li>
{/foreach}

</ul>
</div>
{/if}
<script type="text/javascript">
{literal}
	jQuery(function() {
		jQuery('ul.da-thumbs > li').hoverdir();
	});
{/literal}
</script>

</div>