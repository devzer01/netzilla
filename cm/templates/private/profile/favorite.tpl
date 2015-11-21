{include file='private/header.tpl'}
		<div class="container-content">
        {include file='private/profile_menu.tpl'}
		{include file='private/banner.tpl'}
<!-- start box content-->
            <div class="box-content">
                <h1>Favoriten</h1>
                <ul class="container-profile-list-icon">
                	{foreach from=$favorites item=favorite}
                    <li>
                        <div class="container-profile-icon">
                            <a href="{$smarty.const.APP_PATH}/profile/view/{$favorite.username}"><div class="border-profile-icon"></div></a>
                            <img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$favorite.picturepath}"/>
                        </div>
                        <span>{$favorite.username}</span>
                        <a href="#" data-username='{$favorite.username}' class="btn-del-fav"></a>
                    </li>
                    {/foreach}
                </ul>
            </div>
{include file='private/footer.tpl'}