{if !$mobile_verified}
<a href="#" onclick="showVerifyMobileDialog(); return false;"><img src="images/bannere-mobile.png"/></a>

<script>
{literal}
function showVerifyMobileDialog()
{
	var mbox = new Ajax.Request("ajaxRequest.php",
	{
		method: "post",
		parameters: "action=getCurrentUserMobileNo",
		onComplete: function(originalRequest) {
			if((originalRequest.responseText==="Step2") || (originalRequest.responseText==="Step3"))
			{
				switch (originalRequest.responseText)
				{
					case 'Step2':
						var popup_url = '?action=incompleteinfo';
						break;
					case 'Step3':
						var popup_url = '?action=mobileverify';

				}
				Lightview.show({
								href: popup_url,
								rel: 'ajax',
								options: {
									autosize: true,
									topclose: true
								}
							});
				return false;
			}
		}
	});
}
{/literal}
</script>
{/if}