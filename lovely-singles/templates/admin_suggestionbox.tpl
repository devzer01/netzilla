<div class="result-box">
<h1>{#MANAGE_SUGGESTION_BOX#}</h1>
<div class="result-box-inside-nobg">
<a href="?action=admin_suggestionbox&do=write" class="button">{#MANAGE_SUGGESTION_BOX_NEW_DIARY#}</a>
<br /><br />
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<form id="admin_suggestionbox_form" name="admin_suggestionbox_form" method="post" action="">
<tr>
<td>
<table border="0" cellpadding="2" cellspacing="1" width="100%">
<tr bgcolor="#b6b6b6" height="28px">
<th width="50px" class="text-title">Index</th>
<th class="text-title">Subject</th>
<th width="120px" class="text-title">Date/Time</th>
<th width="40px" class="text-title">Edit</th>
<th width="60px" class="text-title"><a href="#" class="sitelink" onclick="if(confirm(confirm_delete_box))deleteSuggestion('admin_suggestionbox_form')">Delete</a></th>
</tr>
{section name="suggestion_box" loop=$suggestion_box}
<tr  bgcolor="{cycle values="#663333,#996666"}" height="24">
<td width="35px" align="center">{$smarty.section.suggestion_box.index+1}</td>
<td style="padding-left:15px;"><a href="?action=admin_suggestionbox&do=view&id={$suggestion_box[suggestion_box].id}" class="link-inrow">{$suggestion_box[suggestion_box].subject|truncate:70:"..."}</a></td>
<td align="center" width="120px" style="padding:5px;">{$suggestion_box[suggestion_box].datetime}</td>
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
</div>