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

<!--icon search -->

    	<ul class="container-icon-search">
        	<li><a href="#" id="search_online_button" onclick="return doSearch('action=search&type=searchOnline');"><img src="images/cm-theme/s-online.png" width="114" height="134" /><p>Online</p></a></li>
            <li><a href="#" onclick="return doSearch('action=search&type=searchNewestMembers')"><img src="images/cm-theme/s-news.png" width="114" height="134" /><p>{#Newest#}</p></a></li>
            <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=w')"><img src="images/cm-theme/s-man.png" width="114" height="134" /><p>{#MAN_SEARCH_WOMAN#}</p></a></li>
            <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=m')"><img src="images/cm-theme/s-women.png" width="114" height="134" /><p>{#WOMAN_SEARCH_MAN#}</p></a></li>
            <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=m')"><img src="images/cm-theme/s-gay.png" width="114" height="134" /><p>{#MAN_SEARCH_MAN#}</p></a></li>
            <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=w')"><img src="images/cm-theme/s-less.png" width="114" height="134" /><p>{#WOMAN_SEARCH_WOMAN#}</p></a></li>
        </ul>

<!--end icon search -->
    <div style="float:right; margin-top:20px;">
<!--box search -->    
    <div class="container-box-content-01">
    	<div class="box-content-01-t-l"></div>
        <div class="box-content-01-t-m"></div>
        <div class="box-content-01-t-r"></div>
        <div class="box-content-01-m-l search-height"></div>
        <div class="box-content-01-m-m search-height">
        	<h1 style="margin-bottom:10px;">{#Search#}</h1>
        	<input name="username" type="text" id="username" placeholder="Search Name:" class="formfield_01" style="width:130px; margin-bottom:10px;">
            <a href="#" class="btn-search" style="width:60px;" onclick="return  doSearch('action=search&type=searchUsername&username='+jQuery('#username').val())">Search</a><br class="clear" />
			<form id="search_form">
			<input name="action" type="hidden" value="search" id="action"/>
			<input name="type" type="hidden" value="searchMembers" id="action"/>
            <label>{#Gender#}:</label>
			{if $smarty.session.right_search.q_minage neq ""}
			{assign var="select_q_minage" value=$smarty.session.right_search.q_minage}
			{else}
			{assign var="select_q_minage" value=18}
			{/if}
			<select name="q_gender" id="q_gender" class="formfield_01" style="width:140px; margin-bottom:10px;">
				<option value="">{#Any#}</option>
				{html_options options=$gender}
			</select>
            <label>{#Age#}:</label>
			<select name="q_minage" id="q_minage" onchange="ageRange('q_minage', 'q_maxage')" class="formfield_01" style="width:50px; margin-bottom:10px;">
				{html_options options=$age selected=$select_q_minage}
			</select>
            <label style="margin-left:10px; width:20px !important;">{#To#}:</label>
			 {if $smarty.session.right_search.q_maxage neq ""}
			{assign var="select_q_maxage" value=$smarty.session.right_search.q_maxage}
			{else}
			{assign var="select_q_maxage" value=$select_q_minage+2}
			{/if}
			<select name="q_maxage" id="q_maxage" class="formfield_01" style="width:50px; margin-bottom:15px;">
				{html_options options=$age selected=$select_q_maxage}  
			</select>
            <label>{#Have_Photo#}:</label>
			<select name="q_picture" id="q_picture" class="formfield_01" style="width:140px; margin-bottom:10px;">
				{html_options options=$picyesno selected=$smarty.session.right_search.q_picture}
			</select>
            <label>{#Country#}:</label>
			<select id="q_country" name="country" onchange="loadOptionState('#q_state', this.options[this.selectedIndex].value, ''); if((jQuery(this).val()!=1)) jQuery('#state_span').hide(); else jQuery('#state_span').show();" class="formfield_01" style="width:140px; margin-bottom:10px;">
			</select>
			<span id="state_span" style="display: none">
            <label>{#State#}:</label>
			<select id="q_state" name="state" class="formfield_01" style="width:140px; margin-bottom:10px;">
			</select>
			</span>
			<span style="display: none">
            <label>{#City#}:</label>
			<select id="q_city" name="city" class="formfield_01" style="width:140px; margin-bottom:10px;">
			</select>
			</span>
            <a href="#" class="btn-search" onclick="return doSearch(jQuery('#search_form').serialize())" style="width:200px; line-height:30px !important;">{#search#}</a>
			</form>
        </div>
        
        <div class="box-content-01-m-r search-height"></div>
        
        <div class="box-content-01-b-l"></div>
        <div class="box-content-01-b-m"></div>
        <div class="box-content-01-b-r"></div>
    </div>
<!--End box search --> 

    </div>
     <br class="clear" />

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
		q_country_select = 0;
		q_state_select = 0;
		ajaxRequest("loadOptionCountry", "", "", q_loadOptionCountry, reportError);
	});
{/literal}
</script>
