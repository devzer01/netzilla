<!-- {$smarty.template} -->
<div class="user_profile_menu">
	<div style="display:block; float:left; margin-right:30px; width:80px;">
	
		{if $datas.picturepath !=""}
			{if $smarty.session.sess_mem eq 1}
				<a href="thumbnails.php?file={$datas.picturepath}" title="{$datas.username|regex_replace:'/@.*/':''}" class="linklist lightview"><img src="thumbnails.php?file={$datas.picturepath}&w=78&h=104" width="78" height="104" border="0" class="listimg"></a>
			{else}
				<a href="?action=register&type=membership&search=loneyheartads&cate=loneyheartads&username={$datas.username}"><img src="thumbnails.php?file={$datas.picturepath}&w=78&h=104" width="78" height="104" border="0" class="listimg"></a>
			{/if}
		{else}
			{if $smarty.session.sess_mem eq 1}
				<a href="thumbs/default.jpg" title="{$datas.username|regex_replace:'/@.*/':''}" class="linklist lightview"><img src="thumbs/default.jpg" width="78" height="104" border="0" class="listimg" /></a>
			{else}
				<a href="./?action=register&type=membership&search=profile&cate=profile&username={$datas.username}"><img src="thumbs/default.jpg" width="78" height="104" border="0" class="listimg" /></a>
			{/if}
		{/if}
	
	</div>
	<div style=" display:block; float:left; margin-right:10px; width:370px; min-height:60px;">
	
		{#Name#}: <a href="?action=viewprofile&username={$datas.username}" class="link-inrow">{if $datas.username}{$datas.username|regex_replace:"/@.*/":""}{else}{$smarty.get.username|regex_replace:"/@.*/":""}{/if}</a><br />
		{#Headline#}: {$datas.headline}<br />
		{assign var="civilstatus" value=$datas.civilstatus} 
		{#ADs_text#}: {$datas.text|replace:"\n":""|wordwrap:80:"<br />":true|stripslashes}<br />
		{if $smarty.session.sess_username != ""}
		    <!--{if $smarty.session.sess_mem=="1"}
		        <a href="?action=lonely_heart_ads_view&username={$datas.username}" class="button">
		        	{#Whole_Ad#}
		        </a>
		        {else}
		        <a href="#" class="button" onClick="return GB_show("Nur f�r Mitglieder", "alert.php", 170, 420)">
		        	{#Whole_Ad#}
		        </a>
		    {/if} -->
		    { if $smarty.session.sess_mem=="1"}
		        <a href="?action=viewprofile&username={$datas.username}" class="button">
		        	{#Member_profile#}
		        </a>
		    {else}
		        <a href="#" class="button" onClick="return GB_show("Nur f�r Mitglieder", "alert.php", 170, 420)">
		        	{#Member_profile#}
		        </a>
		    {/if}
		    <!--{ if $smarty.session.sess_mem=="1"} 
		        	<a href="?action=mymessage&type=writemessage&username={$datas.username}" class="button"> {#Mail_to#}</a>
		    {else} 
		        	<a href="#" class="button" onClick="return GB_show("Nur für Mitglieder", "alert.php", 170, 420)"> {#Mail_to#}</a> 
			{/if}-->
		{else}
		<a href="?action=register&amp;type=membership&amp;cate=lonely&amp;username={$datas.username}" class="button">{#Mail_to#}</a>
	{/if}
		
	</div>
    <br class="clear" />
</div>