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
		jQuery('#search_online_button').trigger('click');
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

<div id="container-content-search">
    <ul id="container-btn-quick-search">
    	<li>
        	<a href="#" onclick="return doSearch('action=search&type=searchNewestMembers')">
            <img src="images/cm-theme/news.png" width="114" height="134" />
            <p>{#Newest#}</p>
            </a>
        </li>
        <li>
        	<a href="#" id="search_online_button" onclick="return doSearch('action=search&type=searchOnline');">
            <img src="images/cm-theme/online.png" width="114" height="134" />
            <p>Online</p>
            </a>
        </li>
        <li>
        	<a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=w')">
            <img src="images/cm-theme/man.png" width="114" height="134" />
            <p>{#MAN_SEARCH_WOMAN#}</p>
            </a>
        </li>
        <li>
        	<a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=m')">
            <img src="images/cm-theme/gay.png" width="114" height="134" />
            <p>{#MAN_SEARCH_MAN#}</p>
            </a>
        </li>
        
        <li>
        	<a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=m')">
            <img src="images/cm-theme/women.png" width="114" height="134" />
            <p>{#WOMAN_SEARCH_MAN#}</p>
            </a>
        </li>
        <li>
        	<a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=w')">
            <img src="images/cm-theme/less.png" width="114" height="134" />
            <p>{#WOMAN_SEARCH_WOMAN#}</p>
            </a>
        </li>
    </ul>
    
    <div id="container-seach-form">
    <label>{#USERNAME#}:</label>
    <input name="username" type="text" id="username" class="formfield_01" style="width:180px !important;"/>
    <div style="float:left; margin-right:10px; margin-left:5px;"><a href="#" class="btn-red-s" onclick="return  doSearch('action=search&type=searchUsername&username='+jQuery('#username').val())">Suche</a></div>


	<form id="search_form">
    <input name="action" type="hidden" value="search" id="action"/>
    <input name="type" type="hidden" value="searchMembers" id="action"/>
    <label style="margin-left:40px;">{#Gender#}:</label>
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
    <select name="q_minage" id="q_minage" onchange="ageRange('q_minage', 'q_maxage')" class="formfield_01" style="width:100px !important;">
    {html_options options=$age selected=$select_q_minage}
    </select>
    <div style="float:left; text-align:center; width:52px; font-size:14px; font-weight:bold;">{#To#}</div>
    {if $smarty.session.right_search.q_maxage neq ""}
    {assign var="select_q_maxage" value=$smarty.session.right_search.q_maxage}
    {else}
    {assign var="select_q_maxage" value=$select_q_minage+2}
    {/if}
    <select name="q_maxage" id="q_maxage" class="formfield_01" style="width:100px !important;">
    {html_options options=$age selected=$select_q_maxage}  
    </select>

    <label style="margin-left:50px;">{#Have_Photo#}: </label>
    <select name="q_picture" id="q_picture" class="formfield_01">
    {html_options options=$picyesno selected=$smarty.session.right_search.q_picture}  
    </select>
	<br class="clear" />
    <label>{#Country#}:</label>
    <select id="q_country" name="country" onchange="loadOptionState('#q_state', this.options[this.selectedIndex].value, '');loadOptionCity('#q_city', $('q_state')[$('q_state').selectedIndex].value, ''); if((jQuery(this).val()!=1)) jQuery('#state_span').hide(); else jQuery('#state_span').show();" class="formfield_01"><option></option></select>

	<span id="state_span" style="display: none">
    <label style="margin-left:50px;">{#State#}:</label>
    <select id="q_state" name="state" onchange="loadOptionCity('#q_city', this.options[this.selectedIndex].value, '')" class="formfield_01"><option></option></select>
	</span>
	<span style="display: none">
    <label>{#City#}:</label>
    <select id="q_city" name="city" class="formfield_01"><option></option></select>
    <br class="clear" />
	</span>
	<br class="clear" />
    <label></label>
    <div style="float:left; margin-left:150px;"><a href="#" class="btn-red" onclick="return doSearch(jQuery('#search_form').serialize())">{#search#}</a></div>
	</form>    
    </div>
    
</div>

<div id="container-content" style="display: none;">
<span id='search-result-container'></span>
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
		ajaxRequest("loadOptionCountry", "", "", q_loadOptionCountry, reportError);
		ageRange('q_minage', 'q_maxage');
		//jQuery('#search_online_button').trigger('click');
	});
{/literal}
</script>
