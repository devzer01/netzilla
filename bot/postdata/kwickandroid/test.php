<?php

require_once "config.php";
require_once "bot.php";
require_once "kwickandroid.php";


$worker = new kwickandroid();

$worker->google();
