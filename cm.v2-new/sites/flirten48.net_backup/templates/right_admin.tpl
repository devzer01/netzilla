<!-- {$smarty.template} -->
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
<h2>Admin Search User</h2>
<form id="admin_search_form" name="admin_search_form" method="get">
<div class="qsbox" style="margin-left: 10px">
<label>Username:</label>
<span><input type="hidden" id="action" name="action"  value="{$smarty.get.action}"/><input type="text" id="search_username" name="u" class="box" value="{$smarty.get.u}" ></span>
<br class="clear" />
<label>Fake or Real:</label>
<span>
<select name="f" id="f" class="box" >
<option value=""> {#Any#}  </option>
<option value="0" {if $smarty.get.f eq '0'} selected="selected"{/if}>Real</option>
<option value="1" {if $smarty.get.f eq '1'} selected="selected"{/if}>Fake</option>
</select>
</span>
<br class="clear" />
<label>Gender:</label>
<span>
<select name="g" id="g_gender" class="box" >
<option value=""> {#Any#}  </option>
{html_options options=$gender selected=$smarty.get.g} 
</select>
</span>
<br class="clear" />
<label>Looking for:</label>
<span>
<select name="lg" id="l_gender" class="box" >
<option value=""> {#Any#}  </option>
{html_options options=$gender selected=$smarty.get.lg} 
</select>
</span>
<br class="clear" />
<label>Country:</label>
<span>
<select id="country" name="co" onchange="loadOptionState('state', this.options[this.selectedIndex].value, '');loadOptionCity('city', 0, '')" class="box"></select>
</span>
<br class="clear" />
<label>State:</label>
<span>
<select id="state" name="s" onchange="loadOptionCity('city', this.options[this.selectedIndex].value, '')" class="box"></select>
</span>
<br class="clear" />
<label>City:</label>
<span>
<select id="city" name="ci"  class="box"></select>
</span>
<br class="clear" />
<label class="quicktext">Age</label>
<span>
{if $smarty.get.min_age neq ""}
{assign var="select_min_age" value=$smarty.get.min_age}
{else}
{assign var="select_min_age" value=18}
{/if}
<select name="min_age" id="min_age" onchange="ageRange('min_age', 'max_age')" class="input-quick-select02">
{html_options options=$age selected=$select_min_age}  
</select>
To 
{if $smarty.get.max_age neq ""}
{assign var="select_max_age" value=$smarty.get.max_age}
{else}
{assign var="select_max_age" value=$select_min_age+2}
{/if}
<select name="max_age" id="max_age" class="input-quick-select02">
{html_options options=$age selected=99}  
</select>
</span>
<a href="#" onclick="$('#admin_search_form').submit(); return false;" class="butregisin">SEARCH</a>

</div>

</form>