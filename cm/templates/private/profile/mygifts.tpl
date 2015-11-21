 {include file='private/header.tpl'}
		<div class="container-content">
        {include file='private/profile_menu.tpl'}
		{include file='private/banner.tpl'}

 
 <!-- start box content-->
            <div class="box-content">
                <h1>Meine Geschenke</h1>
                
                <!--start -->
                {foreach from=$mygifts item=gift}
                <div class="container-gift">
                    <ul class="container-profile-icon">
                        <li>
                            <div class="container-profile-icon">
                                <a href="#"><div class="border-profile-icon"></div></a>
                                <img src="{$smarty.const.URL_WEB}/{$gift.info.image_path}" width="112" height="113" />
                            </div>
                        </li>
                    </ul>
                    <div class="container-user-send-gift">
                    	<ul class="user-send-gift">
                    		{foreach from=$gift.senders item=sender}
                        		<li><a href="{$smarty.const.APP_PATH}/profile/view/{$sender.sender}"><img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$sender.picturepath}&w=46&h=46" height="46" /><span>{$sender.sender}</span><div class="user-send-num">{$sender.times}</div></a></li>
                        	{/foreach}
                        </ul>
                    </div>
                 </div>
                 {/foreach}
                 <!--end -->
            </div>
        <!-- end box content -->
        </div>
        <!--end -->
 {include file='private/footer.tpl'}