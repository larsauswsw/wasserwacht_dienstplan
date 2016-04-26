<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery-1.12.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>
  <body>
  <center>
  <h1>Dienstplan Halbendorf 2016</h1>


<?php

$settings = parse_ini_file("settings.ini", TRUE);

$servername = $settings['db']['servername'];
$username = $settings['db']['username'];
$password = $settings['db']['password'];
$dbname = $settings['db']['dbname'];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$email_from = $settings['email']['from'];
$email_to = $settings['email']['to'];
$email_subject = $settings['email']['subject'];

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$changes = false;
$email_body = "Hallo,\n\nes gibt neue Änderungen im Dienstplan:\n\nTag - Ort - Alter Wert - Neuer Wert\n";
$email_header = "From: Lars Miesner <" . $email_from . ">\r\n";
$email_header .= "Mime-Version: 1.0\r\n";
$email_header .= "Content-type: text/plain; charset=utf-8";

foreach ($_POST['tage'] as $tag => $value){
    #echo "Datum : " . $tag . "<br>";
    foreach($value as $ort => $inputValue){
	#echo "Ort         : " . $ort . "<br>";
	#echo "Input Value : " . $inputValue . "<br>";

	$selectSql = "SELECT " . $ort . " FROM tage where tag='" . $tag . "';";
	$oldValueResult = $conn->query($selectSql);
	$oldValueResult = $oldValueResult->fetch_assoc();
	$oldValue = $oldValueResult[$ort];
	if ($inputValue != $oldValue) {
	    $changes = true;
	    $sqlUpdate = "UPDATE tage SET " . $ort . "='" . $inputValue . "' WHERE tag='" . $tag . "';";
	    if ($conn->query($sqlUpdate) === TRUE) {
	        #echo "Record updated successfully";
	    } else {
	        exit("Fehler beim Speichern der Daten: " . $conn->error);
	    }
	    $email_body .= " - ". $tag . " - " . $ort . " - " . $oldValue . " - " . $inputValue . "\n"; 
	    $sqlInsert = "INSERT INTO auditlog (tag,ort,alter_wert,neuer_wert,zeitpunkt) VALUES ('". $tag . "','" . $ort . "','" . $oldValue . "','" . $inputValue . "',NOW());";
            if ($conn->query($sqlInsert) === TRUE) {
                #echo "Record updated successfully";
            } else {
                exit("Fehler beim Speichern der Daten: " . $conn->error);
            }
	}
    }
}


if ($changes == true) {
    echo "<div class='alert alert-success'>Deine Daten wurden erfolgreich eingetragen. Du kannst sie dir <a href='index.php?mode=view'>hier</a> ansehen.</div>";

    mail($email_to,$email_subject,$email_body, $email_header);

} else {
echo "<div class='alert alert-info'>Es wurden keine Änderungen vorgenommen. Du kannst dich <a href='index.php'>hier</a> eintragen.</div>";
}



?>
</center>
</body>
</html>
