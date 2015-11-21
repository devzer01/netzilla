<!-- {$smarty.template} -->
<h1 class="admin-title">{#Add_New_User#}</h1>
<div class="register-page-box-inside">

<!--<div class="admin-register-page-box-inside"> -->
<div align="center"><b style="color: red">{$text}</b></div><br>

<form enctype="multipart/form-data" name="adduser_form" id="adduser_form" method="post" action="">			    
<div id="stepWizard">
<div id="stepPage1">					
<div align="center">{include file="admin_adduser1.tpl"}</div>
</div>
<div id="stepPage2" style="display:none ">
<div align="center">{include file="admin_adduser2.tpl"}</div>
</div>
<div id="stepPage3" style="display:none ">
<div align="center">{include file="admin_adduser3.tpl"}</div>
</div>
</div>
</form>
</div>