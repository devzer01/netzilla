{include file='private/header.tpl'}
<!--start -->  
        <div class="container-content">
        {include file='private/profile_menu.tpl'}
        {include file='private/banner.tpl'}
        <!-- -->
        	<ul class="container-search">
            	<li><a href="{$smarty.const.APP_PATH}/search/newest"><img src="images/news.png" width="114" height="134" /><span>Neueste</span></a></li>
                <li><a href="{$smarty.const.APP_PATH}/search/spec/m4w"><img src="images/man.png" width="114" height="134" /><span>Mann sucht Frau</span></a></li>
                <li><a href="{$smarty.const.APP_PATH}/search/spec/m4m"><img src="images/gay.png" width="114" height="134" /><span>Mann sucht Mann</span></a></li>
                <li><a href="{$smarty.const.APP_PATH}/search/online"><img src="images/online.png" width="114" height="134" /><span>Online</span></a></li>
                <li><a href="{$smarty.const.APP_PATH}/search/spec/w4m"><img src="images/women.png" width="114" height="134" /><span>Frau sucht Mann</span></a></li>
                <li><a href="{$smarty.const.APP_PATH}/search/spec/w4w"><img src="images/less.png" width="114" height="134" /><span>Frau sucht Frau</span></a></li>
            </ul>
		<!-- -->
        <br class="clear" />
        <!-- start box content -->
            <div class="box-content">
            	<h1>Suchen</h1>
                
                <div class="container-edit-profile">
                <label class="edit-profile-left"><strong>Benutzername:</strong></label>
                <label>
                   <input id='username' name="username" type="text" class="formfield_01"/>
                </label>
                <br class="clear" />
                <a href="#" id='searchuser' class="btn-search">Suche</a>
                </div>
                
                <form id='advance' method='post' action='{$smarty.const.APP_PATH}/search/'>
              <div class="container-edit-profile">
                <label class="edit-profile-left"><strong>Geschlecht:</strong></label>
                <label>
                	<select name="gender" class="formfield_01">
                    	{html_options options=$gender}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Alter:</strong></label>
                <label>
                	<select name="minage" class="formfield_01 age">
                    	{html_options options=$age}
                    </select>
                </label>
                <label>
                    <div style="float:left; width:33.33%; height:40px; line-height:40px; text-align:center; font-weight:bold;">bis:</div>
                    <select name="maxage" class="formfield_01 age">
                    	{html_options options=$age selected=50}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Nationalität:</strong></label>
                <label>
                	<select id='country' name="country" class="formfield_01">
                		<option value=''>All</option>
                    	{html_options options=$countries selected=$member.country}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Land/Kanton:</strong></label>
                <label>
                	<select id='state' name="state" class="formfield_01">
                		<option value=''>All</option>
                    	{html_options options=$states}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Stadt:</strong></label>
                <label>
                	<select id='city' name="city" class="formfield_01">
                		<option value=''>All</option>
                    	{html_options options=$cities}
                    </select>
                </label>
                <br class="clear" />
                <!-- 
                <label class="edit-profile-left"><strong>Männer:</strong></label>
                <label>
                	<select name="lookmen" class="formfield_01">
                    	{html_options options=$yesno value=$member.lookwomen}
                    </select>
                </label>
                <br class="clear" />
                <label class="edit-profile-left"><strong>Frauen:</strong></label>
                <label>
                	<select name="lookwomen" class="formfield_01">
                    	{html_options options=$yesno}
                    </select>
                </label>
                 -->
                <br class="clear" />
                <a href="#" id='searchadvance' class="btn-search">Suche</a>
                </div>
                 </form>
            </div>
            <!-- end box content -->        
        </div>
        <!--end -->
<script type='text/javascript'>
	$(function () {
		$("#searchuser").click(function () {
			window.location.href = app_path + '/search/username/' + $("#username").val();
		});
		
		$("#searchadvance").click(function () {
			$("#advance").submit();
		});
		
		$("#country").change(function (e) {
 			e.preventDefault();
 			$.get("{$smarty.const.APP_PATH}/ajax/state/" + $(this).val(), function (json) {
 				if (json.status == 0) {
 					$("#state").html("");
 					$("#state").append("<option value=''>All</option>");
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
 					$("#city").append("<option value=''>All</option>");
 					$.each(json.cities, function (k, v) {
 						$("#city").append("<option value='" + v.id + "'>" + v.name + "</option>");
 					});
 				}
 			});
 		});
	});
</script>
{include file='private/footer.tpl'}