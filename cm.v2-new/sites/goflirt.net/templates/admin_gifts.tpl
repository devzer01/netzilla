<h2>Gift List</h2>
<table width="100%" border=0>
	<tr bgcolor="#2d2d2d" height="28px"><td>Gift Id</td><td>Gift</td><td>Coins</td><td>Date Added</td><td>Del</td></tr>
	{foreach from=$gifts item=gift name=gifts}
		<tr bgcolor="#{cycle values=fde6be,ccb691}">
			<td style='text-align: center;'><strong>{$gift.id}</strong></td>
			<td><img src="{$gift.image_path}" /></td>
			<td>
				<input type='text' id='coin_{$gift.id}' data-id='{$gift.id}' name='coins' class='coins' value='{$gift.coins}' />
				<a id='save_{$gift.id}' style='display: none;' href="#" class='save' data-id='{$gift.id}'>Save</a>
			</td>
			<td>{$gift.created}</td>
			<td>
				<a href="?action=admin_gifts&sub_action=del&id={$gift.id}">Delete</a>
			</td>
		</tr>
	{/foreach}
</table>
<h2>Add New Gift</h2>
<form method='post' action='?action=admin_gifts&sub_action=upload' enctype="multipart/form-data">
	<strong>Category</strong> {html_options name=gift_category_id options=$gift_categories} <br/>
	<strong>Coins</strong> <input type='text' name='coins' /> <br/>
	<strong>Gift File</strong> <input type='file' name='gift' /> <br />
	<input type='submit' />
</form>

<script type='text/javascript'>
	{literal}
	jQuery(function () {
		jQuery(".save").click(function (e) {
			e.preventDefault();
			var gift_id = jQuery(this).data('id');
			var that = jQuery(this);
			var coins = jQuery("#coin_" + gift_id).val();
			
			jQuery.ajax({
				url: '?action=admin_gifts&sub_action=update_coins',
				data: {gift_id: gift_id, coins: coins},
				type: 'post',
				dataType: 'json',
				success: function (json) {
					that.hide();
				}
			});
		});
		
		jQuery(".coins").keypress(function (e) {
			var id = jQuery(this).data('id');
			jQuery("#save_" + id).show();
		});
	});
	{/literal}
</script>