{literal}
<script language="javascript" type="text/javascript">
country_select = "{/literal}{$smarty.get.co}{literal}";
state_select = "{/literal}{$smarty.get.s}{literal}";
city_select = "{/literal}{$smarty.get.ci}{literal}";

window.onload = function(){
	ajaxRequest("loadOptionCountry", "", "", loadOptionCountry, reportError);
};
var Opt = Array();
function clickInOpt(obj){
	Opt[obj.name] = obj.value; 
}
function clickOutOpt(obj){
	Opt[obj.name] = ""; 
	obj.checked=false;
}
function chkOpt(obj){
 if(obj.value==Opt[obj.name]) clickOutOpt(obj); 
 else clickInOpt(obj); 
}

function searchMember(){
	var frmObj = document.forms["search_form"];
	frmObj.submit();
}
</script>
{/literal}

<script src="js/jquery-ui-1.9.2.custom.js"></script>
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.9.2.custom.css" />

<form id="search_form" name="search_form" method="get" onsubmit="searchMember(); return false;">
<div class="qsbox">
<label>Registered between:</label>
<span>
	<br/>
	<input type="hidden" id="action" name="action"  value="{$smarty.get.action}"/>
	<input type="text" name="fromdate" id="fromdate" readonly="readonly" value="{$smarty.get.fromdate}" class="box" size="5"/>
	and
	<input type="text" name="todate" id="todate" readonly="readonly" value="{$smarty.get.todate}" class="box" />
</span><br/>

<label>Username:</label>
<span><input type="text" id="search_username" name="u" class="box" value="{$smarty.get.u}" ></span><br/>

<label>{#Gender#}:</label>
<span>
<select name="g" id="g_gender" class="box" >
<option value=""> {#Any#}  </option>
{html_options options=$gender selected=$smarty.get.g} 
</select>
</span><br/>

<label>Country:</label>
<span>
<select id="country" name="co" onchange="loadOptionState('#state', this.options[this.selectedIndex].value, '');loadOptionCity('#city', 0, '')" class="box"></select>
</span><br/>

<label>State:</label>
<span>
<select id="state" name="s" onchange="loadOptionCity('#city', this.options[this.selectedIndex].value, '')" class="box"></select>
</span><br/>

<label>City:</label>
<span>
<select id="city" name="ci"  class="box"></select>
</span><br/>
<a href="#" onclick="searchMember(); return false;" class="butregisin">SEARCH</a>
</div>

</form>
<script>
{literal}
	jQuery(function() {
		jQuery("#fromdate").datepicker({ dateFormat: 'yy-mm-dd', minDate: new Date(2012, 1 - 1, 1), maxDate: 0});
		jQuery("#todate").datepicker({ dateFormat: 'yy-mm-dd', minDate: new Date(2012, 1 - 1, 1), maxDate: 0});
	});
{/literal}
</script>