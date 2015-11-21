<?php
/**
 * start profile score : NOI
 **/

/*
 * Add profile score
 */

$rate_group = array(
					array(
						'elm_name' => 'username',
						'elm_operand' => ''
						),
					array(
						'elm_name' => 'password',
						'elm_operand' => ''
						),
					array(
						'elm_name' => 'email',
						'elm_operand' => ''
						),
					array(
						'elm_name' => 'gender',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'birthday',
						'elm_operand' => '0000-00-00'
						),
					array(
						'elm_name' => 'country',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'state',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'city',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'height',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'weight',
						'elm_operand' => '0'
						),
					/*array(
						'elm_name' => 'appearance',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'eyescolor',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'haircolor',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'hairlength',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'beard',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'zodiac',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'civilstatus',
						'elm_operand' => '0'
						),
					array(
						'elm_name' => 'sexuality',
						'elm_operand' => '0'
						),*/
					array(
						'elm_name' => 'tattos',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'smoking',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'glasses',
						'elm_operand' => '2'
						),
					/*array(
						'elm_name' => 'handicapped',
						'elm_operand' => '2'
						),*/
					array(
						'elm_name' => 'piercings',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'lookmen',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'lookwomen',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'lookpairs',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'minage',
						'elm_operand' => '-1'
						),
					array(
						'elm_name' => 'maxage',
						'elm_operand' => '100'
						),
					array(
						'elm_name' => 'relationship',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'onenightstand',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'affair',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'friendship',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'cybersex',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'picture_swapping',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'live_dating',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'role_playing',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 's_m',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'partner_exchange',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'voyeurism',
						'elm_operand' => '2'
						),
					array(
						'elm_name' => 'description',
						'elm_operand' => ''
						),
					array(
						'elm_name' => 'picturepath',
						'elm_operand' => ''
						)
				);

$progress_score = array();
$progress_no_score = array();
$complete_profile = array();
$total_score = 20;

if(($_GET['proc'] == 'edit') && ($_SESSION['sess_admin'] == 1))
	$userid = funcs::getUserid($_GET['user']);
else
	$userid = $_SESSION['sess_id'];

$phonenumber = funcs::getCurrentUserMobileNo();
$profile_mobile = funcs::getText($_SESSION['lang'], '$progress_bar_mobile');
if((isset($phonenumber)) && ($phonenumber=="Verified"))
{	
	$progress_score['Phone_Number']['text'] = $profile_mobile;
	$progress_score['Phone_Number']['score'] = 20;
	$total_score += 20;
}
else
{
	$progress_no_score['Phone_Number']['text'] = $profile_mobile;
	$progress_no_score['Phone_Number']['score'] = 0;
}

$profile = funcs::getProfile($userid);	//get profile data

foreach($rate_group  as $elment)
{
	//echo $elment['elm_name']." : ".$elment['elm_operand']." : '".$profile[$elment['elm_name']]."'";
	if(($profile[$elment['elm_name']] == $elment['elm_operand']))
	{
		$complete_profile[] = 'false';
		//echo " false";
	}
	//echo "<br/>";
}
//$complete_profile[] = 'false';
$profile_bar = funcs::getText($_SESSION['lang'], '$progress_bar_profile');
if(count($complete_profile) <= 0)
{	
	$progress_score['Complete_Profile']['text'] = $profile_bar;
	$progress_score['Complete_Profile']['score'] = 20;
	$total_score += 20;
}
else
{
	$progress_no_score['Complete_Profile']['text'] = $profile_bar;
	$progress_no_score['Complete_Profile']['score'] = 0;
}

/*
 * Add album score
 */

$fotoalbum = funcs::getAllFotoAlbum($userid);
$fotoalbumTemp = funcs::getAllFotoAlbumFromTemp($userid);
$numData = count($fotoalbum)+count($fotoalbumTemp);

$photo_bar = funcs::getText($_SESSION['lang'], '$progress_bar_photo');
if($numData > 0)
{
	$progress_score['Photo_Album']['text'] = $photo_bar;
	$progress_score['Photo_Album']['score'] = 20;
	$total_score += 20;
}
else
{
	$progress_no_score['Photo_Album']['text'] = $photo_bar;
	$progress_no_score['Photo_Album']['score'] = 0;
}

/*
 * Add lonely heard ads score
 */

$lonely_heart_data = funcs::getNumLonelyHeart($userid);

$ads_bar = funcs::getText($_SESSION['lang'], '$progress_bar_ads');
if($lonely_heart_data > 0)
{
	$progress_score['Lonely_heart_ads']['text'] = $ads_bar;
	$progress_score['Lonely_heart_ads']['score'] = 20;
	$total_score += 20;
}
else
{
	$progress_no_score['Lonely_heart_ads']['text'] = $ads_bar;
	$progress_no_score['Lonely_heart_ads']['score'] = 0;
}

//echo "<pre> W"; print_r($progress_score); echo "</pre>";

//echo "<pre> N"; print_r($progress_no_score); echo "</pre>";

$progress_final = array_merge($progress_no_score, $progress_score);

$smarty->assign('progress_bar_mobile_text', funcs::getText($_SESSION['lang'], '$progress_bar_mobile_text'));

//echo "<pre> F"; print_r($progress_final); echo "</pre>";
/**
 * end profile score : NOI
 **/
?>