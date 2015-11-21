<?php

require_once 'Validators.php';

class SOPGCustomMerchantClient {
    private $client;
	
    public function __construct($endPoint) {
        try {			
            $this->client = new SoapClient($endPoint);
        } catch (Exception $e) {
            echo $e->getMessage();
        }		
    }
	
    /**
     * Calls the ExecutePayment SOPG web service request.
     * 
     * @param username
     *          Username to use for the web service call.
     * @param password
     *          Password to use for the web service call.
     * @param mtid
     *          Merchant transaction id to use for the web service call. It must
     *          be unique for each invocation.
     * @param subId
     *          SubId to use for the web service call.
     * @param pin
     *          PIN number to use for the web service call.
     * @param amount
     *          Amount to use for the web service call.
     * @param currency
     *          Currency to use for the web service call. It must contain a 3
     *          letter ISO 4217 currency code.
     * @param merchantClientId
     *          ID of the client to use for the web service call. This is
     *          merchant's internal id to identify the client. The parameter is
     *          optional and can be <code>null</code>.
     * @param pnUrl
     *         Payment notification URL to use for the web service call. The
     *         parameter is optional and can be <code>null</code>.
     * @param clientIp
     *          IP address of the client to use for the web service call. This is
     *          the IP of the client when connecting to the merchant. The
     *          parameter is optional and can be <code>null</code>.
     * @return Response of the ExecutePayment web service call. The call was
     *         successful if resultCode and errorCode of the response are 0. For
     *         list of possible error codes refer to merchant documentation.
     * @throws InvalidArgumentException
     *           If required parameters are null or empty.
     */
    public function executePayment($username, $password, $mtid, $subId, $pin, $amount, $currency, $merchantClientId, $pnUrl, $clientIp) {
        Validators::validateStringNotNull($username, 'username');
        Validators::validateStringNotNull($password, 'password');
        Validators::validateStringNotNull($mtid, 'mtid');
        Validators::validateStringNotNull($subId, 'subId');
        Validators::validateStringNotNull($pin, 'pin');
        Validators::validateCurrency($currency);
    
        $params = array('username'=>$username, 
                        'password'=>$password,
                        'id'=>$mtid,
                        'subId'=>$subId,
                        'amount'=>$amount,
                        'currency'=>strtoupper($currency),
                        'pin'=>$pin,
                        'merchantclientid'=>$merchantClientId,
                        'pnUrl'=>$pnUrl,
                        'clientIp'=>$clientIp);
    
        $response = $this->client->executePayment($params);
      										
        return $response->executePaymentReturn;    
    }

    /**
     * Calls the ExecutePaymentACK SOPG web service request.
     * 
     * @param username
     *          Username to use for the web service call.
     * @param password
     *          Password to use for the web service call.
     * @param mtid
     *          Merchant transaction id to use for the web service call.
     * @param subId
     *          SubId to use for the web service call.
     * @return Response of the ExecutePaymentACK web service call. The call was
     *         successful if resultCode and errorCode of the response are 0. For
     *         list of possible error codes refer to merchant documentation.
     * @throws InvalidArgumentException
     *           If required parameters are null or empty.
     */
    public function executePaymentACK($username, $password, $mtid, $subId) {
        Validators::validateStringNotNull($username, 'username');
        Validators::validateStringNotNull($password, 'password');
        Validators::validateStringNotNull($mtid, 'mtid');
        Validators::validateStringNotNull($subId, 'subId');
   
        $params = array('username'=>$username, 
                        'password'=>$password,
                        'id'=>$mtid,
                        'subId'=>$subId);

        $response = $this->client->executePaymentACK($params);

        return $response->executePaymentACKReturn;   	
    }

    /**
     * Calls the CancelPayment SOPG web service request.
     * 
     * @param username
     *          Username to use for the web service call.
     * @param password
     *          Password to use for the web service call.
     * @param mtid
     *          Merchant transaction id to use for the web service call.
     * @param subId
     *          SubId to use for the web service call.
     * @return Response of the CancelPayment web service call. The call was
     *         successful if resultCode and errorCode of the response are 0. For
     *         list of possible error codes refer to merchant documentation.
     * @throws InvalidArgumentException
     *           If required parameters are null or empty.
     */
    public function cancelPayment($username, $password, $mtid, $subId) {
        Validators::validateStringNotNull($username, 'username');
        Validators::validateStringNotNull($password, 'password');
        Validators::validateStringNotNull($mtid, 'mtid');
        Validators::validateStringNotNull($subId, 'subId');

        $params = array('username'=>$username, 
                        'password'=>$password,
                        'id'=>$mtid,
                        'subId'=>$subId);

        $response = $this->client->cancelPayment($params);

        return $response->cancelPaymentReturn;       
    }
}
