<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
{***************************** Start include top menu ********************************}
{include file="top.tpl"}
{******************************* End include top menu ********************************}
<body>
<div class="container-bg-header">
<div class="">
	<div class="container-wrapper">
    	<header>
        	<div class="container-logo"></div>
            <ul class="container-menu">
				{if !$smarty.session.sess_externuser}
				{******************************** Start Head Menu *****************************************}
				{include file="menu.tpl"}
				{********************************** End Head Menu *****************************************}
				{/if}
            </ul>
        </header>
		{if $smarty.get.action eq "searcholddd"}
			
		{else}
			{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
				
				
				<div class="container-profile-bar">
                  <div class="container-recent-profile-left">
                    <ul class="container-recent-border">
                        <h2 style="text-align:center;">Letzte Nachrichten</h2>
                        {foreach from=$recent_contacts item="item"}
							<li>
								<a href="?action=viewprofile&username={$item.username}" class="profile-border"></a>
                                <img src="thumbnails.php?file={$item.picturepath}&w=70&h=70" width="70" height="70" class="profile-img"/>
		                        <a href="?action=chat&username={$item.username}" class="q-icon-r chat"><span>Chat</span></a>
							</li>
						{/foreach}
                    </ul>
                  </div>
                    <ul class="container-profile-border my-profile">
                        <li>
                            <a href="?action=profile" class="profile-border {if $profile.approval}approval{/if}"></a><img src="thumbnails.php?file={$MyPicture}&w=106&h=105" width="106" height="105" class="profile-img">
                        </li>
                    </ul>
                  <div class="container-recent-profile-right">
                    <ul class="container-recent-border">
                        <h2 style="text-align:center;">Kontaktvorschläge</h2>
                        {foreach from=$random_contacts item="item"}
							<li>
								<a href="?action=viewprofile&username={$item.username}" class="profile-border"></a>
                                <img src="thumbnails.php?file={$item.picturepath}&w=70&h=70" width="70" height="70" class="profile-img"/>
		                        <a href="?action=chat&username={$item.username}" class="q-icon-r chat"><span>Chat</span></a>
							</li>
						{/foreach}
                    </ul>
                  </div>
              </div>
			{else}
			<!--box login -->
				{include file="form-login.tpl"}
			<!--End box login -->
			{/if}
		{/if}
		
		
	
		<div class="container-content">
			<div class="container-top-content">
			{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
				<div class="container-coins-top"><b>Sie haben!</b><br><strong><span id="coinsArea" style="padding: 0px"></span> coins</strong></div>
			{/if}		
			
			
			
			
			{if $smarty.get.action eq "search"}
			<br class="clear" />
			<div class="clear">&nbsp;</div>
			<ul class="container-search-icon">
                <li><a href="#" id="onlineSearch" onclick="return doSearch('action=search&type=searchOnline');"><img src="images/s-online.png" width="114" height="134"></a><p>Online</p></li>
                <li><a href="#" onclick="return doSearch('action=search&type=searchNewestMembers')"><img src="images/s-news.png" width="114" height="134"></a><p>Neueste</p></li>
                <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=m')"><img src="images/s-women.png" width="114" height="134"></a><p>{#WOMAN_SEARCH_MAN#}</p></li>
                <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=w')"><img src="images/s-man.png" width="114" height="134"></a><p>{#MAN_SEARCH_WOMAN#}</p></li>
                <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=w')"><img src="images/s-less.png" width="114" height="134"></a><p>{#WOMAN_SEARCH_WOMAN#}</p></li>
                <li><a href="#" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=m')"><img src="images/s-gay.png" width="114" height="134"></a><p>{#MAN_SEARCH_MAN#}</p></li>
            </ul>
            <br class="clear">
                           
            <div style="background:url(images/bg-opd.png); width:1024px; height:auto; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; padding:20px; margin-bottom:10px">
                <div class="container-search-form">
                <div style="margin-bottom:10px; float:left;">
            	<label>{#USERNAME#}:</label>
            	<input name="username" type="text" id="username" class="formfield_01" style=" width:268px; margin-right:10px">
                <a href="#" class="btn-login" style="width:60px; margin-right:10px;" onclick="return  doSearch('action=search&type=searchUsername&username='+jQuery('#username').val())">{#Search#}</a>
                </div>
                
                <br class="clear">
            	<form id="search_form">
				<input name="action" type="hidden" value="search" id="action"/>
				<input name="type" type="hidden" value="searchMembers" id="type"/>
					
                <label>{#Gender#}:</label>
                {if $smarty.session.right_search.q_minage neq ""}
				{assign var="select_q_minage" value=$smarty.session.right_search.q_minage}
				{else}
				{assign var="select_q_minage" value=18}
				{/if}
				<select name="q_gender" id="q_gender" class="formfield_01" style="width:85px; margin-right:10px">
					<option value="">{#Any#}</option>
					{html_options options=$gender}
				</select>
                
                <label style="width:60px !important;">{#Have_Photo#}:</label>
                <select name="q_picture" id="q_picture" class="formfield_01" style="width:86px; margin-right:10px">
					{html_options options=$picyesno selected=$smarty.session.right_search.q_picture}
				</select>
					
                <label style="width:40px !important;">{#Age#}:</label>
                <select name="q_minage" id="q_minage" onchange="ageRange('q_minage', 'q_maxage')" class="formfield_01" style="width:93px; margin-right:10px">
					{html_options options=$age selected=$select_q_minage}
				</select>
                
                <label style="width:30px !important;">{#To#}</label>
                {if $smarty.session.right_search.q_maxage neq ""}
				{assign var="select_q_maxage" value=$smarty.session.right_search.q_maxage}
				{else}
				{assign var="select_q_maxage" value=$select_q_minage+2}
				{/if}
				<select name="q_maxage" id="q_maxage" class="formfield_01" style="width:93px; margin-right:10px">
					{html_options options=$age selected=$select_q_maxage}  
				</select>
                <label style="width:80px !important;">{#Country#}:</label>
                <select id="q_country" name="country" onchange="loadOptionState('#q_state', this.options[this.selectedIndex].value, ''); if((jQuery(this).val()!=1)) jQuery('#state_span').hide(); else jQuery('#state_span').show();" class="formfield_01" style=" width:200px; margin-right:10px"></select>
                <a href="#" onclick="return doSearch(jQuery('#search_form').serialize())" class="btn-login" style="width:60px;">Suche</a>
                
                
                <span id="state_span" style="display: none">
                <br class="clear">
				<label>{#State#}:</label>
				<select id="q_state" name="state" class="formfield_01" style="width:225px; margin-right:10px"></select>
				</span>

				<span style="display: none">
				<label>{#City#}:</label>
				<select id="q_city" name="city" class="formfield_01" style="width:225px; margin-right:10px">
				</select>
				</span>
				
                </form>
                </div>
                <br class="clear">
            </div>
            <div class="clear"></div>
            <div>&nbsp;</div>
			{/if}
			
			
			
			</div>
{************************************* Start body *************************************}
{if $smarty.session.sess_username != "" or $smarty.cookies.sess_username neq ""}
	{if $smarty.get.action eq "testpay"}
		{include file="payment_1.tpl"}
	{elseif $smarty.get.action eq "payportal1"}
		{include file="payment_1.tpl"}
	{elseif $smarty.get.action eq "terms"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "terms-2"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "policy"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "policy-2"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "refund"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "refund-2"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "imprint"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "webcam"}
		{include file="cam_default.tpl"}
	{elseif $smarty.get.action eq "bonusverify"}
		{include file="bonusverify_step2.tpl"}
	{elseif $smarty.get.action eq "validCode2"}
		{include file="sms_validcode2.tpl"}
	{else}
		{if file_exists("`$smarty.const.SITE`templates/`$smarty.get.action`.tpl")}
			{include file="`$smarty.get.action`.tpl"}
		{else}
			{include file="main.tpl"}
		{/if}
	{/if}
{elseif $smarty.get.action eq "register"}
	{if ($section eq "regis-step1-result")}
		{include file="regis-step1-result.tpl"}
	{else}
		{include file="register.tpl"}
	{/if}
{elseif $smarty.get.action eq "viewcard_mail"}
	{include file="viewcard.tpl"}
{elseif $smarty.get.action eq "terms"}
	{include file="content.tpl"}
{elseif $smarty.get.action eq "terms-2"}
	{include file="content.tpl"}
{elseif $smarty.get.action eq "policy"}
	{include file="content.tpl"}
{elseif $smarty.get.action eq "policy-2"}
	{include file="content.tpl"}
{elseif $smarty.get.action eq "refund"}
	{include file="content.tpl"}
{elseif $smarty.get.action eq "refund-2"}
	{include file="content.tpl"}
{elseif $smarty.get.action eq "imprint"}
	{include file="content.tpl"}
{elseif $smarty.get.action eq "membership"}
	{include file="membership_1.tpl"}
{else}
	{if file_exists("`$smarty.const.SITE`templates/`$smarty.get.action`.tpl")}
		{include file="`$smarty.get.action`.tpl"}
	{else}
		{include file="main.tpl"}
	{/if}
{/if}
{************************************* End body *************************************}
		</div>
		<br class="clear" />
	</div>
	<!--end warper-->
</div>
</div>
{******************************* Start include Footer *********************************}
{include file="footer.tpl"}
{******************************** End include Footer **********************************}
<div id="mask"></div>
</body>
</html>