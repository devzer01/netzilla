<h5 class="title">{#Edit_Profile#}</h5>
{literal}
<script type="text/javascript" language="javascript">
function removePic()
{
	{/literal}
	{if $smarty.session.sess_admin eq "1" and $smarty.get.from eq "admin"}
		parent.location = "?action=editprofile&type=removepic&user={$smarty.get.user}&proc={$smarty.get.proc}";
	{else}
		parent.location = "?action=editprofile&type=removepic";
	{/if}
	{literal}
}
function updateEdit_datetime()
{
	ajaxRequest("updateEdit_datetime", "username={/literal}{$save.username}{literal}", "", "updateLoop", "reportError");
}

function updateLoop(originalRequest)
{
	if(originalRequest.status == 200)
		setTimeout("updateEdit_datetime()", 30000);
}

updateEdit_datetime();
</script>
{/literal}

<form id="editProfile" enctype="multipart/form-data" name="editProfile" method="post" action="">

<ul id="container-profile-list" style="float:left; margin-bottom:5px;">
    <li>
    {if $save.picturepath neq ""}
    <a href="javascript:if(confirm('Are you sure to remove foto?')) removePic();">
        <div class="profile-list">
           <div class="boder-profile-img"><img src="images/cm-theme/delete-boder-img.png" width="120" height="121" /></div>
           <div class="img-profile">
                <img src="thumbnails.php?file={$save.picturepath}&w=112&h=113" width="112" height="113" border="0">
          </div>
      </div>
    </a>
 
    {else}
    <a href="#">
        <div class="profile-list">
           <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
           <div class="img-profile">
                <img src="thumbnails.php?file=default.jpg" width="112" height="113" border="0">
          </div>
      </div>
    </a>
    {/if}
        
    </li>
</ul>



<div style=" width:847px; height:auto; float:left; margin:10px; padding:10px; border-left:1px solid rgba(0,0,0,0.1);">

<b>Email:{$save.email}</b>
<input name="" type="checkbox" value=""  checked="checked"/> Receive all message on mobile phone. (Please verify your Phone number)

<div class="line-txt-profile-edit">
<strong class="txt-profile-edit">{#Gender#}:</strong>
{html_radios id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
<!--<div id="gender_info" class="error_info"></div> -->
</div>

<div class="line-txt-profile-edit">
<strong class="txt-profile-edit">{#Birthday#}:</strong>
{html_options id="date" name="date" options=$date selected=$save.date onchange="getZodiac(document.getElementById('date').value, document.getElementById('month').value)"}
{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value), getZodiac(document.getElementById('date').value, document.getElementById('month').value)" selected=$save.month} 
{html_options id="year" name="year" options=$year onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year}
</div>

<div class="line-txt-profile-edit">
<strong class="txt-profile-edit"><!--{#Country#} -->Land:</strong>
{html_options id="country" name="country" options=$country selected=$save.country style="width:155px" onchange="loadOptionState('state', this.options[this.selectedIndex].value, '');loadOptionCity('city', $('state').options[$('state').selectedIndex].value,''); checkNullSelectOption(this)"}
<!--<div id="country_info" class="error_info"></div> -->
</div>

<div class="line-txt-profile-edit">
<strong class="txt-profile-edit"><!--{#City#} -->PLZ:</strong>
{html_options id="city" name="city" options=$city selected=$save.city style="width:155px" onchange="checkNullSelectOption(this)"}
<!--<div id="city_info" class="error_info"></div> -->
</div>

<div style="width:846px; height:auto;">
<strong class="txt-profile-edit">{#Your#} {#Description#}:</strong>
<textarea id="description" name="description" columns="20" rows="12" style="width:450px" class="textarea" onkeyup="checkNullTextArea(this, 'Please tell us abit more about yourself.')" onblur="checkNullTextArea(this, 'Please tell us abit more about yourself.')">{$save.description|stripslashes}</textarea>	
<!--<div id="description_info" class="error_info" style="font-weight: bold;"></div> -->
</div>

<div>
{if $smarty.session.sess_admin eq "1" and $smarty.get.from eq "admin"}
<strong class="txt-profile-edit">{#Select_Image#}:</strong>
<input type="text" id="picturepath" name="picturepath" value="{$save.picturepath}" readonly="readonly" /> 
<input type="button" value="Browse" onclick="window.open('?action=image_dir', 'popup', 'location=0,status=1,scrollbars=1,width=500,height=500,resizable=1')" />					
{/if}
<strong class="txt-profile-edit">{#Select_Image#}:</strong>
<input name="upload_pic" type="hidden" value="yes" />
<input name="picturepath" type="file" id="picturepath" style="width:350px;" />
<br />
{#Images_policy#}
</div>

<div>
<input type="hidden" name="submit_button" value="1" />
<a href="javascript: void(0)" onclick="if(callNullEditprofile()) $('editProfile').submit()" class="btn-yellow-left">{#SUBMIT#}</a>
<a href="{if ($smarty.session.sess_admin eq "1") and ($smarty.get.from eq "admin")}{$smarty.session.SmartyPaginate.default.url}{else}index.php{/if}" class="btn-yellow-left">{#BACK#}</a>
</form>
</div>

</div>

{literal}
<script language="javascript" type="text/javascript">
country_select = "{/literal}{$save.country}{literal}";
state_select = "{/literal}{$save.state}{literal}";
city_select = "{/literal}{$save.city}{literal}";
fileExtension = "{/literal}{$config_image}{literal}";
fileDescription = "Images ({/literal}{$config_image}{literal})";
ajaxRequest("loadOptionCountry", "", "", "loadOptionCountry1", "reportError");
getNumDate("date", $("month").options[$("month").selectedIndex].value, $("year").options[$("year").selectedIndex].value);
InitFlashObj();
</script>
{/literal}



<script>
var picturepath_obj = document.getElementById("picturepath");
</script>
