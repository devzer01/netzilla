<!-- {$smarty.template} --><head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{#TITLE#}</title>


	<link href="css/css-theme.css" rel="stylesheet" type="text/css" />
	{if $deviceType eq 'computer'}
	{else}
	<link href="css/css-theme-mobile.css" rel="stylesheet" type="text/css" />
	{/if}
	<link href="css/bubblepopup.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/page.css" type="text/css" media="screen">
	<link rel="stylesheet" href="css/layerslider.css" type="text/css" media="screen">
    <link rel="stylesheet" type="text/css" href="css/jquery.gritter.css" />
    <link href="css/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css" />   
    <link href="css/MetroNotificationStyle.min.css" rel="stylesheet" type="text/css" />   
	<!--<link href="css/style.css" rel="stylesheet" type="text/css" /> -->
    
	<script language="javascript" type="text/javascript" src="configs/{$smarty.session.lang}.js"></script>
	<script language="javascript" type="text/javascript" src="js/prototype.js"></script>
	<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
	<script type="text/javascript" src="js/lightview.js"></script>
	<link rel="stylesheet" type="text/css" href="css/lightview.css" />
	<script language="javascript" type="text/javascript" src="js/lang.js"></script>
	<script type="text/javascript" src="js/jquery-1.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.js"></script>
	<script type="text/javascript" src="js/MetroNotification.js"></script>
	<script language="javascript" type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript">
		jQuery.noConflict();
		//var GB_ROOT_DIR = "js/greybox/";
	</script>
    
<!--dating48 -->
<script type="text/javascript" src="js/cm-theme/sliding.form.js"></script>


{literal}

<script type="text/javascript">
jQuery(document).ready(function () {
	// if user clicked on button, the overlay layer or the dialogbox, close the dialog	
	jQuery('a.btn-ok, #dialog-overlay, #dialog-box, #dialog-box-confirm').click(function () {		
		jQuery('#dialog-overlay, #dialog-box, #dialog-box-confirm').hide();		
		return false;
	});
	
	// if user resize the window, call the same function again
	// to make sure the overlay fills the screen and dialogbox aligned to center	
	jQuery(window).resize(function () {
		//only do it if the dialog box is not hidden
		if (!jQuery('#dialog-box, #dialog-box-confirm').is(':hidden')) popup();		
	});	
});

//Popup dialog
function popup(message) {
	// get the screen height and width  
	var maskHeight = jQuery(document).height();  
	var maskWidth = jQuery(window).width();
	
	// calculate the values for center alignment
	var dialogTop =  jQuery(window).scrollTop() + ((jQuery(window).height() - jQuery('#dialog-box').height())/2);
	var dialogLeft = (maskWidth/2) - (jQuery('#dialog-box').width()/2); 
	
	// assign values to the overlay and dialog box
	jQuery('#dialog-overlay').css({height:maskHeight, width:maskWidth}).show();
	jQuery('#dialog-box').css({top:dialogTop, left:dialogLeft}).show();
	
	// display the message
	jQuery('#dialog-message').html(message);
}

var root_path = '{/literal}{$smarty.const.URL_WEB}{literal}';
</script>
{/literal}
</head>