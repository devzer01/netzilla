<!-- {$smarty.template} -->
<style>
{literal}
	.picture-item { float: left; margin-right: 5px; margin-bottom: 5px}
	.picture-username { text-align: center; font-size: 12px; color: red; font-weight: bold; overflow: hidden}
	.picture-item div.delete { display:none; text-align: right; margin: -145px 0px 0 0; height: 145px; position: relative; z-index: 100; background-color: #FFFFFF; opacity:0.6; filter:alpha(opacity=60);}
	.picture-item div.delete a {margin: 5px}
	.picture-item:hover div.delete { display:block; }
{/literal}
</style>
<h1 class="admin-title">Approval</h1>
<div id="trList" style="display:block; margin-top:10px;">
{if $list}
	<form action="" method="post">
	Select : <a href="#" class="admin-link" id="select_all">ALL</a> | <a href="#" class="admin-link" id="select_invert">INVERT</a> | <a href="#" class="admin-link" id="select_none">NONE</a><br/><br/>
	{foreach from=$list item="item"}
		<div class="picture-item">
		<img src="thumbnails.php?file={$item.picturepath}&w=129&h=145" width="129" height="145"/>
		<div class="delete">
			<a href="?action=admin_approval&rotate_left_picture={$item.id}" onclick="if(confirm('Are you sure to rotate this picture?')) return true; else return false;"><img src="images/icon/rotate_left.png"></a>
            <a href="?action=admin_approval&rotate_right_picture={$item.id}" onclick="if(confirm('Are you sure to rotate this picture?')) return true; else return false;"><img src="images/icon/rotate_right.png"></a> 
            <a href="?action=admin_approval&approve_picture={$item.id}" onclick="if(confirm('Are you sure to approve this picture?')) return true; else return false;"><img src="images/icon/checked.png"></a>
			<a href="?action=admin_approval&delete_picture={$item.id}" onclick="if(confirm('Are you sure to delete this picture?')) return true; else return false;"><img src="images/icon/b_drop.png"></a>
		</div>
		<div class="picture-username"><input type="checkbox" name="id[]" value="{$item.id}"/> <a href="?action=viewprofile&username={$item.username}&from=admin" class="admin-link">{$item.username}</a></div>
		</div>
	{/foreach}
	<br clear="both"/>
	<div class="page">
		{paginate_prev} {paginate_middle} {paginate_next}
		<input type="hidden" name="type" value="{$smarty.get.type}"/>
		<input type="submit" name="submit" value="Approve"/>
		<input type="submit" name="submit" value="Delete"/>
	</div>
	</form>
{elseif $descriptions}
	<form action="" method="post">
	Select : <a href="#" class="admin-link" id="select_all">ALL</a> | <a href="#" class="admin-link" id="select_invert">INVERT</a> | <a href="#" class="admin-link" id="select_none">NONE</a><br/><br/>
	<table border="1">
	<tr>
		<th></th><th>Username</th><th width="500px">Description</th><th>Action</th>
	</tr>
	{foreach from=$descriptions item="item"}
	<tr>
		<td><input type="checkbox" name="id[]" value="{$item.id}"/></td>
		<td>{$item.username}</td>
		<td>{$item.description}</td>
		<td>
			<a href="?action=admin_approval&approve_description={$item.id}" onclick="if(confirm('Are you sure to approve this description?')) return true; else return false;"><img src="images/icon/checked.png"></a>
			<a href="?action=admin_approval&delete_description={$item.id}" onclick="if(confirm('Are you sure to delete this description?')) return true; else return false;"><img src="images/icon/b_drop.png"></a>
		</td>
	</tr>
	{/foreach}
	</table>
	<br clear="both"/>
	<div class="page">
		{paginate_prev} {paginate_middle} {paginate_next}
		<input type="hidden" name="type" value="{$smarty.get.type}"/>
		<input type="submit" name="submit" value="Approve"/>
		<input type="submit" name="submit" value="Delete"/>
	</div>
	</form>
{elseif $delete_accounts}
	<table border="1">
	<tr>
		<th>Username</th><th width="500px">Datetime</th><th>Action</th>
	</tr>
	{foreach from=$delete_accounts item="item"}
	<tr>
		<td>{$item.username}</td>
		<td>{$item.delete_datetime}</td>
		<td><a href="?action=admin_manageuser&user={$item.username}&proc=del" onclick="return confirm(confirm_delete_box)" title="Delete">
			<img src="images/icon/b_drop.png" width="16" height="16" border="0">
			</a></td>
	</tr>
	{/foreach}
	</table>
{/if}

	</div>
</div>

<script>
{literal}
jQuery(function() {
	jQuery('#select_all').click(function() {
		var checkboxes = jQuery(this).closest('form').find(':checkbox');
		checkboxes.prop('checked', true);
	});

	jQuery('#select_none').click(function() {
		var checkboxes = jQuery(this).closest('form').find(':checkbox');
		checkboxes.prop('checked', false);
	});

	jQuery('#select_invert').click(function() {
		var checkboxes = jQuery(this).closest('form').find(':checkbox');
		checkboxes.each( function() {
			jQuery(this).prop('checked', !jQuery(this).prop('checked'));
		});
	});
});
{/literal}
</script>