<?php


include 'connect_prod.php';
require 'class/PHPMailerAutoload.php';

// mando le email a tutti quelli che stanno scadendo

$sql_scadenza = "SELECT * FROM annullate_last4days";
$resultscadenza = $conn_prod_booking->query($sql_scadenza); 

$oggi = date('d/m/Y');
$giornifa = date('d/m/Y', time() - 4 * 86400);
    
    
$oggetto = "PRENOTAZIONI ANNULLATE dal ".$giornifa." al ".$oggi;


$intestazione = "<!-- <table style=\"margin: 0pt auto; border: 3px solid #015d6e;border-radius:20px;-moz-border-radius:20px;-webkit-border-radius:20px\"> -->"
        . "     <tbody>"
        . "     <table style=\"font-family:Verdana;font-size:14px;border: 3px solid #ff7700;color:black;background:#ffffff;opacity:0.85;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;border-spacing: 10px;border-collapse: separate;\">"
        . "     <TR style=\"background:#eeeeee;font-weight: bold\"><TH>Data</TH><TH>Data Inserimento</TH><TH>Data Annullamento</TH><TH>Ora Inizio</TH><TH>Ora Fine</TH><TH>Allestimento</TH><TH>Nome</TH><TH>Tipo Risorsa</TH><TH>Sede</TH>";


while($row = $resultscadenza->fetch_assoc()) {

switch ($row[sede]) {
    case 1:
        $sede = 'EUR';
        break;
    case 2:
        $sede = 'BOE';
        break;
    case 3:
        $sede = 'REG';
        break;    
    }
    
$corpo = $corpo . "<TR style=\"border-bottom: 1px solid #ff7700;\"><TD>". date('d/m/Y', strtotime($row['data']))."</TD><TD>".date( 'd/m/Y', strtotime($row['data_inserimento']))."</TD><TD><strong>".date( 'd/m/Y', strtotime($row['data_annullamento']))."</strong></TD><TD>".$row['ora_inizio']."</TD><TD>".$row['ora_fine']."</TD><TD>".$row['allestimento']."</TD><TD>".$row['nome']."</TD><TD>".$row['tipo_risorsa']."</td><td>".$sede."</td>";

$corpodeltestotxt = "Il messaggio e' formattato in HTML, attivare tale modalita'.";
}

$testo_email = $intestazione . $corpo . "</TR></tbody></table></table>";



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
$mail->FromName = "Notifica Intranet";
$mail->AddReplyTo("info@pickcenter.com", "Informazioni");
$mail->AddAddress("max@swhub.io","MS");
$mail->AddAddress("francesca@pickcenter.com","FD");
$mail->AddAddress("maura@pickcenter.com","MV");
$mail->AddAddress("roberta@pickcenter.com","RG");
//$mail->AddAddress("cea@pickcenter.com","LC");
//$mail->AddAddress("raffaella@pickcenter.com","RN");

$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Subject = $oggetto;
$mail->Body    = $testo_email;
$mail->AltBody = $corpodeltestotxt;
$mail->send();

$conn_prod_booking->close();


?>