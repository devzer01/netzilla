<?php
$strFileName = "textfiles/test.txt";
$objFopen = fopen($strFileName, 'w');
fwrite($objFopen, 'this is test write permission.');
fclose($objFopen);