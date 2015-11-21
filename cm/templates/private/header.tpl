<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<title>Mobile-flirt48</title>
<link href="{$smarty.const.APP_PATH}/css/style.css" rel="stylesheet" type="text/css" />
<!--JQUERY CODES GO HERE -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<!--[if lt IE 9]>
<script src="{$smarty.const.APP_PATH}/assets/js/modernizr.js"></script>
<![endif]-->
<script src="{$smarty.const.APP_PATH}/assets/js/superfish.js"></script>
<script src="{$smarty.const.APP_PATH}/assets/js/easyaspie.js"></script>
<script type="text/javascript">
	var app_path = '{$smarty.const.APP_PATH}';
    $(document).ready(function() {
        $('nav').easyPie();
    });    
    </script>

<link rel="stylesheet" href="{$smarty.const.APP_PATH}/assets/css/main.css"/>
<script src="{$smarty.const.APP_PATH}/js/mobile.js"></script>
</head>

<body>
    <div class="container-wrapper">
        <div class="wrapper">
        
        <!--start header -->
        	<header>
            	<div class="container-logo"><a href="{$smarty.const.APP_PATH}/" class="link-logo"></a></div>
                 
                <nav class="applePie">
                	<div class="menubtn"><span>Menu</span></div>
                    <ul id="nav">
                        <li><a href="{$smarty.const.APP_PATH}/" class="icon-home"><span>Startseite</span></a></li>
                        <li><a href="{$smarty.const.APP_PATH}/chat" class="icon-massage"><span>Nachrichten</span></a></li>
                        <li><a href="{$smarty.const.APP_PATH}/search" class="icon-search"><span>Suchen</span></a></li>
                        <li><a href="{$smarty.const.APP_PATH}/coins" class="icon-coins"><span>Coins</span></a></li>
                        <li><a href="{$smarty.const.APP_PATH}/profile" class="icon-profile"><span>Profil</span></a></li>
                        <li><a href="{$smarty.const.APP_PATH}/help" class="icon-help"><span>Help</span></a></li>
                        <li><a href="{$smarty.const.APP_PATH}/logout" class="icon-logout"><span>Logout</span></a></li>
                    </ul>
                </nav>
                
                <div id='msgcount' class="new-message">..</div>
                
            </header>
        <!--end header --> 