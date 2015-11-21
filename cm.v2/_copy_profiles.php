<?php
require_once('classes/top.class.php');

/*for($i=801; $i<900; $i++)
{
	mysql_select_db("lovely-singles.com");
	$profiles = DBConnect::assoc_query_2D("SELECT * FROM member WHERE gender=2 AND isactive=1 AND picturepath!='' ORDER BY id DESC LIMIT ".$i.",1");

	foreach($profiles as $profile)
	{
		echo $profile['username'];
		$targetDir = UPLOAD_DIR.$profile['id'];
		$srcFile = "../lovely-singles.com/thumbs/".$profile['picturepath'];

		if(file_exists($srcFile))
		{
			if(!file_exists($targetDir))
				mkdir($targetDir);

			copy($srcFile, UPLOAD_DIR.$profile['picturepath']);

			mysql_select_db("flirt48.net");
			if($profile['gender']=='1')
				$profile['lookwomen']='1';
			if($profile['gender']=='2')
				$profile['lookmen']='1';

			$colnames = array_flip(DBconnect::get_col_names('member'));
			$profile = array_intersect_key($profile, $colnames);
			DBconnect::assoc_insert_1D($profile, 'member');
			echo " => ok.";
		}
		else
		{
			echo " => failed.";
		}
		echo "<br/>";
	}
}*/

$profiles = DBConnect::assoc_query_2D("SELECT id, picturepath FROM member2 WHERE fake=1 AND picturepath!='' ORDER BY id DESC");
foreach($profiles as $profile)
{
	$src = "/var/www/lovely-singles.com/thumbs/".$profile['picturepath'];
	if(file_exists($src))
	{
		$thumbs_dir = "/var/www/cm.v2/sites/flirt48.net/thumbs2";
		if(!is_dir($thumbs_dir."/".$profile['id']))
			mkdir($thumbs_dir."/".$profile['id']);
	
		$filename = basename($profile['picturepath']);
		$dest_path = $profile['id']."/".$filename;
		$dest = $thumbs_dir."/".$dest_path;
		/*if(copy($src, $dest))
			echo "<img src='thumbs2/".$dest_path."'/><br/>";
		else
			echo "Cannot copy ".$src."<br/>";*/

		if(!copy($src, $dest))
			echo "Cannot copy ".$src."<br/>";
		else
			echo "Copy finished. => ".$src."<br/>";

		DBConnect::execute_q("UPDATE member2 SET picturepath = '".$dest_path."' WHERE id = ".$profile['id']);
	}
}
?>