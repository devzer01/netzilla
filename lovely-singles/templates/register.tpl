{if $userProfile && $smarty.get.cate eq "profile"}
	<div class="text-alert">
		<h1>{#reg_headline_start#} {$userProfile.username|regex_replace:"/@.*/":""}{#reg_headline_end#}</h1>
	</div>
	<div id="Showprofile">
		<div align="center">{include file="body-profile.tpl" year=$thisyear}</div>
	</div>
{elseif $lonelyProfile && $smarty.get.cate eq "lonely"}
	<div id="Showprofile">
		<div class="text-alert">
			<h1>{#reg_headline_start#} {$smarty.get.username|regex_replace:"/@.*/":""}{#reg_headline_end#}</h1>
		</div>
		<div align="center">{include file="body-lonely.tpl" year=$thisyear}</div>
	</div>
{/if}

<div class="register-page-box">
	<h1>{#Register#}</h1>
	<div class="register-page-box-inside">
		<div align="center">
			<b style="color: orange">{$text}</b>
		</div><br>			
			{include file="regis-step1.tpl"}
	</div>
</div>