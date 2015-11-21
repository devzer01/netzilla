<!-- {$smarty.template} -->
<h2>{#Favourites#}</h2>
<div class="result-box">

<div class="result-box-inside-nobg">
<div style="width:150px; float:left; display:block;">{#Search#}: </div><div style="width:200px; float:left; display:block;">
<input id="search" name="search" type="text" size="25" class="input" value="{$smarty.get.searchChar}" /></div>
<a href="#" onclick="parent.location='?action=favorite&searchChar='+$('search').value" class="buttonsearch">{#search#}</a>
</div>
<br class="clear" />
{include file="listfavorite.tpl"}
</div>