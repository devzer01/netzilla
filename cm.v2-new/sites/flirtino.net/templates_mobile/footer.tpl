{if !$smarty.session.sess_externuser}
<footer>
    <div class="container-footer">
	<a href="?action=terms">{#AGB#}</a>   |
	<a href="?action=imprint">{#IMPRESSUM#}</a>   |
	<a href="?action=policy">{#WIDERRUFSRECHT#}</a> |
	<a href="?action=faqs">{#FAQS#}</a> |
	<a href="?action=refund">{#REFUND_POLICY#}</a> |
	<a href="?action=mobile&active=0">Desktop version</a>
	{if $smarty.session.sess_username != "" or $smarty.cookies.sess_username neq ""}| <a href="?action=delete_account">{#delete_account#}</a>{/if}
    </div>
</footer>
<div id="mask"></div>

{literal}
	<!-- mousewheel plugin -->
	<!-- custom scrollbars plugin -->
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-30528203-2']);
		_gaq.push(['_trackPageview']);

		(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();

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
														jQuery("#new_msg").html("<div class='message-alert'>"+ currentStatus['2'] + "</div>");

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
{/literal}
{/if}