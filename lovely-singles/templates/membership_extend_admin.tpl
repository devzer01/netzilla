<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{#TITLE#}</title>
	<link href="{$url_web}/css/flirtpub.css" rel="stylesheet" type="text/css" />
</head>
<body style="margin:0px 0px 0px 0px;">
<table width='100%'  border='0' height='100%' cellspacing='0' cellpadding='0' bgcolor="#db9ced">
	<tr><td><img src='images/dot.gif' height='5' width='1' border='0'></td></tr>	
	<tr>
	<td width='100%' align='center' valign='top'>
	<table width='900' border='0' cellspacing='0' cellpadding='0' >
		{*<tr><td><img src='{$url_web}/images/head_mail.gif' width="900"/></td></tr>*}
		<tr>
			<td background="{$url_web}/images/h1.gif" height="6" width="900"></td>
		</tr>
		<tr>
			<td class="bg" align="right" background="{$url_web}/images/h2.gif" height="13" valign="middle" width="900"></td>
		</tr>
		<tr>
			<td background="{$url_web}/images/h3.gif" height="131" width="900"></td>
		</tr>
		<tr><td bgcolor="#FFFFFF" height="5"></td></tr>
		<tr>
	  		<td width='900' align='center'>
         		 <table width='100%' height='100%' border='0' cellpadding='0' cellspacing='0'>       		 
				 <tr>
					<td width='12'><img src='{$url_web}/images/clt.gif' width="12" height="16"/></td>
					<td background='{$url_web}/images//cbg.gif'></td>
					<td width='11'><img src='{$url_web}/images/crt.gif' width="11" height="16"/></td>
				</tr>		
				<tr>
					<td width='12' background='{$url_web}/images/cl.gif'></td>
					<td bgcolor="#a05dc0"> 
					<div align='center'>
					<table width='98%' border='0' cellspacing='0' cellpadding='0'>
					<tr>	
						<td align="left">
						<table width='100%' border='0' cellspacing='0' cellpadding='0'>
						<tr><td height="10"></td></tr>
						<tr><td>Guten Morgen, Petra!</td></tr>
						<tr>
							<td valign='top'>
								<table width='100%'  border='0' cellspacing='1' cellpadding='4'>
								<tr valign='top'><td height='10' ></td></tr>								 
								<tr bgcolor="#00CCFF" align="center">
									<td>Zahlungs ID</td>
									<td>Mitglieds ID</td>
									<td>Nickname</td>
									<td>Name</td>
									<td>Handy Nr.</td>
									<td>Strasse </td>
									<td>PLZ </td>
									<td>Stadt </td>
									<td>Summe</td>							
									<td>Transaktions-Nr</td>
									<td>Bank</td>
									<td>Blz</td>
									<td>Konto Nr.</td>
									<td>Mitgliedschaft beendet</td>
									<td>beendet am</td>
									<td>Bezahlt bis (neu)</td>
								</tr>
								{foreach key=key from=$member item=member}
								<tr bgcolor="{cycle values="#f7f7f7,#d0d0d0"}" align="left">
									<td>{$member.ID}</td>
									<td>{$member.m_id}</td>
									<td>{$member.m_username}</td>
									<td>{$member.real_name}</td>
									<td>{$member.m_mobileno}</td>
									<td>{$member.real_street}</td>
									<td>{$member.real_plz}</td>
									<td>{$member.real_city}</td>
									<td>{$member.sum_paid}</td>
									<td>{$member.former_transaction_no}</td>										
									<td>{$member.bank_name}</td>
									<td>{$member.bank_blz}</td>
									<td>{$member.bank_account}</td>
									{if $member.cancelled == 1}
									<td>Ja</td>
									<td>{$member.cancelled_date|date_format:"%Y-%m-%d"}</td>
									{else}
									<td>Nein</td>
									<td>----</td>
									<td>{$member.new_paid_until|date_format:"%Y-%m-%d"}</td>
									{/if}
								</tr>
								{/foreach}
								<tr><td height="10"></td></tr>
							   </table>
							</td>
						</tr>
						</table>
						
						</td>
					</tr>		
					</table>
					</div>
					</td>
						<td width='10' background='{$url_web}/images/cr.gif' height="120"></td>
					</tr>
					<tr>
						<td width='12'><img src='{$url_web}/images/cld.gif' width="12" height="16"/></td>
						<td background='{$url_web}/images/cbgd.gif'></td>
						<td width='11'><img src='{$url_web}/images/crd.gif' width="11" height="16"/></td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td bgcolor="#FFFFFF" height="5"></td></tr>
				</table>
			</td>
		</tr>
</table>
</body>
</html>