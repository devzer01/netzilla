<!-- {$smarty.template} -->
{literal}
<script language="javascript">
	function confirmDelete(path){
		if(confirm("Do you realy want to delete this card?")){
			window.location.href=path;
		}
	}

  function jsUpload(upload_field)
  {
	var filename = upload_field.value;

	// If you want to restrict the file types that
	// get uploaded, uncomment the following block.

	var re_text = /\.jpg|\.jpeg|\.gif|\.png/i;
	if (filename.search(re_text) == -1)
	{	
	  alert("File must have a valid image extension (jpg, jpeg, gif, png).");
	  upload_field.form.reset();
	  return false;
	}

	upload_field.form.submit();
	//document.getElementById("upload_status").innerHTML = "uploading..<br/><img src='./indicator.gif'>";
	upload_field.disabled = true;
	return true;
  }
</script>
{/literal}

<h1 class="admin-title">{#MANAGE_CARD#}</h1>


<div style="padding:10px;">
<form action="?action=uploadcard" target="upload_iframe" method="post" enctype="multipart/form-data">
  <input type="hidden" name="fileframe" value="true">
  <label for="file">{#Add_New_Card#}:</label>
  <input type="file" name="Filedata" id="file" onChange="jsUpload(this)">
</form>
<iframe name="upload_iframe" style="width: 400px; height: 100px; display: none;"></iframe>
</div>

<table width="100%"  border="0" cellspacing="1" cellpadding="5">
<tr bgcolor="{$table_bg_top}" height="28px">
<td align="center" class="text-title"><a href="#">{#Picture#}</a></td>
<td align="center" class="text-title"><a href="#">{#Show#}</a></td>
<td align="center" class="text-title"><a href="#">{#Delete#}</a></td>
</tr>

{foreach  key=key  from=$cardrec item=curr_id}
<tr bgcolor="{cycle values="$table_bg_1,$table_bg_2"}">
<td align="center"  style="padding:10px 0;">
<table width="150" height="100"  border="0" cellpadding="1" cellspacing="1" bgcolor="#000000">
<tr>
<td><img src="{$curr_id.cardtmp}" width="150"></td>
</tr>
</table>
</td>
<td align="center" bgcolor="#FFFFFF">
{if $curr_id.cardshow==1}
<a href="?action=admin_managecard&proc=open&cid={$curr_id.cardid}&value=0&page={$smarty.get.page}">
<img src="images/icon/checked.png" width="16" height="16" border="0">
</a>
{else}
<a href="?action=admin_managecard&proc=open&cid={$curr_id.cardid}&value=1&page={$smarty.get.page}">
<img src="images/icon/unchecked.png" width="16" height="16" border="0">
</a>
{/if}
</td>
<td align="center" bgcolor="#FFFFFF">
<a href="javascript: confirmDelete('?action=admin_managecard&proc=del&cid={$curr_id.cardid}&page={$smarty.get.page}')">
<img src="images/icon/b_drop.png" width="16" height="16" border="0">
</a>
</td>
</tr>
{/foreach}
</table>

<div class="page">{$page_number}&nbsp;</div>
