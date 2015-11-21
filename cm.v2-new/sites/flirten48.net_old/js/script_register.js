function checkUsernameSilent(username)
{
	if(checkNull(username))
	{
		if(username==old_username)
		{
			//$("username_indicator").innerHTML="OK";
			$("username_info").innerHTML = "";
			username_ok = true;
		}
		else
		{
			if((username.length>=6) && (username.length<=30))
				var mbox = new Ajax.Request("ajax_handle.php?action=isUsername&username="+username, {method: "post", onComplete: checkUsernameSilentResult});
			else
			{
				//$("username_indicator").innerHTML="X";
				$("username_info").innerHTML = "Nickname must be 6-30 characters.";
				username_ok = false;
			}
		}
	}
	else
	{
		//$("username_indicator").innerHTML="X";
		$("username_info").innerHTML = "Empty nickname.";
		username_ok = false;
	}
}

function checkUsernameSilentResult(originalRequest)
{
	if(originalRequest.responseText==0)
	{
		//$("username_indicator").innerHTML="OK";
		$("username_info").innerHTML = "";
		username_ok = true;
	}
	else
	{
		//$("username_indicator").innerHTML="X";
		$("username_info").innerHTML = "Duplicate nickname.";
		username_ok = false;
	}
	hideWaitingBox();
}