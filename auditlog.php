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



echo "<table>";
echo "<tr><td>Zeitpunkt</td><td>Datum</td><td>Ort</td><td>Alter Wert</td><td>Neuer Wert</td></tr>";
if ($result = $conn->query("SELECT * FROM auditlog order by zeitpunkt DESC")) {
 #   printf("Select returned %d rows.\n", $result->num_rows);
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
	echo "<tr>";
        echo "<td>" . $row['zeitpunkt'] . "</td>";
	echo "<td>" . $row['tag'] . "</td>";
        echo "<td>" . $row['ort'] . "</td>";
        echo "<td>" . $row['alter_wert'] . "</td>";
        echo "<td>" . $row['neuer_wert'] . "</td>";
	echo "</tr>";
    }

    /* free result set */
    $result->close();
}
echo "</table>";

?>
