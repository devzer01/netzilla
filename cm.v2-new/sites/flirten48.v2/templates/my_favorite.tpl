{literal}
<script>
jQuery(function(){loadFavorite("favorite-list-container", '{/literal}{$style}{literal}');});
</script>
{/literal}

{if $style eq '2'}
	<!--show profile page -->
	<div>
	<div class="title">
    	<div class="title-left"></div><h1>{#FAVOURITES#}</h1><div class="title-right"></div>
    </div>
	<span id="favorite-list-container" class="fav-profile-page"></span>
	</div>
	<!--end show profile page -->
{else}
	<br class="clear" />
	<div class="container-content-full">
    	<div class="title">
        	<div class="title-left"></div><h1>{#FAVOURITES#}</h1><div class="title-right"></div>
        </div>
	<span id="favorite-list-container"></span>
</div>
{/if}