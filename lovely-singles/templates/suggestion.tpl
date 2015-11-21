{if $smarty.get.do eq "view_suggestion"}
<div class="result-box">
<h1>{#Suggestion_box#}</h1>
<div class="result-box-inside-nobg">
{include file="suggestion_view.tpl"}
</div>
</div>
{elseif $smarty.get.do eq "view_message"}
<div class="result-box">
<h1>{#Suggestion_box#}</h1>
<div class="result-box-inside-nobg">
{include file="suggestion_message_view.tpl"}
</div>
</div>
{elseif $smarty.get.do eq "sugg2"}
{if $smarty.get.type eq 'complete'}
<div class="result-box">
<h1>{#Suggestion_box#}</h1>
<div class="result-box-inside-nobg">
{include file="suggestion_complete.tpl"}
</div>
</div>
{else}
<div class="result-box">
<h1>{#Suggestion_box#}</h1>
<div class="result-box-inside-nobg">
{include file="suggestion_page2.tpl"}
</div>
</div>
{/if}
{elseif $smarty.get.do eq "sugg3"}
<div class="result-box">
<h1>{#Suggestion_box#} | {#Suggestion_Diary_head#}</h1>
<div class="result-box-inside-nobg">
{include file="suggestion_page3.tpl"}
</div>
</div>
{elseif $smarty.get.do eq "sugg4"}
<div class="result-box">
<h1>{#Suggestion_box#} | {#Suggestion_Message_head#}</h1>
<div class="result-box-inside-nobg">
{include file="suggestion_page4.tpl"}
</div>
</div>
{else}
<div class="result-box">
<h1>{#Suggestion_box#}</h1>
<div class="result-box-inside-nobg" style="padding:0 !important;">
{include file="suggestion_default.tpl"}
</div>
</div>
{/if}
