{include file="search_result_box.tpl"}
<script>
{literal}
jQuery(document).ready(function($) {
	window.onhashchange = function () {
		loadByHash();
	}

	if(window.location.hash.replace("#", "")!="")
		loadByHash();
	else
		jQuery('#search_women_button').trigger('click');
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