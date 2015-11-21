<!-- {$smarty.template} -->
{***************************** Start include top menu ********************************}

	{include file="top.tpl"}

{******************************* End include top menu ********************************}

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center" >
  <tr>
   <td width="900" valign="top" background="images/bgspace.gif" class="bg"><!-- headder-->
  <table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td background="images/h1.gif" width="900" height="6"></td>
  </tr>
  <tr>
    <td width="900" height="13" align="right" valign="middle" background="images/h2.gif" class="bg"><table width="57" height="13" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="23"  ><img src="images/flag-ger.gif" width="23" height="13" /></td>
    <td width="5"></td>
    <td width="24" ><img src="images/flag-eng.gif" width="24" height="13" /></td>
  <td width="5"></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td background="images/h3.gif" width="900" height="131"></td>
  </tr>
</table>

<!-- table center -->
<table width="900" border="0" cellspacing="0" cellpadding="0">
<tr>
<td colspan="5" height="5"></td>
</tr>
{if $smarty.get.action eq 'admin_manageuser'}
<tr>
<td>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
{*********left admin menu**************}  
    <td width="169" align="right" valign="top">
	{include file="left-admin.tpl"}
	</td>
{*********end leftmenu****************}
</tr>
</table>
</td>
    <td width="10" valign="bottom"></td>
    <td valign="top">
	<table width="540" border="0" cellspacing="0" cellpadding="0">
	<tr>
   		<td height="150" valign="top">
		{******************************** Start Main ********************************************}
		<div>
				{if $smarty.get.action eq 'admin_managecard'}
					{include file='admin_managecard.tpl'}
				{elseif $smarty.get.action eq 'search_admin'}
					{include file='body_search_admin.tpl'}
				{elseif $smarty.get.action eq 'admin_manageuser'}
					{include file='admin_manageuser.tpl'}
				{elseif $smarty.get.action eq 'admin_message'}
					{include file='admin_message.tpl'}
				{elseif $smarty.get.action eq 'admin_suggestionbox'}
					{if $smarty.get.do eq "edit"}
						{include file='admin_suggestionbox_write.tpl'}
					{elseif $smarty.get.do eq "view"}
						{include file='admin_suggestionbox_view.tpl'}
					{elseif $smarty.get.do eq "write"}
						{include file='admin_suggestionbox_write.tpl'}
					{else}
						{include file='admin_suggestionbox.tpl'}
					{/if}
				{elseif $smarty.get.action eq 'admin_viewmessage'}
					{include file='admin_viewmessage.tpl'}
				{elseif $smarty.get.action eq 'editprofile'}
					{include file='editprofile.tpl'}
				{elseif ($smarty.get.action eq 'register') and ($smarty.get.type eq 'membership')}
					{include file='register.tpl'}
				{elseif $smarty.get.action eq 'viewprofile'}
					{include file='viewprofile.tpl'}
				{/if}					
		</div>		
{******************************** End Main ********************************************}
	</td>
  </tr>
  <tr>
    <td height="47"></td>
  </tr>
</table>
    </td>
    <td width="10" valign="bottom"></td>
	<td>
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align="right" valign="top" >
		{************** start include right menu **********************}

			{include file="right_menu.tpl"}

		{******************* end include right menu *******************}
    </td>
	</tr>
	</table>
	</td>
  </tr>
{else}
<tr>
	<td>
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
	{*********left admin menu**************}  
    <td width="169" align="right" valign="top">
	{include file="left-admin.tpl"}
	{*********end leftmenu****************}
	</td>
	</tr>
	</table>
	</td>
    <td width="10" valign="bottom"></td>
    <td valign="top">
	<table width="540" border="0" cellspacing="0" cellpadding="0">
	<tr>
   		<td height="150" valign="top">
		{******************************** Start Main ********************************************}
		<div>
				{if $smarty.get.action eq 'admin_managecard'}
					{include file='admin_managecard.tpl'}
				{elseif $smarty.get.action eq 'search_admin'}
					{include file='body_search_admin.tpl'}
				{elseif $smarty.get.action eq 'admin_manageuser'}
					{include file='admin_manageuser.tpl'}
				{elseif $smarty.get.action eq 'admin_message'}
					{include file='admin_message.tpl'}
				{elseif $smarty.get.action eq 'admin_suggestionbox'}
					{if $smarty.get.do eq "edit"}
						{include file='admin_suggestionbox_write.tpl'}
					{elseif $smarty.get.do eq "view"}
						{include file='admin_suggestionbox_view.tpl'}
					{elseif $smarty.get.do eq "write"}
						{include file='admin_suggestionbox_write.tpl'}
					{else}
						{include file='admin_suggestionbox.tpl'}
					{/if}
				{elseif $smarty.get.action eq 'admin_viewmessage'}
					{include file='admin_viewmessage.tpl'}
				{elseif $smarty.get.action eq 'editprofile'}
					{include file='editprofile.tpl'}
				{elseif ($smarty.get.action eq 'register') and ($smarty.get.type eq 'membership')}
					{include file='register.tpl'}
				{elseif $smarty.get.action eq 'viewprofile'}
					{include file='viewprofile.tpl'}
				
				{/if}
								
		</div>		
{******************************** End Main ********************************************}
	</td>
  </tr>
  <tr>
    <td height="47"></td>
  </tr>
</table>
    </td>
    <td width="10" valign="bottom"></td>
	<td></td>
  </tr>
{/if}
</table>
</td>	
</tr>
<tr>
<td width="900" height="374" valign="bottom" background="images/bgbody_04.gif"></td>
</tr>
{******************************* Start include Footer *********************************}

	{include file="footer.tpl"}  

{******************************** End include Footer **********************************}
</table>
</body>
</html>
