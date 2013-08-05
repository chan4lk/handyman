<?php


include_once 'MoUssdReceiver.php';
include_once 'MtUssdSender.php';
include_once 'log.php';
include_once 'mysqlProcess.php';
ini_set('error_log', 'ussd-app-error.log');

$receiver = new MoUssdReceiver(); // Create the Receiver object

$receiverSessionId = $receiver->getSessionId();
session_id($receiverSessionId); //Use received session id to create a unique session
session_start();

$content = $receiver->getMessage(); // get the message content
$address=$receiver->getAddress();
$tel= processAddress($receiver->getAddress()); // get the sender's address
$requestId = $receiver->getRequestID(); // get the request ID
$applicationId = $receiver->getApplicationId(); // get application ID
$encoding = $receiver->getEncoding(); // get the encoding value
$version = $receiver->getVersion(); // get the version
$sessionId = $receiver->getSessionId(); // get the session ID;
$ussdOperation = $receiver->getUssdOperation(); // get the ussd operation

//logFile("[ content=$content, address=$address, requestId=$requestId, applicationId=$applicationId, encoding=$encoding, version=$version, sessionId=$sessionId, ussdOperation=$ussdOperation ]");

//your logic goes here......
$responseMsg = array(
    "main" =>       "1.Register
                     2.Help                    
                     000.Exit",
    "register" => "Select Field
                    1.Household
                    2.Travelling
                    3.Vehicle Based
                    4.Food
                    5.Medical                    
                    999.Back",
    "help" => "Help
                    Use this service to register your self as a handyman. Thank you!.
                    999.Back",
    "household" => "1.Remodling
                    2.Rewiring
                    3.Carpentry
                    4.Tile & Flooring
                    5.Painting & Wall Treatments
                    6.Plumbing
                    7.Shelving & Storage
                    999.Back",
    "travelling" => "1.Taxi - Trishaw 
                    2.Taxi - Van
                    3.Ticket Booking - Airline                  
                    999.Back",
    "vehicle" =>    "1.Light Vehicle Repair
                    2.Heavy Vehicle Repair
                    3.Sell Vehicle
                    999.Back",
    "food"=>       "1. Catering Service
                    2. Restaurant - Pre Order Food
                    999. Back"
                    ,
    "medical"=>"1.Caretakers
                2.On Call Doctors
                3.On Call Nurse
                999. Back",
    "user"=> "1. Set Name
              2. Set address
              3. Regiter Now
              999. back",
    "regnow"=>"sucessfully registered
                999. back",
    "name" => "Set the name",
    "userAddrees" =>"Set the user's Address"
       
);

