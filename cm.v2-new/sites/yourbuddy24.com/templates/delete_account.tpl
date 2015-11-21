<h1 class="title">{#delete_account#}</h1>
<div class="container-box-content-03">
	<div class="box-content-01-t-l"></div>
	<div class="box-content-01-t-m" style="width:900px !important;"></div>
	<div class="box-content-01-t-r"></div>
	<div class="box-content-03-m">
	<!-- register form -->       
	<div class="register-page-box">
	
<div align="center" style="width:auto; font-size:14px; margin-top:30px; margin-bottom:30px;">
{if $smarty.get.confirm eq 1}
		{#delete_account_successfully#}
	{else}
		{#delete_account_description#}<br/><br class="clear" />
<div style=" width:220px; margin-top:10px;">
<a href="?action={$smarty.get.action}&confirm=1" class="btn-search" style="width:80px;">{#Yes#}</a>
<a href="?action=profile" class="btn-search" style="width:80px;">{#No#}</a>

</div>
</div>
	{/if}


</div>

<div id="boxes">
<div id="dialogChangeEmail" class="window">
<div style="background-color: white; width: 100%"></div>
</div>
	
	</div>
	<!--end register form -->
	</div>
	<div class="box-content-01-b-l"></div>
	<div class="box-content-01-b-m" style="width:900px !important;"></div>
	<div class="box-content-01-b-r"></div>
</div>
