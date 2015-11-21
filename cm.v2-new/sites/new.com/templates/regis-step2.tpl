<!-- {$smarty.template} -->
<div class="mobile-number-banner">
	<div class="mobile-number-text">{#reg2_banner#}</div>
</div>
{if $save.countrycode}
<div style="line-height:18px; margin-bottom:20px;">{#reg2_headline_intro#}</div>
	{if ($text4 neq '')}
		<div class="text_info" align="center">{$text4}</div>
	{else}
	<form id="register_form" name="register_form" method="post" action="?action=incompleteinfo{if $smarty.get.action eq 'incompleteinfo_skip'}&nextstep=mobileverify_skip{else}&nextstep=mobileverify{/if}">	
		<label class="text">{#mobile#}:</label>
		<span>
			<div id="country_code" style="float:left;padding-top:2px;width:40px;">+{$save.countrycode}</div>
			<input type="text" id="phone_code2" name="phone_code2" value="{$save.phone_code2}" class="code" maxlength="4" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" onblur="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)"/>
			<input type="text" id="phone_number" name="phone_number" value="{$save.phone_number}" class="boxcode" maxlength="10" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" onblur="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" />
			<br clear="all"/>
			<div id="phone_number_info" class="error_info"></div>
		</span>
		<br clear="all"/>
		<label class="text">&nbsp;</label>
		<span>
			<input type="hidden" name="submit_form" value="1"/>
			<!--<a href="javascript: void(0)" onclick="if(checkNullSignup2()) $('register_form').submit();" class="button-mobile">{#reg2_submit#}</a> -->
			<a href="javascript: void(0)" onclick="submitAjaxFormIncompleteInfo()" class="button-mobile">{#reg2_submit#}</a>
			{if $smarty.get.action eq "incompleteinfo_skip"}
			<a href="./" onclick="" class="button-mobile">{#reg2_skip#}</a>
			{/if}
		</span>
		
	</form>
	<div style="line-height:18px; margin-top:10px; margin-bottom:20px; clear:both">{#phone_number_guide#}</div>
	{/if}
{else}
{#phone_number_not_support#}<br/><br/>
{/if}