<!-- {$smarty.template} -->
{literal}
<script type="text/javascript">
<!--
	function loadAddPackageBox()
	{
		var url = "?action=admin_manage_package_popup";
		jQuery("#dialog").load(url).dialog({ width: 550 });
	}
	function loadEditPackageBox(id)
	{
		var url = "?action=admin_manage_package_popup&package_id="+id;
		jQuery("#dialog").load(url).dialog({ width: 550 });
	}
// -->
</script>
{/literal}
<h1 class="admin-title">{#MANAGE_PACKAGE#}</h1>
<div class="result-box">
	<br />
	<div class="result-box-inside">
    <div style="padding-left:20px;">
		<form name="changecurrency" id="changecurrency" action="" method="post" >
			{#CURRENCY#}:
			<select name="currency_type" id="currency_type">
				{foreach key=key from=$currname item=curname}
                    <option value="{$curname.name}"
                        {if $smarty.post.currency_type}
                            {if $curname.name eq $smarty.post.currency_type}
                                {php}{echo 'selected';}{/php}
                            {/if}
                        {elseif $curname.name eq $confdata.value}
                            {php}{echo 'selected';}{/php}
                        
                        {/if}
                        >
                        {$curname.name}
                    </option>
				{/foreach}
			</select>
			<input type="submit" value=" Save " class="button">
		</form>
        <br class="clear" />
	<a href="#" class="btn-admin" onclick="loadAddPackageBox()">{#Add_package#}</a>
	<br class="clear"/><br />
    </div>
	{if $managepackage}
		<table width="100%"  border="0">
			<tr bgcolor="#2d2d2d" height="28px">
				<td align="center" width="100" class="text-title"></td>
				<td align="center" width="100" class="text-title"><a class="sitelink">{#PRICE#}</a></td>
				<td align="center" width="150" class="text-title"><a class="sitelink">{#COINS#}</a></td>
				<td align="center" width="90" class="text-title"><a class="sitelink">Edit</a></td>
				<td align="center" width="90" class="text-title"><a class="sitelink">Delete</a></td>
			</tr>
			
			{foreach key=key from=$managepackage item=packagedata}
			{php}{$i++;}{/php}
			<tr  bgcolor="{cycle values="#dbdfe2,#fff"}">
			
				<td align="center">{php}{echo $i;}{/php}</td>
				
				<td align="center">{$packagedata.currency_price}</td>
				
				<td align="center">{$packagedata.coin}</td>	
				
				<td align="center"><a href="#" onclick="loadEditPackageBox({$packagedata.id})" class="link"><img src="images/icon/b_edit.png" width="16" height="16" border="0"></a></td>
				
				<td align="center"><a href="?action=admin_manage_package&del_id={$packagedata.id}" onclick="if(confirm('please confirm delete')) return true; else return false;"><img src="images/icon/b_drop.png" width="16" height="16" border="0"></a></td>
				
			</tr>
			{/foreach}
			
		</table>
	{/if}
	</div>

	<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
</div>
<div id="dialog" title="Package"></div>