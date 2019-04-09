<?php

date_default_timezone_set('Europe/Rome');

$servername_prod = '192.168.1.10';
$username_prod = 'root';
$password_prod = 'fm105pick';
$db_prod1 = 'booking';
$db_prod2 = 'intranet';
$db_prod3 = 'radius';
$db_prod4 = 'crm';
$db_prod5 = 'crm_punti';

// creo connessione
$conn_prod_booking = new mysqli($servername_prod,$username_prod,$password_prod,$db_prod1); //produzione.booking
$conn_prod_intranet = new mysqli($servername_prod,$username_prod,$password_prod,$db_prod2); //produzione.intranet
$conn_prod_radius = new mysqli($servername_prod,$username_prod,$password_prod,$db_prod3); //produzione.radius
$conn_prod_crm = new mysqli($servername_prod,$username_prod,$password_prod,$db_prod4); //produzione.crm
$conn_prod_punti = new mysqli($servername_prod,$username_prod,$password_prod,$db_prod5); //produzione.crm_punti


// controllo connessioni
if ($conn_prod_booking->connect_error) { die("Errore connessione Produzione, Booking: " . $conn->connect_error); } 
if ($conn_prod_intranet->connect_error) { die("Errore connessione Produzione, Intranet: " . $conn->connect_error); } 
if ($conn_prod_radius->connect_error) { die("Errore connessione Produzione, Radius: " . $conn->connect_error); } 
if ($conn_prod_crm->connect_error) { die("Errore connessione Produzione, Radius: " . $conn->connect_error); } 

?>