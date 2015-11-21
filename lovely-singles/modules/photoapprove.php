<?php
//check permission type//
$permission_lv = array(1, 2, 3, 4, 8);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

$userid = $_SESSION[sess_id];

	if(!isset($_GET['next']))
		SmartyPaginate::reset();

	SmartyPaginate::connect();
	SmartyPaginate::setLimit(20); //smarty paging set records per page
	SmartyPaginate::setPageLimit(5); //smarty paging set limit pages show
	SmartyPaginate::setUrl("./?s=".$_GET['s']); //smarty paging set URL

	if($_POST[approve]){
		//Approve Photo							
		Photo::ApprovePhoto($_POST[EmailChatID],$_POST[ch_photo],$_POST[ch_fsk18]);			
	}elseif($_POST[denine]){
		//Denine Photo				
		Photo::DeninePhoto($_POST[EmailChatID],$_POST[ch_photo]);					
	}	
	
	$PhotoData = Photo::getNoActivePhoto();	

	if(count($PhotoData) > 0){

		for($i=0;$i<count($PhotoData);$i++){			
			$tmp[$PhotoData[$i]["userid"]]["picturepath"] = "";	
			$tmp[$PhotoData[$i]["userid"]]["id"] = $PhotoData[$i]["id"];
			$tmp[$PhotoData[$i]["userid"]]["userid"] = $PhotoData[$i]["userid"];
			$tmp[$PhotoData[$i]["userid"]]["username"] = $PhotoData[$i]["username"];	
			$tmp[$PhotoData[$i]["userid"]]["status"] = $PhotoData[$i]["status"];
			$tmp[$PhotoData[$i]["userid"]]["photoalbume"] = "";
			$tmp[$PhotoData[$i]["userid"]]["fsk18tmp"] = $PhotoData[$i]["fsk18"];

			//FSK18
				switch($PhotoData[$i]["fsk18"]){
						case "y" : 
							$tmp[$PhotoData[$i]["userid"]]["fsk18"] = "Yes";
							break;

						case "n" : 
							$tmp[$PhotoData[$i]["userid"]]["fsk18"] = "No";
							break;

						default:
							$tmp[$PhotoData[$i]["userid"]]["fsk18"] = "Not Check";
							break;
					}
		}

		for($i=0;$i<count($PhotoData);$i++){
			if($PhotoData[$i]["status"]==1){
				$tmp[$PhotoData[$i]["userid"]]["id"] = $PhotoData[$i]["id"];
				$tmp[$PhotoData[$i]["userid"]]["picturepath"] = $PhotoData[$i]["picturepath"];
				$tmp[$PhotoData[$i]["userid"]]["fsk18tmp"] = $PhotoData[$i]["fsk18"];

				//FSK18
					switch($PhotoData[$i]["fsk18"]){
						case "y" : 
							$tmp[$PhotoData[$i]["userid"]]["fsk18"] = "Yes";
							break;

						case "n" : 
							$tmp[$PhotoData[$i]["userid"]]["fsk18"] = "No";
							break;

						default:
							$tmp[$PhotoData[$i]["userid"]]["fsk18"] = "Not Check";
							break;
					}					
			}

			if($PhotoData[$i]["status"]==2){
				$tmp[$PhotoData[$i]["userid"]]["photoalbume"] = "y";
			}
		}

		$smarty->assign("PhotoData", array_slice($tmp, SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit()));
		$smarty->assign("numPhotoData", count($tmp));
		SmartyPaginate::setTotal(count($tmp));//print_r($tmp);exit;
	}else{
		$smarty->assign("numPhotoData", 0);
		SmartyPaginate::setTotal(0);
	}

$smarty->display('admin.tpl'); //select template file
?>