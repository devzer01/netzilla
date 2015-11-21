{include file='private/header.tpl'}

<!--start -->  
        <div class="container-content">
        {include file='private/profile/top.tpl' desc="Y"}
        <!-- start box content-->
            <div class="box-content">
                <h1>Fotoalbum</h1>
                
                <ul class="container-foto-list-icon">
                	{foreach from=$fotoalbum item=foto}
                    <li>
                        <div class="container-foto-icon">
                            <a href="#"><div class="border-profile-icon"></div></a>
                            <img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$foto.picturepath}&w=112&h=113" width="112" height="113" />
                        </div>
                    </li>
                    {/foreach}
                </ul>
            </div>
        <!-- end box content -->
        
        </div>
        <!--end -->
{include file='private/footer.tpl'}