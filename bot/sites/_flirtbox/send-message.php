<?php
ob_start();
require_once 'funcs.php';
set_time_limit(0);

function flush_buffers(){
	echo "<br/><script>window.scrollTo(0, document.body.scrollHeight);</script>";

	ob_end_flush();
	ob_flush();
	flush();
	ob_start();
}

if($_POST)
{

	$subject = $_POST['subject'];
	$message = $_POST['message'];
	$proxy = $_POST['proxy'];
	$login_gender = $_POST['login-gender'];
	$gender = $_POST['gender'];
	$age_group = $_POST['age_group'];
	$country = $_POST['country'];
	$timer = $_POST['timer'];

	//count all sender in database
	$arrcount_sender = funcs::db_count_sender();
	$count_sender = $arrcount_sender['count_sender'];

	//send message per login account
	$send_amount = $_POST['send-amount'];


	//userdata
	$arrUserData = funcs::db_get_userlist();

	foreach($arrUserData as $data) {
		$username = $data[username];
		$password = $data[password];

		echo "[ ".date("Y-m-d H:i:s")." ] "."Logging in with profile: ".$username;
		flush_buffers();

		//last page
		$lastpage = funcs::db_get_last_page();
		$start = $lastpage['lastpage'] + 1;
		$end = ($start - 1) + ($send_amount / 35);

		//start send message
		if($subject != "" && $message != "" && $start != "")
		{

			if($start == $end)
			{
				echo "[ ".date("Y-m-d H:i:s")." ] "."Start sending message...";
				flush_buffers();

				$url = "http://www.flirtbox.co.uk/quickSearch.php?resultpage=$start&gender=$login_gender&targettedGender=$gender&age_group=$age_group&country=$country&location=&username=";
				$content =  funcs::get_contents($url, $username, $password, $proxy);

				// 			insert into database
				// 			funcs::insertSearch($username, $url, $content);
				// 			$data = funcs::db_get_htmlcontent($username, $url);

				$strFileName = "textfiles/".$username."page-".$start.".txt";
				$objFopen = fopen($strFileName, 'w');
				fwrite($objFopen, $content);
				fclose($objFopen);

				$data = file_get_contents($strFileName);
				if($data!="")
				{
					// insert log
					funcs::insertUserLog($username, $country.$start);
					funcs::db_insert_log_page($username, $end);

					preg_match_all('/.*<div class="username"><a href="\/(.*)">.*<\/a><\/div>.*/',$data, $matches);// $matches[1] = list(username);


					for($i=0; $i<count($matches[0]); $i++)
					{
						if($timer!='')
						{
							echo "[ ".date("Y-m-d H:i:s")." ] "."Sleep for ".$timer."s.";
							flush_buffers();
							sleep($timer);
						}

						// 					if($i <= $countsenduser)
						// 					{
						$checkname = funcs::dbCheckSentUser($username, $matches[1][$i]);
						if($checkname['name'] != $matches[1][$i])
						{
							try
							{
								//('username', 'password', 'to', 'subject', 'message');
								funcs::sendMessage($username, $password, $matches[1][$i], $subject, $message, $proxy);
								if(funcs::insertLog($username, $matches[1][$i]))
								{
									echo "[ ".date("Y-m-d H:i:s")." ] ".'messsage sent to '.$matches[1][$i];
									flush_buffers();
								}
								
							}
							catch(Exception $e)
							{
								file_put_contents("textfiles/".$username."age".$start.".txt","[ ".date("Y-m-d H:i:s")." ] ".$e->getMessage(),FILE_APPEND);
								echo "[ ".date("Y-m-d H:i:s")." ] "."EXCEPTION ".$matches[1][$i];
								flush_buffers();
							}
						}
						else
						{
							echo 'could not sent message to '.$matches[1][$i];
							flush_buffers();
						}
						// 					}
							

					}//end for
					
				}
				unlink($strFileName);

			}
			else
			{
				echo "[ ".date("Y-m-d H:i:s")." ] "."Start sending message...";
				flush_buffers();
				for ($page = $start; $page <= $end; $page++)
				{

					$url = "http://www.flirtbox.co.uk/quickSearch.php?resultpage=$page&gender=$login_gender&targettedGender=$gender&age_group=$age_group&country=$country&location=&username=";
					$content =  funcs::get_contents($url, $username, $password, $proxy);
					// 				funcs::insertSearch($username, $url, $content);

					// 				$data = funcs::db_get_htmlcontent($username, $url);
					// 				$newdata = stripslashes($data);

					$strFileName = "textfiles/".$username."page-".$page.".txt";
					$objFopen = fopen($strFileName, 'w');
					fwrite($objFopen, $content);
					fclose($objFopen);

					$data = file_get_contents($strFileName);

					if($data!="")
					{
						// 				insert log
						funcs::insertUserLog($username, "country: ".$country."  ".$start."-".$end);
						funcs::db_insert_log_page($username, $end);
						
						preg_match_all('/.*<div class="username"><a href="\/(.*)">.*<\/a><\/div>.*/', $data, $matches);// $matches[1] = list(username);
							
						for($i=0; $i<count($matches[0]); $i++)
						{
							if($timer!='')
							{
								echo "[ ".date("Y-m-d H:i:s")." ] "."Sleep for ".$timer."s.";
								flush_buffers();
								sleep($timer);
							}

							// 						if($i <= $countsenduser)
							// 						{
							$checkname = funcs::dbCheckSentUser($username, $matches[1][$i]);

							if($checkname['name'] != $matches[1][$i])
							{
								try
								{
									//('username', 'password', 'to', 'subject', 'message');
									funcs::sendMessage($username, $password, $matches[1][$i], $subject, $message, $proxy);

									if(funcs::insertLog($username, $matches[1][$i]))
									{
										echo "[ ".date("Y-m-d H:i:s")." ] ".'messsage sent to '.$matches[1][$i];
										flush_buffers();
									}
									else
									{
										echo "[ ".date("Y-m-d H:i:s")." ] ".'could not sent message to '.$matches[1][$i];
										flush_buffers();
									}
								}
								catch(Exception $e)
								{
									file_put_contents("textfiles/".$username."age".$start.".txt","[ ".date("Y-m-d H:i:s")." ] ".$e->getMessage(),FILE_APPEND);
									echo "[ ".date("Y-m-d H:i:s")." ] "."EXCEPTION ".$matches[1][$i];
									flush_buffers();
								}

							}
							else
							{
								echo "[ ".date("Y-m-d H:i:s")." ] ".'could not sent message to '.$matches[1][$i];
								flush_buffers();
							}
							// 						}


						}
						unlink($strFileName);
					}
					else {
						echo "[ ".date("Y-m-d H:i:s")." ] "."no data";
						flush_buffers();
					}
				}
				unlink($strFileName);
			}//end else
		}//end if data not empty

	}//end foreach user



}//end post


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<style type="text/css">
body { /* 	font:bold 13px "MS Sans Serif"; */
	font: 13px "Arial";
}

