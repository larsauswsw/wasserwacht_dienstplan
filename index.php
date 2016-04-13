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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully";

setlocale (LC_ALL, 'de_DE');

echo "<form action='update.php' method='post' >";

if ($_GET['mode'] != 'view'){
    echo "<div class='alert alert-danger alert-block'><strong>ACHTUNG!</strong> Ungespeicherte Änderungen (gelb hinterlegt) gehen verloren, wenn die Seite ohne Speichern verlassen wird. Bitte auf &quot;&Auml;nderungen speichern&quot; klicken.</div>";
    echo "<input type='submit' value='Änderungen speichern' class='btn btn-large btn btn-danger '>";
    echo "<br><br>";
}

echo "<table>";
echo "<tr><td colspan=5>Dienstplan Wochenenden</td></tr>";
echo "<tr><td><b>Datum:</b></td><td><b>Textil</b><br>Rettungsschwimmer<br>mind. Silber/ 18 Jahre</td><td><b>Textil</b><br>Rettungsschwimmer<br>mind. Bronze/ 18 Jahre</td><td><b>FKK</b><br>Rettungsschwimmer<br>mind. Silber/ 18 Jahre</td><td><b>FKK</b><br>Rettungsschwimmer<br>mind. Bronze/ 18 Jahre</td></tr>";
if ($result = $conn->query("SELECT * FROM tage order by tag")) {
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
	if ($row['tag'] == '2016-05-16' ){
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
	    echo "<td><input type='text' name='tage[" . $row['tag'] . "][textil1]' value='" . $row['textil1'] . "' maxlength='255'></td>";
	    echo "<td><input type='text' name='tage[" . $row['tag'] . "][textil2]' value='" . $row['textil2'] . "' maxlength='255'></td>";
	    echo "<td><input type='text' name='tage[" . $row['tag'] . "][fkk1]' value='" . $row['fkk1'] . "' maxlength='255'></td>";
	    echo "<td><input type='text' name='tage[" . $row['tag'] . "][fkk2]' value='" . $row['fkk2'] . "' maxlength='255'></td>";
	}
	echo "</tr>";
    }

    /* free result set */
    $result->close();
}
echo "</table>";

if ($_GET['mode'] != 'view'){
    echo "<input type='submit' value='Speichern'>";
}

/*
echo "<hr>";


echo "<table>";
echo "<tr><td colspan=5>Dienstplan Ferien</td></tr>";
echo "<tr><td>Datum</td><td>Textil 1</td><td>Textil 2</td><td>FKK 1</td><td>FKK 2</td></tr>";
if ($result = $conn->query("SELECT * FROM ferien order by tag")) {
#   printf("Select returned %d rows.\n", $result->num_rows);
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" .strftime("%A, %d.%m.%Y", strtotime($row['tag'])) . "</td>";
	if ($_GET['mode'] == 'view'){
            echo "<td>" . $row['textil1'] . "</td>";
            echo "<td>" . $row['textil2'] . "</td>";
            echo "<td>" . $row['fkk1'] . "</td>";
            echo "<td>" . $row['fkk2'] . "</td>";
        } else {
            echo "<td><input name='ferien[" . $row['tag'] . "][textil1]' value='" . $row['textil1'] . "' maxlength='255'></td>";
            echo "<td><input name='ferien[" . $row['tag'] . "][textil2]' value='" . $row['textil2'] . "' maxlength='255'></td>";
            echo "<td><input name='ferien[" . $row['tag'] . "][fkk1]' value='" . $row['fkk1'] . "' maxlength='255'></td>";
            echo "<td><input name='ferien[" . $row['tag'] . "][fkk2]' value='" . $row['fkk2'] . "' maxlength='255'></td>";
        }
	echo "</tr>";
    }

    $result->close();
}
echo "</table>";




if ($_GET['mode'] != 'view'){
    echo "<input type='submit' value='Speichern'>";
}
echo "</form>";

*/



?>

</center>

<script type="text/javascript">
$( document ).ready(function() {
    $( 'input[type="text"]' ).on('propertychange input', function (e) {
	$(e.target).addClass( "changed" ); ;
    });
});
</script>



</body>
</html>
