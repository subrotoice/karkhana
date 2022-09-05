<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email sending</title>
</head>
<body>
<?php
$host = "localhost";
$username = "vioniko_new";
$password = "6!xIj6z0";
$database = "admin_vioniko_new_fresh";
$conn = mysql_connect($host, $username, $password);
mysql_select_db($database);

    
$dateStr1=date('Y-m-d');
$dateStr2=date('Y-m-d', strtotime('6 day'));
var_dump($dateStr1);
// $diff=date_diff($date1, $date2);
// var_dump($diff->days);
// $sql = "SELECT * FROM tracking_payment where paymentDate>='$dateStr1%' and paymentDate<='$dateStr2%'";
// $sql = 'SELECT userid, email, paymentDate FROM tracking_Payment, usuario WHERE usuario.clave=tracking_Payment.userid';
$sql = "SELECT userid, email, paymentDate, remainderSent FROM tracking_Payment, usuario WHERE paymentDate>='$dateStr1%' and paymentDate<='$dateStr2%' and usuario.clave=tracking_Payment.userid AND remainderSent IS NULL";

$result = mysql_query( $sql, $conn ); // Esecute the query 33
while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "<pre>"; var_dump($row);
}
  
// echo $date . "<br>";
// echo $date2 . "<br>";
// echo date('Y-m-d h:i:s', strtotime('1 hour')) . "<br>";
// echo date('Y-m-d h:i:s', strtotime('2 hour')) . "<br>";
// echo date('Y-m-d h:i:s', strtotime('-1 day')) . "<br>";
// var_dump($different);


include_once '../lib_mailing.php';

try {
    $sql = "SELECT userid, email, paymentDate, remainderSent FROM tracking_Payment, usuario WHERE paymentDate>='$dateStr1%' and paymentDate<='$dateStr2%' and usuario.clave=tracking_Payment.userid AND remainderSent IS NULL";
    var_dump($sql); exit();
    // $sql = "SELECT * FROM tracking_subroto_test";
    $result = mysql_query( $sql, $conn ); // Esecute the query 33
    while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        echo "<pre>"; var_dump($row);
        // $email = $row['email'];
        // echo $email . " ";
        // $mail->addAddress($email);    //Add a recipient
    }

    // $mail->addAddress('subroto.doict@gmail.com', 'Joe User Goutom');     //Add a recipient
	//Content
	$mail->isHTML(true);                                  //Set email format to HTML
	$mail->Subject = '22 Here is the subject ' . $dateStr1;
	$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	if ($ok = $mail->send()) {
        // $sql = "update tracking_Payment set remainderSent=$dateStr1 WHERE paymentDate>='$dateStr1%' and paymentDate<='$dateStr2%' AND remainderSent IS NULL";
        // var_dump($sql);
        // $result = mysql_query( $sql, $conn ); // Esecute the query 
		echo 'Message has been sent';
	} else {
		echo 'Not Send Message else';
	}
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>

</body>
</html>
