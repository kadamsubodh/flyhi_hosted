<?php
session_start();
$paymentType=$_SESSION['paymentType'];
$orderID=$_SESSION['orderID'];
$sessionID=$_SESSION['sessionID'];
$sessionVersion=$_SESSION['sessionVersion'];
$successIndicator=$_SESSION['successIndicator'];
$merchantId = $_SESSION['merchantId'];

$FirstName =  $_SESSION["FirstName"];
$LastName = $_SESSION['LastName'];
$email = $_SESSION['email'];
$phone = $_SESSION['phone'];
$currency = $_SESSION['currency'];
$amount = $_SESSION['amount'];
$description = $_SESSION['description'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];

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
$getOrderRequest = callAPI('GET', 'https://gateway-japa.americanexpress.com/api/rest/version/50/merchant/'. $merchantId .'/order/'.$orderID,$data, $username, $password);
$response = json_decode($getOrderRequest, true);

// 3rd request CAPTURE
$authenticationToken= $response['transaction'][0]['3DSecure']['authenticationToken'];
$transactionID = $authenticationToken;
$amount = $response['amount'];
$transaction = (object)["amount" => $amount, "currency" => $currency];
$data = (object)["transaction" => $transaction, "apiOperation" => "CAPTURE"];

 $captureRequest = callAPI('PUT', 'https://gateway-japa.americanexpress.com/api/rest/version/50/merchant/'.$merchantId .'/order/'. $orderID .'/transaction/'. $transactionID, json_encode($data), $username, $password);

$response = json_decode($captureRequest, true);
if($response['order']['status'] == "CAPTURED" && $response['result'] == "SUCCESS") {
	echo "<pre>"; print_r($response); exit();
	$address = $response['billing']['address']['street'] . ", " . $response['billing']['address']['city'] . ", " . $response['billing']['address']['stateProvince'] . "-" . $response['billing']['address']['postcodeZip'] . ", " . $response['billing']['address']['country']; 

	$orderTime = $response['order']['creationTime'];
	$transactionTime = $response['timeOfRecord'];
	$orderAmmount = $response['order']['amount'];
	$transactionReceipt = $response['transaction']['receipt'];
	


}

?>
