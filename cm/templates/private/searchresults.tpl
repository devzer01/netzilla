 {include file='private/header.tpl'}
		<div class="container-content">
        {include file='private/profile_menu.tpl'}
		{include file='private/banner.tpl'}
 
 <!-- start box content -->
            <div class="box-content">
            	<h1>Suchergebnis</h1>
                 <ul id='sresult' class="container-profile-list-icon">
                 	{foreach from=$members item=member}
                    <li>
                        <div class="container-profile-icon">
                            <a href="{$smarty.const.APP_PATH}/profile/view/{$member.username}"><div class="border-profile-icon"></div></a>
                            <img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$member.picturepath}&w=112&h=112" width="112" height="113" />
                        </div>
                        <span>{$member.username}</span>
                    </li>
                    {foreachelse}
                    	<li><div>Keine Suchergebnisse gefunden</div></li>
                    {/foreach}
                </ul>
                <a href='#' id='more'>More</a>
            </div>
            <!-- end box content -->
        </div>
        
        <script type='text/javascript'>
        	$(function () {
        		$("#more").click(function (e) {
        			$.get(app_path + "/search/more/", function (html) {
        				$("#sresult").append(html);
        			});
        		});
        	});
        </script>
        
{include file='private/footer.tpl'}