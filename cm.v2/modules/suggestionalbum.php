<?php
//select template file//
$permission_lv = array(1, 4, 8, 9);
funcs::checkPermission($smarty, $permission_lv);
$picture_list = array();
array_push($picture_list, array("big" => "kummerkasten/schaukel.jpg", "small" => "kummerkasten/schaukel_klein.jpg"));
array_push($picture_list, array("big" => "kummerkasten/mickey.jpg", "small" => "kummerkasten/mickey_klein.jpg"));
array_push($picture_list, array("big" => "kummerkasten/schulter.jpg", "small" => "kummerkasten/schulter_klein.jpg"));
array_push($picture_list, array("big" => "kummerkasten/sofa.jpg", "small" => "kummerkasten/sofa_klein.jpg"));
array_push($picture_list, array("big" => "kummerkasten/streng.jpg", "small" => "kummerkasten/streng_klein.jpg"));
array_push($picture_list, array("big" => "kummerkasten/tools.jpg", "small" => "kummerkasten/tools_klein.jpg"));

array_push($picture_list, array("big" => "kummerkasten/n01.jpg", "small" => "kummerkasten/n01-s.jpg"));
array_push($picture_list, array("big" => "kummerkasten/n02.jpg", "small" => "kummerkasten/n02-s.jpg"));
array_push($picture_list, array("big" => "kummerkasten/n03.jpg", "small" => "kummerkasten/n03-s.jpg"));
array_push($picture_list, array("big" => "kummerkasten/n04.jpg", "small" => "kummerkasten/n04-s.jpg"));
array_push($picture_list, array("big" => "kummerkasten/n05.jpg", "small" => "kummerkasten/n05-s.jpg"));

array_push($picture_list, array("big" => "kummerkasten/n07.jpg", "small" => "kummerkasten/n07-s.jpg"));
array_push($picture_list, array("big" => "kummerkasten/n08.jpg", "small" => "kummerkasten/n08-s.jpg"));
array_push($picture_list, array("big" => "kummerkasten/n09.jpg", "small" => "kummerkasten/n09-s.jpg"));

foreach($picture_list as $key => &$val)
{
	if($val['big'] != "")
	{
		list($val['width'], $val['height'], $type, $attr) = getimagesize($val['big']);
	}
}
$smarty->assign("picture_list", $picture_list);
$smarty->display('index.tpl');