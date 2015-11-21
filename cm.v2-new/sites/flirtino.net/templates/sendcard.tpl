<!-- {$smarty.template} -->
<h2>{#SENDCARD#}</h2>
<div class="result-box">
<div class="result-box-inside-nobg">
<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
        	{foreach from=$rec item=item}
        	<div class="cardlist">
			<a href="{$item.cardpath}" class='lightview' rel='gallery[mygallery]' title=""><img src="{$item.cardtmp}"/></a><br/>
            <a href="index.php?action=sendcard_to&card={$item.cardid}" class="cardbutton">Send</a>
            </div>
            {/foreach}
    </td>
</tr>
</table>
</div>
<div class="pagein">{$page_number}</div>
</div>
