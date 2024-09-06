
<center><img src="images/loading.gif"></center>

<?php
error_reporting(0);
session_start();
$hashSecretWord = 'tango'; //2Checkout Secret Word
$hashSid = $_REQUEST['sid'];; //2Checkout account number
$hashTotal = $_REQUEST['total']; //Sale total to validate against
$hashOrder = $_REQUEST['order_number']; //2Checkout Order Number
$StringToHash = strtoupper(md5($hashSecretWord . $hashSid . $hashOrder . $hashTotal));

if ($StringToHash != $_REQUEST['key']) {
	$result = '2'; 
	} else { 
	$result = '1';
}

$_SESSION['order_number'] 	= $_REQUEST['order_number'];
$_SESSION['invoice_id'] 	= $_REQUEST['invoice_id'];
$_SESSION['planName'] 		= $_REQUEST['li_0_name'];
$_SESSION['amount'] 	    = $_REQUEST['total'];
$_SESSION['res'] 	    	= $result;

sleep(3);

echo "<script>window.location ='thankyou'</script>";
