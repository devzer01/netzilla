<!-- {$smarty.template} -->
{if $smarty.get.do eq "view_suggestion"}
	<h2>{#Suggestion_box#}</h2>
	<div class="result-box">
		<div class="result-box-inside-nobg">
			{include file="suggestion_view.tpl"}
		</div>
	</div>
{elseif $smarty.get.do eq "view_message"}
	<h2>{#Suggestion_box#}</h2>
	<div class="result-box">
		<div class="result-box-inside-nobg">
			{include file="suggestion_message_view.tpl"}
		</div>
	</div>
{elseif $smarty.get.do eq "sugg2"}
	{if $smarty.get.type eq 'complete'}
		<h2>{#Suggestion_box#}</h2>
		<div class="result-box">
			<div class="result-box-inside">
				{include file="suggestion_complete.tpl"}
			</div>
		</div>
	{else}
		<h2>{#Suggestion_box#}</h2>
		<div class="result-box">
			<div class="result-box-inside">
				{include file="suggestion_page2.tpl"}
			</div>
		</div>
	{/if}
{elseif $smarty.get.do eq "sugg3"}
	<h2>{#Suggestion_box#} | {#Suggestion_Diary_head#}</h2>
	<div class="result-box">
		<div class="result-box-inside">
			{include file="suggestion_page3.tpl"}
		</div>
	</div>
{elseif $smarty.get.do eq "sugg4"}
	<h2>{#Suggestion_box#} | {#Suggestion_Message_head#}</h2>
	<div class="result-box">
		<div class="result-box-inside">
			{include file="suggestion_page4.tpl"}
		</div>
	</div>
{else}
	<h2>{#Suggestion_box#}</h2>
	<div class="result-box">
		<div class="result-box-inside-nobg" style="padding:0 !important;">
			{include file="suggestion_default.tpl"}
		</div>
	</div>
{/if}
