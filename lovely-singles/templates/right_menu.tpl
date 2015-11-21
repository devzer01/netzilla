{if !$smarty.session.sess_externuser}
<form id="qsearch_form" name="qsearch_form" method="post" action="proc_from.php?from=./?action=search&amp;card={$card}" >
<div class="qsboxsearch">
	<label><input name="q_forsearch" type="radio" value="1" {if $smarty.session.right_search.q_forsearch eq 1}checked="checked"{/if}/><strong>{#Lonely_heart_ads#}</strong></label>
    <label><input name="q_forsearch" type="radio" value="2" {if $smarty.session.right_search.q_forsearch neq 1}checked="checked"{/if} /><strong>{#Profile#}</strong></label>
<!--<label>{#Nickname#} :</label><input type="text" id="q_nickname" name="q_nickname" class="box"  value="{$smarty.get.q_nickname}"/>-->
<label>{#Gender#} :</label>
<select  name="q_gender" id="q_gender" class="box">
<option value="">{#Any#}</option>
{html_options options=$gender selected=$smarty.session.right_search.q_gender} 
</select>
<label>{#Have_Photo#} :</label>
<select name="q_picture" id="q_picture" class="box">
{html_options options=$picyesno selected=$smarty.session.right_search.q_picture}  
</select>
<label>{#Country#} :</label>
<select id="q_country" name="country" onchange="loadOptionState('q_state', this.options[this.selectedIndex].value, '');loadOptionCity('q_city', $('q_state')[$('q_state').selectedIndex].value, '')" class="box"><option></option></select>
<label>{#State#} :</label><select id="q_state" name="state" onchange="loadOptionCity('q_city', this.options[this.selectedIndex].value, '')" class="box"><option></option></select>
<label>{#City#} :</label><select id="q_city" name="city" class="box"><option></option></select>
<script language="javascript" type="text/javascript">
	{if ($smarty.session.right_search.country neq "") or ($smarty.session.right_search.country neq 0)}
		q_country_select = {$smarty.session.right_search.country};
	{/if}

	{if ($smarty.session.right_search.state neq "") or ($smarty.session.right_search.state neq 0)}
		q_state_select = {$smarty.session.right_search.state};
	{/if}

	{if ($smarty.session.right_search.city neq "") or ($smarty.session.right_search.city neq 0)}
		q_city_select = {$smarty.session.right_search.city};
	{/if}
	
	{literal}
		ajaxRequest("loadOptionCountry", "", "", "q_loadOptionCountry", "reportError");
		
		var Opt = Array();
		function clickInOpt(obj){
			Opt[obj.name] = obj.value; 
		}

		function clickOutOpt(obj){
			Opt[obj.name] = ""; 
			obj.checked=false;
		}

		function chkOpt(obj){
			if(obj.value==Opt[obj.name]){ 
				clickOutOpt(obj); 
			}else{
				clickInOpt(obj); 
			}
		}
	{/literal}
</script>
<label>{#Age#} :</label>
{if $smarty.session.right_search.q_minage neq ""}
{assign var="select_q_minage" value=$smarty.session.right_search.q_minage}
{else}
{assign var="select_q_minage" value=16}
{/if}
<select name="q_minage" id="q_minage" onchange="ageRange('q_minage', 'q_maxage')" class="boxage">
{html_options options=$age selected=$select_q_minage}  
</select>
<label class="to">{#To#}</label>
{if $smarty.session.right_search.q_maxage neq ""}
{assign var="select_q_maxage" value=$smarty.session.right_search.q_maxage}
{else}
{assign var="select_q_maxage" value=$select_q_minage+2}
{/if}
<select name="q_maxage" id="q_maxage" class="boxagelast">
{html_options options=$age selected=$select_q_maxage}  
</select>
<br clear="all" />
<br clear="all" />
<a href="#" onclick="document.getElementById('qsearch_form').submit(); return false;" class="butsearchbox">{#Search#}</a>
{if $smarty.session.sess_username != ""}
<a href="./?action=adv_search" class="butsearchbox">{#ADV_SEARCH#}</a>
{/if}
</div>
</form>
<script language="javascript" type="text/javascript">ageRange('q_minage', 'q_maxage');</script>
{/if}