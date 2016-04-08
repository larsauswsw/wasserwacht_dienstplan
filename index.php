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




echo "<form action='update.php' method='post' >";
echo "<table>";
echo "<tr><td colspan=4>Dienstplan Wochenenden</td></tr>";
echo "<tr><td>Datum</td><td>Textil 1</td><td>Textil 2</td><td>FKK 1</td><td>FKK 2</td></tr>";
if ($result = $conn->query("SELECT * FROM wochenenden order by tag")) {
 #   printf("Select returned %d rows.\n", $result->num_rows);
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
	echo "<tr>";
        echo "<td>" . $row['tag'] . "</td>";
	if ($_GET['mode'] == 'view'){
	    echo "<td>" . $row['textil1'] . "</td>";
            echo "<td>" . $row['textil2'] . "</td>";
            echo "<td>" . $row['fkk1'] . "</td>";
            echo "<td>" . $row['fkk2'] . "</td>";
	} else {
	    echo "<td><input name='wochenenden[" . $row['tag'] . "][textil1]' value='" . $row['textil1'] . "' maxlength='255'></td>";
	    echo "<td><input name='wochenenden[" . $row['tag'] . "][textil2]' value='" . $row['textil2'] . "' maxlength='255'></td>";
	    echo "<td><input name='wochenenden[" . $row['tag'] . "][fkk1]' value='" . $row['fkk1'] . "' maxlength='255'></td>";
	    echo "<td><input name='wochenenden[" . $row['tag'] . "][fkk2]' value='" . $row['fkk2'] . "' maxlength='255'></td>";
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

echo "<hr>";


echo "<table>";
echo "<tr><td colspan=4>Dienstplan Ferien</td></tr>";
echo "<tr><td>Datum</td><td>Textil 1</td><td>Textil 2</td><td>FKK 1</td><td>FKK 2</td></tr>";
if ($result = $conn->query("SELECT * FROM ferien order by tag")) {
#   printf("Select returned %d rows.\n", $result->num_rows);
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['tag'] . "</td>";
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

    /* free result set */
    $result->close();
}
echo "</table>";




if ($_GET['mode'] != 'view'){
    echo "<input type='submit' value='Speichern'>";
}
echo "</form>";





?>
