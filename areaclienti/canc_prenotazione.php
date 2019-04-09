<?php
include ('tech/connect.php');
include ('session.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $conn->query("DELETE FROM acs_prenotazioni WHERE id_prenotazione = '".$id."'");
    
    header("Location: crea_calendario.php");}
else {
    header("Location: cra_calendario.php"); } 

$conn->close();
?>