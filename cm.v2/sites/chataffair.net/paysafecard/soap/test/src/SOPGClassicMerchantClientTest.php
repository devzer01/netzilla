<?php

require_once './../../client/src/SOPGClassicMerchantClient.php';

$username = "USER_CLASSIC";
$password = "PASS";
$currency = "EUR";
$amount = 0.01;
$okUrl = "http://okurl";
$nokUrl = "http://nokurl";
$pin = "5000000000002517";

$mid = "1000001243";
$serialNumber = "5000000000002517;0.01";
$dispositionState = "S";

$card1 = array('pin' => '5000000000002517');
$card2 = array('pin'=> '5000000000002511');
$cards = array($card1, $card2);

function generateMtid() {
  $time = gettimeofday();
  $mtid = $time['sec'];
  $mtid .= $time['usec'];
  return $mtid;
}

function checkResponse($response) {
  if ($response->resultCode == 0 && $response->errorCode ==0) {
    return "OK";
  }
  return $response->errorCode;
}

function testAssignCardToDisposition($client) {
  global $username, $password, $amount, $currency, $okUrl, $nokUrl, $pin;
  $dispoMtid = generateMtid();
  print "Create Disposition: ";
  $createDispoResponse = $client->createDisposition(
                                      $username, 
                                      $password, 
                                      $dispoMtid,
                                      null, 
                                      $amount, 
                                      $currency, 
                                      $okUrl, 
                                      $nokUrl,
                                      null,
                                      null,
                                      null);	

  print checkResponse($createDispoResponse);
  
  print "\nAssignCardToDisposition: ";
  $assignCardResponse = $client->assignCardToDisposition(
                                            $username, 
                                            $password, 
                                            $dispoMtid, 
                                            null, 
                                            $amount, 
                                            $currency, 
                                            $pin);
  print checkResponse($assignCardResponse);
}

function testAssignCardsToDisposition($client) {
  global $username, $password, $amount, $currency, $okUrl, $nokUrl, $cards;
  $dispoMtid = generateMtid();
  print "\nCreate Disposition: ";
  $createDispoResponse = $client->createDisposition(
                                        $username, 
                                        $password, 
                                        $dispoMtid,
                                        null, 
                                        $amount, 
                                        $currency, 
                                        $okUrl, 
                                        $nokUrl,
                                        null,
                                        null,
                                        null);	
  
  print checkResponse($createDispoResponse);
  
  print "\nAssignCardsToDisposition: ";
  $assignCardsResponse = $client->assignCardsToDisposition(
                                              $username, 
                                              $password, 
                                              $dispoMtid, 
                                              null, 
                                              $amount, 
                                              $currency, 
                                              null,
                                              1,
                                              $cards);
  print checkResponse($assignCardsResponse);
}

function testModifyDispositionValue($client) {
  global $username, $password, $amount, $currency, $okUrl, $nokUrl, $pin;
  $dispoMtid = generateMtid();
  print "\nCreate Disposition: ";
  $createDispoResponse = $client->createDisposition(
                                      $username, 
                                      $password, 
                                      $dispoMtid,
                                      null, 
                                      0.02, 
                                      $currency, 
                                      $okUrl, 
                                      $nokUrl,
                                      null,
                                      null,
                                      null);	

  print checkResponse($createDispoResponse);
  
  print "\nAssignCardToDisposition: ";
  $assignCardResponse = $client->assignCardToDisposition(
                                            $username, 
                                            $password, 
                                            $dispoMtid, 
                                            null, 
                                            0.02, 
                                            $currency, 
                                            $pin);
  print checkResponse($assignCardResponse);

  print "\nModifyDispositionValue: ";
  $modifyDispoResponse = $client->modifyDispositionValue(
                                         $username, 
                                         $password, 
                                         $dispoMtid,
                                         null, 
                                         $amount, 
                                         $currency);
  print checkResponse($modifyDispoResponse);
}

