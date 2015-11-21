{if $messages}
<table>
{if $messages|@count ge 0}
<tr>
	<td colspan="3">{#Message_History#}:</td>
</tr>
{/if}
<tr>
	<td colspan="3" height="1px" bgcolor="#BBBBBB"></td>
</tr>
<tr>
	<td colspan="3" height="20px"></td>
</tr>
{section name="index" loop=$messages}
<tr>
	<td valign="top">{$messages[index].datetime}</td>
	<td valign="top" style="color: red">{$messages[index].username}:</td>
	<td valign="top" align="left">{$messages[index].message|replace:"\r\n":"<br>"}</td>
</tr>
{/section}
</table>
{/if}