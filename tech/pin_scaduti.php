<?php

include 'connect.php';

$pinscaduti = $conn2->query("SELECT * FROM visual_phonebook WHERE scadenza_pin < curdate() and scadenza_pin != '0000-00-00'");

while ($riga = $pinscaduti->fetch_assoc()) {
    
    $id_pin = $riga["id"];
    $oldpin = $riga["pin"];
    $newpin = '55' . substr($oldpin, 2);
    $conn2->query("UPDATE visual_phonebook SET pin = '".$newpin."' WHERE id ='".$id_pin."'");

}

$conn2->close(); 

?>