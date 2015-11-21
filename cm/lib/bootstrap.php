<?php

require_once 'basesmarty.php';
require_once 'modifier.username.php';

if(!isset($_SESSION['state']) || !$_SESSION['state']) {
	$_SESSION['state'] = md5(uniqid(rand(), TRUE));
}

$locale = 'de_DE.utf8';

putenv("LANG=" . $locale);
setlocale(LC_ALL, $locale);

$domain = "cm";
bindtextdomain($domain, $_SERVER['DOCUMENT_ROOT'] . APP_PATH . "/locale");
bind_textdomain_codeset($domain, "UTF-8");
textdomain($domain);

