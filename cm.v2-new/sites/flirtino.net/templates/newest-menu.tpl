<!-- {$smarty.template} -->
<div class="banner-{$smarty.session.lang}">
<a href="./?action=newest&amp;new=f" class="first">{if $smarty.get.new eq "f"}<u>{#Newest#} {#Women#}</u>{else}{#Newest#} {#Women#}{/if}</a>
<a href="./?action=newest&amp;new=m" class="second">{if $smarty.get.new eq "m"}<u>{#Newest#} {#Men#}</u>{else}{#Newest#} {#Men#}{/if}</a>
</div>