{literal}
<script>
jQuery(function(){loadFavorite("favorite-list-container", '{/literal}{$style}{literal}');});
</script>
{/literal}

{if $style eq '2'}
<div id="container-content" style="display: none;">
<h1>{#FAVOURITES#}</h1>
<span id="favorite-list-container"></span>
</div>
{else}
<div id="container-content" style="display: none;">
<h1>{#FAVOURITES#}</h1>
<span id="favorite-list-container"></span>
</div>
{/if}