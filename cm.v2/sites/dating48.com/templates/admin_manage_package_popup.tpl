<!-- {$smarty.template} -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<body>
<div>
	<h1>
		{if $package_rate}
			{#EDIT#}
		{elseif $execute_status}
			{$execute_status}
		{else}
			{#ADD#}
		{/if}
	</h1>
	{if !$execute_status}	
		<form id="register_form" name="register_form" method="post" action="">
			
			<fieldset>
				<label class="text">{#PRICE#}:</label>
				<span>{$user}</span>
				<input name="price" type="text" id="price" value="{$package_rate.currency_price}" />
				<br clear="all"/>

				<label class="text">{#COIN#}:</label>
				<span>
					<input name="coin" type="text" id="coin" value="{$package_rate.coin}" maxlength="30" class="box" />
				</span>
				<input name="id" id="id" type="hidden" value="{$package_rate.id}" />
				<input name="type" id="type" type="hidden" value="{$smarty.get.curtype}" />
				<br clear="all"/>
				<input type="submit" value="{if !$smarty.get.package_id}{#Add_package#}{else}{#Edit_package#}{/if}">
			</fieldlist>
		</form>
	{else}
	{/if}	
</div>
</body>
</html>