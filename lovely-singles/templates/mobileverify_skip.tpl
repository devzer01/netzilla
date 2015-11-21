<div class="result-box">
	<h1>{#Register#}</h1>
		<div class="register-page-box-inside">
			<div>
				<form id="mobile_verify_form" name="mobile_verify_form" method="post" action="" class="formfield">
					<div id="mobile_ver_code_info" class="text_info" align="center">{$text}</div>
					<br clear="all"/>
					<div align="center" style="margin-bottom:5px; padding-bottom:10px;">{#Mobile_Verify_Txt#}</div>
					<label class="text2" style="padding-right:5px;">{#Fill_Verify#}: </label>
					<span class="popup_lightview">
						<input id="mobile_ver_code" name="mobile_ver_code" type="text" style="width:208px;" class="input" onkeyup="checkNullVerifyCode(this.value)" onblur="checkNullVerifyCode(this.value)"/>
						<br clear="all"/>
						<br clear="all"/>
						<img src="images/progress-bar.gif" />
					</span>
					<br clear="all"/>
				
					<label class="text2"></label>
					<span class="popup_lightview" style=" padding-left:5px;">
						<input type="button" class="button" name="b_mobileverify" onclick="document.getElementById('mobile_verify_form').submit(); return false;" value="{#SUBMIT#}" />
						<input type="hidden" name="submit_hidden" value="1" />
						<input type="hidden" name="act" value="mobileverify" />
					</span>
				</form>
				<br clear="all"/>
			</div>
			
			<div>
				<form id="wrongnumber_form" name="wrongnumber_form" method="post" action="" class="formfield">
					<div class="text_info" align="center">{$text2}</div>
					<div align="center" style="margin-bottom:5px; padding-bottom:10px;">{#Is_Your_Number#|replace:'[phone_number]':$currentNumber}</div>
					<label class="text2"></label>
					<span class="popup_lightview">
						<input type="button" class="button" name="b_mobileverify" onclick="document.getElementById('wrongnumber_form').submit(); return false;" value="{#BACK#}" />
						<input type="hidden" name="submit_hidden" value="1" />
						<input type="hidden" name="act" value="wrongnumber" />
					</span>
				</form>
				<br clear="all"/>
			</div>

			<div>
				<form id="resend_mobile_verify_form" name="resend_mobile_verify_form" method="post" action="" class="formfield">
					<div id="resend_code_info" class="text_info" align="center">{$text3}</div>
					<div align="center" style="margin-bottom:5px; padding-bottom:10px;">{#Resend_Verify#}</div>
					<label class="text2"></label>
					<span class="popup_lightview">
						<input type="button" class="button" name="b_mobileverify" onclick="document.getElementById('resend_mobile_verify_form').submit(); return false;" value="{#Resend#}" />
						<input type="hidden" name="submit_hidden" value="1" />
						<input type="hidden" name="act" value="resendmobileverify" />
					</span>
				</form>
			</div>

			<!--<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td>
					<div align="center"><b class="text_info">{$text}</b></div>
					<form id="mobile_verify_form" name="mobile_verify_form" method="post" action="" class="formfield">
						<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td colspan="3" align="center">{#Mobile_Verify_Txt#}</td>
							</tr>
							<tr>
								<td align="right" width="300px">{#Fill_Verify#}:</td>
								<td width="10"></td>
								<td align="left"><input id="mobile_ver_code" name="mobile_ver_code" type="text" style="width:208px;" class="input" /></td>
							</tr>
							<tr>
								<td align="right"></td>
								<td width="10"></td>
								<td align="left"><img src="images/progress-bar.gif" /></td>
							</tr>
							<tr>
								<td align="right"></td>
								<td width="10"></td>
								<td align="left">
									<input type="button" class="button" name="b_mobileverify" onclick="document.getElementById('mobile_verify_form').submit(); return false;" value="{#SUBMIT#}" />
									<input type="hidden" name="submit_hidden" value="1" />
									<input type="hidden" name="act" value="mobileverify" />
								</td>
							</tr>
						</table>
					</form>

					<div align="center"><b class="text_info">{$text2}</b></div>
					<form id="wrongnumber_form" name="wrongnumber_form" method="post" action="" class="formfield">
						<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td align="center" colspan="3">{#Is_Your_Number#|replace:'[phone_number]':$currentNumber}</td>
							</tr>
							<tr>
								<td align="right" width="300px"></td>
								<td width="10"></td>
								<td align="left">
									<input type="button" class="button" name="b_mobileverify" onclick="document.getElementById('wrongnumber_form').submit(); return false;" value="{#BACK#}" />
									<input type="hidden" name="submit_hidden" value="1" />
									<input type="hidden" name="act" value="wrongnumber" />
								</td>
							</tr>
						</table>
					</form>

					<div align="center"><b class="text_info">{$text3}</b></div>
					<form id="resend_mobile_verify_form" name="resend_mobile_verify_form" method="post" action="" class="formfield">
						<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td align="center" colspan="3">{#Resend_Verify#}</td>
							</tr>
							<tr>
								<td align="right" width="300px" valign="top"></td>
								<td width="10"></td>
								<td align="left">
									<input type="button" class="button" name="b_mobileverify" onclick="document.getElementById('resend_mobile_verify_form').submit(); return false;" value="{#Resend#}" />
									<input type="hidden" name="submit_hidden" value="1" />
									<input type="hidden" name="act" value="resendmobileverify" />
								</td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
			</table>-->
	</div>
</div>