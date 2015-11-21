<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
{***************************** Start include top menu ********************************}
{include file="top.tpl"}
{******************************* End include top menu ********************************}
<body>
<div class="container-bg-header">
<div class="container-bg-footer">
	<div class="wrapper">
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
		{if $smarty.get.action eq "search"}
			<div class="container-search-box">
				<div class="container-search-form">
					<h1>{#Search#}</h1>
					<label>{#USERNAME#}:</label>
					<input  name="username" type="text" id="username" class="formfield_01" style=" width:143px; margin-right:10px"/>
					<a href="#" class="btn-search" style="width:60px; margin-right:10px;"  onclick="return  doSearch('action=search&type=searchUsername&username='+jQuery('#username').val())">{#Search#}</a>

					<form id="search_form">
					<input name="action" type="hidden" value="search" id="action"/>
					<input name="type" type="hidden" value="searchMembers" id="type"/>

					<label style="width:90px !important;">{#Gender#}:</label>
					{if $smarty.session.right_search.q_minage neq ""}
					{assign var="select_q_minage" value=$smarty.session.right_search.q_minage}
					{else}
					{assign var="select_q_minage" value=18}
					{/if}
					<select name="q_gender" id="q_gender" class="formfield_01" style="width:85px; margin-right:10px">
						<option value="">{#Any#}</option>
						{html_options options=$gender}
					</select>

					<label style="width:70px !important;">{#Have_Photo#}:</label>
					<select name="q_picture" id="q_picture" class="formfield_01" style="width:86px; margin-right:10px">
						{html_options options=$picyesno selected=$smarty.session.right_search.q_picture}
					</select>
					<br class="clear" />

					<label>{#Age#}:</label>
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

					<label style="width:90px !important;">{#Country#}:</label>
					<select id="q_country" name="country" onchange="loadOptionState('#q_state', this.options[this.selectedIndex].value, ''); if((jQuery(this).val()!=1)) jQuery('#state_span').hide(); else jQuery('#state_span').show();" class="formfield_01" style="width:250px; margin-right:10px">
					</select>
					<br class="clear" />

					<span id="state_span" style="display: none">
					<label>{#State#}:</label>
					<select id="q_state" name="state" class="formfield_01" style="width:225px; margin-right:10px"></select>
					</span>

					<span style="display: none">
					<label>{#City#}:</label>
					<select id="q_city" name="city" class="formfield_01" style="width:225px; margin-right:10px">
					</select>
					</span>

					<label></label><a href="#" class="btn-login" onclick="return doSearch(jQuery('#search_form').serialize())">Suche</a>
					<br class="clear" />
					</form>
				</div>
				<div class="container-search-icon">
					<a href="#" class="search-m" id="search_women_button" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=w')"><span>{#MAN_SEARCH_WOMAN#}</span></a>
					<a href="#" class="search-w" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=m')"><span>{#WOMAN_SEARCH_MAN#}</span></a>
					<a href="#" class="search-mm" onclick="return doSearch('action=search&type=searchGender&wsex=m&sex=m')"><span>{#MAN_SEARCH_MAN#}</span></a>
					<a href="#" class="search-ww" onclick="return doSearch('action=search&type=searchGender&wsex=w&sex=w')"><span>{#WOMAN_SEARCH_WOMAN#}</span></a>
				</div>
			</div>
		{else}
			{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
				<div class="container-search-box">
					<div class="container-profile">

						<ul class="container-profile-list" style="margin:20px 10px 20px 20px;">
							<li><a href="?action=profile" class="profile-boder {if $profile.approval}approval{/if}"></a><img src="thumbnails.php?file={$MyPicture}&w=108&h=108" width="108" height="108" class="profile-img"/></li>
						</ul>

						<div style="width:345px; height:150px; float:left; margin-top:10px;">
						<h2>Letzte Nachrichten</h2>
							<ul class="container-recent">
							{foreach from=$recent_contacts item="item"}
								<li>
									<a href="?action=viewprofile&username={$item.username}" class="profile-boder"></a>
                                    <img src="thumbnails.php?file={$item.picturepath}&w=70&h=70" width="70" height="70" class="profile-img"/>
			                        <a href="?action=chat&username={$item.username}" class="q-icon q-right q-chat"><span>fav</span></a>
								</li>
							{/foreach}
							</ul>
						</div>

						<div style="width:345px; height:150px; float:left; margin-top:10px; margin-left:20px;">
						<h2>Kontaktvorschläge</h2>
							<ul class="container-recent">
							{foreach from=$random_contacts item="item"}
								<li>
									<a href="?action=viewprofile&username={$item.username}" class="profile-boder"></a><img src="thumbnails.php?file={$item.picturepath}&w=70&h=70" width="70" height="70" class="profile-img"/>
			                        <a href="?action=chat&username={$item.username}" class="q-icon q-right q-chat"><span>fav</span></a>
								</li>
							{/foreach}
							</ul>
						</div>

						<ul class="container-profile-list" style="margin:20px 20px 20px 10px;">
							<li><a href="#" class="profile-boder"></a>
							<div style="width:108px; height:108px; margin:7px 0 0 6px; text-align:center;">
								<div style="margin-top:40px;">
								Sie haben!<br />
								<strong style="margin-top:5px; display:block; font-size:16px;"><span id="coinsArea" style="padding: 0px"></span></strong>
								</div>
							</div>
							</li>
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
</body>
</html>