<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
					<td align="center" background="images/bgcenter.gif" class="text12whitebold" ><img src="images/xx.gif" />
					:: {$profile.username} ::</td>
					<td background="images/bgr.gif" width="12" height="24"></td>
				 </tr>
</table>
<table width="60%" border="0" align="center" cellpadding="4" cellspacing="0">
	<form id="lonely_heart_form" name="lonely_heart_form" method="post" action=""> 
	<tr>
		<td align="center" colspan="2" height="30px" valign="middle"></td>
	</tr>
	<tr>
	  <td width="120"><b>{#Target_group#}:</b></td>
		<td>{html_options id="taget" name="target" options=$targetGroup style="width:205px "}</td>
	</tr>
	<tr>
	  <td width="120"><b>{#Category#}:</b></td>
		<td>{html_options id="category" name="category" options=$category style="width:205px "}</td>
	</tr>
	<tr>
	  <td width="120"><b>{#Headline#}:</b></td>
		<td><input name="headline" type="text" id="headline" maxlength="100" style="width:200px "></td>
	</tr>
	<tr>
	  <td width="120" valign="top"><b>{#Text#}:</b></td>
		<td><textarea id="text" name="text" style="width:200px" onkeydown="return keyLength(800, this.value)"></textarea></td>
	</tr>
	<tr>
		<td align="center" colspan="2" height="10px"></td>
	</tr>
	<tr>
		<td></td>
		<td>
		<input type="hidden" id="submit_hidden" name="submit_hidden" value="submit" />
		<input type="image" src="images/senden_bt.gif"  id="send_button" name="send_button" onclick="return checkWriteLonely();" value="{#SEND#}">
		</td>
	</tr>
	</form>			
</table>
