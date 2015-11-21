<!-- {$smarty.template} -->
<script>
{literal}
jQuery(document).ready(function($) {
	window.onhashchange = function () {
		loadByHash();
	}

	loadByHash();
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
<div id="container-content">
<h1>SEARCH</h1>
<div style=" float:left; background:url(images/cm-theme/bg-search-page.png) no-repeat;">
<!--start quick search -->
<ul id="container-btn-quick-search">
    <li>
        <a href="#" onclick="return doSearch('action=search&type=searchNewestMembers')">
        <img src="images/cm-theme/news.png" width="114" height="134" />
        <p>{#Newest#}</p>
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
        <a href="#" onclick="return doSearch('action=search&type=searchOnline');">
        <img src="images/cm-theme/online.png" width="114" height="134" />
        <p>Online</p>
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
<!--end quick search -->
</div>
</div>

<div class="container-content-search">
<!--start search form -->
<div id="container-seach-form">
    <label>{#USERNAME#}:</label>
    <input name="username" type="text" id="username" class="formfield_01" style="width:188px !important;"/>
    <div style="float:left; margin-left:10px;">
    <a href="#" onclick="return  doSearch('action=search&type=searchUsername&username='+jQuery('#username').val())"  class="btn-search">Suche</a>
    </div>
    <br class="clear" />

	<form id="search_form">
    <input name="action" type="hidden" value="search" id="action"/>
    <input name="type" type="hidden" value="searchMembers" id="action"/>
    <label>{#Gender#}:</label>
    <select  name="q_gender" id="q_gender" class="formfield_01">
    <option value="">{#Any#}</option>
    {html_options options=$gender} 
    </select>
    
    <label>{#Age#}: </label>
    {if $smarty.session.right_search.q_minage neq ""}
    {assign var="select_q_minage" value=$smarty.session.right_search.q_minage}
    {else}
    {assign var="select_q_minage" value=18}
    {/if}
    <select name="q_minage" id="q_minage" onchange="ageRange('q_minage', 'q_maxage')" class="formfield_01" style="width:74px !important;">
    {html_options options=$age selected=$select_q_minage}
    </select>
    <div style="float:left; text-align:center; width:52px; font-size:11px; font-weight:bold;">{#To#}</div>
    {if $smarty.session.right_search.q_maxage neq ""}
    {assign var="select_q_maxage" value=$smarty.session.right_search.q_maxage}
    {else}
    {assign var="select_q_maxage" value=$select_q_minage+2}
    {/if}
    <select name="q_maxage" id="q_maxage" class="formfield_01" style="width:74px !important;">
    {html_options options=$age selected=$select_q_maxage}  
    </select>
    <br class="clear" />
    <label>{#Have_Photo#}: </label>
    <select name="q_picture" id="q_picture" class="formfield_01">
    {html_options options=$picyesno selected=$smarty.session.right_search.q_picture}  
    </select>
    
    <label>{#Country#}:</label>
    <select id="q_country" name="country" onchange="loadOptionState('q_state', this.options[this.selectedIndex].value, '');loadOptionCity('q_city', $('q_state')[$('q_state').selectedIndex].value, '')" class="formfield_01"><option></option></select>
    <br class="clear" />
    <label>{#State#}:</label>
    <select id="q_state" name="state" onchange="loadOptionCity('q_city', this.options[this.selectedIndex].value, '')" class="formfield_01"><option></option></select>
   
    <label>{#City#}:</label>
    <select id="q_city" name="city" class="formfield_01"><option></option></select>
    <br class="clear" />
    <label></label>
    <div style="float:right; margin-right:132px;"><a href="#" class="btn-search" onclick="return doSearch(jQuery('#search_form').serialize())" style="width:179px;">{#search#}</a></div>
	</form>    
    </div>
<!--end search form -->
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
		ajaxRequest("loadOptionCountry", "", "", "q_loadOptionCountry", "reportError");
		ageRange('q_minage', 'q_maxage');
	});
{/literal}
</script>