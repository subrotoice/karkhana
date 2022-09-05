<!DOCTYPE html>
<html>
<body>

<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "karkhana";

$conn = new mysqli($host, $username, $password, $database);

$dateStr1=date('Y-m-d');
$dateStr2=date('Y-m-d', strtotime('6 day'));
 
// $date1=date_create(date('Y-m-d')); update
// $date2=date_create(date('Y-m-d', strtotime('6 day')));
// $diff=date_diff($date1, $date2);
// var_dump($diff);
// // select * from evento where fechahora between '2022-08-26 11:00:00' and '2022-08-26 12:00:00' and reminder_sent = 0;
// $resultadotot= mysql_query("SELECT * FROM tracking_payment where paymentDate between '$date1' and '$date1'", $conexion);
// $totres = mysql_num_rows ($resultadotot);
// var_dump();
// SELECT * FROM tracking_payment WHERE paymentDate BETWEEN '2022-9-4' and '2022-9-9';
// SELECT userid, email, paymentDate FROM usuario, tracking_payment where usuario.clave=tracking_payment.userid and paymentDate>='2022-09-05%' and paymentDate<='2022-09-11%';
// Simple Query execute
$sql = "SELECT * FROM tracking_payment where paymentDate>='$dateStr1%' and paymentDate<='$dateStr2%'";
var_dump($sql);
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    var_dump($row);
  }
} else {
  echo "0 results";
}

?>

</body>
</html>
