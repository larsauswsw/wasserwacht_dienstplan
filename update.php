<?php

echo "Modus: " . $_POST['mode'];
#var_dump($_POST['wochenenden']);
echo "<hr>";
#var_dump($_POST['ferien']);


foreach ($_POST['wochenenden'] as $tag => $value){
    echo "Datum : " . $tag . "<br>";
    foreach($value as $ort => $inputValue){
	echo "Ort         : " . $ort . "<br>";
	echo "Input Value : " . $inputValue . "<br>";
    }
}













?>

