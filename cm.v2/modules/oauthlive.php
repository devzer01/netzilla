<?php
/*
 * login_with_microsoft.php
 *
 * @(#) $Id: login_with_microsoft.php,v 1.2 2013/07/31 11:48:04 mlemos Exp $
 *
 */

ini_set('error_log', '/var/log/apache2/php.log');

    require('libs/http.php');
    require('libs/oauth_client.php');

    $client = new oauth_client_class;
    $client->server = 'Microsoft';
    $client->debug = true;
    $client->debug_http = true;
    $client->redirect_uri = OAUTH_LIVE_REDIRECT;

    $client->client_id = LIVE_CLIENT_ID; 
    $client->client_secret = LIVE_CLIENT_SECRET;
    
    /* API permissions
     */
    $client->scope = 'wl.basic wl.emails wl.contacts_emails';
    if(($success = $client->Initialize()))
    {
        if(($success = $client->Process()))
        {
            if(strlen($client->authorization_error))
            {
                $client->error = $client->authorization_error;
                $success = false;
            }
            elseif(strlen($client->access_token))
            {
            	
            	if (!isset($_GET['subaction'])) {
            		$html="
            		<HTML>
            		<BODY>
            		<SCRIPT type='text/javascript'>
            		window.close()
            		</SCRIPT>
            		</BODY>
            		</HTML>";
            		echo $html;
            		exit;
            	}
            	
                $success = $client->CallAPI(
                    'https://apis.live.net/v5.0/me/contacts',
                    'GET', array(), array('FailOnAccessError'=>true), $user);
                
                $emails = array();
                foreach ($user->data as $entry) {
                	if (trim($entry->emails->preferred) != '') {
                		$emails[] = array('email' => $entry->emails->preferred, 'title' => $entry->name);
                	}
                }
                
                //$emails = array('nayana@corp-gems.com'); //TODO: REMOVE AFTER TEST
                
                $smarty->assign('emails', $emails);
                $smarty->display('social_email.tpl');
                
                
                $client->ResetAccessToken();
            }
        }
        $success = $client->Finalize($success);
    }
    
exit;