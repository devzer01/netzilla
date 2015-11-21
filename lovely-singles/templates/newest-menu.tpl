<div style="height:210px; float:left;">
<div class="banner-{$smarty.session.lang}">
<a href="./?action=newest&amp;new=f" class="first">{if $smarty.get.new eq "f"}<u>{#Newest#} {#Women#}</u>{else}{#Newest#} {#Women#}{/if}</a>
<a href="./?action=newest&amp;new=m" class="second">{if $smarty.get.new eq "m"}<u>{#Newest#} {#Men#}</u>{else}{#Newest#} {#Men#}{/if}</a>
</div>

<div style="width:280px; height:auto; background:#2d2d2d; position:relative; top:-122px; float:right; z-index:50px; margin-left:10px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; ">

<div style="margin:1px; background:url(images/bg-box-02.jpg) repeat-x #FFF; width:278px; height:330px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; float:left;">

{if ($smarty.session.sess_username =="")}
	{include file="register-box.tpl"}
{/if}

</div>

</div>

</div><br class="clear" />

