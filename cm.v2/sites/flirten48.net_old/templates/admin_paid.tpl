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
{if $userrec}
<div class="result-box">
<h1>Payments</h1>
<div class="result-box-inside-nobg">

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>

<table width="100%" height="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td>
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
{if $smarty.get.order eq "name"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=name&type=asc">Name</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=name&type=desc">Name</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=name&type=asc" class="sitelink">Name</a>
{/if}
</td>
<td align="center" width="100">
{if $smarty.get.order eq "street"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=street&type=asc">Strasse</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=street&type=desc">Strasse</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=street&type=asc" class="sitelink">Strasse</a>
{/if}
</td>
<td align="center" width="100">
{if $smarty.get.order eq "plz"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=plz&type=asc">PLZ</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=plz&type=desc">PLZ</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=plz&type=asc" class="sitelink">PLZ</a>
{/if}
</td>
<td align="center" width="100">
{if $smarty.get.order eq "city"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=city&type=asc">Stadt</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=city&type=desc">Stadt</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=city&type=asc" class="sitelink">Stadt</a>
{/if}
</td>

<td align="center" width="100">
{if $smarty.get.order eq "payday"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payday&type=asc">Bezahlt am</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payday&type=desc">Bezahlt am</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payday&type=asc" class="sitelink">Bezahlt am</a>
{/if}
</td>
<td align="center" width="100">
{if $smarty.get.order eq "until"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=until&type=asc">Bezahlt bis</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=until&type=desc">Bezahlt bis</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=until&type=asc" class="sitelink">Bezahlt bis</a>
{/if}
</td>
<td align="center" width="100">
{if $smarty.get.order eq "type"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=type&type=asc">Stufe</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=type&type=desc">Stufe</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=type&type=asc" class="sitelink">Stufe</a>
{/if}
</td>
<td align="center">
{if $smarty.get.order eq "via"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=via&type=asc">Per</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=via&type=desc">Per</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=via&type=asc" class="sitelink">Per</a>
{/if}
</td>

<td align="center">
{if $smarty.get.order eq "sum_paid"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=sum_paid&type=asc">€</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=sum_paid&type=desc">€</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=sum_paid&type=asc" class="sitelink">€</a>
{/if}
</td>


<td align="center">
{if $smarty.get.order eq "payment_complete"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payment_complete&type=asc">BEZ.</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payment_complete&type=desc">BEZ.</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=payment_complete&type=asc" class="sitelink">BEZ.</a>
{/if}
</td>
{if $userrec.0.reminder_costs ne 0}
<td align="center">
{if $smarty.get.order eq "reminder_costs"}
{if $smarty.get.type eq "desc"}
<a href="?action=admin_paid&o={$smarty.get.o}&order=reminder_costs&type=asc">Geb&uuml;hren</a> <img src="images/s_desc.png">
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=reminder_costs&type=desc">Geb&uuml;hren</a> <img src="images/s_asc.png">
{/if}
{else}
<a href="?action=admin_paid&o={$smarty.get.o}&order=reminder_costs&type=asc" class="sitelink">Geb&uuml;hren</a>
{/if}
</td>
{/if}

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
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.real_name}</td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.real_street}</td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.real_plz}</td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.real_city}</td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.payday}</td>
<td width="100px" {if $color}style="color: white"{/if}>{$userdata.new_paid_until}</td>
<td width="100px" {if $color}style="color: white"{/if}>
{if $userdata.new_type eq 2}
VIP
{elseif $userdata.new_type eq 3}
Premium
{elseif $userdata.new_type eq 4}
Standard
{else}
{$userdata.new_type}
{/if}
</td>
<td {if $color}style="color: white"{/if}>
{if $userdata.paid_via eq 1}
KK
{elseif $userdata.paid_via eq 2}
PP
{elseif $userdata.paid_via eq 3}
ÜW
{elseif $userdata.paid_via eq 4}
ELV
{elseif $userdata.paid_via eq 5}
Bar	
{elseif $userdata.paid_via eq 6}
GP																				
{else}
{$userdata.paid_via}
{/if}
</td>
<td width="100px" {if $color}style="color: white"{/if} align="center">{$userdata.sum_paid}</td>
<td {if $color}style="color: white"{/if} align="center">
{if $userdata.payment_complete eq "1"}
J
{else}
N
{/if}
</td>
{if $userdata.reminder_costs ne 0}
<td align="center">{$userdata.reminder_costs}</td>

{/if}

</tr>
{if $userdata.errormsg}
<tr>
<td colspan="12" bgcolor="#f4e9f4" align="left" style="color: #FF3300; border: solid 1px #FF3300;">{$userdata.errormsg|wordwrap:100:"<br />":true}</td>
</tr>
{/if}
{/foreach}
</table>
</td>
</tr>
</table>
</td></tr>
</table>
</div>
<div class="page">{paginate_prev class="linklist"} {paginate_middle format="page" page_limit="5" class="linklist"} {paginate_next class="linklist"}</div>
</div>
{else}
<div class="result-box">
<h1>Payments</h1>
<div class="result-box-inside-nobg">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>
No payments available.
</td></tr>
</table>
</div>
</div>
{/if}