<?php

require_once './../../client/src/SOPGDistributorClient.php';

$username = 'USER_CUSTOM';
$password = 'PASS';
$subId = 'test';
$serial = '101702517';
$pin = '5000000000002517';

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

function testActivateCard($client) {
  global $username, $password, $subId, $serial;
  $mtid = generateMtid();
  print "ActivateCard: ";
  $activateCardResponse = $client->activateCard(
                                          $username, 
                                          $password, 
                                          $mtid,
                                          $subId, 
                                          $serial);

  print checkResponse($activateCardResponse);
}

function testDeactivateCard($client) {
  global $username, $password, $subId, $serial;
  $mtid = generateMtid();
  print "\nDeactivateCard: ";
  $deactivateCardResponse = $client->deactivateCard(
                                          $username, 
                                          $password, 
                                          $mtid,
                                          $subId, 
                                          $serial);

  print checkResponse($deactivateCardResponse);
}

function testGetBalance($client) {
  global $username, $password, $subId, $pin;
  $mtid = generateMtid();
  print "\nGetBalance: ";
  $getBalanceResponse = $client->getBalance(
                                      $username, 
                                      $password, 
                                      $mtid, 
                                      $subId, 
                                      $pin);
  print checkResponse($getBalanceResponse);
}

function testGetCardInfo($client) {
  global $username, $password, $subId, $pin;
  $mtid = generateMtid();
  print "\nGetCardInfo: ";
  $getCardInfoResponse = $client->getCardInfo(
                                        $username, 
                                        $password, 
                                        $mtid, 
                                        $subId, 
                                        $pin);
  print checkResponse($getCardInfoResponse);
}

try {
  $client = new SOPGDistributorClient('https://soatest.paysafecard.com/psc/services/PscService?wsdl');
  
  testActivateCard($client);
  testDeactivateCard($client);
  testGetBalance($client);
  testGetCardInfo($client);
} catch (Exception $e) {
  print 'Failed!';
  print $e;
}

?>
