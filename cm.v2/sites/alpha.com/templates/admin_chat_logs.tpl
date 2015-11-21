<table width="100%" id="log_message">
	<thead>
		<tr bgcolor="#2d2d2d" height="28px">
			<td align="center" width="250"><a href="#" class="sitelink">From</a></td>
			<td align="center" width="250"><a href="#" class="sitelink">To</a></td>
			<td align="center" width="250"><a href="#" class="sitelink">Date / Time</a></td>
			<td align="center"><a href="#" class="sitelink">Message</a></td>
			<td align="center">Action</td>
		</tr>
	</thead>
	<tbody></tbody>
</table>


{literal}
<style type="text/css">
#log_message td { padding: 15px 5px; }
</style>
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