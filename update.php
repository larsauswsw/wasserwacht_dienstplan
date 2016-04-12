<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>

<?php

$settings = parse_ini_file("settings.ini", TRUE);

$servername = $settings['db']['servername'];
$username = $settings['db']['username'];
$password = $settings['db']['password'];
$dbname = $settings['db']['dbname'];
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$changes = false;

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
	    
	    $sqlInsert = "INSERT INTO auditlog (tag,ort,alter_wert,neuer_wert,zeitpunkt) VALUES ('". $tag . "','" . $ort . "','" . $oldValue . "','" . $inputValue . "',NOW());";
            if ($conn->query($sqlInsert) === TRUE) {
                #echo "Record updated successfully";
            } else {
                exit("Fehler beim Speichern der Daten: " . $conn->error);
            }
	}
    }
}

/*
foreach ($_POST['ferien'] as $tag => $value){
    #echo "Datum : " . $tag . "<br>";
    foreach($value as $ort => $inputValue){
        #echo "Ort         : " . $ort . "<br>";
        #echo "Input Value : " . $inputValue . "<br>";

        $selectSql = "SELECT " . $ort . " FROM ferien where tag='" . $tag . "';";
        $oldValueResult = $conn->query($selectSql);
        $oldValueResult = $oldValueResult->fetch_assoc();
        $oldValue = $oldValueResult[$ort];
        if ($inputValue != $oldValue) {
	    $changes = true;
            $sqlUpdate = "UPDATE ferien SET " . $ort . "='" . $inputValue . "' WHERE tag='" . $tag . "';";
            if ($conn->query($sqlUpdate) === TRUE) {
                #echo "Record updated successfully";
            } else {
                exit("Fehler beim Speichern der Daten: " . $conn->error);
            }

            $sqlInsert = "INSERT INTO auditlog (tag,ort,alter_wert,neuer_wert,zeitpunkt) VALUES ('". $tag . "','" . $ort . "','" . $oldValue . "','" . $inputValue . "',NOW());";
            if ($conn->query($sqlInsert) === TRUE) {
                #echo "Record updated successfully";
            } else {
                exit("Fehler beim Speichern der Daten: " . $conn->error);
            }
        }
    }
}
*/


if ($changes == true) {
    echo "Deine Daten wurden erfolgreich eingetragen. Du kannst sie dir <a href='index.php?mode=view'>hier</a> ansehen.";
} else {
    echo "Es wurden keine Änderungen vorgenommen. Du kannst dich <a href='index.php'>hier</a> eintragen.";
}



?>
</body>
</html>
