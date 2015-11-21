<?php
require_once 'funcs.php';

$num = funcs::db_count_profile();

echo "num::".$num;