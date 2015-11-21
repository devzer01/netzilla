<link href="css/ui-lightness/jquery-ui-1.9.2.custom.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="js/jquery-ui-1.9.2.custom.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<div class="result-box">
	<h1>Add coins (Paypal)</h1>
	<div class="result-box-inside-nobg">
	{if $error}{$error}<br/><br/>{/if}
	<form action="" id="addBonusForm" method="post"/>
	<table>
	<tr>
		<td>Cart ID:</td><td><input type="text" name="id" id="id"/></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="ADD"/></td>
	</tr>
	</table>
	</form>
	</div>
</div>
<script language="javascript" type="text/javascript">
{literal}
jQuery('#datetime').datetimepicker({dateFormat: 'yy-mm-dd'});
{/literal}
</script>