div {
	line-height: 50px;
}
</style>
<script type="text/javascript" src="include/jquery-1.7.2.js"></script>
<script type="text/javascript">
function listTo(page)
{
	var end = document.getElementById('end');
	
	if(page==202)
	{
		option = '<option value=' + page + '>' + page + '</option>';
		end.innerHTML = option;
	}
	else
	{
		var option;
		for(var i=page; i<=202; i++)
		{
			option = option + '<option value=' + i + '>' + i + '</option>';
		}
		end.innerHTML = option;
		
	}
}

function checkProxy(proxy)
{
	$("#proxystatus").empty().html('<img src="images/ajax-loader.gif" />');
	//$("#proxystatus").load("test-proxy.php?ip="+proxy);
	$.ajax({
		  type: 'POST',
		  url: 'test-proxy.php',
		  data: "ip=" + proxy,
		 
		}).done(function( html ) {
			$("#proxystatus").empty().html('');
			 $("#proxystatus").append(html);
		});
	
}

function checkForm()
{
	
	if(document.getElementById('login-gender').value=='')
	{
		alert('please select login Gender');
		document.getElementById('login-gender').focus();

		return false;
	}
	
	else if(document.getElementById('subject').value=='')
	{
		alert('please insert subject');
		document.getElementById('subject').focus();

		return false;
	}
	else if(document.getElementById('message').value=='')
	{
		alert('please insert message');
		document.getElementById('message').focus();

		return false;
	}

	
	
}
</script>
</head>
<body>




