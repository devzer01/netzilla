<table width="100%" border="0" cellpadding="0" cellspacing="0" celpadding="0">
	<tr>
		<td align="center">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="26" background="images/bg_sex.jpg">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
					<td align="center" background="images/bgcenter.gif" class="text14whitebold" ><img src="images/xx.gif" />
					::{#Write_Card#}::</td>
					<td background="images/bgr.gif" width="12" height="24"></td>
				 </tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr id="trAdd"  >
	  <td><div align="center"><br>
	     <table width="150" height="100"  border="0" cellpadding="1" cellspacing="1" bgcolor="#000000"> 
		   <tr><td width="200" bgcolor="#FFFFFF"> <div align="center">   <img src="{$image}">  </div></td></tr>
	     </table>
      </div></td>
 	</tr>
	<tr><td height="10"></td></tr>
	<tr >
	  <td><form name="form1" method="post" action="proc_from.php?from=?action=writecard" >
	    <table width="300"  border="0" align="center" cellpadding="0" cellspacing="5">
          <tr>
            <td><b>{#To#}:</b></td>
			
            <td><input id="to" name="to" type="text" style="width:180px; cursor:pointer" 
		  				value="{$uinfo.username}"  onBlur="this.value='{$uinfo.username}'">
						<input type="hidden" name="card" value="{$card}">	</td>		
          </tr>
          <tr>
            <td><b>{#Subject#}:</b></td>
            <td><input id="subject" name="subject" type="text" style="width:180px" value="Happy Birth Day"></td>
          </tr>
          <tr>
            <td valign="top"><b>{#Message#}:</b></td>
            <td><textarea id="message" name="message" style="width:360px"></textarea></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" id="send_button" name="send_button" onclick="return checkWriteMessage();" value="{#SEND#}"></td>
          </tr>
        </table>
      </form>
	  </td>
	</tr>
</table>
