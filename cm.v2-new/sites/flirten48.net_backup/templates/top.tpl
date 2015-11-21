<!-- {$smarty.template} --><head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
	<meta name="viewport" content="width=1040">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{#TITLE#}</title>
	
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	{if $deviceType eq 'computer'}
	{else}
		<link href="css/css-theme-mobile.css" rel="stylesheet" type="text/css" />
	{/if}
	
	<!-- experimental style sheets -->
	<link href="css/MetroNotificationStyle.min.css" rel="stylesheet" type="text/css" />
	<link href="css/bubblepopup.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/layerslider.css" type="text/css" media="screen">
    <link rel="stylesheet" type="text/css" href="css/jquery.gritter.css" />
    <link href="css/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css" />
    
       
	<!-- experimental style sheets end -->
	 
	<link href="css/bootstrap.icons.css" rel="stylesheet">
    <link href="css/jquery.toolbars.css" rel="stylesheet" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="js/jquery.toolbar.js"></script>
    <script src="js/script.js"></script>
    <script src="js/prettify.js"></script>    
    <script src="js/MetroNotification.js"></script>
	
	<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="js/jquery.fancybox.js"></script>
    
    <script src="js/jquery.validationEngine-de.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    
    <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" />
    
    <script type="text/javascript" src="configs/{$smarty.session.lang}.js"></script>
    {literal}
    <!--[if lt IE 9]>
<script>
  var e = ("abbr,article,aside,audio,canvas,datalist,details," +
    "figure,footer,header,hgroup,mark,menu,meter,nav,output," +
    "progress,section,time,video").split(',');
  for (var i = 0; i < e.length; i++) {
    document.createElement(e[i]);
  }
</script>
<![endif]-->
    {/literal}
    <script type="text/javascript">
    {literal}
    jQuery(document).ready(function($) {
    
        // Define any icon actions before calling the toolbar
        $('.toolbar-icons a').on('click', function( event ) {
            event.preventDefault();
        });
    });
    
    {/literal}
    </script>
</head>