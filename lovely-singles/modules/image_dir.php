<?php
$permission_lv = array(1, 8);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

$dir = PROFILE_PICS_PATH;

$root = $dir;

if($_GET['start'] != "")
{
	if((strripos($_GET['start'],"..")) === false)
		$dir .= $_GET['start'];
	else
	{
		echo funcs::getText($_SESSION['lang'], '$img_dir1');
		exit();
	}
}

$arr = explode("/",$_GET['start']);
$upper_level = "";
for($i = 0; $i < count($arr)-2; $i++)
{
	$upper_level .= $arr[$i]."/";
}
$list = array();
if(is_dir($dir))
{
	if($dh = opendir($dir))
	{
		$num = 0;
		while((($file = readdir($dh)) !== false) && ($num < 50))
		{
			$file = trim($file);
			$type = filetype($dir.$file);
			$len = strlen($file);
			$ext = strrpos($file, ".");
			$size = filesize($dir.$file);
			if($size > (1024*1024))
				$size = number_format($size/(1024*1024), 2,".",",")." MB";
			elseif($size > 1024)
				$size = number_format($size/1024, 2,".",",")." kB";
			else
				$size = number_format($size, 2,".",",")." B";
			
			if($type == "dir")
			{
				$extension = "[dir]";
			}
			else
			{
				$extension = substr($file,$ext+1,$len-($ext+1));
				$num++;
			}
			
			if(in_array(strtolower($extension), array("jpg","jpeg","gif","png","[dir]")))
			{
				array_push($list, array('filename'	=> $file,
											'extension' => $extension,
											'type'		=> $type,
											'size'		=> $size
										));
			}
		}
	}
}
$smarty->assign('upper_level',$upper_level);
$smarty->assign('root',$root);
$smarty->assign('dir',$dir);
$smarty->assign('list',$list);
$smarty->display('image_dir.tpl');
?>