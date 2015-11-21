<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
{literal}
<script type="text/javascript">
<!--
	function getcurtype(type)
	{
		window.location="?action=admin_manage_package&curtype="+type;
	}
// -->
</script>
{/literal}
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<div class="result-box">
	<h1>{#MANAGE_PACKAGE#}</h1>
	<div class="result-box-inside-nobg">
		<form name="changecurrency" id="changecurrency" action="" method="post">
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
			<input type="submit" value=" Save ">
		</form>
	<a href="?action=admin_manage_package_popup&curtype={if !$smarty.get.curtype}{$confdata.id}{else}{$smarty.get.curtype}{/if}" rel="gb_page_center[400, 250]" class="button">{#Add_package#}</a>
<br /><br />
	{if $managepackage}
		<table width="100%"  border="0">
			<tr bgcolor="#b6b6b6" height="28px">
				<td align="center" width="100" class="text-title"></td>
				
				<td align="center" width="100" class="text-title">{#PRICE#}</td>
				
				<td align="center" width="150" class="text-title">{#COINS#}</td>
				
				<td align="center" width="90" class="text-title">Edit</td>
				
				<td align="center" width="90" class="text-title">Delete</td>
			</tr>
			
			{foreach key=key from=$managepackage item=packagedata}
			{php}{$i++;}{/php}
			<tr  bgcolor="{cycle values="#663333,#996666"}">
			
				<td align="center">{php}{echo $i;}{/php}</td>
				
				<td align="center">{$packagedata.currency_price}</td>
				
				<td align="center">{$packagedata.coin}</td>	
				
				<td align="center"><a href="?action=admin_manage_package_popup&package_id={$packagedata.id}" rel="gb_page_center[400, 250]" class="link"><img src="images/icon/b_edit.png" width="16" height="16" border="0"></a></td>
				
				<td align="center"><a href="?action=admin_manage_package&del_id={$packagedata.id}" onclick="if(confirm('please confirm delete')) return true;"><img src="images/icon/b_drop.png" width="16" height="16" border="0"></a></td>
				
			</tr>
			{/foreach}
			
		</table>
	{/if}
	</div>

	<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
</div>
