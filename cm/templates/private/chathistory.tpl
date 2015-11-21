<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
        <!--start -->  
        <div class="container-content">

        <h1 class="title-chat-history">
            <a href="{$smarty.const.APP_PATH}/chat"></a><img src="{$smarty.const.APP_PATH}/images/icon-title-chat-history.png" width="12" height="12" style="margin-right:3px;"/>{$rcpt.username|username}
        </h1>

        <!-- start box history -->
        <div id='boxhistory' class="box-history">
        	
            
        </div>
        <!-- end box history -->
        <div class="container-btn-chat">
        <p class="text_remain_detail"><strong><span id='txtleft'>140</span></strong> Zeichen übrig.</p>
            <div class="container-text">
            <textarea id='msg' name="msg" class="formfield_01 chat-input" max='140'></textarea>
            </div>
            <div class="container-btn-chat-50">				
            <table width="100%" border="1" cellspacing="5" cellpadding="0">
                <tr>
                    <td style="width:49%;">                 
                       <a href="{$smarty.const.APP_PATH}/profile/mobile" class="btn-sms-chat blank"><span>SMS <font>Versenden</font></span></a>
                    </td>
                    <td style="width:1px; background:#333;"></td>
                    <td>
                    <div style="width:100%; height:49px; float:left;">
                    <a href="#" class="btn-email-chat"><span>Email <font>Versenden</font></span></a>
                    </div>
                    </td>
                </tr>
            </table>
                
            </div>
            
        </div>
        </div>
        <!--end -->

        </div>
                
        
    </div>
    
    <script type='text/javascript'>
    
    	//var show = true;
    
    	function loadChatHistory() {
    		var scrollTop = window.pageYOffset;
			$("#boxhistory").load('{$smarty.const.APP_PATH}/chat/log/{$rcpt.username|username}', function () {
					window.scrollTo(0, $("#boxhistory")[0].scrollHeight);	
			});
    	}
    	
    	function markRead()
    	{
    		$.get("{$smarty.const.APP_PATH}/ajax/markreadall/{$rcpt.username|username}", function () {
    			//console.log('test');
    		});
    	}
    
    	$(function () {
    		loadChatHistory();
    		markRead();
    		$(".btn-email-chat").click(function (e) {
    			e.preventDefault();
    			var msg = $('#msg').val();
    			var data = { to: '{$rcpt.username|username}', msg: msg };
    			
    			$.ajax({
    				
    				url: '{$smarty.const.APP_PATH}/chat/write',
    				data: data,
    				type: 'post',
    				dataType: 'json',
    				success: function (json) {
    					if (json.status == 1) {
    						window.location.href = '{$smarty.const.APP_PATH}/coins';
    					} else if (json.status == 0) {
    						loadChatHistory();
    						$("#msg").val('');
    					}
    				}
    			});
    		});
    		
    		var checklimit = function(){
    	    	
    	    	var text      = $("#msg").val();
    	    	var maxlength = parseInt($("#msg").attr("max"));
    	    	
    	    	if(text.length > maxlength) {
    	    		return false;	
    	    	}
    	    	$("#txtleft").html(maxlength - text.length);
    	    	return true;
    	    };
    		
    		//$("#msg").keypress(checklimit);
    		$("#msg").keydown(checklimit);
    		$("#msg").keyup(function (e) {
    			var text      = $("#msg").val();
    	    	var maxlength = parseInt($("#msg").attr("max"));
    	    	$("#txtleft").html(maxlength - text.length);
    	    	return true;
    		});
    		$("#msg").on("paste", checklimit);
    		
    		window.setInterval(function () {
    			loadChatHistory();
    			markRead();
    		}, 5000);
    		
    	});
    </script>
    
</body>
</html>