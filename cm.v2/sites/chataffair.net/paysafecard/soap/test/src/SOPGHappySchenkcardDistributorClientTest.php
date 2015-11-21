<?php

require_once './../../client/src/SOPGHappySchenkcardDistributorClient.php';

$username = 'mvtest';
$password = 'mvtest';
$subId = 'test';
$accountNumber = '0103025000000006';

function checkResponse($response) {
  if ($response->resultCode == 0 && $response->errorCode ==0) {
    return "OK";
  }
  return $response->errorCode;
}

function testOpenLoopGiftcardActivation($client) {
  global $username, $password, $subId, $accountNumber;
  $messageId = rand(1, 100000);
  print "OpenLoopGiftcardActivation: ";
  $openLoopGiftcardActivationResponse = $client->openLoopGiftcardActivation(
                                                       $username, 
                                                       $password, 
                                                       $messageId, 
                                                       $accountNumber, 
                                                       $subId);	

  print checkResponse($openLoopGiftcardActivationResponse);
}

function testOpenLoopGiftcardReversal($client) {
  global $username, $password, $subId, $accountNumber;
  $messageId = rand(1, 100000);
  print "\nOpenLoopGiftcardReversal: ";
  $openLoopGiftcardReversalResponse = $client->openLoopGiftcardReversal(
                                                       $username, 
                                                       $password, 
                                                       $messageId, 
                                                       $accountNumber, 
                                                       $subId);	

  print checkResponse($openLoopGiftcardReversalResponse);
}

try {
  $client = new SOPGHappySchenkcardDistributorClient('https://soatest.paysafecard.com/psc/services/PscService?wsdl');
  
  testOpenLoopGiftcardActivation($client);
  testOpenLoopGiftcardReversal($client);
} catch (Exception $e) {
  print 'Failed!';
  print $e;
}

?>
