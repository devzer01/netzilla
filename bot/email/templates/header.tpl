<!DOCTYPE html>
<html lang="utf8">
	<head>
	<title>Send Message Statistics</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<!-- Bootstrap -->
	<base href='{$smarty.const.BASE_URL}'>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css" rel="stylesheet" media="screen">
	<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript" >
	    if(typeof String.prototype.trim !== 'function') {
  			String.prototype.trim = function() {
    			return this.replace(/^\s+|\s+$/g, ''); 
  			}
	    }
	</script>
	
	<script src="js/js/highcharts.js"></script>
	<script src="js/js/modules/data.js"></script>
	<script src="js/js/modules/exporting.js"></script>
	
	<script src="js/jquery.stickytableheaders.min.js"></script>
	
	<style>
	
	th
	{
    padding: 5px; /* NOTE: th padding must be set explicitly in order to support IE */
    text-align: center;        
    font-weight:bold;
    line-height: 2em;
    color: #FFF;
    background-color: #555;
	}
	
	td
	{
		text-align: center;
	}
	
	table tr:hover
	{
		background-color:#f2e8da;
	}
	
	</style>
	
	</head>
	<body>

	{include file='menu.tpl'}