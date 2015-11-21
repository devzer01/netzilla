<div class="result-box">
<h1>{#Favourites#}</h1>
<div class="result-box-inside-nobg">
<div style="width:150px; float:left; display:block;">{#Search#}: </div><div style="width:200px; float:left; display:block;">
<input id="search" name="search" type="text" size="25" class="input" value="{$smarty.get.searchChar}" /></div>
<a href="#" onclick="parent.location='?action=favorite&searchChar='+$('search').value" class="buttonsearch">{#search#}</a>
<br class="clear" />
</div>
{include file="listfavorite.tpl"}
</div>