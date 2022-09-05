<?php
// get the q parameter from URL
// $q = $_REQUEST["q"];
$time = date("Y-m-d H:i:s");
var_dump($time);exit;

$paymentStatus = $q . " Activated";
$paymentStatus = "<b style='color:#7ab317;'>" . $paymentStatus . "<b>";
// Output "no suggestion" if no hint was found or output correct values
echo $paymentStatus === "" ? "no suggestion" : $paymentStatus;
?>

