<!-- {$smarty.template} -->
<div class="result-box">

<h1>{#Write_Card#}</h1>

<div class="result-box-inside-nobg">
<div align="center" style="margin-top:10px;"><img src="{$image}" style=" width:400px; border:4px solid #FFFFFF;"></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" celpadding="0">
	<tr><td height="10"></td></tr>
	<tr >
	  <td align="center">
	    <table width="300"  border="0" align="center" cellpadding="0" cellspacing="5">
		  <form name="form1" method="post" action="proc_from.php?from=./?action=sendcard_to" ><tr>
            <td  align="right" class="text12grey"><b>{#Email#}:</b></td>
			 <td  align="left"><input id="to" name="to" type="text" style="width:180px;" class="input">
					 <input type="hidden" name="card" value="{$card}">	
			 </td>
          </tr>
          <tr>
            <td align="right" class="text12grey"><b>{#Subject#}:</b></td>
            <td  align="left"><input id="subject" name="subject" type="text" style="width:180px" class="input"></td>
          </tr>
          <tr>
            <td align="right" valign="top" class="text12grey"><b>{#Message#}:</b></td>
            <td  align="left"><textarea id="message" name="message" style="width:270px; height:180px" class="input"></textarea></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="left"><input name="submit" type="submit" value="{#SUBMIT#}" onclick="return checkWriteMessage()" class="button"> </td>
            <input type="hidden" id="submit" name="submit" value="{#SUBMIT#}">
          </tr></form>
        </table>
      </td>
	</tr>
</table>
</div>
</div>