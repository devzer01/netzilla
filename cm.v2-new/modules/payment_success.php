<?php
/*$item=DBConnect::assoc_query_1D("SELECT * from purchases_log WHERE id='".$_GET['transaction_id']."' and reference_id='".$_GET['reference_id']."'");
if($item)
{
	if($_GET['redirect']=='1')
	{
		$_SESSION['payment_success']=1;
		header("location: ?action=payment_success&transaction_id=".$_GET['transaction_id']."&reference_id=".$_GET['reference_id']);
	}
	else
	{
		$smarty->assign("google_analytics",$_SESSION['payment_success']);
		$smarty->assign("item",$item);
		$smarty->assign("content",$content);
		$smarty->display('index.tpl');
		$_SESSION['payment_success']=null;
	}
}
else
{
	header("location: .");
}*/
$smarty->display('index.tpl');
?>