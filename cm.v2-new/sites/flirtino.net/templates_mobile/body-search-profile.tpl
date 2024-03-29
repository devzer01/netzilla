<!-- {$smarty.template} -->
<div class="user_profile_menu">
	<div style="display:block; float:left;margin-right:30px; margin-bottom:10px; width:80px;">
	
		{if $datas.picturepath !=""}
			{if $smarty.session.sess_mem eq 1}
				<a href="thumbnails.php?file={$datas.picturepath}" title="{$datas.username|regex_replace:'/@.*/':''}" class="linklist lightview"><img src="thumbnails.php?file={$datas.picturepath}&w=78&h=104" width="78" height="104" border="0" class="listimg"></a>
			{else}
				<a href="./?action=register&type=membership&search=profile&cate=profile&username={$datas.username}"><img src="thumbnails.php?file={$datas.picturepath}&w=78&h=104" width="78" height="104" border="0" class="listimg"></a>
			{/if}
		{else}
			{if $smarty.session.sess_mem eq 1}
				<a href="thumbs/default.jpg" title="{$datas.username|regex_replace:'/@.*/':''}" class="linklist lightview"><img src="thumbs/default.jpg" width="78" height="104" border="0" class="listimg" /></a>
			{else}
				<a href="./?action=register&type=membership&search=profile&cate=profile&username={$datas.username}"><img src="thumbs/default.jpg" width="78" height="104" border="0" class="listimg" /></a>
			{/if}
		{/if}
		
	</div>
	<div style="display:block; float:left; margin-right:50px; margin-bottom:10px; width:180px;"">
	
		{#Name#}: 
		{ if $smarty.session.sess_mem=="1"}
			<a href="?action=viewprofile&username={$datas.username}" class="link-inrow">
		{else}
			<a href="?action=register&type=membership&search=profile&cate=profile&username={$datas.username}" class="link-inrow">
		{/if}
		{$datas.username|regex_replace:"/@.*/":""}</a><br />
		{#City#}: {$datas.city}<br />
		{#Civil_status#}: {$datas.civilstatus}
		
	</div>
	<div style=" display:block; float:left; margin-right:50px; margin-bottom:10px; width:180px; text-align:left;">
		{assign var="thisY" value=$datas.birthday|date_format:"%Y"}
		{if $datas.birthday eq "0000-00-00"}
			{assign var="Age" value="-"}
		{else}
			{assign var="Age" value="`$year-$thisY`"}
		{/if}
		{#Age#}:  {$Age} {#Year#}<br />
		{#Appearance#}: {$datas.appearance}<br />
		{#Height#}: {$datas.height}<br />
	</div>
	<div style=" display:block; float:left; margin-right:10px; width:580px; margin-bottom:10px; text-align:left;">
		{#Description#}: {$datas.description|nl2br|mb_truncate:50:"...":"utf-8"|stripslashes}
        <br />
        {if $smarty.session.sess_username != ""}
		<!--{if $datas.added eq 1}
			<a href="#" class="button" onclick="if(confirm(confirm_delete_box)) goUrl('{$smarty.server.REQUEST_URI}&do=del&delname={$datas.username}')">{#Remove#}</a>
		{else}
	    	<a href="#" class="button" onclick='ajaxRequest("addFavorite", "username={$datas.username}", "", addFavorite, "")'>{#Add_to_Favorite#}</a>
	    {/if}-->
	    
	    { if $smarty.session.sess_mem=="1"}
	    	<a href="?action=viewprofile&username={$datas.username}" class="button">{#Member_profile#}</a>
	    {else}
	    	<a href="#" class="button" onClick='return GB_show("Nur für Mitglieder", "alert.php", 170, 420)'>{#Member_profile#}</a>
	    {/if}
	    
	    <!--{ if $smarty.session.sess_mem=="1"}
	    	<a href="?action=lonely_heart_ads_view&username={$datas.username}" class="button">{#Member_ADs#}</a>
	    {else}
	    	<a href="#" class="button" onClick='return GB_show("Nur für Mitglieder", "alert.php", 170, 420)'>{#Member_ADs#}</a>
	    {/if}-->
	    
	    <!--{ if $smarty.session.sess_mem=="1"}
	    	<a href="?action=mymessage&type=writemessage&username={$datas.username}" class="button">{#Mail_to#}</a>
	    {else}
	    	<a href="#" class="button" onClick='return GB_show("Nur für Mitglieder", "alert.php", 170, 420)'>{#Mail_to#}</a>
	    {/if}-->
		{else}
			<a href="?action=register&type=membership&search=profile&cate=profile&username={$datas.username}" class="button">{#Mail_to#}</a>
		{/if}
	</div>


	
    
    
</div>