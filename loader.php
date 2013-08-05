<?php
	include('UssdReceiver.php');
	include('UssdSender.php');

	$destinationAddress =""; // Define required parameters to receive response
    $message ="";
    $applicationId ="";
    $encoding ="";
    $version ="";
    $sessionId ="";
    $ussdOperation ="";
    $password ="";
    $chargingAmount =""; 

	//Simulator USSD address
	$ussd_url = "http://localhost:7000/ussd/send";
	$array = json_decode(file_get_contents('php://input'), true);
	
	if($array!=null)
	{
		$receiver =new UssdReceiver($array);
		
		$applicationId = $receiver->getApplicationId();
	
		$password = 'chan';
		$version = $receiver->getVersion();
		$responseMsg = $receiver->getMessage();
		$sessionId = $receiver->getRequestID();
		$ussdOperation = $receiver->getUssdOperation();
		$destinationAddress = $receiver->getAddress();
		$encoding = $receiver->getEncoding();
		$chargingAmount = "5";
	
		
		$sender = new UssdSender($ussd_url);

		$res = $sender->ussd($applicationId, $password, $version, $responseMsg,
                         $sessionId, $ussdOperation, $destinationAddress, $encoding, $chargingAmount); 
                       /*  */
}
	}
	else{

		echo 'use Simulator to use ussd service'
	}
	*/
?>