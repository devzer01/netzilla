<div class="container-metropopup">
<div class="metropopup-content">
<font style="font-size:1.5em; padding-bottom:2%; display:block;">{#Mobile_Verify_Txt#}</font>

        <form id="mobile_verify_form" name="mobile_verify_form" method="post" action="" class="formfield">
           
            <span>
                <input id="mobile_ver_code" name="mobile_ver_code" type="text" style="width:350px;" class="formfield_01" onkeyup="checkNullVerifyCode(this.value)" onblur="checkNullVerifyCode(this.value)"  placeholder="{#Fill_Verify#}"/>

               <!-- <img src="images/progress-bar.gif" /> -->
            </span>

            <span>
				<a href="#" onclick="submitAjaxFormMobileVerify(); return false;" class="btn-popup">{#SUBMIT#}</a>
            </span>
        </form>
       <br class="clear" />

        <div style=" float:left; width:80%; margin-top:10px; border:1px solid #66F; padding:10px; background:#929cc6;">
        <form id="wrongnumber_form" name="wrongnumber_form" method="post" action="" class="formfield">

			<span style="float:left;">{#Is_Your_Number#|replace:'[phone_number]':$currentNumber}</span><br class="clear" />
            <a href="#" onclick="submitAjaxFormWrongnumber(); return false;" class="btn-popup" style="margin-left:0; margin-top:10px;">{#BACK#}</a>

        </form>
		</div>
        
         <div style=" float:left; width:80%;  margin-top:10px; border:1px solid #66F; padding:10px; background:#929cc6;">
        <form id="resend_mobile_verify_form" name="resend_mobile_verify_form" method="post" action="" class="formfield">
            <div id="resend_code_info" class="text_info" align="center"></div>
            <span style="float:left;">{#Resend_Verify#}</span><br class="clear" />
			<a href="#" onclick="submitAjaxFormResendVerify(); return false;" class="btn-popup" style="margin-left:0; margin-top:10px;">{#Resend#}</a>

        </form>
        </div>
<br class="clear" />

</div></div>