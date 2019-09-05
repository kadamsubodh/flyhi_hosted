<?php


session_start();
$HASHING_METHOD = 'sha512'; // md5,sha1
$ACTION_URL = "https://secure.ebs.in/pg/ma/payment/request/";

// This post.php used to calculate hash value using md5 and redirect to payment page.
if(isset($_POST['secretkey']))
    $_SESSION['SECRET_KEY'] = $_POST['secretkey'];
else
    $_SESSION['SECRET_KEY'] = ''; //set your secretkey here

$hashData = $_SESSION['SECRET_KEY'];

$_POST['name'] = $_POST['txtFirstName']." ".$_POST['txtLastName'];
unset($_POST['txtFirstName']);
unset($_POST['txtLastName']);
unset($_POST['secretkey']);
unset($_POST['Proceed']);

/**** Added By Pranali Start ***/

$postData = $_POST;

/**** Added By Pranali End ****/

ksort($postData );
foreach ($postData as $key => $value){
    if (strlen($value) > 0) {
        $hashData .= '|'.$value;
    }
}


if (strlen($hashData) > 0) {
    $secureHash = strtoupper(hash($HASHING_METHOD, $hashData));
}
//echo $secureHash; exit;
?>
<html>
<!--onLoad="document.payment.submit();" -->
<body onLoad="document.payment.submit();">
<h3>Please wait, redirecting to process payment..</h3>

<?php //echo "<pre>"; print_r($_POST); exit; ?>
<form action="<?php echo $ACTION_URL?>" name="payment" method="POST">
    <?php
    foreach($_POST as $key => $value) {
//echo $key ." => ". $value."<br>";
        ?>
        <input type="hidden" value="<?php echo $value;?>" name="<?php echo $key;?>"/>
        <?php
    }
    ?>
    <input type="hidden" value="<?php echo $secureHash; ?>" name="secure_hash"/>
</form>
<?php exit;?>
</body>
</html>
