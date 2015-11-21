<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
{include file="top.tpl"}
<body>
<div class="result-box">
	<h1>{#Lonely_Heart_Ads#}</h1>
	<div class="result-box-inside-nobg">
  		<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
        {if $smarty.get.action eq "lonely_heart_ads"}
		<tr align="left">
			<td>
            	Hier kannst du deine Kontaktanzeigen aufgeben, bearbeiten und löschen.
                <br /><br />
				Diese geben dir die Möglichkeit, direkt nach Gleichgesinnten zu suchen und deine speziellen Wünsche, Hoffnungen oder Leidenschaften anderen kundzutun!
			</td>
		</tr>
		{else if $smarty.get.action eq "lonely_heart_ads_view"}
        <tr align="left">
        	<td>Hier siehst du alle aktuellen Anzeigen des ausgewählten Mitglieds</td>
        </tr>
		{/if}
        {if $lonely_heart}
        <tr>
        	<td>
                <form id="lonely_heart_form" name="lonely_heart_form" method="post" action="">
                {if $smarty.get.action eq "lonely_heart_ads_view"}  
                    {section name="lonely_heart" loop=$lonely_heart}
                    {******************}
                    {include file="ads_box.tpl" datas=$lonely_heart[lonely_heart]}
                    {******************}
                    {/section}
                {else}
                	<br />
                    <table border="0" cellpadding="2" cellspacing="1" width="100%" align="center">
                    <tr bgcolor="#b6b6b6" height="28px">
                    <th width="35px" class="text-title">{#Index#}</th>
                    <th width="60px" class="text-title">{#Target#}</th>
                    <th width="90px" class="text-title">{#Category#}</th>
                    <th width="200px" class="text-title">{#Headline#}</th>
                    <th width="100px" class="text-title">{#Datetime#}</th>
                    {if $smarty.get.action eq "lonely_heart_ads"}
                    <th width="20px" class="text-title">{#Edit#}</th>
                    <th width="20px" class="text-title"><a href="#" class="sitelink" onclick="if(confirm('Are you sure to delete selected lonely heart?')) deleteLonelyHeart('lonely_heart_form'); else return false;">{#Delete#}</a></th>
                    {/if}
                    </tr>
                    {section name="lonely_heart" loop=$lonely_heart}
                    <tr bgcolor="{cycle values='#663333,#996666'}">
                        <td align="center">{$smarty.section.lonely_heart.index+1}</td>
                        <td style="padding-left:10px;">{$lonely_heart[lonely_heart].target}</td>
                        <td style="padding-left:10px;">{$lonely_heart[lonely_heart].category}</td>
                        {if $smarty.get.action eq "lonely_heart_ads"}
                        <td style="padding-left:10px;"><a class="sitelink" href="javascript: popup('?action=lonely_heart_ads&do=view&lonelyid={$lonely_heart[lonely_heart].id}', 450,820)" class="linklist">{$lonely_heart[lonely_heart].headline|truncate:40:"..."}</a></td>
                        {elseif $smarty.get.action eq "lonely_heart_ads_view"}
                        <td style="padding-left:10px;"><a class="sitelink" href="javascript: popup('?action=lonely_heart_ads_view&do=view&lonelyid={$lonely_heart[lonely_heart].id}&username={$smarty.get.username}', 450,820)" class="linklist">{$lonely_heart[lonely_heart].headline|truncate:40:"..."}</a></td>
                        {/if}
                        <td style="padding-left:10px;">{$lonely_heart[lonely_heart].datetime}</td>
                        {if $smarty.get.action eq "lonely_heart_ads"}
                        <td align="center"><a class="sitelink" href="?action=lonely_heart_ads&do=edit&lonelyid={$lonely_heart[lonely_heart].id}"><img border="0" src="images/icon/b_edit.png" /></a></td>
                        <td align="center"><input type="checkbox" id="lonely_heart_id" name="lonely_heart_id[]" value="{$lonely_heart[lonely_heart].id}"></td>
                        {/if}
                    </tr>
                    {/section}
                    </table>
                {/if}
                </form>
			</td>
		</tr>
        {/if}
        </table>  
    </div>
    <div class="pagein">Seite {paginate_prev} {paginate_middle} {paginate_next}</div>
</div>
</body>
</html>