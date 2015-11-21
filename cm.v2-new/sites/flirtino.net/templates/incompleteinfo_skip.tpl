<!-- {$smarty.template} -->
<h2 class="title" style="margin:10px 0 0 0;">{#reg2_title#}</h2>
<div id="container-content-profile-home">
<div style="line-height:20px; width:auto; margin:10px 10px 10px 10px; border:1px solid #000; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:#fff6dd; padding:10px;">


<div style=" margin:0 10px;"> 
	{if $save.countrycode}
	<div style="line-height:18px; margin-bottom:20px; margin-top:20px;">{#reg2_headline_intro#}</div>
	{if ($text4 neq '')}
		<div class="text_info" align="center">{$text4}</div>
	{else}
	<form id="register_form" name="register_form" method="post" action="?action=incompleteinfo_skip">	
		<label>{#mobile#}:</label>
		<span>
			+{if $save.countrycode}{$save.countrycode} 
			{else} 
			<input type="text" id="phone_code1" name="phone_code1" value="" class="code" style="display: inline; float: none;" maxlength="3" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" onblur="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" placeholder="Country code"/>
			{/if}
			<input type="text" id="phone_code2" name="phone_code2" value="{$save.phone_code2}" class="code" style="display: inline; float: none;" maxlength="4" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" onblur="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" placeholder="Vorwahl"/>
			<input type="text" id="phone_number" name="phone_number" value="{$save.phone_number}" class="boxcode" style="display: inline; float: none;" maxlength="10" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" onblur="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" placeholder="" />
			<!--<br clear="all"/>
			<div id="phone_number_info" class="error_info"></div> -->
		</span>
		<br clear="all"/>
		<label class="text">&nbsp;</label>
		<span>
			<input type="hidden" name="submit_form" value="1"/>
			<a href="javascript: void(0)" onclick="if(checkNullSignup2()) $('register_form').submit();" class="btn-yellow-long">{#reg2_submit#}</a>
			<!--<a href="javascript: void(0)" onclick="submitAjaxFormIncompleteInfo()" class="button-mobile">{#reg2_submit#}</a> -->
			{if $smarty.get.action eq "incompleteinfo_skip"}
			<a href="?action=profile#editprofile" onclick="" class="btn-yellow-long">{#reg2_skip#}</a>
			{/if}
		</span>
		<br clear="all"/>
	</form>
    
	<div style="line-height:18px; margin:10px 0;">{#phone_number_guide#}</div>
	{/if}
	{else}
		{#phone_number_not_support#}
	{/if}
</div>
</div>
</div>