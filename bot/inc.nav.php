<?php
require('routeros.php');

$API = new routeros_api();

$API->debug = false;

if ($API->connect('192.168.1.1', 'admin', 'netzillacompany')) {
	$table_html = "";
	$icon_html =  "";
	$table_html .= "<table>";

	$ARRAY = $API->comm('/interface/getall', array("?type"=>"pptp-in"));
	$ip_arr = $API->comm('/ip/address/getall');
	//$ARRAY = $API->comm('/interface/getall', array("?name"=>"PPTP-Germany1"));
	foreach($ARRAY as $interface)
	{
		if(strpos($interface['name'],"PPTP-Germany") !== false)
		{
			if($interface['disabled']==='false')
			{
				$table_html .= "<tr>";
				$user = strtolower(str_replace("PPTP-","",$interface['name']));
				$last = $API->comm('/ppp/secret/getall', array("?name"=>$user));
				$last = $last[0]['last-logged-out'];

				$table_html .= "<td>".str_replace("PPTP-Germany","Router ",$interface['name'])."</td>";

				if(isset($interface['comment']))
				{
					$table_html .= "<td>".$interface['comment']."</td>";
				}

				if($interface["running"]=="true")
				{
					$table_html .= "<td>Online</td><td>";
					/*$interfaces = false;
					foreach($ip_arr as $temp_ip)
					{
						if($temp_ip['interface']==$interface['name'])
						{
							$ip = $temp_ip['network'];
							$API2 = new routeros_api();
							try
							{
								if ($API2->connect($ip, 'admin', 'netzillacompany')) {
									$interfaces = $API2->comm('/interface/getall', array("?type"=>"ppp-out"));
									foreach($interfaces as $usb)
									{
										$table_html .= $usb['name']." => ".($usb['running']=="true"?"Online":"Offline").", ";
									}
								}
							}
							catch(Exception $e)
							{
								$table_html .= "Failed to get informations.";
							}
							break;
						}
					}*/
					$table_html .= "</td>";
					$icon_html .= "<img src='images/online.png' height='24'/>";
				}
				else
				{
					$table_html .= "<td>Offline</td><td>".$last."</td>";
					$icon_html .= "<img src='images/offline.png' height='24'/>";
				}

				$table_html .= "</tr>";
			}
		}
	}
	$table_html .= "</table>";
	//echo $icon_html;
	echo " <a href='#' onclick='$(\"#router-detail\").toggle(\"slow\"); return false;'>$icon_html</a><br/>";
	echo "<div style='display:none;' id='router-detail'>$table_html</div>";
	$API->disconnect();
}
?>
<div id="domainsStatus"></div>
<div style="width: 790px; margin:10px auto; padding:10px; border:1px dashed #008B13">
	<?php if($_SESSION['password']==ADMIN_PASSWORD){?>
		<a href="index.php" class="nav">BOTs</a> | 
		<a href="presets.php" class="nav">Presets</a> |
		<a href="/bot/op/" class="nav">Operations</a> |  
		<a href="manage-site.php?action=add" class="nav">Add site</a> | 
		<!--<a href="manage-site.php?action=delete" class="nav">Delete site</a> | -->
		<a href="manage-user.php?action=add" class="nav">Add user</a> | 
		<a href="manage-user.php?action=delete" class="nav">Delete user</a> | <!---->
		<a href="manage-message.php?action=add" class="nav">Messages</a> | 
		<a href="delete-user-msg.php" class="nav">Delete User Sent MSG</a> |
		<!-- <a href="manage-message.php?action=delete" class="nav">Delete message</a> | -->
		<a href="manage-url.php" class="nav">Mask URL</a> | 
		<a href="manage-log.php" class="nav">Logs</a> |
		<a href="stat/report" class="nav">Report</a> |
		<a href="stat/daily" class="nav">Message Graph</a> |
		<a href="stat/redirect" class="nav">Redirect Report</a> |
		<a href="stat/profilereport" class="nav">Profile Report</a> |
		<a href="emails.php" class="nav">Email Accounts</a> |
		<a href="vcards.php" class="nav">VCards</a> |
		<a href="settings.php" class="nav">Settings</a> |
		<a href="../monitor/bot-servers" class="nav" target="_blank">Server Status</a> |

	<?php }?>

	<?php if($_SESSION['password']=="BotTestProcess"){?>
		<a href="bot-test.php" class="nav">Bot Test</a> |
		<a href="profile-test.php?action=add" class="nav">Add Profile Test</a> |
	<?php }?>

	<?php if($_SESSION['password']=="Flensburg1"){?>
		<a href="summary-report.php" class="nav">Report</a> |
	<?php }?>

	<a href="logout.php" class="nav">Log Out</a>
</div>

<script>
$(function(){getExpiringRedirecDomainsList()});

function getExpiringRedirecDomainsList()
{               
	$.ajax({
		url: "http://netzilla.no-ip.org/monitor/getExpiringDomainsList"
		
	}).done(function(response){
		var parseResponse = $.parseJSON(response);
		if(parseResponse.length > 0)
		{
			resultTxt = '*** <a class="nav" href="#" onclick="alert(\'';
			$.each(parseResponse, function(i, domain){
				resultTxt += domain.name +'\t\t\t'+domain.expires+'\\n';
			});
			resultTxt += '\')">Expiring redirect domains</a>';
			$("#domainsStatus").html(resultTxt);
		}
	});
}
</script>
