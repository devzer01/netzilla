<?php

require_once 'Validators.php';

class SOPGHappySchenkcardDistributorClient {
    private $client;

    public function __construct($endPoint) {
        try {			
            $this->client = new SoapClient($endPoint);
        } catch (Exception $e) {
            throw new Exception('Error creating SoapClient: ' . $e->getMessage());
        }		
    }

    /**
     * Calls the OpenLoopGiftcardActivation SOPG web service request.
     * 
     * @param username
     *          Username to use for the web service call.
     * @param password
     *          Password to use for the web service call.
     * @param messageId
     *          Message id to use for the web service call. It must be unique for
     *          each invocation.
     * @param accountNumber
     *          Account number of the card being activated.
     * @param subId
     *          SubId to use for the web service call.
     * @return Response of the OpenLoopGiftcardActivation web service call. The
     *         call was successful if resultCode and errorCode of the response are
     *         0. For list of possible error codes refer to merchant
     *         documentation.
     * @throws InvalidArgumentException
     *           If required parameters are null or empty.
     */	
    public function openLoopGiftcardActivation($username, $password, $messageId, $accountNumber, $subId) {
        Validators::validateStringNotNull($username, 'username');
        Validators::validateStringNotNull($password, 'password');
        Validators::validateStringNotNull($accountNumber, 'accountNumber');
        Validators::validateStringNotNull($subId, 'subId');
    
        $params = array('username'=>$username, 
                        'password'=>$password,
                        'messageId'=>$messageId,
                        'accountNumber'=>$accountNumber,
                        'subId'=>$subId);

        $response = $this->client->openLoopGiftcardActivation($params);

        return $response->openLoopGiftcardActivationReturn;
    }

    /**
     * Calls the OpenLoopGiftcardReversal SOPG web service request.
     * 
     * @param username
     *          Username to use for the web service call.
     * @param password
     *          Password to use for the web service call.
     * @param messageId
     *          Message id to use for the web service call. It must be unique for
     *          each invocation.
     * @param accountNumber
     *          Account number of the card being reversed.
     * @param subId
     *          SubId to use for the web service call.
     * @return Response of the OpenLoopGiftcardReversal web service call. The call
     *         was successful if resultCode and errorCode of the response are 0.
     *         For list of possible error codes refer to merchant documentation.
     * @throws InvalidArgumentException
     *           If required parameters are null or empty.
     */	
    public function openLoopGiftcardReversal($username, $password, $messageId, $accountNumber, $subId) {
        Validators::validateStringNotNull($username, 'username');
        Validators::validateStringNotNull($password, 'password');
        Validators::validateStringNotNull($accountNumber, 'accountNumber');
        Validators::validateStringNotNull($subId, 'subId');

        $params = array('username'=>$username, 
                        'password'=>$password,
                        'messageId'=>$messageId,
                        'accountNumber'=>$accountNumber,
                        'subId'=>$subId);

        $response = $this->client->openLoopGiftcardReversal($params);

        return $response->openLoopGiftcardReversalReturn;
    }
}
