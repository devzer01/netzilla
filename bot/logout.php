<?php
session_start();
$_SESSION['password'] = null;
unset($_SESSION['password']);
unset($_SESSION['logged']);
header("location: .");//
exit;//
?>