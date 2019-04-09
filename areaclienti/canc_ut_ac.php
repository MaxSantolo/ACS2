<?php
include ('../tech/connect.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $conn->query("UPDATE acs_utenti SET livello='DISABILITATO' WHERE id_utente = '".$id."'");
    
    
    header("Location: elenco_utenti_ac.php");}
else {
    header("Location: elenco_utenti_ac.php"); } 

$conn->close();
?>