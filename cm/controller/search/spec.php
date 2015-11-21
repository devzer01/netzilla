<?php 

require_once 'lib/memcached.php';
require_once 'lib/dbo/member.php';


$m = nzmemcache::getInstance();
$m->connect('localhost', 11211);

$dbo_member = new dbo_member();

$total = 12;

switch ($spec) {
	case 'w4m':
		$members = $dbo_member->getMembersBySpec(GENDER_FEMALE, 1, 0);
		break;
	case 'w4w':
		$members = $dbo_member->getMembersBySpec(GENDER_FEMALE, 0, 1);
		break;
	case 'm4w':
		$members = $dbo_member->getMembersBySpec(GENDER_MALE, 0, 1);
		break;
	case 'm4m':
		$members = $dbo_member->getMembersBySpec(GENDER_FEMALE, 1, 0);
		break;
}

shuffle($members);
$members = array_slice($members, 0, $total);
$smarty->assign('members', $members);
$smarty->display('private/searchresults.tpl');