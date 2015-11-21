<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
{***************************** Start include top menu ********************************}
{include file="top.tpl"}
{******************************* End include top menu ********************************}
{assign var="table_bg_top" value="#0074a2"}
{assign var="table_bg_1" value="#eeeeee"}
{assign var="table_bg_2" value="#e2e2e2"}
<body>
<link href="css/css-theme.css" rel="stylesheet" type="text/css" />
<link href="css/admin.css" rel="stylesheet" type="text/css" />
<div class="container-bg-header">
<div class="top-bg">

	<div id="warper" class="wrapper" style="min-height:150px !important; width:994px !important; margin:0 auto;">
		<header>
			<div class="container-logo"></div>
			<nav>
				
				{if !$smarty.session.sess_externuser}
				{******************************** Start Head Menu *****************************************}
				{include file="menu.tpl"}
				{********************************** End Head Menu *****************************************}
				{/if}
				
			</nav>
		</header>
      </div>
      
     
        <!--main admin -->
        <div class="container-main-admin">
            <table border="0" align="left" cellpadding="0" cellspacing="0" style="border:0; position:relative;">
              <tr>
                <td align="left" valign="top">
                <div class="container-left-menu" id="left-admin-td">
                            {********* admin menu**************}  
                            
                            {if !$smarty.session.sess_externuser}
                            <h1>{#ADMINISTRATOR#}</h1>
                            {include file="left-admin.tpl"}
                            {/if}
                            
                            {if $smarty.get.action eq "admin_manageuser"}
                            {include file="right_admin.tpl"}
                            {elseif $smarty.get.action eq "admin_manage_bonus"}
                            {include file="admin_manage_bonus_searchbox.tpl"}
                            {elseif $smarty.get.action eq "admin_copyfakeprofiles_already"}
                            {include file="admin_manage_bonus_searchbox.tpl"}
                            {else}
                            {if $smarty.session.sess_externuser}
                            <script>jQuery('#left-admin-td').hide();</script>
                            {/if}
                            {/if}
            
                            {*********end admin menu****************}
                  </div>
                </td>
                <td align="left" valign="top" width="100%">
                <div class="container-content-admin">
                	{************************************* Start body *************************************}
                {if $smarty.get.action eq "admin_manageuser"}
                
                {******************************** Start Main ********************************************}
                
                {include file="admin_manageuser.tpl"}
                
                {******************************** End Main ********************************************}
                
                <br clear="all" />
                
                {else}
                
                {******************************** Start Main ********************************************}
                {if $smarty.get.action eq "admin_managecoin"}
                {include file="admin_managecoin.tpl"}
                {elseif $smarty.get.action eq "admin_manage_package"}
                {include file="admin_manage_package.tpl"}
                {elseif $smarty.get.action eq "admin_managecard"}
                {include file="admin_managecard.tpl"}
                {elseif $smarty.get.action eq "admin_manage_contents"}
                {include file="admin_manage_contents.tpl"}
                {elseif $smarty.get.action eq "admin_message"}
                {include file="admin_message.tpl"}
                {elseif $smarty.get.action eq "admin_manage_bonus"}
                {include file="admin_manage_bonus.tpl"}
                {elseif $smarty.get.action eq "admin_bonus_history"}
                {include file="admin_bonus_history.tpl"}
                {elseif $smarty.get.action eq "admin_coin_statistics"}
                {include file="admin_coin_statistics.tpl"}
                {elseif $smarty.get.action eq "admin_coin_statistics_details"}
                {include file="admin_coin_statistics_details.tpl"}
                {elseif $smarty.get.action eq "admin_suggestionbox"}
                {if $smarty.get.do eq "edit"}
                {include file="admin_suggestionbox_write.tpl"}
                {elseif $smarty.get.do eq "view"}
                {include file="admin_suggestionbox_view.tpl"}
                {elseif $smarty.get.do eq "write"}
                {include file="admin_suggestionbox_write.tpl"}
                {else}
                {include file="admin_suggestionbox.tpl"}
                {/if}
                {elseif $smarty.get.action eq "admin_viewmessage"}
                {include file="admin_viewmessage.tpl"}
                {elseif $smarty.get.action eq "editprofile"}
                {include file="editprofile.tpl"}
                {elseif ($smarty.get.action eq "register") and ($smarty.get.type eq "membership")}
                {include file="register.tpl"}
                {elseif $smarty.get.action eq "viewprofile"}
                {include file="viewprofile.tpl"}
                {elseif $smarty.get.action eq "admin_history"}
                {include file="admin_history.tpl"}
                {elseif $smarty.get.action eq "admin_new_members"}
                {include file="admin_new_members.tpl"}
                {elseif $smarty.get.action eq "admin_adduser"}
                {include file="admin_adduser.tpl"}
                {elseif $smarty.get.action eq "admin_paid"}
                {include file="admin_paid.tpl"}
                {elseif ($smarty.get.action eq "admin_paid_copy") or ($smarty.get.action eq "admin_paid_edit")}
                {include file="admin_paid_copy.tpl"}
                {elseif $smarty.get.action eq "admin_chat_logs"}
                {include file="admin_chat_logs.tpl"}
                {else}
                {if file_exists("`$smarty.const.SITE`templates/`$smarty.get.action`.tpl")}
                {include file="`$smarty.get.action`.tpl"}
                {/if}
                {/if}
                {******************************** End Main ********************************************}
                
                {/if}
                {************************************* End body *************************************}              
                </div>
                </td>
              </tr> 
            </table>
	  </div>
        <!--end main admin -->
		<br class="clear" />
	
</div>
</div>

{******************************* Start include Footer *********************************}
{include file="footer.tpl"}
{******************************** End include Footer **********************************}
<div id="mask"></div>
</body>
</html>