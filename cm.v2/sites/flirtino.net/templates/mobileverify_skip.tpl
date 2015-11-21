<!-- {$smarty.template} 33 -->
<h2>{#Register#}</h2>
<div class="register-page-box-inside">
	<div>
		<form id="mobile_verify_form" name="mobile_verify_form" method="post" action="" class="formfield">
			<div align="center"><b class="text_info">{$text}</b></div>
			<div align="center" style="margin-bottom:5px;">{#Mobile_Verify_Txt#}</div>
			<label class="text2">{#Fill_Verify#}:</label>
			<span>
				<input id="mobile_ver_code" name="mobile_ver_code" type="text" style="width:208px;" class="input" onkeyup="checkNullVerifyCode(this.value)" onblur="checkNullVerifyCode(this.value)"/>
				<br clear="all"/>
				<div id="mobile_ver_code_info" class="error_info"></div>
				<br clear="all"/>
				<img src="images/progress-bar.gif" />
			</span>
			<br clear="all"/>
		
			<label class="text2"></label>
			<span style="width:100%;">
				<input type="button" class="button-cen" name="b_mobileverify" onclick="document.getElementById('mobile_verify_form').submit(); return false;" value="{#SUBMIT#}" />
				<input type="hidden" name="submit_hidden" value="1" />
				<input type="hidden" name="act" value="mobileverify" />
			</span>
		</form>
		<br clear="all"/>
	</div>
	
	<div>
		<form id="wrongnumber_form" name="wrongnumber_form" method="post" action="" class="formfield">
			<div class="text_info" align="center">{$text2}</div>
			<div align="center" style="margin-bottom:5px;">{#Is_Your_Number#|replace:'[phone_number]':$currentNumber}</div>
			<label class="text2"></label>
			<span style="width:100%;">
				<input type="button" class="button-cen" name="b_mobileverify" onclick="document.getElementById('wrongnumber_form').submit(); return false;" value="{#BACK#}" />
				<input type="hidden" name="submit_hidden" value="1" />
				<input type="hidden" name="act" value="wrongnumber" />
			</span>
		</form>
		<br clear="all"/>
	</div>

	<div>
		<form id="resend_mobile_verify_form" name="resend_mobile_verify_form" method="post" action="" class="formfield">
			<div id="resend_code_info" class="text_info" align="center">{$text3}</div>
			<div align="center" style="margin-bottom:5px;">{#Resend_Verify#}</div>
			<label class="text2"></label>
			<span style="width:100%;">
				<input type="button" class="button-cen" name="b_mobileverify" onclick="document.getElementById('resend_mobile_verify_form').submit(); return false;" value="{#Resend#}" />
				<input type="hidden" name="submit_hidden" value="1" />
				<input type="hidden" name="act" value="resendmobileverify" />
			</span>
		</form>
	</div>
</div>