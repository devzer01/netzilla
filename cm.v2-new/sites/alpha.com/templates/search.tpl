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
<div class="container-search-box">
            <div class="container-search-form">
            	<h1>{#Search#}</h1>
            	<label>Benutzername:</label>
            	<input name="" type="text" class="formfield_01" style=" width:143px; margin-right:10px"/>
                <a href="#" onclick="return doSearch('action=search&type=searchUsername&username='+jQuery('#username').val())" class="btn-search" style="width:60px; margin-right:10px;">Suche</a>
                <form id="search_form">
					<input name="action" type="hidden" value="search" id="action"/>
					<input name="type" type="hidden" value="searchMembers" id="type"/>
                	<label style="width:90px !important;">{#Gender#}:</label>
                	<select name="q_gender" id='q_gender' class="formfield_01" style=" width:85px; margin-right:10px">
                		<option value="">{#Any#}</option>
						{html_options options=$gender}
                	</select>
                	
	                <label style="width:70px !important;">{#Have_Photo#}:</label>
	                <select name="q_picture" id='q_picture' class="formfield_01" style=" width:86px; margin-right:10px">
	                	{html_options options=$picyesno selected=$smarty.session.right_search.q_picture}
	                </select>
	                <br class="clear" />
	                
                <label>{#Age#}:</label>
                
                {if $smarty.session.right_search.q_minage neq ""}
					{assign var="select_q_minage" value=$smarty.session.right_search.q_minage}
				{else}
					{assign var="select_q_minage" value=18}
				{/if}
                
                <select name="q_minage" id='q_minage' class="formfield_01" onchange="ageRange('#q_minage', '#q_maxage')" style=" width:93px; margin-right:10px">
                	{html_options options=$age selected=$select_q_minage}
                </select>
                
                 {if $smarty.session.right_search.q_maxage neq ""}
					{assign var="select_q_maxage" value=$smarty.session.right_search.q_maxage}
				{else}
					{assign var="select_q_maxage" value=$select_q_minage+2}
				{/if}
                
                	<label style="width:30px !important;">bis</label>
                <select name="q_maxage" id='q_maxage' class="formfield_01" style=" width:93px; margin-right:10px">
                	{html_options options=$age selected=$select_q_maxage}  
                </select>
                
                
                <label style="width:90px !important;">{#Country#}:</label>
                <select id="q_country" name="country" onchange="loadOptionState('#q_state', this.options[this.selectedIndex].value, ''); if((jQuery(this).val()!=1)) jQuery('#state_span').hide(); else jQuery('#state_span').show();" name="" class="formfield_01" style=" width:250px; margin-right:10px"></select>
                <br class="clear" />
				<span id="state_span" style="display: none">
					<select id="q_state" name="state" class="formfield_01" style="width:140px; margin-bottom:10px;">
					</select>
				</span>
				<span style="display: none">
		            <label>{#City#}:</label>
					<select id="q_city" name="city" class="formfield_01" style="width:140px; margin-bottom:10px;">
					</select>
				</span>
                <label></label><a href="#" onclick="return doSearch($('#search_form').serialize())" class="btn-login">Suche</a>
                <br class="clear" />
                </form>
            </div>
            <div class="container-search-icon">
            	<a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=w')" class="search-m"><span>Mann sucht Frau</span></a>
                <a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=m')" class="search-w"><span>Frau sucht Mann</span></a>
                <a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=m')" class="search-mm"><span>Mann sucht Mann</span></a>
                <a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=w')" class="search-ww"><span>Frau sucht Frau</span></a>
            </div>
        </div>
<!--end icon search -->
     <br class="clear" />
<div id="container-content" style="display: none;">
<span id='search-result-container'></span>
</div>



<script type="text/javascript">
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
