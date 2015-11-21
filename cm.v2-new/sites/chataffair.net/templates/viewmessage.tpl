<!-- {$smarty.template} -->
<h2>{if ($smarty.get.type=='inbox')}{#View_Inbox_Message#}{else}{#View_Outbox_Message#}{/if}</h2>
<div class="result-box">
    
	<div class="result-box-inside-nobg">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="left" width="85%">
                        <table border="0" cellpadding="4" cellspacing="0" width="100%">
                        <tr>
                            <td valign="top" class="text12grey"><b>{if ($smarty.get.type=='inbox')}{#From#}{else}{#To#}{/if}:</b></td>
                            <td>{$message.username}</td>
                        </tr>
                        <tr>
                            <td valign="top" class="text12grey"><b>{#Datetime#}:</b></td>
                            <td>{$message.datetime}</td>
                        </tr>
                        <tr>
                            <td valign="top" class="text12grey"><b>{#Subject#}:</b></td>
                            <td>{$message.subject|wordwrap:80:"<br />":true}</td>
                        </tr>
                        <tr>
                            <td valign="top" class="text12grey"><b>{#Message#}:</b></td>
                            <td>
				{if strstr($message.message,'action=bonusverify')} 
					{$message.message}
				{else}
					{*$message.message|replace:"\n":"<br>"|wordwrap:80:"<br />":true*}
					{$message.message|nl2br}
				{/if}
			    </td>
                        </tr>
                        </table>
                    </td>
                    {if $smarty.get.type == 'inbox'}
                    <td width="78" align="left">
                        <table width="78" height="98" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#164A63">
                        <tr>
                            <td width="78" height="98" bgcolor="#FFFFFF">
                                {if $path != ''}
                                    <img src="thumbnails.php?file={$path}&w=87&h=100" border="0" width="87" height="100" alt="" />
                                {else}
                                    <img src="thumbs/default.jpg" border="0" width="87" height="100" alt="" />
                                {/if}
                            </td>
                        </tr>
                        </table>
                    </td>
                    {/if}
                </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td height="30px"></td>
        </tr>
        <tr>
            <td>
                <form id="view_message_form" name="view_message_form" method="post" action="">
                <input type="hidden" id="act" name="act" value="" />
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center">
                        <input type="hidden" id="messageid" name="messageid[]" value="{$message.id}" />
                        <input type="button" id="delete_button" name="delete_button" value="{#Delete#}" class="button" onclick="$('act').value='del';document.forms.view_message_form.submit()" />
                        {if $smarty.get.type eq 'inbox'}
                        	<input type="button" name="archive_button" value="{#Archive#}" class="button" onclick="$('act').value='archive';document.forms.view_message_form.submit()" />
                            {if ($smarty.session.sess_permission eq 1) or ($smarty.session.sess_permission eq 2)}
								{if ($smarty.get.username ne 'System Admin')}
									<input type="submit" id="reply_button" name="reply_button" onClick="replyMessage(this.form.id)" value="{#Reply#}" class="button" />
								{/if}
                            {else}
                                {if $smarty.session.sess_permission eq 3}
                                    <input type="submit" id="answer_button" name="answer_button" onClick="$('view_message_form').action = '?action=mymessage&type=writemessage';" value="{#Reply#}" class="button" />
                                {/if}
								{if ($smarty.get.username ne 'System Admin')}
									<input type="button" id="reply_button" name="reply_button" onclick="location = '?action=mymessage&type=writemessage&username={$message.username}';" value="{#Reply#}"  class="button" />
								{/if}
                            {/if}
                        {/if}
						{if ($smarty.get.username ne 'System Admin')}
							<!--<input type="button" id="profil_button" name="profil_button" onclick = "popup('?action=lonely_heart_ads_view&do=view_profile&username={$message.username}', 800,820)" value="{#Profile#}" class="button" />
							<input type="button" id="lonely_button" name="lonely_button" onclick="popup('?action=lonely_heart_ads_view&do=veiw_lonely&username={$message.username}', 450,820)" value="{#Lonely_heart_ads#}"  class="button" />-->
							
							<input type="button" id="profil_button" name="profil_button" onclick="location = '?action=viewprofile&username={$message.username}';" value="{#Profile#}" class="button" />
							<input type="button" id="lonely_button" name="lonely_button" onclick="location = '?action=lonely_heart_ads_view&username={$message.username}&backurl=viewmessage';" value="{#Lonely_heart_ads#}"  class="button" />
                        {/if}
						<input type="button" id="back_button" name="back_button" onclick="location = '?action=mymessage&type={$smarty.get.type}';" value="{#BACK#}" class="button" />
                    </td>
                </tr>
                </table>
                </form>	
            </td>
        </tr>
        {if $message.username}	
        <tr>
            <td>
                {literal}
                <script language="javascript">
                function showMessageHistory(originalRequest){
                    $("history_div").innerHTML = originalRequest.responseText;
                }
                </script>
                {/literal}	
                <table width="100%" align="center">
                <tr>
                    <td colspan="2" height="10px"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <div id="history_div">
                            <input type="button" id="getMsgHistory_button" name="getMsgHistory_button" onclick="ajaxRequest('getMessageHistory','username={$message.username}','','showMessageHistory','')" value="{#Message_History#}"  class="button" />
                            <br /><br />
                            {include file="message_history.tpl" messages=$message_before}
                        </div>
                    </td>
                </tr>
                </table>
            </td>
        </tr>
        {/if}
        </table>
	</div>
</div>