//logFile("Previous Menu is := " . $_SESSION['menu-Opt']); //Get previous menu number
if (($receiver->getUssdOperation()) == "mo-init") { //Send the main menu
    loadUssdSender($sessionId, $responseMsg["main"]);
    if (!(isset($_SESSION['menu-Opt']))) {
        $_SESSION['menu-Opt'] = "main"; //Initialize main menu
    }

}
if (($receiver->getUssdOperation()) == "mo-cont") {
    $menuName = null;

    switch ($_SESSION['menu-Opt']) {
        case "main":
            switch ($receiver->getMessage()) {
                case "1":
                    $menuName = "register";
                    break;
                case "2":
                    $menuName = "help";
                    break;                
                default:
                    $menuName = "main";
                    break;
            }
            $_SESSION['menu-Opt'] = $menuName; //Assign session menu name
            break;
        case "register":
            $_SESSION['menu-Opt'] = "reg-hist"; //Set to company menu back
            switch ($receiver->getMessage()) {
                case "1":
                    $menuName = "household";
                    break;
                case "2":
                    $menuName = "travelling";
                    break;
                case "3":
                    $menuName = "vehicle";
                    break; 
                case "4":
                    $menuName = "food";
                                   break;  
                case "5":
                    $menuName = "medical";
                                   break;                           
                case "999":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;
                default:
                    $menuName = "main";
                    break;
            }
            break;
            case "help":
            $_SESSION['menu-Opt'] = "help-hist"; //Set to company menu back
            switch ($receiver->getMessage()) {   
                case "999":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;             
                default:
                    $menuName = "main";
                    break;
            }
            break;
            case "travel":
            $_SESSION['menu-Opt'] = "travel-hist"; //Set to company menu back
            switch ($receiver->getMessage()) {
                case "999":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;
                default:
                    $menuName = "user";
                    break;
            }
            break;
            case "vehicle" || "household" || "food" || "medical":
            $_SESSION['menu-Opt'] = "vehicle-hist"; //Set to company menu back
            switch ($receiver->getMessage()) {
                                         
                case "999":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;
                default:
                    $menuName = "user";
                    break;
            }
            break;
        case "user":
            $_SESSION['menu-Opt'] = "user-hist"; //Set to product menu back
            switch ($receiver->getMessage()) {
                case "1":
                    $menuName = "name";
                    break;
                case "2":
                    $menuName = "userAddrees";
                    break;
                case "3":
                    $menuName = "regnow";
                    break;
                case "999":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;
                default:
                    $menuName = "main";
                    break;
            }
            break;  
	case "regnow":
            $_SESSION['menu-Opt'] = "regnow-hist"; //Set to company menu back
            switch ($receiver->getMessage()) {
                                         
                case "999":
                    $menuName = "main";
                    $_SESSION['menu-Opt'] = "main";
                    break;
                default:
                    $menuName = "main";
                    break;
            }
            break;               
        case "reg-hist" || "travel-hist"|| "help-hist" || "user-hist"|| "vehicle-hist" || "regnow-hist":
            switch ($_SESSION['menu-Opt']) { //Execute menu back sessions
                case "reg-hist":
                    $menuName = "register";
                    break;
                case "travel-hist":
                    $menuName = "travel";
                    break;  
                case "vehicle-hist":
                    $menuName = "vehicle";
                    break; 
                case "help-hist":
                    $menuName = "help";
                    break;  
                case 'user-hist':
                                $menuName="user";
                                 break; 
		case 'regnow-hist':
                    $menuName="regnow";
                    break;                
            }
            $_SESSION['menu-Opt'] = $menuName; //Assign previous session menu name
            break;
    }

    if ($receiver->getMessage() == "000") {
        $responseExitMsg = "Exit Program!";
        $response = loadUssdSender($sessionId, $responseExitMsg);
        session_destroy();
    } else {
        //logFile("Selected response message := " . $responseMsg[$menuName]);
        $response = loadUssdSender($sessionId, $responseMsg[$menuName]);
    }

}
/*
    Get the session id and Response message as parameter
    Create sender object and send ussd with appropriate parameters
**/

function loadUssdSender($sessionId, $responseMessage)
{
    $password = "password";
    $destinationAddress = "tel:94772866596";
    if ($responseMessage == "000") {
        $ussdOperation = "mt-fin";
    } else {
        $ussdOperation = "mt-cont";
    }
    $chargingAmount = "5";
    $applicationId = "APP_003319";
    $encoding = "440";
    $version = "1.0";

    try {
        // Create the sender object server url

       $sender = new MtUssdSender("http://localhost:7000/ussd/send/");   // Application ussd-mt sending http url
       // $sender = new MtUssdSender("https://api.dialog.lk/ussd/send"); // Application ussd-mt sending https url
        $response = $sender->ussd($applicationId, $password, $version, $responseMessage,
            $sessionId, $ussdOperation, $destinationAddress, $encoding, $chargingAmount);
        return $response;
    } catch (UssdException $ex) {
        //throws when failed sending or receiving the ussd
       // error_log("USSD ERROR: {$ex->getStatusCode()} | {$ex->getStatusMessage()}");
        return null;
    }
}
function processAddress($address)
{
   
    return str_split($address,4)[1];
}

function insertUser()
{
   $sql = "INSERT INTO `handyman`.`ussd_reg` (`id`, `tel`, `field`) VALUES (NULL, '$tel', '$field');";
    mysql_select_db('ussd_reg');
    $retval = mysql_query( $sql, $conn );
    if(!$retval) die("error".mysql_error());
}
?>
