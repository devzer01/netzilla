<!-- {$smarty.template} -->
<script>
{literal}
jQuery(document).ready(function($) {
	window.onhashchange = function () {
		loadByHash();
	}

	if(window.location.hash.replace("#", "")!="")
		loadByHash();
	else
	{
		//jQuery('#search_online_button').trigger('click');
		doSearch(jQuery('#search_form').serialize());
	}
});

function loadByHash()
{
	if(window.location.hash.replace("#", "")!="")
	{
		var data = window.location.hash.replace("#", "");
		jQuery.get("",data, searchResultHandle);
	}
}

function doSearch()
{
	window.location.hash='#'+arguments[0];
	return false;
}

function page(src)
{
	return doSearch(src.href.substring(src.href.indexOf("?") + 1));
}

function searchResultHandle(data)
{
	jQuery('#search-result-container').parent().show();
	if(data)
	{
		jQuery('#search-result-container').html(data);
	}
	else
	{
		jQuery('#search-result-container').html("{/literal}<div align='center' style='padding:10px;'>{#NoResult#}</div>{literal}");
	}
}
{/literal}
</script>

<div class="container-box-content">
    <div class="container-left-form-search">
    	<div class="search-title-box"><span>Find Name, use and remix</span></div>
        <h1>Search Using:</h1>

        
        <div class="container-seach-form">
        <form id="search_form">
        <input name="action" type="hidden" value="search" id="action"/>
        <input name="type" type="hidden" value="searchMembers" id="action"/>
        <label>{#Gender#}:</label>
        <select  name="q_gender" id="q_gender" class="formfield_01">
        <option value="">{#Any#}</option>
        {html_options options=$gender} 
        </select>
        <br class="clear" />
        <label>{#Age#}: </label>
        {if $smarty.session.right_search.q_minage neq ""}
        {assign var="select_q_minage" value=$smarty.session.right_search.q_minage}
        {else}
        {assign var="select_q_minage" value=18}
        {/if}
        <select name="q_minage" id="q_minage" onchange="ageRange('q_minage', 'q_maxage')" class="formfield_01" style="width:120px !important;">
        {html_options options=$age selected=$select_q_minage}
        </select>
        <div style="float:left; text-align:center; width:50px; line-height:28px; font-size:14px; font-weight:bold;">{#To#}</div>
        {if $smarty.session.right_search.q_maxage neq ""}
        {assign var="select_q_maxage" value=$smarty.session.right_search.q_maxage}
        {else}
        {assign var="select_q_maxage" value=$select_q_minage+2}
        {/if}
        <select name="q_maxage" id="q_maxage" class="formfield_01" style="width:120px !important;">
        {html_options options=$age selected=$select_q_maxage}  
        </select>
        <br class="clear" />
        <label>{#Have_Photo#}: </label>
        <select name="q_picture" id="q_picture" class="formfield_01">
        {html_options options=$picyesno selected=$smarty.session.right_search.q_picture}  
        </select>
        <br class="clear" />
        <label>{#Country#}:</label>
        <select id="q_country" name="country" onchange="loadOptionState('q_state', this.options[this.selectedIndex].value, '');loadOptionCity('q_city', $('q_state')[$('q_state').selectedIndex].value, ''); if((jQuery(this).val()!=1)) jQuery('#state_span').hide(); else jQuery('#state_span').show();" class="formfield_01"><option></option></select>
        <br class="clear" />
        <span id="state_span" style="display: none">
        <label>{#State#}:</label>
        <select id="q_state" name="state" onchange="loadOptionCity('q_city', this.options[this.selectedIndex].value, '')" class="formfield_01"><option></option></select>
        <br class="clear" />
        </span>
        <span style="display: none">
        <label>{#City#}:</label>
        <select id="q_city" name="city" class="formfield_01"><option></option></select>
        <br class="clear" />
        </span>
        <label></label>
        <div style="float:right; margin-right:9px;"><a href="#" class="btn-using-search" onclick="return doSearch(jQuery('#search_form').serialize())">{#search#}</a></div>
        </form>    
        </div>
        
    </div>
    <div class="container-right-form-search">
    	<div style="width:502px; float:left; margin-bottom:10px;">
            <input name="username" type="text" id="username" class="formfield_01 name-search-field" placeholder="Benutzername"/> <br class="clear" />           
            <a href="#" class="btn-blue btn-name-search" onclick="return  doSearch('action=search&type=searchUsername&username='+jQuery('#username').val())">Suche</a>

        </div>
        <ul class="container-quick-search">
            <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=w')"><img src="images/cm-theme/quick-search.png" width="122" height="178" /></a></li>
            <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=m')"><img src="images/cm-theme/quick-search-02.png" width="122" height="178" /></a></li>
            <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=m')"><img src="images/cm-theme/quick-search-03.png" width="122" height="178" /></a></li>
            <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=w')"><img src="images/cm-theme/quick-search-04.png" width="122" height="178" /></a></li>
        </ul>
    </div>
    <br class="clear" />
</div>

<div id="container-content" style="display: none;">
<h1 class="title">Suche Result</h1>
	<span id='search-result-container' class="image_grid portfolio_4col"></span>
</div>


<script language="javascript" type="text/javascript">
{literal}	
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

	jQuery(function()
	{
		ajaxRequest("loadOptionCountry", "", "", "q_loadOptionCountry", "reportError");
		ageRange('q_minage', 'q_maxage');
		//jQuery('#search_online_button').trigger('click');
	});
{/literal}
</script>