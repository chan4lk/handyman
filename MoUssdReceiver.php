<?php

class MoUssdReceiver{

    private $sourceAddress; // Define required parameters to receive response
    private $message;
    private $requestId;
    private $applicationId;
    private $encoding;
    private $version;
    private $sessionId;
    private $ussdOperation;
    private $vlrAddress;

    /*
        decode the json data an get them to an array
        Get data from Json objects
        check the validity of the response
    **/

    public function __construct(){
        $array = json_decode(file_get_contents('php://input'), true);
        $_SESSION['address']=$array['sourceAddress'];
        $this->sourceAddress = $array['sourceAddress'];
        $this->message = $array['message'];
        $this->requestId = $array['requestId'];
        $this->applicationId = $array['applicationId'];
        $this->encoding = $array['encoding'];
        $this->version = $array['version'];
        $this->sessionId = $array['sessionId'];
        $this->ussdOperation = $array['ussdOperation'];
        $this->vlrAddress = $array['vlrAddress'];

        if (!((isset($this->sourceAddress) && isset($this->message)))) {
            //throw new Exception("Some of the required parameters are not provided");
            echo 'use simulator';
        } else {
            // Success received response
            $responses = array("statusCode" => "S1000", "statusDetail" => "Success");
            header("Content-type: application/json");
            echo json_encode($responses);
        }
    }

    /*
        Define getters to return receive data
    **/

    public function getAddress(){
        return $this->sourceAddress;
    }

    public function getMessage(){
        return $this->message;
    }

    public function getRequestID(){
        return $this->requestId;
    }

    public function getApplicationId(){
        return $this->applicationId;
    }

    public function getEncoding(){
        return $this->encoding;
    }

    public function getVersion(){
        return $this->version;
    }

    public function getSessionId(){
        return $this->sessionId;
    }

    public function getUssdOperation(){
        return $this->ussdOperation;
    }

}

?>