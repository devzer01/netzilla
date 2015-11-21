{if $smarty.get.display eq 'part'}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- {$smarty.template} -->
{***************************** Start include top menu ********************************}
{include file="top.tpl"}
{******************************* End include top menu ********************************}
<body>
{/if}

{if count($messages)}
<link href="css/chat.css" rel="stylesheet" type="text/css" />
<div id="messagesContainer">
<ul id="messagesListArea" style="position: relative; height: 500px !important">
{foreach from=$messages item="message"}
	<li class="message_list {if $message.status eq 0}new{/if} {if $message.username eq $smarty.get.username}sender{else}receiver{/if}">
		<div style="float:left;">
			<img src="thumbnails.php?file={$message.picturepath}&w=30&h=30" width="30" height="30" style="border:1px solid #FFF;"/>
			<div style="position:relative; right:-33px; top:-14px;"><img src="images/{if $message.username eq $smarty.get.username}gray_03.png{else}green_03.png{/if}" width="19" height="23"/></div>
		</div>
		<div style="float:left; max-width:350px;">
			<span style="display:block; float:left; padding-left:5px; font-size:11px; line-height:13px; padding-top:2px;"><strong>{$message.username}</strong> [{$message.datetime}]</span> {if ($message.status eq 0) and ($message.username eq $username)} !NEW{/if}
			<br />
			<span class="message">
			<p>
			{if $message.username eq $smarty.const.ADMIN_USERNAME_DISPLAY}
				{$message.message}
			{else}
				{$message.message|strip_tags|replace:"<":"&lt;"|replace:">":"&gt;"|nl2br}
			{/if}
			</p>
			{if $message.attachment_coins>0}
			{include file="attachments-coins-display2.tpl" coins=$message.attachment_coins}
			{/if}
			</span>
		</div>
	</li><br class="clear"/>
{/foreach}
</ul>
</div>
{else}
<div class="result-box">
	<div class="result-box-inside-nobg">No messages.</div>
</div>
{/if}
{if $smarty.get.display eq 'part'}
</body>
</html>
{/if}