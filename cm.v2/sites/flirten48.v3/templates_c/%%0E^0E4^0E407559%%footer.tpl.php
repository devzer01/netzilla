<?php /* Smarty version 2.6.14, created on 2013-11-29 14:45:23
         compiled from footer.tpl */ ?>
<?php if (! $_SESSION['sess_externuser']): ?>
<div class="container-footer">
<div id="container-footer">
<a href="?action=terms"><?php echo $this->_config[0]['vars']['AGB']; ?>
</a>   |  
<a href="?action=imprint"><?php echo $this->_config[0]['vars']['IMPRESSUM']; ?>
</a>   |  
<a href="?action=policy"><?php echo $this->_config[0]['vars']['WIDERRUFSRECHT']; ?>
</a> | 
<a href="?action=faqs"><?php echo $this->_config[0]['vars']['FAQS']; ?>
</a> | 
<a href="?action=refund"><?php echo $this->_config[0]['vars']['REFUND_POLICY']; ?>
</a>
<?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>| <a href="?action=delete_account"><?php echo $this->_config[0]['vars']['delete_account']; ?>
</a><?php endif; ?>
</div>
</div>
<?php echo '
	<script type="text/javascript">
		(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');

		ga(\'create\', \'UA-45948954-1\', \'flirten48.net\');
		ga(\'send\', \'pageview\');
		'; ?>

		<?php if ($_SESSION['sess_id']): ?>
		<?php echo '
		var currentMsgCount = null;
		function showCurrentStatus()
		{
			jQuery.ajax({ url: "ajaxRequest.php", 
					type: "post", 
					data: "action=fetchAllStatus", 
					success: function(data, status, originalRequest) {
												if(originalRequest.responseText!="")
												{
													var currentStatus = new Array();
													currentStatus = eval(originalRequest.responseText);

													if(currentStatus[\'2\']==0)
													{
														currentMsgCount = 0;
														jQuery("#new_msg").html("");
													}
													else
													{
														jQuery("#new_msg").html("<font style=\'display:block; position:relative; top:-88px; left:35px; margin-left:1px; text-align:center; height:26px; font-size:10px; font-weight:bold; font-style:italic; line-height:24px;  width:27px; background: url(images/cm-theme/alert.png) no-repeat 2px 0; float:left; color:#FFFFFF;\'>"+ currentStatus[\'2\'] + "</font>");

														if(currentMsgCount != null)
														{
															if(currentMsgCount < currentStatus[\'2\'])
															{
																currentMsgCount = currentStatus[\'2\'];
																notificationSoundElement.pause();
																notificationSoundElement.play();
															}
														}
														else
															currentMsgCount = currentStatus[\'2\'];
													}
												}
											}
										});
		}

		(function(jQuery){
			showCurrentStatus();
			var refreshStatus = setInterval(function() {
				showCurrentStatus();
			}, 5000);
		})(jQuery);
		'; ?>

		<?php endif; ?>
		<?php echo '
	</script>
'; ?>

<?php endif; ?>