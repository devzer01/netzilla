{include file="email_header.tpl"}
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px; font-weight:bold; font-size:14px;">
    {$intro}<br /><br />
    <b>Subject:</b>&nbsp;&nbsp;{$subj}<br /><br />
	<b>Message:</b>&nbsp;&nbsp;{$mess}<br /><br />
    {$footer1}
	<br />{$footer2}<br />{$footer3}
  </td>
  </tr>
  </table>
{include file="email_footer.tpl"}