function testGetSerialNumbers($client) {
  global $username, $password, $amount, $currency, $okUrl, $nokUrl, $pin;
  $dispoMtid = generateMtid();
  print "\nCreate Disposition: ";
  $createDispoResponse = $client->createDisposition(
                                      $username, 
                                      $password, 
                                      $dispoMtid,
                                      null, 
                                      $amount, 
                                      $currency, 
                                      $okUrl, 
                                      $nokUrl,
                                      null,
                                      null,
                                      null);	

  print checkResponse($createDispoResponse);
  
  print "\nAssignCardToDisposition: ";
  $assignCardResponse = $client->assignCardToDisposition(
                                            $username, 
                                            $password, 
                                            $dispoMtid, 
                                            null, 
                                            $amount, 
                                            $currency, 
                                            $pin);
  print checkResponse($assignCardResponse);

  print "\nGetSerialNumbers: ";
  $getSerialsResponse = $client->getSerialNumbers(
                                     $username, 
                                     $password, 
                                     $dispoMtid, 
                                     null, 
                                     $currency);
  print checkResponse($getSerialsResponse);
}

function testGetDispositionRawState($client) {
  global $username, $password, $amount, $currency, $okUrl, $nokUrl, $pin;
  $dispoMtid = generateMtid();
  print "\nCreate Disposition: ";
  $createDispoResponse = $client->createDisposition(
                                      $username, 
                                      $password, 
                                      $dispoMtid,
                                      null, 
                                      $amount, 
                                      $currency, 
                                      $okUrl, 
                                      $nokUrl,
                                      null,
                                      null,
                                      null);	

  print checkResponse($createDispoResponse);
  
  print "\nAssignCardToDisposition: ";
  $assignCardResponse = $client->assignCardToDisposition(
                                            $username, 
                                            $password, 
                                            $dispoMtid, 
                                            null, 
                                            $amount, 
                                            $currency, 
                                            $pin);
  print checkResponse($assignCardResponse);

  print "\nGetDispositionRawState: ";
  $getDispoResponse = $client->getDispositionRawState(
                                     $username, 
                                     $password, 
                                     $dispoMtid, 
                                     null, 
                                     $currency);
  print checkResponse($getDispoResponse);
}

function testExecuteDebit($client) {
  global $username, $password, $amount, $currency, $okUrl, $nokUrl, $pin;
  $dispoMtid = generateMtid();
  print "\nCreate Disposition: ";
  $createDispoResponse = $client->createDisposition(
                                      $username, 
                                      $password, 
                                      $dispoMtid,
                                      null, 
                                      $amount, 
                                      $currency, 
                                      $okUrl, 
                                      $nokUrl,
                                      null,
                                      null,
                                      null);	

  print checkResponse($createDispoResponse);
  
  print "\nAssignCardToDisposition: ";
  $assignCardResponse = $client->assignCardToDisposition(
                                            $username, 
                                            $password, 
                                            $dispoMtid, 
                                            null, 
                                            $amount, 
                                            $currency, 
                                            $pin);
  print checkResponse($assignCardResponse);

  print "\nExecuteDebit: ";
  $executeDebitResponse = $client->executeDebit(
                                       $username, 
                                       $password, 
                                       $dispoMtid,
                                       null, 
                                       $amount, 
                                       $currency,
                                       1, 
                                       null);
  print checkResponse($executeDebitResponse);
}

function testGetMid($client) {
  global $username, $password, $currency;
  print "\nGetMid: ";
  $getMidResponse = $client->getMid(
                                $username, 
                                $password, 
                                $currency);
  print checkResponse($getMidResponse);
}


try {
  $client = new SOPGClassicMerchantClient('https://soatest.paysafecard.com/psc/services/PscService?wsdl');
  
  testAssignCardToDisposition($client);
  testAssignCardsToDisposition($client);
  testModifyDispositionValue($client);
  testGetSerialNumbers($client);
  testGetDispositionRawState($client);
  testExecuteDebit($client);
  testGetMid($client);
} catch (Exception $e) {
  print 'Failed!';
  print $e;
}
?>