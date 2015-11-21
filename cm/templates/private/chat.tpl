{include file='private/header.tpl'}
		<div class="container-content">
        {include file='private/profile_menu.tpl'}
		{include file='private/banner.tpl'}
<!-- start box content-->
            <div class="box-content">
                <h1>Nachrichten</h1>
                
                <!--start -->
                <ul class="container-chat-list">
                	{foreach from=$contacts item=contact}
                	<li class="profile-list">
                        <ul class="container-profile-icon">
                            <li>
                                <div class="container-profile-icon">
                                    <a href="{$smarty.const.APP_PATH}/chat/history/{$contact.username|username}"><div class="border-profile-icon"></div></a>
                                    <img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$contact.picturepath}" width="112" height="113" />
                                </div>
                            </li>
                        </ul>
                        
                        {if $contact.readx eq "0"}
                        	<div class="container-profile-list-name new">
                        {else}
                        	<div class="container-profile-list-name">
                        {/if}
                        	<span>{$contact.username|username}</span> <br class="clear" />
                            <ul class="container-icon-profile-list">
                            	<li class="left-coner"><a href="{$smarty.const.APP_PATH}/profile/view/{$contact.username|username}"><img src="{$smarty.const.APP_PATH}/images/s-icon-01.png"/></a></li>
                                {if $contact.isfavorite eq "1"}
                                	<li><a href="#" data-username='{$contact.username|username}' class='cunfav'><img src="images/s-icon-03.png"/></a></li>
                                {else}
                                	<li><a href="#" data-username='{$contact.username|username}' class='cfav'><img src="images/s-icon-02.png"/></a></li>
                                {/if}
                                <li class="right-coner"><a href="#" data-username='{$contact.username|username}' class='closechat'><img src="{$smarty.const.APP_PATH}/images/s-icon-05.png"/></a></li>
                            </ul>
                            <!--<a href="{$smarty.const.APP_PATH}/chat/history/{$contact.username|username}" class="btn-goto-chat"></a> -->
                        </div>
                    </li>
                    {/foreach}
                </ul>
                <!--end -->
                
            </div>
        <!-- end box content -->
        </div>
        <!--end -->
{include file='private/footer.tpl'}