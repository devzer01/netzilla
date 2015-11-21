<?php
//connect to mysql
mysql_connect("212.227.100.222","haust","dungeon33"); 

//select which database you want to edit
mysql_select_db("haust_test"); 

//If cmd has not been initialized
if(!isset($cmd)) 
{
   //display all the news
   $result = mysql_query("SELECT * FROM jokes WHERE flag = 0 LIMIT 10"); 

   echo "<h1>Witze Kategorisieren</h1>";     
   
   //run the while loop that grabs all the news scripts
   while($r=mysql_fetch_array($result)) 
   { 
      //grab the title and the ID of the news
      $header=$r["joke_header"];//take out the title
      $text=$r["joke_text"];//take out the title
      $id=$r["ID"];//take out the id
     
	 //make the title a link
      echo "<a href='jokes.php?cmd=edit&id=$id'>$header - Editieren</a>";
      echo "<br>";
    }
}

if($_GET["cmd"]=="edit" || $_POST["cmd"]=="edit")
{
	if (!isset($_POST["submit"]))
	{
		$id = $_GET["id"];
		
		$sql = "SELECT * FROM jokes WHERE id=$id";
		$result = mysql_query($sql);        
		$myrow = mysql_fetch_array($result);
		?>
		<form action="jokes.php" method="post">
		<input type=hidden name="id" value="<?php echo $myrow["id"] ?>">
		
		Name:<INPUT TYPE="TEXT" NAME="header" VALUE="<?php echo $myrow["joke_header"] ?>" SIZE=30><br>
		Witz:<TEXTAREA NAME="text" ROWS=10 COLS=30><? echo $myrow["joke_text"] ?></TEXTAREA><br>
		Kategorie 1:<INPUT TYPE="checkbox" NAME="category1" VALUE="<?php echo (($myrow["category_1"]== 1) ? ' checked="checked"' : '') ?>"><br>
		Kategorie 2:<INPUT TYPE="checkbox" NAME="category2" VALUE="<?php echo (($myrow["category_2"]== 1) ? ' checked="checked"' : '') ?>"><br>
		Kategorie 3:<INPUT TYPE="checkbox" NAME="category3" VALUE="<?php echo (($myrow["category_3"]== 1) ? ' checked="checked"' : '') ?>"><br>            
		
		<input type="hidden" name="cmd" value="edit"> 
		<input type="submit" name="submit" value="submit">
		
		</form>
	<? 
	}
	if ($_POST["submit"])
	{
		$header = $_POST["header"];
		$text = $_POST["text"];
		$category1 = (isset($_POST["category1"]) ?  1 : 0);
		$category2 = (isset($_POST["category2"]) ?  1 : 0);
		$category3 = (isset($_POST["category3"]) ?  1 : 0); 
		
		$sql = "UPDATE jokes SET joke_header ='$header', joke_text ='$text', category_1=$category1, category_2=$category2, category_3=$category3, flag=1 WHERE id=$id";
		$result = mysql_query($sql);

		if($result) header("location: jokes.php");
		else echo "Datenbank Fehler!";
	}   
}

?>