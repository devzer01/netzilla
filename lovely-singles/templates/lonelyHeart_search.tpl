<div class="result-box">
<h1>{#Lonely_Heart_Ads#} {#search#}</h1>
<div class="result-box-inside-nobg">
<div class="container-lonely-search-box">
<br class="clear" />
<form id="lonelysearch_form" name="lonelysearch_form" method="post" action="proc_from.php?from=./?action=search&card={$card}" >
<input id="q_forsearch" name="q_forsearch" type="hidden" value="1"/>

<label>{#Nickname#}:</label>
<span>
<input type="text" id="q_nickname" name="q_nickname" value="{$smarty.session.right_search.q_nickname}" style="width:150px" />
<input type="hidden" id="q_username" name="q_username">
</span>

<label>{#Gender#}:</label>
<span>
<select  name="q_gender" id="q_gender" style="width:155px">
<option value="">{#Any#}</option>
{html_options options=$gender selected=$smarty.session.right_search.q_gender} 
</select>
</span>
<br class="clear" />
<label>{#Have_Photo#}:</label>
<span>
<select name="q_picture" id="q_picture" style="width:155px">
{html_options options=$picyesno selected=$smarty.session.right_search.q_picture}  
</select>
</span>

<label>{#Country#}:</label>
<span>
<select id="q_country" name="country" style="width:155px" onchange="loadOptionState('q_state', this.options[this.selectedIndex].value, '');loadOptionCity('q_city', 0, '')"></select>
</span>

<br class="clear" />
<label>{#State#}:</label>
<span>
<select id="q_state" name="state" style="width:155px" onchange="loadOptionCity('q_city', this.options[this.selectedIndex].value, '')"></select>
</span>

<label>{#City#}:</label>
<span>
<select id="q_city" name="city" style="width:155px"></select>
<script language="javascript" type="text/javascript">
					{if ($smarty.session.right_search.country neq '') or ($smarty.session.right_search.country neq 0)}
						q_country_select = {$smarty.session.right_search.country};
					{/if}
					{if ($smarty.session.right_search.state neq '') or ($smarty.session.right_search.state neq 0)}
						q_state_select = {$smarty.session.right_search.state};
					{/if}
					{if ($smarty.session.right_search.city neq '') or ($smarty.session.right_search.city neq 0)}
						q_city_select = {$smarty.session.right_search.city};
					{/if}
					
					ajaxRequest('loadOptionCountry', '', '', 'q_loadOptionCountry', 'reportError');
					{literal}
					var Opt = Array();
					function clickInOpt(obj){
						Opt[obj.name] = obj.value; 
					}
					function clickOutOpt(obj){
						Opt[obj.name] = ''; 
						obj.checked=false;
					}
					function chkOpt(obj){
					 if(obj.value==Opt[obj.name]) clickOutOpt(obj); 
					 else clickInOpt(obj); 
					}
					{/literal}
					</script>
</span>

<br class="clear" />

<label>{#i_am#}:</label>
<span>
<select name="self_gender" id="self_gender" style="width:155px" class="box1px">
<option value="1" {if $smarty.session.gender eq '1'}selected="selected"{/if}>{#male#}</option>
<option value="2" {if $smarty.session.gender eq '2'}selected="selected"{/if}>{#female#}</option>
</select>
</span>

<label>{#Age#}:</label>
<span>
<select name="q_minage" id="q_minage" style="width:45px" class="box1px" onchange="ageRange('q_minage', 'q_maxage')">
{html_options options=$age selected=$smarty.session.right_search.q_minage}  
</select>
&nbsp;{#To#}&nbsp;
{if $smarty.session.right_search.q_maxage neq ''}
{assign var="select_q_maxage" value=$smarty.session.right_search.q_maxage}
{else}
{assign var="select_q_maxage" value=99}
{/if}
<select name="q_maxage" id="q_maxage" style="width:45px" class="box1px">
{html_options options=$age selected=$select_q_maxage}  
</select>
</span>
<br class="clear" />
<label>&nbsp;</label><span><a href="#" onClick="document.getElementById('lonelysearch_form').submit();" class="buttonsearch">{#lonely_heart_ad_Search_Button#}</a></span>
<br class="clear" />
</form>
</div>
</div>
</div>
{include file="newest_lonely_heart.tpl"}