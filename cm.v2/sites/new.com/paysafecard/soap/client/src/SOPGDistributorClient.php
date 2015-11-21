<?php

require_once 'Validators.php';

class SOPGDistributorClient {
    private $client;

    public function __construct($endPoint) {
        try {			
            $this->client = new SoapClient($endPoint);
        } catch (Exception $e) {
            throw new Exception('Error creating SoapClient: ' . $e->getMessage());
        }		
    }

    /**
     * Calls the ActivateCard SOPG web service request.
     * 
     * @param username
     *          Username to use for the web service call.
     * @param password
     *          Password to use for the web service call.
     * @param mtid
     *          Merchant transaction id to use for the web service call.
     * @param subId
     *          SubId to use for the web service call.
     * @param serial
     *          Serial number to use for the web service call.
     * @return Response of the ActivateCard web service call. The call was
     *         successful if resultCode and errorCode of the response are 0. For
     *         list of possible error codes refer to merchant documentation.
     * @throws InvalidArgumentException
     *           If required parameters are null or empty.
     */	
    public function activateCard($username, $password, $mtid, $subId, $serial) {
        Validators::validateStringNotNull($username, 'username');
        Validators::validateStringNotNull($password, 'password');
        Validators::validateStringNotNull($mtid, 'mtid');
        Validators::validateStringNotNull($subId, 'subId');
        Validators::validateStringNotNull($serial, 'serial');

        $params = array('username'=>$username, 
                        'password'=>$password,
                        'id'=>$mtid,
                        'subId'=>$subId,
                        'serial'=>$serial);

        $response = $this->client->activateCard($params);

        return $response->activateCardReturn;
    }

    /**
     * Calls the DeactivateCard SOPG web service request.
     * 
     * @param username
     *          Username to use for the web service call.
     * @param password
     *          Password to use for the web service call.
     * @param mtid
     *          Merchant transaction id to use for the web service call.
     * @param subId
     *          SubId to use for the web service call.
     * @param serial
     *          Serial number to use for the web service call.
     * @return Response of the DeactivateCard web service call. The call was
     *         successful if resultCode and errorCode of the response are 0. For
     *         list of possible error codes refer to merchant documentation.
     * @throws InvalidArgumentException
     *           If required parameters are null or empty.
     */
    public function deactivateCard($username, $password, $mtid, $subId, $serial) {
        Validators::validateStringNotNull($username, 'username');
        Validators::validateStringNotNull($password, 'password');
        Validators::validateStringNotNull($mtid, 'mtid');
        Validators::validateStringNotNull($subId, 'subId');
        Validators::validateStringNotNull($serial, 'serial');

        $params = array('username'=>$username, 
                        'password'=>$password,
                        'id'=>$mtid,
                        'subId'=>$subId,
                        'serial'=>$serial);

        $response = $this->client->deactivateCard($params);

        return $response->deactivateCardReturn;
    }
    
    /**
     * Calls the GetBalance SOPG web service request.
     * 
     * @param username
     *          Username to use for the web service call.
     * @param password
     *          Password to use for the web service call.
     * @param mtid
     *          Merchant transaction id to use for the web service call.
     * @param subId
     *          SubId to use for the web service call.
     * @param pin
     *          PIN number to use for the web service call.
     * @return Response of the GetBalance web service call. The call was
     *         successful if resultCode and errorCode of the response are 0. For
     *         list of possible error codes refer to merchant documentation.
     * @throws InvalidArgumentException
     *           If required parameters are null or empty.
     */
    public function getBalance($username, $password, $mtid, $subId, $pin) {
        Validators::validateStringNotNull($username, 'username');
        Validators::validateStringNotNull($password, 'password');
        Validators::validateStringNotNull($mtid, 'mtid');
        Validators::validateStringNotNull($subId, 'subId');
        Validators::validateStringNotNull($pin, 'pin');

        $params = array('username'=>$username, 
                        'password'=>$password,
                        'id'=>$mtid,
                        'subId'=>$subId,
                        'pin'=>$pin);

        $response = $this->client->getBalance($params);

        return $response->getBalanceReturn;
    }

    /**
     * Calls the GetCardInfo SOPG web service request.
     * 
     * @param username
     *          Username to use for the web service call.
     * @param password
     *          Password to use for the web service call.
     * @param mtid
     *          Merchant transaction id to use for the web service call.
     * @param subId
     *          SubId to use for the web service call.
     * @param pin
     *          PIN number to use for the web service call.
     * @return Response of the GetCardInfo web service call. The call was
     *         successful if resultCode and errorCode of the response are 0. For
     *         list of possible error codes refer to merchant documentation.
     * @throws InvalidArgumentException
     *           If required parameters are null or empty.
     */
    public function getCardInfo($username, $password, $mtid, $subId, $pin) {
        Validators::validateStringNotNull($username, 'username');
        Validators::validateStringNotNull($password, 'password');
        Validators::validateStringNotNull($mtid, 'mtid');
        Validators::validateStringNotNull($subId, 'subId');
        Validators::validateStringNotNull($pin, 'pin');

        $params = array('username'=>$username, 
                        'password'=>$password,
                        'id'=>$mtid,
                        'subId'=>$subId,
                        'pin'=>$pin);

        $response = $this->client->getCardInfo($params);

        return $response->getCardInfoReturn;    
    }
}
