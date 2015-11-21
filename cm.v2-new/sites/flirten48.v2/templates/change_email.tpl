<!-- {$smarty.template} -->
<div class="container-metropopup">
	<div class="metropopup-content">
	<font style="font-size:2em; padding-bottom:2%; display:block;">Bitte gebe deine richtige Emailadresse an</font>
	<form id="change_email_form" name="change_email_form">
    <div style="margin-top:12px; margin-right:10px; float:left;">
	<input id="email" name="email" type="text" style="width:350px; margin-top:3px;" placeholder="{#Your#} {#Email#}" class="formfield_01" onkeypress="return isValidCharacterPattern(event,this.value,2)" />
    </div>
	<a href="#" onclick="submitChangeEmail(); return false" class="btn-upload">{#SUBMIT#}</a>
	</form>
    <br class="clear" />
    <span style="margin-top:10px; float:left;">(Klikce einfach ins Leere um das Fenster wieder zu schliessen!)</span>
    <br class="clear" />
	</div>
</div>