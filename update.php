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
  <h1>Dienstplan Halbendorf 2019</h1>


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
$email_body = "Hallo,<br><br>es gibt neue Änderungen im Dienstplan:<br><br><table><tr><td>Tag</td><td>Ort</td><td>Alter Wert</td><td>Neuer Wert</td></tr>";
$email_header = "From: Lars Miesner <" . $email_from . ">\r\n";
$email_header .= "Mime-Version: 1.0\r\n";
$email_header .= "Content-type:text/html;charset=UTF-8";

foreach ($_POST['tage'] as $tag => $value){
    #echo "Datum : " . $tag . "<br>";
    foreach($value as $ort => $inputValue){
	#echo "Ort         : " . $ort . "<br>";
	#echo "Input Value : " . $inputValue . "<br>";

	$selectSql = "SELECT " . $ort . " FROM tage where archiv=false and tag='" . $tag . "';";
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
	    $email_body .= "<tr><td>". $tag . "</td><td>" . $ort . "</td><td>" . $oldValue . "</td><td>" . $inputValue . "</td></tr>"; 
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
    $email_body .= "</table>";
    mail($email_to,$email_subject,$email_body, $email_header);

} else {
echo "<div class='alert alert-info'>Es wurden keine Änderungen vorgenommen. Du kannst dich <a href='index.php'>hier</a> eintragen.</div>";
}



?>
</center>

<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["setDomains", ["*.lars.is/wasserwacht","*.lars.is/wasserwacht"]]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//lars.is/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 4]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//lars.is/piwik/piwik.php?idsite=4" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->


</body>
</html>
