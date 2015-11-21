<!-- {$smarty.template} -->
{literal}
<STYLE TYPE="text/css">
<!--
.redgray, .redgray TD, .redgray TH
{
	background-image: url('images/red-gray.gif');
	background-repeat: repeat-x;
	background-color: gray;
	color: white;
}

.red, .red TD, .red TH
{
	background-color: red;
	color:white;
}

.lightbrown, .lightbrown TD, .lightbrown TH
{
	background-color: #94775c;
	color:white;
}

.green, .green TD, .green TH
{
	background-color: #8aad00;
	color:white;
}

.darkgray, .darkgray TD, .darkgray TH
{
	background-color: #555555;
	color:white;
}
.orange, .orange TD, .orange TH
{
	background-color: orange;
	color:white;
}

-->
</STYLE>
{/literal}

<div class="result-box">
<h1>Payments</h1>

<div style='margin-bottom: 10px;'>
<form method="get">
	<input type="hidden" name="action" value="{$smarty.get.action}"/>
	Username: <input type='text' name='username' id='username' value="{$username}" /> <button>Search</button>
</form>
</div>


<div class="result-box-inside-nobg">
<table width="100%"  border="0" cellspacing="1" cellpadding="2">
<tr bgcolor="#b6b6b6" height="28px">
<td align="center">
{if $smarty.get.order eq "username"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=username&type=asc" >Nickname</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=username&type=desc" >Nickname</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=username&type=asc" class="sitelink">Nickname</a>
{/if}
</td>
<td align="center" width="100">
{if $smarty.get.order eq "price"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=price&type=asc">Price</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=price&type=desc">Price</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=price&type=asc" class="sitelink">Price</a>
{/if}
</td>
<td align="center" width="100">
{if $smarty.get.order eq "coin_amount"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=coin_amount&type=asc">Coin Amount</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=coin_amount&type=desc">Coint Amount</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=coin_amount&type=asc" class="sitelink">Coint Amount</a>
{/if}
</td>
<td align="center" width="100">
{if $smarty.get.order eq "currency"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=currency&type=asc">Currency</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=currency&type=desc">Currency</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=currency&type=asc" class="sitelink">Currency</a>
{/if}
</td>
<td align="center" width="100">
{if $smarty.get.order eq "purchase_datetime"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=purchase_datetime&type=asc">Purchase Datetime</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=purchase_datetime&type=desc">Purchase Datetime</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=purchase_datetime&type=asc" class="sitelink">Purchase Datetime</a>
{/if}
</td>

<td align="center" width="100">
{if $smarty.get.order eq "purchase_finished_date"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=purchase_finished_date&type=asc">Purchase Finished Date</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=purchase_finished_date&type=desc">Purchase Finished Date</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=purchase_finished_date&type=asc" class="sitelink">Purchase Finished Date</a>
{/if}
</td>
<td align="center" width="100">
{if $smarty.get.order eq "payment_method"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payment_method&type=asc">Payment Method</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payment_method&type=desc">Payment Method</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payment_method&type=asc" class="sitelink">Payment Method</a>
{/if}
</td>
<td align="center">
{if $smarty.get.order eq "payment_type"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payment_type&type=asc">Payment Type</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payment_type&type=desc">Payment Type</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payment_type&type=asc" class="sitelink">Payment Type</a>
{/if}
</td>

<td align="center">
{if $smarty.get.order eq "reference_id"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=reference_id&type=asc">Reference Id</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=reference_id&type=desc">Reference Id</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=reference_id&type=asc" class="sitelink">Reference Id</a>
{/if}
</td>
</tr>
{foreach key=key from=$userrec item=userdata}
	{if ($userdata.copy_from ne "0") and ($userdata.recall eq "1")}
		{assign var="color" value="class='red'"}	<!-- {cycle values="#eeeeee,#d0d0d0"} -->
	{elseif $userdata.copy_from ne "0"}
		{assign var="color" value="class='darkgray'"}	<!-- {cycle values="#eeeeee,#d0d0d0"} -->
	{elseif $userdata.prolonging eq "1"}
		{if $userdata.recall eq "1"}
			{assign var="color" value="class='orange'"}
		{else}	
			{assign var="color" value="class='lightbrown'"}
		{/if}										<!-- {cycle values="#eeeeee,#d0d0d0"} -->
	{elseif $userdata.payment_complete eq "0"}
		{assign var="color" value="0"}
	{elseif $userdata.recall eq "1"}
		{assign var="color" value="class='red'"}	<!-- {cycle values="#eeeeee,#d0d0d0"} -->
	{elseif $userdata.copied eq "1"}
		{assign var="color" value="class='redgray'"}	<!-- {cycle values="#eeeeee,#d0d0d0"} -->
	{elseif $userdata.days_ago gte 6}
		{assign var="color" value="class='green'"}	<!-- {cycle values="#eeeeee,#d0d0d0"} -->
	{else}
		{assign var="color" value="0"}
	{/if}
<tr {if $color}{$color}{else}bgcolor="{cycle values="#006de0,#003873"}"{/if} height="24">
<td align="left" style="padding-left:5px"><a href="?action=viewprofile&username={$userdata.username}&from=admin" class="sitelink" {if $color}style="color: white"{/if}>{$userdata.username}</a></td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.price}</td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.coin_amount}</td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.currency}</td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.purchase_datetime}</td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.purchase_finished_date}</td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.payment_method}</td>
<td {if $color}style="color: white"{/if}>{$userdata.payment_type}</td>
<td width="100px" {if $color}style="color: white"{/if} align="center">{$userdata.reference_id}</td>
</tr>
{foreachelse}
	<tr>
		<td colspan='8'>No Results</td>
	</tr>
{/foreach}
</table>
</div>
<div class="page">{paginate_prev class="pre-pager"} {paginate_middle format="page" page_limit="5" class="num-pager"} {paginate_next class="next-pager"}</div>
</div>