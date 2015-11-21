<div class="container-metropopup">
	<div class="metropopup-content">
		<font style="font-size:1.5em; padding-bottom:2%; display:block;">{#Send_coins#}</font>
		<table>
		<tr>
			<td>
				<img src="thumbnails.php?username={$smarty.get.username}&w=112&h=113"/>
			</td>
			<td>
				<form id="send-coins-form" name="send-coins-form" method="post" action="" class="formfield">
				{#how_many_coins_send#} {$smarty.get.username}?<br/>
				Amount: <input type="text" name="coins" style="width:300px;" class="formfield_01" value="{$smarty.const.COIN_EMAIL}"><br/>
				Message: <input type="text" name="message" style="width:300px;" class="formfield_01"><br/>

				<a href="#" onclick="sendCoins('{$smarty.get.username}'); return false" class="btn-popup">{#SEND#}</a>
				</form>
			</td>
		</tr>
		</table>
	</div>
	<br class="clear" />
	</div>
</div>

<script>
{literal}
var sendingCoins = false;

function sendCoins(username)
{
	if(!sendingCoins)
	{
		sendingCoins = true;
		jQuery.ajax({ type: "POST", url: "?action=send-coins&username="+username, data: jQuery("#send-coins-form").serialize(), success:(function(result){sendingCoins = false; if(result=="FINISHED") {jQuery('#mask').hide(); jQuery('.window').hide(); coinsBalance();}else{alert(result);}}) });
	}
}

function coinsBalance()
{
	jQuery.ajax(
	{
		type: "GET",
		url: "?action=chat&type=coinsBalance",
		success:(function(result)
		{
			jQuery('#coinsArea').text(result);
		})
	});
}
{/literal}
</script>