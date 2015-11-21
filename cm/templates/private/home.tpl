{include file='private/header.tpl'}
	<!--start -->  
        <div class="container-content">
        {include file='private/banner.tpl'}
        
        <!-- start box content -->
            <div class="box-content-02">
                <h1>ONLINE MITGLIEDER</h1>
                <ul class="container-profile-list-icon">
                	{foreach from=$online item=member}
                    <li>
                        <div class="container-profile-icon">
                            <a href="{$smarty.const.APP_PATH}/profile/view/{$member.username}"><div class="border-profile-icon"></div></a>
                            <img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$member.picturepath}&w=120&h=120"/>
                        </div>
                        <span>{$member.username}</span>
                    </li>
                    {/foreach}
                </ul>
            </div>
        <!-- end box content -->
     
        <!-- start box content-->
            <div class="box-content">
                <h1>NEUESTE MITGLIEDER</h1>
                <ul class="container-profile-list-icon">
                    {foreach from=$newest item=member}
                    <li>
                        <div class="container-profile-icon">
                            <a href="{$smarty.const.APP_PATH}/profile/view/{$member.username}"><div class="border-profile-icon"></div></a>
                            <img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$member.picturepath}&w=120&h=120"/>
                        </div>
                        <span>{$member.username}</span>
                    </li>
                    {/foreach}
                </ul>
            </div>
        <!-- end box content -->
        </div>
        <!--end -->
{include file='private/footer.tpl'}