<?php
	//session_start();

	 $isEntered = null;
	 $isRegisterMenu = null; 
	 $messages = "chan";
	 $_SESSION['message']+="chan";
// some code here


	
if($input!=null){
//For any process, we can generate/display Output message on the phone. It may be a multiple line message output if you wish
if($input['message']!=null){
	
	if($input['message']== "*123#"){
		$data["message"]=$_SESSION['message'];
		$isEntered = true;	 
	}	
	else if($input['message']== "1"){
		$data["message"]='Enter 1 to select Feild<br/>Enter 0 to exit';		
	}
	else if($isEntered && $input['message']== "2"){
		$data["message"]='thank you using ideamart';		
	}
	
	else if($input['message']== "3"){
		$data["message"]='Select Filed<br/>
							4 Mecanic<br/>
							5 Carpenter<br/>
							6 House maid';
		$feildSelect= true;
	}
	else if($input['message']==4){
		$data["message"]='You have registered as Mechanic';
	}
	else if($input['message']==5){
		$data["message"]='You have registered as Carpenter';
	}
	else if($input['message']==6){
		$data["message"]='You have registered as HouseMaid';
	}
	else{
		$data["message"]=$input['message'];
			}	

}
else{
	$data["message"]='enter 1 to register<br/> enter 2 to exit';
}
//application id. If you need, you can validate it as well. You can also send the same application id as sent by the Simulator
$data["applicationId"]=$input["applicationId"];
$data["password"]="password";
$data["version"]="1.0";
//SessionID to continue session
$data["sessionId"]=$input["sessionId"];
//Continue session
$data["ussdOperation"]="mt-cont";
//Destination phone address
$data["destinationAddress"]=$input["sourceAddress"];
$data["encoding"]="440";
$data["chargingAmount"]="5";


//Encode above $data to json object
$json_string = json_encode($data);


//Simulator USSD address
$json_url = "http://localhost:7000/ussd/send";

//To send Request to simulator, Initilize CURL 
$ch = curl_init( $json_url ) or die("error curl ini");
 
//setting CURL options
$options = array(
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
CURLOPT_POSTFIELDS => $json_string
);

curl_setopt_array( $ch, $options ) or die("error curl post");
 
//Excute request
$result =  curl_exec($ch) or die("error curl execute");
}
else
{
	echo 'please start simulator';
}
?>

<?php

	function RegisterMenu(){
		$data['message'] = "<b>Enter 1 to Register as <br/>HandyMan";
	}//end RegisterMenu
?>

