<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{#title#}</title>
<style>
a.check
{
    font-family: verdana;
	font-size: 10px;
	color: #000000;
	font-weight: none;
	text-decoration: none;
}
a.check:hover
{
    font-family: verdana;
	font-size: 10px;
	color: #000000;
	font-weight: bold;
	text-decoration: none;
}
</style>
</head>
<body>
<?php
	
	//session_start();

	if(isset($_SESSION['sess_id']) && !empty($_SESSION['sess_id']))
		$url = "<a href=\"#\" onClick=\"parent.parent.location.href='index.php?action=register&type=upgrade';\" class=\"check\">Upgrade to Full</a>";
	else
		$url = "<a href=\"#\" onClick=\"parent.parent.location.href='index.php?action=register&type=membership';\" class=\"check\">Jetzt Mitglied werden</a>";

?>

<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td></td>
		<td>
		<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="10"><img src="images/pic_top_l.gif" width="10" height="6" /></td>
				<td background="images/pic_top_c.gif"></td>
				<td width="10"><img src="images/pic_top_r.gif" width="10" height="6" /></td>
			</tr>
			<tr>
				<td width="10" height="101" background="images/p_c_l.gif"></td>
				<td bgcolor="#ECF0F1">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
							<tr>
								<td class="text10black" style="text-align:center "><div align="center"><br> Diese Funktion steht nur unseren Mitgliedern zur Verf&uuml;gung<br><br> Melde dich noch heute an und genieße die Vorteile der kostenloses Mitgliedschaft bei Herzoase! </div></td>
							</tr>
							<tr>
								<td height="5px"></td>
							</tr>
							<tr>
								<td height="30" valign="middle"><div align="center">
								<table width="123" height="18" border="1" cellpadding="0" cellspacing="0" bordercolor="#3B6B84">
									<tr>
										<td align="center" bgcolor="#5BB1DD">
										<?php echo $url; ?>
										</td>
									</tr>
								</table>
								</div></td>
							</tr>				
						</table>
						</td>
					</tr>
				</table>
				</td>
				<td width="10" height="101" background="images/p_c_r.gif"></td>
			</tr>
			<tr>
				<td width="10"><img src="images/p_foot_l.jpg" width="10" height="5" /></td>
				<td background="images/p_foot_c.jpg"></td>
				<td width="10"><img src="images/p_foot_r.jpg" width="10" height="5" /></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</body>
</html>