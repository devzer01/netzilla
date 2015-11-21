{if $smarty.session.sess_mem eq 1}
	{assign var="action" value="?action=viewprofile&amp;username=`$item.username`"}
{else}
	{assign var="action" value="?action=register&amp;type=membership&amp;cate=lonely&amp;username=`$item.username`"}
{/if}
<a href="{$action}" class="link-profile-img">
<div class="profile-list">
	<div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
	<div class="img-profile">
	<img border="0" src="thumbnails.php?file={$item.picturepath}&amp;w=112&amp;h=113" width="112" height="113" alt="{$item.username}"/>
	</div>
</div>
</a>

<p>{$item.username|regex_replace:"/@.*/":""}</p>
{if $smarty.session.sess_mem eq 1}
<div class="container-quick-icon">
{if $style eq '2'}
	<a href="#" class="quick-icon-left del-icon" title="{#Delete#}" onclick="removeFavorite('{$item.username}','favorite-list-container', {$style}); return false;"></a>
{else}
	{if $item.username ne $smarty.session.sess_username}
	<a href="?action=chat&username={$item.username}" class="quick-icon-left message-icon" title="Message"></a>
	{/if}
	{if ($nofavorite ne 'true') and ($item.username ne $smarty.session.sess_username)}
		{if !in_array($item.username, $favorites_list)}
		<a href="#" class="quick-icon-right fav-icon" title="Favorite" onclick="jQuery(this).remove(); return addFavorite('{$item.username}','favorite-list-container');"></a>
		{else}
		<!--ALIGN ICON HERE-->
		<!-- <div class="fav"><img src="images/cm-theme/icon-fav-g.png"/></div> -->
		{/if}
	{elseif $item.username ne $smarty.session.sess_username}
		<a href="#" class="quick-icon-right del-icon-g" title="{#Delete#}" onclick="return removeFavorite('{$item.username}','favorite-list-container')"></a>
	{/if}
{/if}
</div>
{/if}