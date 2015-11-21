{include file='private/header.tpl'}
		<div class="container-content">
        {include file='private/profile_menu.tpl'}
		{include file='private/banner.tpl'}

<!-- start add foto -->
        <div class="container-profile-content">
        
                <ul class="container-profile-icon">
                    <li>
                        <div class="container-profile-icon">
                            <a href="#"><div class="border-profile-icon"></div></a>
                            <img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$member.picturepath}&w=112&h=113" width="112" height="113" />
                        </div>
                        <a href="#" class="btn-del-foto"></a>
                    </li>
                </ul>
                
                <div class="profile-content-add-foto">
                    <div class="container-add-foto-profile">
                    	<form id='fileupload' action='{$smarty.const.APP_PATH}/profile/picture' method='post' enctype='multipart/form-data'>
                        	<strong>Dein Bild hochladen</strong> 
                        	<input type="file" id="profilepic" name="profilepic">
                        	<a href="#" id='upload-foto' class="btn-upload-foto">Upload</a>
                        </form>
                    </div>
                </div>
        </div>
        <!-- -->
        <br class="clear" />
        <!-- start box content -->
            <div class="box-content">
            	<form id='profile' name='profile' method='post' action='{$smarty.const.APP_PATH}/profile/edit'>
            	<h1>Profil editieren</h1>
              <div class="container-edit-profile">
                <label class="edit-profile-left"><strong>Geschlecht:</strong></label>
                <label>
                	<select name="gender" class="formfield_01">
                    	{html_options options=$gender selected=$member.gender}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Geburtstag:</strong></label>
                <label>
                	<select name="day" class="formfield_01 date">
                    	{html_options options=$day selected=$member.bday}
                    </select>
                    <select name="month" class="formfield_01 month">
                    	{html_options options=$month selected=$member.bmonth}
                    </select>
                    <select name="year" class="formfield_01 year">
                    	{html_options options=$year selected=$member.byear}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Nationalität:</strong></label>
                <label>
                	<select id='country' name="country" class="formfield_01">
                    	{html_options options=$countries selected=$member.country}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Land/Kanton:</strong></label>
                <label>
                	<select id='state' name="state" class="formfield_01">
                    	{html_options options=$states selected=$member.state}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Stadt:</strong></label>
                <label>
                	<select id='city' name="city" class="formfield_01">
                    	{html_options options=$cities selected=$member.city}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Männer:</strong></label>
                <label>
                	<select name="lookmen" class="formfield_01">
                    	{html_options options=$yesno selected=$member.lookmen}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Frauen:</strong></label>
                <label>
                	<select name="lookwomen" class="formfield_01">
                    	{html_options options=$yesno selected=$member.lookwomen}
                    </select>
                </label>
                <br class="clear" />
				<div class="container-description">
                	<strong>Deine Erste Kontaktanzeige:</strong><br class="clear" />
                    <textarea name="description" class="formfield_01">{$member.description}</textarea>
                </div>
                <br class="clear" />
                <a href="#" id='saveprofile' class="btn-upload-foto">Abschicken</a>
                </div>
				</form>
            </div>
            <!-- end box content -->
            
            <!-- start box content -->
            <div class="box-content-03">
            	<form id='password' action='{$smarty.const.APP_PATH}/ajax/password'>
            	<h1>Passwort ändern</h1>
              <div class="container-edit-password">
                <label class="edit-profile-left"><strong>Old Passwort:</strong></label>
                <label>
                	<input name="current" type="password" class="formfield_01"/>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>New Passwort:</strong></label>
                <label>
                	<input id='newpass' name="new" type="password" class="formfield_01"/>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Wiederholungs-New Passwort:</strong></label>
                <label>
                	<input id='confirmnew' name="confirmnew" type="password" class="formfield_01"/>
                </label>
                <br class="clear" /> 
                <a href="#" id='changepass' class="btn-upload-foto">Abschicken</a>
                </div>
                 </form>
            </div>
            <!-- end box content -->
        
        </div>
 <script type='text/javascript'>
 	$(function (e) {
 		$("#upload-foto").click(function (e) {
 			$("#fileupload").submit();
 		});
 		
 		$("#country").change(function (e) {
 			e.preventDefault();
 			$.get("{$smarty.const.APP_PATH}/ajax/state/" + $(this).val(), function (json) {
 				if (json.status == 0) {
 					$("#state").html("");
 					$.each(json.states, function (k, v) {
 						$("#state").append("<option value='" + v.id + "'>" + v.name + "</option>");
 					});
 				}
 			});
 		});
 		
 		$("#state").change(function (e) {
 			e.preventDefault();
 			$.get("{$smarty.const.APP_PATH}/ajax/city/" + $(this).val(), function (json) {
 				if (json.status == 0) {
 					$("#city").html("");
 					$.each(json.cities, function (k, v) {
 						$("#city").append("<option value='" + v.id + "'>" + v.name + "</option>");
 					});
 				}
 			});
 		});
 		
 		$("#saveprofile").click(function (e) {
 			e.preventDefault();
 			$("#profile").submit();
 		});
 		
 		$("#changepass").click(function (e) {
 			e.preventDefault();
 			if ($("#newpass").val() != $("#confirmnew").val()) {
 				alert("Passwort not match");
 				return;
 			}
 			$.ajax({
 				url: app_path + '/ajax/password',
 				data: $("#password").serialize(),
 				type: 'post',
 				dataType: 'json',
 				success: function (json) {
 					if (json.status == 1) {
 						alert("Password Not Changed");
 					} else {
 						alert("Passwort Changed");
 					}
 				}
 			});
 		});
 	});
 </script>
 {include file='private/footer.tpl'}