<!-- {$smarty.template} -->
<style>
{literal}
a.btn-admin{display:block; background:url(images/btn-yellow.png) repeat-x; width:170px; height:25px; color:#000; text-align:center; line-height:26px; font-size:12px; font-weight:bold; text-shadow: #fff 1px 1px 4px; float:left; margin-top:8px; border:2px solid #1c1d20; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; margin-right:5px;}
{/literal}
</style>
<div class="result-box">
	<h1>Copy fake profiles</h1>
	<div class="result-box-inside-nobg">
		<form id="searchProfilesForm">
			<input type="hidden" name="search" value="1"/>
			<table>
			<tr>
				<td>Server</td>
				<td>
					<select name="server">
						{foreach from=$servers key="key" item="name"}
						<option value="{$key}">{$name.url}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td>Age</td>
				<td>
					<select name="age_from">
						{section name="age_from" start=18 loop=100 step=1}
						<option value="{$smarty.section.age_from.index}">{$smarty.section.age_from.index}</option>
						{/section}
					</select> -
					<select name="age_to">
						{section name="age_to" start=18 loop=100 step=1}
						<option value="{$smarty.section.age_to.index}">{$smarty.section.age_to.index}</option>
						{/section}
					</select>
				</td>
			</tr>
			<tr>
				<td>Gender</td>
				<td>
					<select name="gender">
						<option value="2">Female</option>
						<option value="1">Male</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Location</td>
				<td>
					<select id="location" name="location">
						{foreach from=$states item="state"}
						{if $state.name}<optgroup label="{$state.name}">{/if}
							{foreach from=$state.cities item="item"}
								<option value="{$state.prefix}{$item.id}">{$item.name}</option>
							{/foreach}
							{foreach from=$state.states item="item"}
								<option value="{$state.prefix}{$item.id}">{$item.name}</option>
							{/foreach}
						{if $state.name}</optgroup>{/if}
						{/foreach}
					</select> <input type="button" onclick="nextLocation(); return false;" value="Next Location"/>
				</td>
			</tr>
			<tr>
				<td colspan="2"><a href="#" id="search_bth" class="btn-admin" onclick="searchProfiles(); return false;">Search</a></td>
			</tr>
			</table>
		</form>
		<br class="clear" /><br class="clear" />

		<div id="search-result-container"></div>
		<br clear="both"/><br/><br/><br/>
	</div>
</div>

<div id="dialog" title="Edit"></div>
<script>
{literal}
jQuery(document).ready(function($) {
	window.onhashchange = function () {
		loadByHash();
	}

	loadByHash();
});

function searchProfiles()
{
	data = jQuery('#searchProfilesForm').serialize();
	if(window.location.hash == ('#'+data))
		loadByHash();
	else
		window.location.hash='#'+data;
}

function loadByHash()
{
	if(window.location.hash.replace("#", "")!="")
	{
		var data = "action={/literal}{$smarty.get.action}{literal}&"+window.location.hash.replace("#", "");
		jQuery.get("", data, searchProfilesHandle);
	}
}

function searchProfilesHandle(data)
{
	if(data)
	{
		jQuery('#search-result-container').html(data);
	}
	else
	{
		jQuery('#search-result-container').html("{/literal}{#NoResult#}{literal}");
	}
}

function copyProfile(server, username)
{
	jQuery.ajax({url: "", data: "action={/literal}{$smarty.get.action}{literal}&server="+server+"&username="+username, success: copyProfilesHandle, dataType: "json"});
}

function copyProfilesHandle(data)
{
	if(data)
	{
		if(data.action)
		{
			eval(data.action);
		}
	}
}

function showEditProfile(server, username)
{
	var url = "?action={/literal}{$smarty.get.action}{literal}&server="+server+"&username="+encodeURIComponent(username)+"&getOnly=1";
	jQuery("#dialog").load(url).dialog({ width: 550 });
	//jQuery.ajax({{/literal}url: "?action={$smarty.get.action}&server="+server+"&username="+username+"&getOnly=1"{literal}, success: copyProfilesHandle, dataType: "json"});
}

function page(src)
{
	argument = src.href.substring(src.href.indexOf("?") + 1);
	window.location.hash = "#"+argument;
	return false;
}

function nextLocation()
{
	var nextElement = jQuery('#location option:selected').next('option');
	var reload=false;
	if (nextElement.length > 0)
	{
		jQuery('#location option:selected').removeAttr('selected').next('option').attr('selected', 'selected');
		reload=true;
	}
	else
	{
		nextElement = jQuery('#location option:selected').next();
		if (nextElement.length > 0)
		{
			jQuery('#location option:selected').next().children('option:first').attr('selected', 'selected');
			reload=true;
		}
		else
		{
			nextElement = jQuery('#location option:selected').parent().next();
			if (nextElement.length > 0)
			{
				if(nextElement.children().length > 0)
				{
					nextElement.children('option:first').attr('selected', 'selected');
					reload=true;
				}
				else
				{
					nextElement.attr('selected', 'selected');
					reload=true;
				}
			}
		}
	}

	if(reload==true)
	{
		jQuery("#search_bth").trigger('click');
	}
}
{/literal}
</script>