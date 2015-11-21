{literal}
<script>
jQuery(function(){loadFavorite("favorite-list-container", '{/literal}{$style}{literal}');});
</script>
{/literal}

{if $style eq '2'}
<!--show profile page -->
<div class="container-newest">
	<h1 class="title">{#FAVOURITES#}</h1>
	<span id="favorite-list-container" class="fav-profile-page"></span>
</div>
<!--end show profile page -->
{else}
<div class="container-newest">
	<h1 class="title">{#FAVOURITES#}</h1>
	<span id="favorite-list-container"></span>
</div>
{/if}