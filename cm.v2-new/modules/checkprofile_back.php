<?php
/**
 * start profile score : NOI
 **/

/*
 * Add profile score
 */

$rate_group = array('0' => array(
								'score' => '10',
								'subset' => array(
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
														)
												)
								),
					'1' => array(
								'score' => '25',
								'subset' => array(
													array(
														'elm_name' => 'height',
														'elm_operand' => '0'
														),
													array(
														'elm_name' => 'weight',
														'elm_operand' => '0'
														),
													array(
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
														),
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
													array(
														'elm_name' => 'handicapped',
														'elm_operand' => '2'
														),
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
														)
												)
								),
					'2' => array(
								'score' => '10',
								'subset' => array(
													array(
														'elm_name' => 'description',
														'elm_operand' => ''
														)
												)
								),
					'3' => array(
								'score' => '20',
								'subset' => array(			
													array(
														'elm_name' => 'picturepath',
														'elm_operand' => ''
														)
												)
								)
					);

$userid = $_SESSION['sess_id'];
$profile = funcs::getProfile($userid);	//get profile data
$total_score = 0;
$sub_score1 = 0;

foreach($rate_group as $subgroup)
{
	//echo "<pre>";
	$count = count($subgroup['subset']);
	$score = $subgroup['score'] / $count;
	
	foreach($subgroup['subset'] as $elment)
	{
		//echo "     ".$elment['elm_name'];
		//echo " : ".$profile[$elment['elm_name']];
		
		if($profile[$elment['elm_name']] != $elment['elm_operand'])
		{
			//echo " : ".$score;
			$sub_score1 += $score;
		}

		//echo "<br/>";
	}
	//echo "<strong>".$sub_score1."</strong>";
	//echo "<br/>";
	//echo "</pre>";
}

/*
 * Add album score
 */

$fotoalbum = funcs::getAllFotoAlbum($userid);
$fotoalbumTemp = funcs::getAllFotoAlbumFromTemp($userid);
$numData = count($fotoalbum)+count($fotoalbumTemp);

if($numData > 0)
	$sub_score2 = 10;
else
	$sub_score2 = 0;

/*
 * Add lonely heard ads score
 */

$lonely_heart_data = funcs::getAllLonely_Heart($userid, SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
if(count($lonely_heart_data) > 0)
	$sub_score3 = 25;
else
	$sub_score3 = 0;

/**
 * end profile score : NOI
 **/
?>