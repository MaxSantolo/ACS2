<?php
include ('../tech/connect.php');
include ('session.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $conn->query("DELETE FROM acs_pacchetti_scaduti WHERE id = '".$id."'");
    
    header("Location: elenco_pacchetti_scaduti.php");}
else {
    header("Location: elenco_pacchetti_scaduti.php"); } 

$conn->close();
?>