{literal}
<script>
jQuery(function(){loadFavorite("favorite-list-container", '{/literal}{$style}{literal}');});
</script>
{/literal}

{if $style eq '2'}
<!--show profile page -->
<div class="container-content-02">
	<h1>{#FAVOURITES#}</h1>
	<span id="favorite-list-container" class="fav-profile-page"></span>
</div>
<!--end show profile page -->
{else}
<h1>{#FAVOURITES#}</h1>
<div style="margin:20px;" id="favorite-list-container"></div>
{/if}