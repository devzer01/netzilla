<!-- {$smarty.template} -->
<div class="register-page-box">
	<h1>{#Mobile_Verify_Code#}</h1>
	<div class="register-page-box-inside">
    <div align="center"><b style="color: orange">{$text}</b></div><br>
    <form id="mobile_verify_form" name="mobile_verify_form" method="post" action="">
    <input type="hidden" name="act" value="mobileverify" />
    <div align="center">{#Mobile_Verify_Txt#}</div><br />

    <label class="text">{#Fill_Verify#}:</label><span><input id="mobile_ver_code" name="mobile_ver_code" type="text" class="box" /></span>
    {*<label class="text"></label><span><input id="mobile_cancel" name="mobile_cancel" type="checkbox" class="input" value="1" />&nbsp;{#Cancel_Verify#}</span>*}
    <label class="text"></label><span><input type="button" class="button" name="b_mobileverify" onclick="document.getElementById('mobile_verify_form').submit(); return false;" value="{#SUBMIT#}" /></span>
    <input type="hidden" name="submit_hidden" value="1" />
    </form>
    
    <form id="resend_mobile_verify_form" name="resend_mobile_verify_form" method="post" action="">
    <input type="hidden" name="act" value="resendmobileverify" />
    <div align="center">{#Resend_Verify#}</div><br />
    <label class="text"></label><input type="button" class="button" name="b_mobileverify" onclick="document.getElementById('resend_mobile_verify_form').submit(); return false;" value="{#Resend#}" /> 
    <span><input type="hidden" name="submit_hidden" value="1" /></span>
    </form>
	</div>
</div>