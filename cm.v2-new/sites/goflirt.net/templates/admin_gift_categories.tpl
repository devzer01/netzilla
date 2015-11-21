<h2>Sticker Categories</h2>
<table width="100%" border=0>
	<tr bgcolor="#2d2d2d" height="28px"><td>Gift Category Name</td></tr>
	{foreach from=$cats item=cat name=cats}
		<tr bgcolor="#{cycle values=fde6be,ccb691}">
			<td style='text-align: center;'><strong>{$cat.name}</strong></td>
		</tr>
	{/foreach}
</table>
<h2>Add New Category</h2>
<form method='post' action='?action=admin_gift_categories&sub_action=upload'>
	<strong>Category Name</strong> <input type='text' name='name' /> <br/>
	<input type='submit' />
</form>