{literal}
<script>
jQuery(function(){loadFavorite("favorite-list-container", '{/literal}{$style}{literal}');});
</script>
{/literal}

{if $style eq '2'}
<div style="display: none; background:url(images/cm-theme/bg-box-content.png) repeat-x 0 10px; float:left; width:1020px;">
<h5 class="title">{#FAVOURITES#}</h5>
<span id="favorite-list-container"></span>
</div>
{else}
<div id="container-favorite" style="display: none;">
<h1 class="title">{#FAVOURITES#}</h1>
<span id="favorite-list-container"></span>
</div>
{/if}