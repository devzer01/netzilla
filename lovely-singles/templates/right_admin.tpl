{literal}
<script language="javascript" type="text/javascript">
country_select = "{/literal}{$smarty.get.co}{literal}";
state_select = "{/literal}{$smarty.get.s}{literal}";
city_select = "{/literal}{$smarty.get.ci}{literal}";

window.onload = function(){
	ajaxRequest("loadOptionCountry", "", "", "loadOptionCountry", "reportError");
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
      //var gender = document.getElementById("q_gender").options[document.getElementById("q_gender").selectedIndex].value;
      //var country = document.getElementById("country").options[document.getElementById("country").selectedIndex].value;
      //var city = document.getElementById("city").options[document.getElementById("city").selectedIndex].value;
      //var state = document.getElementById("state").options[document.getElementById("state").selectedIndex].value;
      //var username = document.getElementById("search_username").value;
      var frmObj = document.forms["search_form"];

      //frmObj.action = "?action=admin_manageuser&g=" + gender + "&co=" + country + "&ci=" + city + "&s=" +  state + "&u=" + username;
      //location = "?action=admin_manageuser&g=" + gender + "&co=" + country + "&ci=" + city + "&s=" +  state + "&u=" + username;
      frmObj.submit();
}

</script>
{/literal}

<form id="search_form" name="search_form" method="get" onsubmit="searchMember(); return false;">
<div class="qsbox">
<label>Username:</label>
<span><input type="hidden" id="action" name="action"  value="{$smarty.get.action}"/><input type="text" id="search_username" name="u" class="box" value="{$smarty.get.u}" ></span>

<label>Fake or Real:</label>
<span>
<select name="f" id="f" class="box" >
<option value=""> {#Any#}  </option>
<option value="0">Real</option>
<option value="1">Fake</option>
</select>
</span>

<label>{#Gender#}:</label>
<span>
<select name="g" id="g_gender" class="box" >
<option value=""> {#Any#}  </option>
{html_options options=$gender selected=$smarty.get.g} 
</select>
</span>

<label>Looking for:</label>
<span>
<select name="lg" id="l_gender" class="box" >
<option value=""> {#Any#}  </option>
{html_options options=$gender selected=$smarty.get.g} 
</select>
</span>

<label>Country:</label>
<span>
<select id="country" name="co" onchange="loadOptionState('state', this.options[this.selectedIndex].value, '');loadOptionCity('city', 0, '')" class="box"></select>
</span>

<label>State:</label>
<span>
<select id="state" name="s" onchange="loadOptionCity('city', this.options[this.selectedIndex].value, '')" class="box"></select>
</span>

<label>City:</label>
<span>
<select id="city" name="ci"  class="box"></select>
</span>

<a href="javascript:searchMember()" class="butsearch">SEARCH</a>

</div>

</form>