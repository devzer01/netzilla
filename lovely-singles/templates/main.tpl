{**************************** Begin Welcome ************************************}
{if ($smarty.session.sess_username =="")} 
	{include file="newest-menu.tpl"}
{else}
	{if $smarty.session.sess_mobile_ver}
		{include file="complete-profile.tpl"}
	{/if}

	{if (($bonusid != '') && ($bonusid > 0))}
		{include file="bonusverify_step1.tpl"}
	{/if}

	{if ($smarty.const.COIN_VERIFY_MOBILE gt 0) && !$mobile_verified}
		{include file="banner-verify-mobile.tpl"}
	{/if}
{/if}

{****************************** End Welcome ************************************}

{************************** Begin Profile visit ********************************}
{if ($smarty.session.sess_username !="") and ($smarty.session.sess_first eq 0)}
	<div class="listbox">
        <h1>{#Users_visit_your_profile#}</h1>
        <div class="listboxin">
            {include file="inc_visitor.tpl"}
        </div>
    </div>
{/if}
{*************************** End Profile visit *********************************}

{*if $show_smsbanner}
<a href="?action=SMS" class="linklist"><img src="images/herzoase-smsbanner.jpg" width="537" border="0" alt="" /></a>
{/if*}

{********************** Start Men and Women of Day  **************************}
<div class="newbox">
	<ul>
	<li>
		<h1>{#Man_of_the_day#}</h1>
		<div class="newboxin">
			<div class="newimg">
				<a href="thumbnails.php?file={$manofday.0.picturepath}" title="{$manofday.0.username} ({$manofday.0.age})" class="lightview"><img border="0" src="thumbnails.php?file={$manofday.0.picturepath}&amp;w=78&amp;h=104" height="104" width="78" alt="{$manofday.0.username}"/></a>
			</div>
			<div class="newname">
				{if $smarty.session.sess_username != ''}
				<a href="?action=viewprofile&amp;username={$manofday.0.username}" class="link-inrow">{$manofday.0.username} ({$manofday.0.age})</a>
				{else}
				<a href="?action=register&amp;type=membership&amp;cate=profile&amp;username={$manofday.0.username}" class="link-inrow">{$manofday.0.username} ({$manofday.0.age})</a>
				{/if}
				<br />
				{$manofday.0.gender}, {$manofday.0.civilstatus}<br />
				{$manofday.0.city}<br />
				{#looking_for#}: 
				{if $manofday.0.lookmen}
					{#Men#}
				{/if}
				{if $manofday.0.lookwomen}
					{#Women#}
				{/if}
			</div>
		</div>
	</li>
	<li class="last">
		<h1>{#Woman_of_the_day#}</h1>
		<div class="newboxin">
			<div class="newimg">
				<a href="thumbnails.php?file={$womanofday.0.picturepath}" title="{$womanofday.0.username} ({$womanofday.0.age})" class="lightview"><img border="0" src="thumbnails.php?file={$womanofday.0.picturepath}&amp;w=78&amp;h=104" height="104" width="78"  alt="{$womanofday.0.username}"/></a>
			</div>
			<div class="newname">
				{if $smarty.session.sess_username != ''}
				<a href="?action=viewprofile&amp;username={$womanofday.0.username}" class="link-inrow">{$womanofday.0.username} ({$womanofday.0.age})</a>
				{else}
				<a href="?action=register&amp;type=membership&amp;cate=profile&amp;username={$womanofday.0.username}" class="link-inrow">{$womanofday.0.username} ({$womanofday.0.age})</a>
				{/if}
				<br />
				{$womanofday.0.gender}, {$womanofday.0.civilstatus}<br />
				{$womanofday.0.city}<br />
				{#looking_for#}: 
				{if $womanofday.0.lookmen}
					{#Men#}
				{/if}
				{if $womanofday.0.lookwomen}
					{#Women#}
				{/if}
			</div>
		</div>
	</li>
	</ul>
</div>
{********************** End Men and Women of Day  **************************}


<!--{********************** Begin Register box **************************}
{if ($smarty.session.sess_username =="")}
	{include file="register.tpl"}
{/if} -->

{********************** End Register box **************************}


{********************** Begin Newest lonely Heart Ads **************************}
	{include file="newest_lonely_heart.tpl"}
{*******************************************************************************}

{********************** Begin Newest lonely Heart Ads **************************}
<div class="listbox">
<h1>COMING SOON: MOVIE UPLOAD!</h1>
<div class="listboxin">
<div style="display:block; width:112px; height:110px; margin-right:13px; background: url(images/tv-schnee01.gif) top no-repeat; float:left;"><img src="images/tv.png" width="112" height="110" alt=""/></div>
<div style="display:block; width:112px; height:110px; margin-right:13px; background: url(images/tv-schnee02.gif) top no-repeat; float:left;"><img src="images/tv.png" width="112" height="110" alt=""/></div>
<div style="display:block; width:112px; height:110px; margin-right:13px; background: url(images/tv-schnee03.gif) top no-repeat; float:left;"><img src="images/tv.png" width="112" height="110" alt=""/></div>
<div style="display:block; width:112px; height:110px; margin-right:13px; background: url(images/tv-schnee04.gif) top no-repeat; float:left;"><img src="images/tv.png" width="112" height="110" alt=""/></div>
<div style="display:block; width:112px; height:110px; margin-right:13px; background: url(images/tv-schnee05.gif) top no-repeat; float:left;"><img src="images/tv.png" width="112" height="110" alt=""/></div>
<div style="display:block; width:112px; height:110px; margin-right:13px; background: url(images/tv-schnee06.gif) top no-repeat; float:left;"><img src="images/tv.png" width="112" height="110" alt=""/></div>
</div>
</div>
{*******************************************************************************}

{**************************** Begin CAM Banner *********************************}
{*if ($smarty.session.sess_username !="") }
<input type="image" src="images/profile_edit.jpg" width="537" id="profile_edit" name="profile_edit" onclick="location = '?action=editprofile'; return false;" value="" />
{if $smarty.const.WEB_CAMS_ENABLE}
{if $smarty.session.sess_permission neq "2"}
<input type="image" src="images/webcams_sonaflirt.jpg" width="537" id="webcam" name="webcam" onclick="location = './?action=webcam'; return false;" value="" />
{/if}
{/if}
{/if*}
{****************************** End CAM Banner *********************************}

{if ($smarty.session.sess_username !="")} 
	{if !$smarty.session.sess_mobile_ver}
		{include file="complete-profile.tpl"}
	{/if}
{/if}