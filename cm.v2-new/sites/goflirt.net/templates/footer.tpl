{if !$smarty.session.sess_externuser}
<div class="footer">
	<a href="?action=terms">{#AGB#}</a>   |
	<a href="?action=imprint">{#IMPRESSUM#}</a>   |
	<a href="?action=policy">{#WIDERRUFSRECHT#}</a> |
	<a href="?action=faqs">{#FAQS#}</a> |
	<a href="?action=refund">{#REFUND_POLICY#}</a>
	{if $smarty.session.sess_username != "" or $smarty.cookies.sess_username neq ""}| <a href="?action=delete_account">{#delete_account#}</a>{/if}
</div>
<div id="mask"></div>

{literal}
	<!-- mousewheel plugin -->
	<!-- custom scrollbars plugin -->
	<script type="text/javascript">
	
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
		  ga('create', 'UA-30528203-4', 'goflirt.net');
		  ga('send', 'pageview');

	

		jQuery(document).ready(function(){
			jQuery(".fancybox").fancybox();
		});
		{/literal}

		{if $smarty.session.sess_id}
		{literal}
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

													if(currentStatus['2']==0)
													{
														currentMsgCount = 0;
														jQuery("#new_msg").html("");
													}
													else
													{
														jQuery("#new_msg").html("<font class='new-msg-counter'>"+ currentStatus['2'] + "</font>");

														if(currentMsgCount != null)
														{
															if(currentMsgCount < currentStatus['2'])
															{
																currentMsgCount = currentStatus['2'];
																notificationSoundElement.pause();
																notificationSoundElement.play();
															}
														}
														else
															currentMsgCount = currentStatus['2'];
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
		{/literal}
		{/if}
		{literal}
	</script>
    <script>
			
			$(function() {
				// Invoke the plugin
				$('input, textarea').placeholder();
				// That’s it, really.	
			});
		</script>
{/literal}
{/if}