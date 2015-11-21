<h1 class="admin-title">Real to Real</h1>
<div class="container-admin-cotent-box">
<table width="100%"  border="0" cellspacing="1" id="log_message">
	<thead>
		<tr bgcolor="{$table_bg_top}" height="28px">
			<td align="center"><a href="#" class="sitelink">From</a></td>
			<td align="center"><a href="#" class="sitelink">To</a></td>
			<td align="center"><a href="#" class="sitelink">Date / Time</a></td>
			<td align="center"><a href="#" class="sitelink">Message</a></td>
			<td align="center"><a href="#" class="sitelink">Action</a></td>
		</tr>
	</thead>
	<tbody></tbody>
</table>
</div>

{literal}

{/literal}
{literal}
<script type="text/javascript">
var _ = {
	host: 'http://'+location.host, table: new Object(),
	reload: function(){
		_.table.fnReloadAjax();
	},
	init: function(){
		_.table = jQuery('#log_message').dataTable({
			"bServerSide": true,
			"bProcessing": true,
			"sAjaxSource": root_path + '?action=admin_chat_logs&get=message',
			"aaSorting": [[2, "desc"]],
			"iDisplayLength" : 25
		});
	}
}
jQuery(document).ready(function(){
	_.init();
});
</script>
{/literal}