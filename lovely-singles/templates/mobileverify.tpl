<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
{***************************** Start include top menu ********************************}
{*include file="top.tpl"*}
{******************************* End include top menu ********************************}
<body>
	<div class="mobileverify-page-box" style="height:270px;">
		<!--<h1>{#Register#}</h1>-->
		<div class="mobileverify-page-box-inside">
					<form id="mobile_verify_form" name="mobile_verify_form" method="post" action="" class="formfield">
						<div id="mobile_ver_code_info" style="color:orange; height:32px; text-align:center"></div>
						<div align="center" style="margin-bottom:5px;">{#Mobile_Verify_Txt#}</div>
						<label class="text2">{#Fill_Verify#}:</label>
						<span class="popup_lightview">
							<input id="mobile_ver_code" name="mobile_ver_code" type="text" style="width:208px;" class="input" onkeyup="checkNullVerifyCode(this.value)" onblur="checkNullVerifyCode(this.value)"/>
							<br clear="all"/>
						</span>
						
						<label class="text2"></label>
						<span class="popup_lightview">
							<img src="images/progress-bar.gif" />
						</span>
						

						<label class="text2"></label>
						<span class="popup_lightview">
							<input type="button" class="button" name="b_mobileverify" onclick="submitAjaxFormMobileVerify()" value="{#SUBMIT#}" />
						</span>
					</form>
					<br clear="all"/>

			
					<form id="wrongnumber_form" name="wrongnumber_form" method="post" action="" class="formfield">
						<div class="text_info" align="center"></div>
						<div align="center" style="margin-bottom:5px;">{#Is_Your_Number#|replace:'[phone_number]':$currentNumber}</div>
						<label class="text2"></label>
						<span class="popup_lightview">
							<input type="button" class="button" name="b_mobileverify" onclick="submitAjaxFormWrongnumber()" value="{#BACK#}" />
						</span>
					</form>
					<br clear="all"/>
					
					
					<form id="resend_mobile_verify_form" name="resend_mobile_verify_form" method="post" action="" class="formfield">
						<div id="resend_code_info" class="text_info" align="center"></div>
						<div align="center" style="margin-bottom:5px;">{#Resend_Verify#}</div>
						<label class="text2"></label>
						<span class="popup_lightview">
							<input type="button" class="button" name="b_mobileverify" onclick="submitAjaxFormResendVerify()" value="{#Resend#}" />
						</span>
					</form>
		</div>
	</div>
</body>
</html>