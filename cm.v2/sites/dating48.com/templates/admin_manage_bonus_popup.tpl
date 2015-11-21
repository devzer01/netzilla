<!-- {$smarty.template} -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<body>
<div>
	<h1>{#Add_Bonus#}</h1>
	{if (($username != '') && ($coin != ''))}
		{$coin} {#Coin_Unit#} {#Result_Text#} '{$username}' 
	{else}
		<form id="register_form" name="register_form" method="post" action="?action=admin_manage_bonus_popup&user={$user}">	
			<fieldset>
				<label class="text">{#USERNAME#}:</label>
				<span>{$user}</span>
				<input name="username" type="hidden" id="username" value="{$user}" />
				<br clear="all"/>

				<label class="text">{#Coin_Text#}:</label>
				<span>
					<input name="coin" type="text" id="coin" value="" maxlength="30" class="box" /> {#Coin_Unit#}
				</span>
				<br clear="all"/>
				<input type="submit" value="Add Bonus">
			</fieldlist>
		</form>
	{/if}
</div>
</body>
</html>