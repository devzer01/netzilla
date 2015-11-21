<?php 

require_once 'lib/memcached.php';
require_once 'lib/dbo/member.php';


$m = nzmemcache::getInstance();
$m->connect('localhost', 11211);

$dbo_member = new dbo_member();

$total = 12;

$members = array();
$members = $dbo_member->getMembersByUsername($username);

$smarty->assign('members', $members);
$smarty->display('private/searchresults.tpl');