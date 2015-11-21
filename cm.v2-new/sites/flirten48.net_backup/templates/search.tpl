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
	jQuery('#search-result-container').show();
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

<section>
    <div class="container-news-bg">
        <div class="container-news">
        <!-- -->
            <section>
            	<ul class="container-news-box">
                	<li>
                        <img src="images/cm-theme/man.jpg" width="183" height="220" />
                        <a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=w')" class="settings-button"><img src="images/cm-theme/bg-news.png" width="183" height="220" /></a>
                        
                    </li>
                    <li>
                        <img src="images/cm-theme/frau.jpg" width="183" height="220" />
                        <a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=m')" class="settings-button"><img src="images/cm-theme/bg-news.png" width="183" height="220" /></a>
                        
                    </li>
                    <li>
                        <img src="images/cm-theme/mm.jpg" width="183" height="220" />
                        <a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=m')" class="settings-button"><img src="images/cm-theme/bg-news.png" width="183" height="220" /></a>
                        
                    </li>
                    <li>
                        <img src="images/cm-theme/ff.jpg" width="183" height="220" />
                        <a href="#" class="settings-button" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=w')"><img src="images/cm-theme/bg-news.png" width="183" height="220" /></a>
                        
                    </li>
                </ul>   
			</section>
			
			<div class="container-search-box">
            	<div class="container-name-search-name">
                    <h1>Name Suchen:</h1>
                    <span>Benutzername</span><input id="username" name="username" type="text" class="formfield_01" style="width:170px !important;"/>
                    <a href="#" class="btn-search" onclick="return doSearch('action=search&type=searchUsername&username='+jQuery('#username').val())">SUCHE</a>
                </div>
                <div class="container-name-search">
                	<form id="search_form">
    					<input name="action" type="hidden" value="search" id="action"/>
    					<input name="type" type="hidden" value="searchMembers" id="action"/>
                    <h1>Suchen:</h1>
                    <span style="width:80px;">{#Gender#}:</span>
                    <select  name="q_gender" id="q_gender" class="formfield_01" style="width:80px !important;">
    					<option value="">{#Any#}</option>
    					{html_options options=$gender} 
    				</select>
                    <span style="margin-left:20px; width:80px;">{#Age#}:</span>
                    {if $smarty.session.right_search.q_minage neq ""}
					    {assign var="select_q_minage" value=$smarty.session.right_search.q_minage}
					{else}
					    {assign var="select_q_minage" value=18}
					{/if}
					    <select name="q_minage" id="q_minage" onchange="ageRange('q_minage', 'q_maxage')" class="formfield_01" style="width:105px !important;">
					    {html_options options=$age selected=$select_q_minage}
					    </select>
					    <span style="width:20px !important; margin:0 10px;">{#To#}</span>
					    {if $smarty.session.right_search.q_maxage neq ""}
					    	{assign var="select_q_maxage" value=$smarty.session.right_search.q_maxage}
					    {else}
					    	{assign var="select_q_maxage" value=$select_q_minage+2}
					    {/if}
					    <select name="q_maxage" id="q_maxage" class="formfield_01" style="width:105px !important;">
					    	{html_options options=$age selected=$select_q_maxage}  
					    </select><br class="clear"/>
                    	<span style="width:80px;">{#Have_Photo#}:</span>
                    		<select name="q_picture" id="q_picture" class="formfield_01" style="width:80px !important;">
    							{html_options options=$picyesno selected=$smarty.session.right_search.q_picture}  
    						</select>
                    	<span style="margin-left:20px; width:80px;">{#Country#}:</span>
                    	<select style="width:182px !important;" id="q_country" name="country" class="formfield_01" onclick="return doSearch(jQuery('#search_form').serialize())"><option></option></select>                    	
                    	
                   	 	<a href="#" class="btn-search" onclick="return doSearch(jQuery('#search_form').serialize())">{#search#}</a>
                   	 	</form>
					</div>
            </div>
            <br class="clear" />
            <!-- -->
        </div>

        </div>
    </section>
<div class="container-favoriten">
<h1 class="favoriten-title">Suchergebnisse</h1>
<section id='search-result-container' style='display: none;'></section>
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
		ageRange('#q_minage', '#q_maxage');
		//jQuery('#search_online_button').trigger('click');
	});
{/literal}
</script>