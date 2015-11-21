<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     file_exists
 * Purpose:  checks to see if file exists...duh
 * Author:   M. Faber
 * Date:     2003-11-20
 * -------------------------------------------------------------
 */
function smarty_function_file_exists($params, &$smarty) {

  extract($params);
  if (file_exists($file)) return true;
  else return false;
}

?>