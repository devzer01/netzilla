{include file='private/header.tpl'}
		<div class="container-content">
        {include file='private/profile_menu.tpl'}
		{include file='private/banner.tpl'}
        <!--start profile -->
        <div class="container-profile-content">
            <ul class="container-profile-icon">
                <li>
                    <div class="container-profile-icon">
                        <a href="#"><div class="border-profile-icon"></div></a>
                        {if $member.foto_pending === false}
                        	<img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$member.picturepath}&w=112&h=113" width="112" height="113" />
                        {else}
                        	<img src="{$smarty.const.URL_WEB}//images/cm-theme/wait.png" width="112" height="113" />
                        {/if}
                    </div>
                </li>
            </ul>
            <div class="profile-content">
                <label><strong>Geschlecht:</strong></label><label>{$member.gender_text}</label>
                <label><strong>Geburtstag:</strong></label><label>{$member.age}</label>
                <label><strong>Nationalität:</strong></label><label>{$member.country_name}</label>
                <label><strong>Land/Kanton:</strong></label><label>{$member.state_name}</label>
                <label><strong>Stadt:</strong></label><label>{$member.city_name}</label>
            </div>
            <div class="container-profile-detail">
            <label><strong>Erste Kontaktanzeige:</strong></label>
            	{if $member.desc_pending === false}
            		<p>{$member.description}</p>
            	{else}
            		<p>wird geprüft!</p>
            	{/if}
            </div>
        </div>
        <!--end profile -->
        
        <!-- start box content-->
            <div class="box-content">
                <h1>Fotoalbum</h1>
                <!--add photo -->
                <div class="container-add-foto">
                <strong>Dein Bild hochladen</strong> 
                <form id='fileupload' method='post' action='{$smarty.const.APP_PATH}/profile/fotoalbum'>
                	<input type="file" id="upload_file" name="upload_file">
                </form>
                <p>*Hochgeladene Bilder dürfen nur dich zeigen, Bilder von anderen Personen, von Personen unter 18 Jahren oder mit jedem anderen Inhalt werden entfernt.</p>
                <a href="#" class="btn-upload-foto">Upload</a>
                </div>
                <!--end add photo -->
                <ul class="container-foto-list-icon">
                	{foreach from=$fotoalbum item=foto}
                    <li>
                        <div class="container-foto-icon">
                            <a href="#"><div class="border-profile-icon"></div></a>
                            {if isset($foto.status) && $foto.status == "2"}
                            	<img src="{$smarty.const.URL_WEB}/images/cm-theme/wait.png" width="112" height="113" />
                            {else}
                            	<img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$foto.picturepath}&w=112&h=113" width="112" height="113" />
                            {/if}
                        </div>
                        <a href="#" data-foto-id='{$foto.id}' class="btn-del-foto"></a>
                    </li>
                    {/foreach}
                </ul>
            </div>
        <!-- end box content -->
        
        </div>
        <!--end -->
 <script type='text/javascript'>
 	$(function (e) {
 		$(".btn-upload-foto").click(function (e) {
 			$('#fileupload').submit();
 		});
 	});
 </script>
{include file='private/footer.tpl'}