<?php
if($username)
{
	echo "<a href='send-message.php'> BACK</a>";
}
else
{
	?>
	<div style="margin: 0 auto; width: 900px;">

		<hr>

		<form name="selectpage" id="selectpage" action="" method="post"
			onsubmit="return checkForm();">
			<!-- 
			<div>
				username: <input type="text" name="username" id="username" value="<?php echo $username;?>">   password: <input type="text" name="password" id="password" value="<?php echo $password;?>">
			</div>
			
			 -->
			<div>
				
				sender list: <select>
				<?php
				$userProfile = funcs::db_get_userdata();
				foreach ($userProfile as $userdata)
				{
					echo "<option value='$userdata[id]'>$userdata[username]</option>";
				}
				?>
				</select> <a href="add-profile.php">Add user profile</a> || <a href="delete-user.php"> delete profile.</a>
			</div>
			<div>
				waiting every: <select name="timer">
					<option value="120">2 min</option>
					<option value="180">3 min</option>
					<option value="240">4 min</option>
					<option value="300">5 min</option>
					<option value="600">10 min</option>
					<option value="900">15 min</option>
				</select>
			</div>

			<div>
				Login Gender: <select name="login-gender" id="login-gender">
					<option value="">--</option>
					<option value="1">Female</option>
					<option value="2">Male</option>
				</select> <font color="red">*important, the results show <b>not your
						gender.</b>
				</font>
			</div>
			<div>SEARCH OPTION:</div>
			<div style="margin: 0 auto;">
				<div style="width: 400px;">
					<div style="float: left; width: 200px;">
						Gender: <select name="gender" id="geder">
							<option value="1">Female</option>
							<option value="2">Male</option>
						</select>
					</div>
					<div style="float: right; width: 200px;">
						Age between: <select id="age_group" name="age_group">
							<option value="0">-</option>
							<option value="1">16 - 19</option>
							<option value="2">18 - 25</option>
							<option value="3">18 - 30</option>
							<option value="4">18 - 35</option>
							<option value="5">20 - 30</option>
							<option value="6">20 - 35</option>
							<option value="7">25 - 30</option>
							<option value="8">25 - 35</option>
							<option value="9">25 - 40</option>
							<option value="10">30 - 40</option>
							<option value="11">35 - 45</option>
							<option value="12">40 - 50</option>
							<option value="13">50 - 60</option>
							<option value="14">55 - 65</option>
							<option value="15">60 - 70</option>
							<option value="16">70+</option>

						</select>
					</div>
				</div>
				<div style="clear: both;"></div>
				<div>
					Country: <select name="country" id="country">
						<option value="" selected>-</option>
						<option value="3">Afghanistan</option>
						<option value="6">Albania</option>
						<option value="55">Algeria</option>
						<option value="1">Andorra</option>
						<option value="9">Angola</option>
						<option value="5">Anguilla</option>
						<option value="4">Antigua and Barbuda</option>
						<option value="10">Argentina</option>
						<option value="7">Armenia</option>
						<option value="13">Aruba</option>
						<option value="12">Australia</option>
						<option value="14">Azerbaijan</option>
						<option value="28">Bahamas</option>
						<option value="21">Bahrain</option>
						<option value="17">Bangladesh</option>
						<option value="16">Barbados</option>
						<option value="31">Belarus</option>
						<option value="18">Belgium</option>
						<option value="32">Belize</option>
						<option value="23">Benin</option>
						<option value="24">Bermuda</option>
						<option value="29">Bhutan</option>
						<option value="26">Bolivia</option>
						<option value="15">Bosnia and Herzegovina</option>
						<option value="30">Botswana</option>
						<option value="27">Brazil</option>
						<option value="25">Brunei Darussalam</option>
						<option value="20">Bulgaria</option>
						<option value="19">Burkina Faso</option>
						<option value="22">Burundi</option>
						<option value="104">Cambodia</option>
						<option value="41">Cameroon</option>
						<option value="33">Canada</option>
						<option value="46">Cape Verde</option>
						<option value="111">Cayman Islands</option>
						<option value="35">Central African Republic</option>
						<option value="194">Chad</option>
						<option value="40">Chile</option>
						<option value="42">China</option>
						<option value="47">Christmas Island</option>
						<option value="34">Cocos (Keeling) Islands</option>
						<option value="43">Colombia</option>
						<option value="106">Comoros</option>
						<option value="36">Congo</option>
						<option value="39">Cook Islands</option>
						<option value="44">Costa Rica</option>
						<option value="88">Croatia (Hrvatska)</option>
						<option value="45">Cuba</option>
						<option value="48">Cyprus</option>
						<option value="49">Czech Republic</option>
						<option value="52">Denmark</option>
						<option value="50">Deutschland</option>
						<option value="51">Djibouti</option>
						<option value="53">Dominica</option>
						<option value="54">Dominican Republic</option>
						<option value="56">Ecuador</option>
						<option value="58">Egypt</option>
						<option value="190">El Salvador</option>
						<option value="80">Equatorial Guinea</option>
						<option value="60">Eritrea</option>
						<option value="57">Estonia</option>
						<option value="62">Ethiopia</option>
						<option value="65">Falkland Islands (Malvinas)</option>
						<option value="67">Faroe Islands</option>
						<option value="64">Fiji</option>
						<option value="63">Finland</option>
						<option value="68">France</option>
						<option value="73">French Guiana</option>
						<option value="159">French Polynesia</option>
						<option value="195">French Southern Territories</option>
						<option value="69">Gabon</option>
						<option value="77">Gambia</option>
						<option value="72">Georgia</option>
						<option value="74">Ghana</option>
						<option value="75">Gibraltar</option>
						<option value="81">Greece</option>
						<option value="76">Greenland</option>
						<option value="71">Grenada</option>
						<option value="79">Guadeloupe</option>
						<option value="83">Guatemala</option>
						<option value="78">Guinea</option>
						<option value="84">Guinea-Bissau</option>
						<option value="85">Guyana</option>
						<option value="89">Haiti</option>
						<option value="87">Honduras</option>
						<option value="86">Hong Kong</option>
						<option value="90">Hungary</option>
						<option value="97">Iceland</option>
						<option value="94">India</option>
						<option value="91">Indonesia</option>
						<option value="96">Iran</option>
						<option value="95">Iraq</option>
						<option value="92">Ireland</option>
						<option value="93">Israel</option>
						<option value="98">Italy</option>
						<option value="99">Jamaica</option>
						<option value="107">Japan</option>
						<option value="100">Jordan</option>
						<option value="112">Kazakhstan</option>
						<option value="102">Kenya</option>
						<option value="105">Kiribati</option>
						<option value="108">Korea (North)</option>
						<option value="109">Korea (South)</option>
						<option value="110">Kuwait</option>
						<option value="103">Kyrgyzstan</option>
						<option value="113">Laos</option>
						<option value="122">Latvia</option>
						<option value="114">Lebanon</option>
						<option value="119">Lesotho</option>
						<option value="118">Liberia</option>
						<option value="123">Libya</option>
						<option value="116">Liechtenstein</option>
						<option value="120">Lithuania</option>
						<option value="121">Luxembourg</option>
						<option value="133">Macau</option>
						<option value="129">Macedonia</option>
						<option value="127">Madagascar</option>
						<option value="140">Malawi</option>
						<option value="142">Malaysia</option>
						<option value="139">Maldives</option>
						<option value="130">Mali</option>
						<option value="137">Malta</option>
						<option value="128">Marshall Islands</option>
						<option value="134">Martinique</option>
						<option value="135">Mauritania</option>
						<option value="138">Mauritius</option>
						<option value="221">Mayotte</option>
						<option value="141">Mexico</option>
						<option value="66">Micronesia</option>
						<option value="126">Moldova</option>
						<option value="125">Monaco</option>
						<option value="132">Mongolia</option>
						<option value="136">Montserrat</option>
						<option value="124">Morocco</option>
						<option value="143">Mozambique</option>
						<option value="131">Myanmar</option>
						<option value="144">Namibia</option>
						<option value="153">Nauru</option>
						<option value="152">Nepal</option>
						<option value="150">Netherlands</option>
						<option value="8">Netherlands Antilles</option>
						<option value="145">New Caledonia</option>
						<option value="155">New Zealand (Aotearoa)</option>
						<option value="149">Nicaragua</option>
						<option value="146">Niger</option>
						<option value="148">Nigeria</option>
						<option value="154">Niue</option>
						<option value="147">Norfolk Island</option>
						<option value="151">Norway</option>
						<option value="11">&Ouml;sterreich</option>
						<option value="156">Oman</option>
						<option value="162">Pakistan</option>
						<option value="167">Palau</option>
						<option value="157">Panama</option>
						<option value="160">Papua New Guinea</option>
						<option value="168">Paraguay</option>
						<option value="158">Peru</option>
						<option value="161">Philippines</option>
						<option value="165">Pitcairn</option>
						<option value="163">Poland</option>
						<option value="166">Portugal</option>
						<option value="169">Qatar</option>
						<option value="170">Reunion</option>
						<option value="171">Romania</option>
						<option value="172">Russia</option>
						<option value="173">Rwanda</option>
						<option value="82">S. Georgia and S. Sandwich Isls.</option>
						<option value="115">Saint Lucia</option>
						<option value="213">Saint Vincent and the Grenadines</option>
						<option value="219">Samoa</option>
						<option value="185">San Marino</option>
						<option value="189">Sao Tome and Principe</option>
						<option value="174">Saudi Arabia</option>
						<option value="37">Schweiz</option>
						<option value="186">Senegal</option>
						<option value="176">Seychelles</option>
						<option value="184">Sierra Leone</option>
						<option value="179">Singapore</option>
						<option value="183">Slovak Republic</option>
						<option value="181">Slovenia</option>
						<option value="175">Solomon Islands</option>
						<option value="187">Somalia</option>
						<option value="222">South Africa</option>
						<option value="61">Espa&ntilde;a/Spain</option>
						<option value="117">Sri Lanka</option>
						<option value="180">St. Helena</option>
						<option value="164">St. Pierre and Miquelon</option>
						<option value="177">Sudan</option>
						<option value="188">Suriname</option>
						<option value="182">Svalbard and Jan Mayen Islands</option>
						<option value="192">Swaziland</option>
						<option value="178">Sweden</option>
						<option value="191">Syria</option>
						<option value="206">Taiwan</option>
						<option value="198">Tajikistan</option>
						<option value="207">Tanzania</option>
						<option value="197">Thailand</option>
						<option value="196">Togo</option>
						<option value="199">Tokelau</option>
						<option value="202">Tonga</option>
						<option value="204">Trinidad and Tobago</option>
						<option value="201">Tunisia</option>
						<option value="203">Turkey</option>
						<option value="200">Turkmenistan</option>
						<option value="193">Turks and Caicos Islands</option>
						<option value="205">Tuvalu</option>
						<option value="209">Uganda</option>
						<option value="208">Ukraine</option>
						<option value="2">United Arab Emirates</option>
						<option value="70">United Kingdom</option>
						<option value="211">Uruguay</option>
						<option value="210">USA</option>
						<option value="212">Uzbekistan</option>
						<option value="217">Vanuatu</option>
						<option value="214">Venezuela</option>
						<option value="216">Viet Nam</option>
						<option value="215">Virgin Islands (British)</option>
						<option value="218">Wallis and Futuna Islands</option>
						<option value="59">Western Sahara</option>
						<option value="220">Yemen</option>
						<option value="224">Zaire</option>
						<option value="223">Zambia</option>
						<option value="225">Zimbabwe</option>

					</select>
				</div>
				<div>
					send amount: <select name="send-amount">
					<?php
					for($i=35; $i<=700; $i++)
					{
						if(($i%35) == 0)
						{
							// 						?>
						<option value="<?php echo $i;?>">
							
							
							
						<?php echo $i;?></option>
						
						
						<?php 
								}
							}
						?>
					</select>
					per login account.
				</div>

				<div>
					subject: <br> <input type="text" name="subject" id="subject"
						value="<?php echo $subject;?>">
				</div>
				<div>
					message: <br>
					<textarea name="message" id="message" rows="5" cols="40">




					<?php echo $message;?></textarea>
				</div>
				<div>
					<input name="submit" type="submit" value="submit">
				</div>
			</div>
		</form>
	</div>
	
	
<?php 
}
?>
<hr>


</body>
</html>
