<!-- {$smarty.template} -->
<h2>{#reg2_title#}</h2>
<div class="register-page-box-inside">
	<div class="mobile-number-banner">
        <div class="mobile-number-text">{#reg2_banner#}</div>
    </div>
	<div style="line-height:18px; margin-bottom:20px;">{#reg2_headline_intro#}</div>
	{if ($text4 neq '')}
		<div class="text_info" align="center">{$text4}</div>
	{else}
	<form id="register_form" name="register_form" method="post" action="?action=incompleteinfo_skip">	
		{literal}							
		<script language="javascript" type="text/javascript">
			//country_select = "{/literal}{$save.country}{literal}"; 
			//state_select = "{/literal}{$save.state}{literal}";
			//city_select = "{/literal}{$save.city}{literal}";
			//fileExtension = "{/literal}{$config_image}{literal}";
			//fileDescription = "Images ({/literal}{$config_image}{literal})";
			//ajaxRequest("loadOptionCountry_with_test_country", "", "", "loadOptionCountry1", "reportError");
			/*getNumDate("date", document.getElementById("month").options[document.getElementById("month").selectedIndex].value, document.getElementById("year").options[document.getElementById("year").selectedIndex].value);*/
			//InitFlashObj();
		</script>
		{/literal}

		<!--<label class="text">{#Country#}:</label>
		<span>
			<select id="country" name="country" onchange="loadOptionState('state', this.options[this.selectedIndex].value, '');loadOptionCity('city', 0, ''); checkNullSelectOption(this); getCountryCode(this.options[this.selectedIndex].value)" class="box"></select>
			<div id="country_info" style="float: left; margin-left: 5px; color: orange;"></div>
		</span>
		<br clear="all"/>

		<label class="text">{#State#}:</label>
		<span>
			<select id="state" name="state" onchange="loadOptionCity('city', this.options[this.selectedIndex].value, '');checkNullSelectOption(this)" class="box"></select>
			<div id="state_info" style="float: left; margin-left: 5px; color: orange;"></div>
		</span>
		<br clear="all"/>

		<label class="text">{#City#}:</label>
		<span>
			<select id="city" name="city" onchange="checkNullSelectOption(this)" class="box"></select>
			<div id="city_info" style="float: left; margin-left: 5px; color: orange;"></div>
		</span>
		<br clear="all"/>

		<label class="text">{#Gender#}:</label>
		<span>
			{html_radios id="gender" name="gender" options=$gender selected=$save.gender onclick="checkNullRadioOption('register_form', this, 'Please select Gender')"}
			<div id="gender_info" style="float: left; margin-left: 5px; color: orange;"></div>
		</span>
		<br clear="all"/>

		<label class="text">{#Yourre_looking_for#}:</label>
		<span>
			{html_radios id="looking_for" name="looking_for" options=$gender selected=$save.looking_for onclick="checkNullRadioOption('register_form', this, 'Please select Your&rsquo;re looking for')"}
			<div id="looking_for_info" style="float: left; margin-left: 5px; color: orange;"></div>
		</span>
		<br clear="all"/>-->

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
			<a href="javascript: void(0)" onclick="if(checkNullSignup2()) $('register_form').submit();" class="button-mobile">{#reg2_submit#}</a>
			<!--<a href="javascript: void(0)" onclick="submitAjaxFormIncompleteInfo()" class="button-mobile">{#reg2_submit#}</a> -->
			{if $smarty.get.action eq "incompleteinfo_skip"}
			<a href="./" onclick="" class="button-mobile">{#reg2_skip#}</a>
			{/if}
		</span>
		
	</form>
	{/if}
</div>
{include file="complete-profile.tpl"}