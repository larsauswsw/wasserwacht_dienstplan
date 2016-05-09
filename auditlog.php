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

