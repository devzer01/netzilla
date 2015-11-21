<?
  print_r($_SESSION);  

  if (count($_GET) > 0)
    $params= $_GET;
  elseif (count($_POST) > 0)
    $params= $_POST;
  else
    $params= array();

  if (count($params) > 0){
    $status= "failed";
	$section = 'failed_message';
}
  else{
    $status= "successful";
	$section = 'okay_message';
  }
    
//$smarty->assign("section",$section);
//$smarty->display('index.tpl');	    
    
    
?>


<!--<html>
  <head><title>Result from ipayment system (silent mode)</title></head>
  <body>
    State of the payment:
    <font color="red">
      <? //echo $status ?>
    </font>
    <br>
    <table border="1"><tr><th>Parameter</th><th>Value</th></tr>

    <?/*
      while (list ($key, $val) = each ($params)) {
        $val= urldecode($val);
        echo "<tr><td>$key</td><td>$val</td></tr>";
      }*/
    ?>

    </table>
  </body>
</html> -->
