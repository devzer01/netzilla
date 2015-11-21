<?php
$style=(isset($_GET['style']) && ($_GET['style']!=""))?$_GET['style']:2;
$smarty->assign('style', $style);
$smarty->display('my_favorite.tpl');
?>