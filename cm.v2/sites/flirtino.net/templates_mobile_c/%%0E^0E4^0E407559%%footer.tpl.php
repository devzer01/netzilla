<?php /* Smarty version 2.6.14, created on 2014-01-06 12:48:52
         compiled from footer.tpl */ ?>
<?php if (! $_SESSION['sess_externuser']): ?>
<footer>
    <div class="container-footer">
	<a href="?action=terms"><?php echo $this->_config[0]['vars']['AGB']; ?>
</a>   |
	<a href="?action=imprint"><?php echo $this->_config[0]['vars']['IMPRESSUM']; ?>
</a>   |
	<a href="?action=policy"><?php echo $this->_config[0]['vars']['WIDERRUFSRECHT']; ?>
</a> |
	<a href="?action=faqs"><?php echo $this->_config[0]['vars']['FAQS']; ?>
</a> |
	<a href="?action=refund"><?php echo $this->_config[0]['vars']['REFUND_POLICY']; ?>
</a> |
	<a href="?action=mobile&active=0">Desktop version</a>
	<?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>| <a href="?action=delete_account"><?php echo $this->_config[0]['vars']['delete_account']; ?>
</a><?php endif; ?>
    </div>
</footer>
<div id="mask"></div>

<?php echo '
	<!-- mousewheel plugin -->
	<!-- custom scrollbars plugin -->
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push([\'_setAccount\', \'UA-30528203-2\']);
		_gaq.push([\'_trackPageview\']);

		(function() {
		var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
		ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
		var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
		})();

		jQuery(document).ready(function(){
			jQuery(".fancybox").fancybox();
		});
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
														jQuery("#new_msg").html("<div class=\'message-alert\'>"+ currentStatus[\'2\'] + "</div>");

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