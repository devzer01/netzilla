<?php

require_once './../../client/src/SOPGCustomMerchantClient.php';

$username = "USER_CUSTOM";
$password = "PASS";
$currency = "EUR";
$amount = 0.01;
$subId = 'test';
$pin = "5000000000002517";

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

function testExecutePayment($client) {
  global $username, $password, $subId, $pin, $amount, $currency;
  $mtid = generateMtid();
  print "ExecutePayment: ";
  $executePaymentResponse = $client->executePayment(
                                          $username, 
                                          $password, 
                                          $mtid,
                                          $subId, 
                                          $pin, 
                                          $amount, 
                                          $currency,
                                          null,
                                          null,
                                          null);

  print checkResponse($executePaymentResponse);
}

function testCancelPayment($client) {
  global $username, $password, $subId, $pin, $amount, $currency;
  $mtid = generateMtid();
  print "\nExecutePayment: ";
  $executePaymentResponse = $client->executePayment(
                                          $username, 
                                          $password, 
                                          $mtid,
                                          $subId, 
                                          $pin, 
                                          $amount, 
                                          $currency,
                                          null,
                                          null,
                                          null);
  print checkResponse($executePaymentResponse);
  
  print "\nCancelPayment: ";
  $cancelPaymentResponse = $client->cancelPayment(
                                        $username, 
                                        $password, 
                                        $mtid, 
                                        $subId);
  print checkResponse($cancelPaymentResponse);
}

try {
  $client = new SOPGCustomMerchantClient('https://soatest.paysafecard.com/psc/services/PscService?wsdl');
  
  testExecutePayment($client);
  testCancelPayment($client);
} catch (Exception $e) {
  print 'Failed!';
  print $e;
}

?>