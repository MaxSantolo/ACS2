<?php
include ('tech/connect.php');
include ('tech/connect_prod.php');

if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['pin'])) {
    $id = $_GET['id'];
    $pincompleto = $_GET['auth'].$_GET['pin'];
    $pin = $_GET['pin'];
    $email = $_GET['mail'];
    
    
    $conn->query("DELETE FROM acs_pacchetti WHERE id_pacchetto = '".$id."'");
    $conn->query("DELETE FROM acs_utenti WHERE pin = '".$pin."'");
    $conn2->query("DELETE FROM visual_phonebook WHERE pin LIKE '".$pincompleto."'");
    $conn_prod_radius->query("DELETE FROM radcheck WHERE username LIKE '".$email."'");
    $conn_prod_radius->query("DELETE FROM radreply WHERE username LIKE '".$email."'");
    
    header("Location:".$_SERVER['HTTP_REFERER'].""); }
else { header("Location:".$_SERVER['HTTP_REFERER'].""); } 

$conn->close();
$conn2->close();
?>