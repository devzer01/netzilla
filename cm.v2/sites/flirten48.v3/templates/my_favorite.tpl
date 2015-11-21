{literal}
<script>
jQuery(function(){loadFavorite("favorite-list-container", '{/literal}{$style}{literal}');});
</script>
{/literal}

{if $style eq '2'}
<!--show profile page -->
<div>
<h5 class="title">{#FAVOURITES#}</h5>
<span id="favorite-list-container" class="fav-profile-page"></span>
</div>
<!--end show profile page -->
{else}
<br class="clear" />
<div id="container-content">
<h1 class="title">{#FAVOURITES#}</h1>
<span id="favorite-list-container"></span>
</div>
{/if}