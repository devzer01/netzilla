<div class="result-box">

<h1>{#Lonely_Heart_Ads#}</h1>

<div class="result-box-inside-nobg">
<div align="center">
<table width="500" align="center" cellpadding="2" cellspacing="1" border="0">
	<form id="lonely_heart_form" name="lonely_heart_form" method="post" action="">
	<tr align="left">
		<td width="120" align="right" style="padding:0 10px 10px 10px"><b>{#Target_group#}:</b></td>
		<td>{html_options id="taget" name="target" options=$targetGroup selected=$lonelyheart.target style="width:205px"}</td>
	</tr>
	<tr align="left">
		<td width="120" align="right" style="padding:0 10px 10px 10px"><b>{#Category#}:</b></td>
		<td>{html_options id="category" name="category" options=$category selected=$lonelyheart.category style="width:205px "}</td>
	</tr>
	<tr align="left"> 
		<td width="120" align="right" style="padding:0 10px 10px 10px"><b>{#Headline#}:</b></td>
		<td><input name="headline" type="text" id="headline" style="width:300px "  value="{$lonelyheart.headline|stripslashes}" maxlength="100" class="input"></td>
	</tr>
	<tr>
		<td width="120" align="right" style="padding:0 10px 10px 10px" valign="top"><b>{#Text#}:</b></td>
		<td>
			<textarea id="text" name="text" style="width:300px; height: 150px;" onKeyDown="limitText(this.form.text,this.form.countdown,800);"
onKeyUp="limitText(this.form.text,this.form.countdown,800);">{$lonelyheart.text|stripslashes}</textarea>
			<font style="line-height:26px;">({#LHD_MAX#})</font>
			<br>
			<input readonly type="text" name="countdown" size="3" value="800"> {#SMS_LEFT#}
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2" height="5px"></td>
	</tr>
	<tr align="left">
		<td></td>
		<td>
		<input type="hidden" id="submit_hidden" name="submit_hidden" value="submit" />
		<input name="send_button" id="send_button" type="button" value="{#SUBMIT#}" onclick="if(checkWriteLonely()) $('lonely_heart_form').submit();" class="button">
		</td>
	</tr>

	</form>			
</table>
</div>
</div>
</div>
