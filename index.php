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
<h1>Dienstplan Halbendorf 2020</h1>

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
//echo "Connected successfully";

setlocale (LC_ALL, 'de_DE');

echo "<form action='update.php' method='post' >";

if ($_GET['mode'] != 'view'){
    echo "<div class='alert alert-danger alert-block'><strong>ACHTUNG!</strong> Ungespeicherte Änderungen (gelb hinterlegt) gehen verloren, wenn die Seite ohne Speichern verlassen wird. Bitte auf &quot;&Auml;nderungen speichern&quot; klicken.</div>";
    echo "<div class='alert alert-danger alert-block'><strong>ACHTUNG!</strong> Nachträgliche Änderungen/Löschungen k&ouml;nnen nur noch durch einen Admnistrator (Karin/Steffen) durchgef&uuml;hrt werden.</div>";
    echo "<input type='submit' value='Änderungen speichern' class='btn btn-large btn btn-danger '>";
    echo "<br><br>";
}

echo "<table>";
echo "<tr><td colspan=5>Dienstplan Halbendorf</td></tr>";
echo "<tr><td><b>Datum:</b></td><td><b>Textil</b><br>Rettungsschwimmer<br>mind. Silber/ 18 Jahre</td><td><b>Textil</b><br>Rettungsschwimmer<br>mind. Bronze/ Sani&auml;tsausbildung</td><td><b>FKK</b><br>Rettungsschwimmer<br>mind. Silber/ 18 Jahre</td><td><b>FKK</b><br>Rettungsschwimmer<br>mind. Bronze/ Sani&auml;tsausbildung</td></tr>";
if ($result = $conn->query("SELECT * FROM tage WHERE archiv = False order by tag")) {
 #   printf("Select returned %d rows.\n", $result->num_rows);
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
	$class = "";
	if (date('N', strtotime($row['tag'])) >= 6){
	    $class = $class . " wochenende";
	}
	if (date('N', strtotime($row['tag'])) == 7){
            $class = $class . " sonntag";
        }
	if ($row['tag'] == '2020-06-01' ){
            $class = $class . " feiertag";
        }


	echo "<tr>";
        echo "<td class='" . $class . "'>" .strftime("%A, %d.%m.%Y", strtotime($row['tag'])) . "</td>";
	if ($_GET['mode'] == 'view'){
	    echo "<td>" . $row['textil1'] . "</td>";
            echo "<td>" . $row['textil2'] . "</td>";
            echo "<td>" . $row['fkk1'] . "</td>";
            echo "<td>" . $row['fkk2'] . "</td>";
	} else {
        if (empty($row['textil1']) || $_SERVER['PHP_AUTH_USER'] == 'admin') {
            echo "<td><input type='text' name='tage[" . $row['tag'] . "][textil1]' value='" . $row['textil1'] . "' maxlength='255'></td>";
        } else {
            echo "<td>" . $row['textil1'] . "</td>";
        }

        if (empty($row['textil2']) || $_SERVER['PHP_AUTH_USER'] == 'admin') {
            echo "<td><input type='text' name='tage[" . $row['tag'] . "][textil2]' value='" . $row['textil2'] . "' maxlength='255'></td>";
        } else {
            echo "<td>" . $row['textil2'] . "</td>";
        }

        if (empty($row['fkk1']) || $_SERVER['PHP_AUTH_USER'] == 'admin') {
            echo "<td><input type='text' name='tage[" . $row['tag'] . "][fkk1]' value='" . $row['fkk1'] . "' maxlength='255'></td>";
        } else {
            echo "<td>" . $row['fkk1'] . "</td>";
        }

        if (empty($row['fkk2']) || $_SERVER['PHP_AUTH_USER'] == 'admin') {
            echo "<td><input type='text' name='tage[" . $row['tag'] . "][fkk2]' value='" . $row['fkk2'] . "' maxlength='255'></td>";
        } else {
            echo "<td>" . $row['fkk2'] . "</td>";
        }
	}
	echo "</tr>";
    }

    /* free result set */
    $result->close();
}
echo "</table>";

if ($_GET['mode'] != 'view'){
    echo "<br>";
    echo "<input type='submit' value='Änderungen speichern' class='btn btn-large btn btn-danger '>";
}

?>

</center>

<script type="text/javascript">
$( document ).ready(function() {
    $( 'input[type="text"]' ).on('propertychange input', function (e) {
	$(e.target).addClass( "changed" ); ;
    });
});
</script>


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
