<h2>Emoticon List</h2>
<table width="100%" border=0>
	<tr bgcolor="#2d2d2d" height="28px"><td>Text Version</td><td>Emoticon</td><td>Delete</td></tr>
	{foreach from=$emoticons item=emoticon name=emoticons}
		<tr bgcolor="#{cycle values=fde6be,ccb691}">
			<td style='text-align: center;'><strong>{$emoticon.text_version}</strong></td>
			<td><img src="../{$emoticon.image_path}" /></td>
			<td><a href="?action=admin_emoticons&sub_action=del&id={$emoticon.id}">Delete</a></td>
		</tr>
	{/foreach}
</table>
<h2>Add New Emoticon</h2>
<form method='post' action='?action=admin_emoticons&sub_action=upload' enctype="multipart/form-data">
	<strong>Text Representation</strong> <input type='text' name='text_version' /> <br/>
	<strong>Emoticon File</strong> <input type='file' name='emoticon' /> <br />
	<input type='submit' />
</form>