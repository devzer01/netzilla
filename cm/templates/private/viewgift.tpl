 {include file='private/header.tpl'}
 <!--start -->  
        <div class="container-content">
        {include file='private/profile/top.tpl' desc="n"}        
        <!-- start box content-->
            <div class="box-content">
                <h1>Geschenke</h1>                
                <ul class="container-foto-list-icon">
                	{foreach from=$gifts item=gift}
                    <li>
                        <div class="container-foto-icon">
                            <a href="#"><div class="border-profile-icon"></div></a>
                            <img src="{$smarty.const.URL_WEB}/{$gift.image_path}" width="112" height="113" />
                        </div>
                    </li>
                    {foreachelse}
                    	<li><p class="alert">Hier können sie ihre versendeten Geschenke an diesen User sehen!</p></li>
                    {/foreach}
                </ul>
            </div>
        <!-- end box content -->
        </div>
        <!--end -->
        
{include file='private/footer.tpl'}