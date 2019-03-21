<?php


include 'connect_prod.php';
require 'class/PHPMailerAutoload.php';

// mando le email a tutti quelli che stanno scadendo

$sql_selmail = "select email from book_account where data_inserimento between curdate() - INTERVAL 30 DAY and curdate()";
$resultmail = $conn_prod_booking->query($sql_selmail); 

$oggi = date('d/m/Y');
$giornifa = date('d/m/Y', time() - 30 * 86400);
    
    
$oggetto = "EMAIL DA AGGIUNGERE A ADWORDS (dal ".$giornifa." al ".$oggi.")";

while($row = $resultmail->fetch_assoc()) {


    
$corpo = $corpo . $row['email'] . "<BR>";

$corpodeltestotxt = "Il messaggio e' formattato in HTML, attivare tale modalita'.";
}




$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet="UTF-8";
//$mail->SMTPSecure = 'tls';
$mail->Host = '10.20.20.227';
//$mail->Port = 587;
//$mail->Username = 'info@pickcenter.com';
//$mail->Password = 'fm105pick';
$mail->SMTPAuth = false;
$mail->From = "info@pickcenter.com";
$mail->FromName = "Pick Center - email per ADWords";
$mail->AddReplyTo("info@pickcenter.com", "Informazioni");
$mail->AddAddress("max@swhub.io","MS");
$mail->AddAddress("francesca@pickcenter.com","FD");
////$mail->AddAddress("maura@pickcenter.com","MV");
////$mail->AddAddress("roberta@pickcenter.com","RG");
//$mail->AddAddress("cea@pickcenter.com","LC");
$mail->AddAddress("roberto.ghislandi@gmail.com","RG");
//$mail->AddAddress("raffaella@pickcenter.com","RN");

$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Subject = $oggetto;
$mail->Body    = $corpo;
$mail->AltBody = $corpodeltestotxt;
$mail->send();

$conn_prod_booking->close();


?>