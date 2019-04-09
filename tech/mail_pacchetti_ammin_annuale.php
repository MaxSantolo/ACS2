<?php

include 'connect_prod.php';
require 'class/PHPMailerAutoload.php';

$oggetto = "PACCHETTI DOPPIO ANNO FISCALE";

// pacchetti non scaduti su due anni fiscali, mail automatica ultimo giorno dell'anno

$sql_scadenza = "SELECT *, book_account.id, book_account.email, importo_utilizzo * restante as valore_rimanente FROM pacchetti_scadenza, book_account WHERE restante > 0 AND data_fine >= curdate() AND id_account = book_account.id ORDER BY data_fine ASC";
$resultscadenza = $conn_prod_booking->query($sql_scadenza); 

$intestazione = "<!-- <table style=\"margin-left:auto; margin-right:auto; border: 3px solid #015d6e;border-radius:20px;-moz-border-radius:20px;-webkit-border-radius:20px\"> -->"
        . "     <tbody>"
        . "     <table style=\"font-family:Verdana;font-size:14px;border: 3px solid #ff7700;color:black;background:#ffffff;opacity:0.85;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;border-spacing: 10px;border-collapse: separate;\">"
        . "     <TR style=\"background:#eeeeee;font-weight: bold\"><TH>Codice</TH><TH>Cliente</TH><TH style=\"text-align:center\">Quantit&agrave;</TH><TH style=\"text-align:center\">Usi</TH><TH style=\"text-align:center\">Restanti</TH><TH style=\"text-align:center\">Tipo di pacchetto</TH><TH style=\"text-align:center\">Data di scadenza</TH><TH>Email</TH><TH>Valore rimanente</TH>";

while($row = $resultscadenza->fetch_assoc()) {

$corpo = $corpo . "<TR style=\"border-bottom: 1px solid #ff7700;\"><TD>".$row['codice']."</TD><TD>".$row['nome_cliente']."</TD><TD style=\"text-align:center\">".$row['quantita']."</TD><TD style=\"text-align:center\">".$row['usi']."</TD><TD style=\"text-align:center;font-weight:bold\">".$row['restante']."</TD><TD style=\"text-align:center\">".$row['tipo_pacchetto']."</TD><TD style=\"text-align:center;font-weight:bold\">".date('d/m/Y', strtotime($row['data_fine']))."</TD><TD>".$row['email']."</td><td>€ ".$row['valore_rimanente']."</TD>";

$corpodeltestotxt = "Il messaggio e' formattato in HTML, attivare tale modalita'.";
}


$testo_email = $intestazione . $corpo . "</TR></tbody></table></table>";

//echo($testo_email);

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
$mail->FromName = "Pacchetti doppio anno fiscale - Notifica dalla Intranet";
$mail->AddReplyTo("info@pickcenter.com", "Informazioni");
$mail->AddAddress("amministrazione@pickcenter.com","APC");
//$mail->AddAddress("bucci@pickcenter.com","MB");
//$mail->AddAddress("segreterie@pickcenter.com","SPC");
//$mail->AddAddress("roberta@pickcenter.com","RG");
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