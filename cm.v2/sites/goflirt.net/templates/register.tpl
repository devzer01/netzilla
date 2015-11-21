<!-- {$smarty.template} -->
{if $text}
<div style="line-height:20px; width:auto; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; margin-top:10px;
background:url(images/cm-theme/bg-opd-r.png); padding:10px; text-align:center; border:2px solid rgba(0,0,0,0.1); color:#FFF;">
{$text}
</div>
{/if}

{if ($smarty.get.action eq "register")}
	{include file="regis-step1.tpl"}
{/if}