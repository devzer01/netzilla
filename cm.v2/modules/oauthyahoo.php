<?php
/*
 * login_with_microsoft.php
 *
 * @(#) $Id: login_with_microsoft.php,v 1.2 2013/07/31 11:48:04 mlemos Exp $
 *
 */

    require('libs/http.php');
    require('libs/oauth_client.php');

    $client = new oauth_client_class;
    $client->debug = false;
    $client->debug_http = true;
    $client->server = 'Yahoo';
    $client->redirect_uri = OAUTH_YAHOO_REDIRECT;

    $client->client_id = YAHOO_CLIENT_ID; 
    $client->client_secret = YAHOO_SECRET;
    
    if(strlen($client->client_id) == 0
    || strlen($client->client_secret) == 0)
        die('Please go to Yahoo Apps page https://developer.apps.yahoo.com/projects/ , '.
            'create a project, and in the line '.$application_line.
            ' set the client_id to Consumer key and client_secret with Consumer secret. '.
            'The Callback URL must be '.$client->redirect_uri).' Make sure you enable the '.
            'necessary permissions to execute the API calls your application needs.';

    if(($success = $client->Initialize()))
    {
        if(($success = $client->Process()))
        {
            if(strlen($client->access_token))
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
                    'http://query.yahooapis.com/v1/yql', 
                    'GET', array(
                        'q'=>'select * from social.contacts(40) where guid=me AND fields.type IN ("email","name") ',
                        'format'=>'json'
                    ), array('FailOnAccessError'=>true), $user);
                
                
                $emails = array();
                foreach ($user->query->results->contact as $entry) {
                	$email = getContactEmail($entry);
                	$title = getContactTitle($entry);
                	
                	if ($email !== false) {
                		$emails[] = array('email' => $email, 'title' => $title);
                	}
                	
                }

                //print_r($user);
                //$emails = array('nayana@corp-gems.com'); //TODO: REMOVE AFTER TEST
                
                $smarty->assign('emails', $emails);
                $smarty->display('social_email.tpl');
            }
        }
        $success = $client->Finalize($success);
    }
    if($client->exit)
        exit;
    if(strlen($client->authorization_error))
    {
        $client->error = $client->authorization_error;
        $success = false;
    }
    
function getContactEmail($entry) {
	return getField($entry, 'email');
}

function getContactTitle($entry) {
	return getField($entry, 'name');
}

function getField($entry, $fld) {
	foreach ($entry->fields as $field) {
		if ($field->type == $fld) {
			if ($fld == 'name') {
				return $field->value->givenName . " " . $field->value->familyName; 	
			}
			
			return $field->value;
		}
	}
	return false;
}