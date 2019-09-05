<?php

session_start();
$_SESSION['paymentType'] = $_POST['paymentType'];
$_SESSION["FirstName"]= $_POST['FirstName'];
$_SESSION['LastName'] = $_POST['LastName'];
$_SESSION['email'] = $_POST['email1'];
$_SESSION['phone'] = $_POST['phone1'];
$_SESSION['currency'] = $_POST['currency1'];
$_SESSION['amount'] = $_POST['amount1'];
$_SESSION['description'] = $_POST['description1'];
$orderID = time().'-'.mt_rand();
$_SESSION['orderID'] = $orderID;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = "";
$password = "";
$merchetId='';
$servername = "localhost";
$db_username = "root";
$db_password = "root";
$dbname = "flyhi_travels";
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if (!$conn->connect_error) {
    $sql = "SELECT * FROM myCredentials";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
        $username=$row['username'];
        $password=$row['password'];
        $merchantId=$row['merchantId'];
        } 
    }
}
$_SESSION['merchantId'] = $merchantId;
$_SESSION['username'] = $username;
$_SESSION['password'] = $password;

function callAPI($method, $url, $data, $username, $password) {

	$curl = curl_init();
	switch ($method) {
		case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);
			if ($data)
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
		case "PUT":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			if ($data)
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
			break;
		default:
		if ($data)
		$url = sprintf("%s?%s", $url, http_build_query($data));
	}
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	'Authorization:Basic bWVyY2hhbnQudGVzdDgxMTAyOTE1ODA6M2UzNjhhNGM4ZTBiNjczNTM3MDE2MWQyNGRkNTYxZGM=',
	));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
	$result = curl_exec($curl);
	$err = curl_error($curl); 
	print_r($err);
	if($err){die("Connection Failure");}
	curl_close($curl);
	return $result;
}

$order = (object)["id" => $orderID, "currency" => $_POST['currency1']];
$data = (object)["order" => $order, "apiOperation" => "CREATE_CHECKOUT_SESSION"];

$checkoutSessionRequest = callAPI('POST', 'https://gateway-japa.americanexpress.com/api/rest/version/50/merchant/' . $merchantId . '/session', json_encode($data), $username, $password);
$content=json_decode($checkoutSessionRequest,true);
$_SESSION['sessionID'] = $sessionID = $content['session']['id'];
$_SESSION['sessionVersion'] = $sessionVersion = $content['session']['version'];
$_SESSION['successIndicator'] = $successIndicator = $content['successIndicator'];
header("Location: amexCheckout.php");
?>


