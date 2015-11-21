<!-- {$smarty.template} -->
{literal}
<script language="javascript" type="text/javascript">
jQuery(function()
{
	country_select = "{/literal}{$smarty.get.co}{literal}";
	state_select = "{/literal}{$smarty.get.s}{literal}";
	city_select = "{/literal}{$smarty.get.ci}{literal}";
	ajaxRequest("loadOptionCountry", "", "", loadOptionCountry, reportError);
});

</script>
{/literal}
<h1>Admin Search User</h1>
<div class="container-admin-search">
<form id="admin_search_form" name="admin_search_form" method="get">

<span>
<input type="hidden" id="action" name="action"  value="{$smarty.get.action}"/><input type="text" id="search_username" name="u" value="{$smarty.get.u}" placeholder="Username:"
class="formfield_admin" style="width:205px;"></span>
<br class="clear" />
<label>Fake or Real:</label>
<span>
<select name="f" id="f" class="formfield_admin" style="width:215px;">
<option value=""> {#Any#}  </option>
<option value="0" {if $smarty.get.f eq '0'} selected="selected"{/if}>Real</option>
<option value="1" {if $smarty.get.f eq '1'} selected="selected"{/if}>Fake</option>
</select>
</span>
<br class="clear" />
<label>Gender:</label>
<span>
<select name="g" id="g_gender" class="formfield_admin" style="width:215px;">
<option value=""> {#Any#}  </option>
{html_options options=$gender selected=$smarty.get.g} 
</select>
</span>
<br class="clear" />
<label>Looking for:</label>
<span>
<select name="lg" id="l_gender" class="formfield_admin" style="width:215px;">
<option value=""> {#Any#}  </option>
{html_options options=$gender selected=$smarty.get.lg} 
</select>
</span>
<br class="clear" />
<label>Country:</label>
<span>
<select id="country" name="co" onchange="loadOptionState('#state', this.options[this.selectedIndex].value, '');loadOptionCity('#city', 0, '')" class="formfield_admin" style="width:215px;"></select>
</span>
<br class="clear" />
<label>State:</label>
<span>
<select id="state" name="s" onchange="loadOptionCity('#city', this.options[this.selectedIndex].value, '')" class="formfield_admin" style="width:215px;"></select>
</span>
<br class="clear" />
<label>City:</label>
<span>
<select id="city" name="ci"  class="formfield_admin" style="width:215px;"></select>
</span>
<br class="clear" />
<label class="quicktext">Age</label>
<span>
{if $smarty.get.min_age neq ""}
{assign var="select_min_age" value=$smarty.get.min_age}
{else}
{assign var="select_min_age" value=18}
{/if}
<select name="min_age" id="min_age" onchange="ageRange('min_age', 'max_age')" class="formfield_admin" style="width:90px;">
{html_options options=$age selected=$select_min_age}  
</select>
<span style="float:left; padding:0 10px; color:#FFF; font-weight:bold; line-height:25px;">To</span> 
{if $smarty.get.max_age neq ""}
{assign var="select_max_age" value=$smarty.get.max_age}
{else}
{assign var="select_max_age" value=$select_min_age+2}
{/if}
<select name="max_age" id="max_age" class="formfield_admin" style="width:90px;">
{html_options options=$age selected=99}  
</select>
</span>
<a href="#" onclick="jQuery('#admin_search_form').submit(); return false;" class="butregisin" style="width:210px;">SEARCH</a>
<br class="clear" />
</form>
</div>