<!-- {$smarty.template} -->
<h1 class="admin-title">{#MANAGE_SUGGESTION_BOX#}</h1>
<div style="margin-top:10px;">
<a href="?action=admin_suggestionbox&do=write" class="butregisin">{#MANAGE_SUGGESTION_BOX_NEW_DIARY#}</a>
<br class="clear" /><br class="clear" />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="admin_suggestionbox_form" name="admin_suggestionbox_form" method="post" action="">
<tr>
<td>
<table border="0" cellpadding="2" cellspacing="1" width="100%">
<tr bgcolor="{$table_bg_top}" height="28px">
<th width="50px" class="text-title"><a href="#">Index</a></th>
<th class="text-title"><a href="#">Subject</a></th>
<th  class="text-title"><a href="#">Date/Time</a></th>
<th width="40px" class="text-title"><a href="#">Edit</a></th>
<th width="60px" class="text-title"><a href="#" onclick="if(confirm(confirm_delete_box))deleteSuggestion('admin_suggestionbox_form')">Delete</a></th>
</tr>
{section name="suggestion_box" loop=$suggestion_box}
<tr  bgcolor="{cycle values="$table_bg_1,$table_bg_2"}" height="24">
<td width="35px" align="center">{$smarty.section.suggestion_box.index+1}</td>
<td style="padding-left:15px;"><a href="?action=admin_suggestionbox&do=view&id={$suggestion_box[suggestion_box].id}" class="admin-link">{$suggestion_box[suggestion_box].subject|truncate:70:"..."}</a></td>
<td align="center"  style="padding:5px;">{$suggestion_box[suggestion_box].datetime}</td>
<td align="center" width="40px"><a href="?action=admin_suggestionbox&do=edit&id={$suggestion_box[suggestion_box].id}"><img border="0" src="images/icon/b_edit.png" /></a></td>
<td align="center" width="40px"><input type="checkbox" id="suggestion_box_id" name="suggestion_box_id[]" value="{$suggestion_box[suggestion_box].id}"></td>
</tr>
{/section}
</table>
</td>
</tr>
</form>	
</table>
</div>
{if $countRecord > 0}
<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
{/if}
