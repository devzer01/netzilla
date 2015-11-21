<!-- {$smarty.template} -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{#TITLE#}</title>
{literal}
<style>
body
{
	font-size: 14px;
}
table
{
	font-size: 14px;
}
</style>
{/literal}
</head>
<body>
/{$smarty.get.start}<br>
<table width="100%">
{section loop=$list name=index}
{if ($list[index].filename eq '.') or ($list[index].filename eq '..' and ($root eq $dir))}
{else}
<tr height="25">
	<td valign="bottom">
	{if $list[index].filename eq '..'}
		{if $dir ne $root}
			<img src='images/folder.gif'> <a href='?action=image_dir&start={$upper_level}'>{$list[index].filename}</a><br>
		{/if}
	{elseif $list[index].type eq 'dir'}
		<img src='images/folder.gif'> <a href='?action=image_dir&start={$smarty.get.start}{$list[index].filename}/'>{$list[index].filename}</a><br>
	{else}
		<img src='{$smarty.const.PROFILE_PICS_URL}{$smarty.get.start}{$list[index].filename}' width="60" height="60" /> <a href='javascript:void(0)' onclick='window.opener.picturepath_obj.value = "{$smarty.get.start}{$list[index].filename}"; window.close()'>{$list[index].filename}</a><br>
	{/if}
	</td>
	<td valign="bottom" align="right" width="100px">
		{if $list[index].type ne 'dir'}
				{$list[index].size}
		{elseif $list[index].filename eq ".."}
		{else}
			-
		{/if}
	</td>
	<td valign="bottom" align="right" width="80px">
		{if $list[index].type ne 'dir'}
			<a href="javascript:void(0)" onclick="window.open('{$smarty.const.PROFILE_PICS_URL}{$smarty.get.start}{$list[index].filename}', 'preview', 'location=0,status=1,scrollbars=1,width=500,height=500,resizable=1')">Preview</a>
		{/if}
	</td>
	<td valign="bottom" align="right" width="80px">
		{$list[index].extension}
	</td>
</tr>
{/if}
{/section}
</table>
</body>
</html>