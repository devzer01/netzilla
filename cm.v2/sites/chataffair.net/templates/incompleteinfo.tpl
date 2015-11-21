<!-- {$smarty.template} -->
<div class="container-metropopup">
<div class="metropopup-content">
<font style="font-size:2em; padding-bottom:2%; display:block;">{#reg2_banner#}</font>

{if $save.countrycode}
<div style="line-height:18px; margin-bottom:20px;">{#reg2_headline_intro#}</div>
	{if ($text4 neq '')}
		<div class="text_info" align="center">{$text4}</div>
	{else}
	<form id="register_form" name="register_form" method="post" action="?action=incompleteinfo{if $smarty.get.action eq 'incompleteinfo_skip'}&nextstep=mobileverify_skip{else}&nextstep=mobileverify{/if}">	
		<label class="text">{#mobile#}:</label>
		<span>
			<div id="country_code" style="float:left;padding-top:2px;width:40px;">+{$save.countrycode}</div>
			<input type="text" id="phone_code2" name="phone_code2" value="{$save.phone_code2}"  maxlength="4" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" onblur="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" class="formfield_01" style="width:50px; margin-right:5px;"/>
			<input type="text" id="phone_number" name="phone_number" value="{$save.phone_number}" class="formfield_01" maxlength="10" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" onblur="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" />
			<br clear="all"/>
			<!--<div id="phone_number_info" class="error_info"></div> -->
		</span>
		<br clear="all"/>
		<label class="text">&nbsp;</label>
		<span style="margin-bottom:10px; float:left;">
			<input type="hidden" name="submit_form" value="1"/>
			<a href="#" onclick="submitAjaxFormIncompleteInfo(); return false;" class="btn-popup">{#reg2_submit#}</a>
			{if $smarty.get.action eq "incompleteinfo_skip"}
			<a href="./" onclick="" class="btn-popup">{#reg2_skip#}</a>
			{else}
			<a href="#" onclick="jQuery('#mask').hide(); jQuery('.window').hide(); return false;" class="btn-popup">{#CANCEL#}</a>
			{/if}
		</span>
		
	</form>
	<div style="line-height:18px; margin-top:10px; clear:both">{#phone_number_guide#}</div>
	{/if}
{else}
{#phone_number_not_support#}
{/if}

</div></div>