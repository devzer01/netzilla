<!-- {$smarty.template} -->
{if !$smarty.session.sess_externuser}
<form id="qsearch_form" name="qsearch_form" method="post" action="proc_from.php?from=./?action=search&amp;card={$card}" >
<div style="float:left;">
    <label><input name="q_forsearch" type="radio" value="1" {if $smarty.session.right_search.q_forsearch eq 1}checked="checked"{/if} style=" background:none;"/></label>
    <label><strong>{#Lonely_heart_ads#}</strong></label>
    <label><input name="q_forsearch" type="radio" value="2" {if $smarty.session.right_search.q_forsearch neq 1}checked="checked"{/if} style=" background:none;"/></label>
    <label><strong>{#Profile#}</strong></label>
<br class="clear" />
    <label class="quicktext">{#Gender#}</label>
    <label>    
    <select  name="q_gender" id="q_gender" class="input-quick-select">
    <option value="">{#Any#}</option>
    {html_options options=$gender selected=$smarty.session.right_search.q_gender} 
    </select>
    </label><br class="clear" />
    
    <label class="quicktext">{#Have_Photo#}</label>
    <label>
    <select name="q_picture" id="q_picture" class="input-quick-select">
    {html_options options=$picyesno selected=$smarty.session.right_search.q_picture}  
    </select>
    </label><br class="clear" />
    
    <label class="quicktext">{#Country#}</label>
    <label>
    <select id="q_country" name="country" onchange="loadOptionState('q_state', this.options[this.selectedIndex].value, '');loadOptionCity('q_city', 0, '')" class="input-quick-select"><option></option></select>
    </label><br class="clear" />
    
    <label class="quicktext">{#State#}</label>
    <label>
    <select id="q_state" name="state" onchange="loadOptionCity('q_city', this.options[this.selectedIndex].value, '')" class="input-quick-select"><option></option></select> 
    </label><br class="clear" />
    
    <label class="quicktext">{#City#}</label>
    <label>
    <select id="q_city" name="city" class="input-quick-select"><option></option></select>
    </label><br class="clear" />
    
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
    
    <label class="quicktext">{#Age#}</label>
    <label>
    {if $smarty.session.right_search.q_minage neq ""}
    {assign var="select_q_minage" value=$smarty.session.right_search.q_minage}
    {else}
    {assign var="select_q_minage" value=16}
    {/if}
    <select name="q_minage" id="q_minage" onchange="ageRange('q_minage', 'q_maxage')" class="input-quick-select02">
    {html_options options=$age selected=$select_q_minage}  
    </select>
    </label>
    
    <label>{#To#}</label>
    <label>
    {if $smarty.session.right_search.q_maxage neq ""}
    {assign var="select_q_maxage" value=$smarty.session.right_search.q_maxage}
    {else}
    {assign var="select_q_maxage" value=$select_q_minage+2}
    {/if}
    <select name="q_maxage" id="q_maxage" class="input-quick-select02">
    {html_options options=$age selected=$select_q_maxage}  
    </select>
    </label><br class="clear" />
    <label class="quicktext"></label>
    <label>
    <a href="#" onclick="document.getElementById('qsearch_form').submit(); return false;" class="form-btn">{#Search#}</a>
    <!--{if $smarty.session.sess_username != ""}
    <a href="./?action=adv_search" class="form-btn">{#ADV_SEARCH#}</a>
    {/if} -->
    </label>
</div>
</form>
<script language="javascript" type="text/javascript">ageRange('q_minage', 'q_maxage');</script>
{/if}