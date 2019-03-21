<?php

include 'connect_prod.php';
require 'class/PHPMailerAutoload.php';

$oggetto = "PACCHETTI IN SCADENZA";

// pacchetti in scadenza per numero di usi

$sql_scadenza = "SELECT *, book_account.id, book_account.email FROM pacchetti_scadenza, book_account WHERE restante = 1 AND data_fine > CURDATE() AND id_account = book_account.id ORDER BY restante ASC";
$resultscadenza = $conn_prod_booking->query($sql_scadenza); 

$intestazione = "<h3 style=\"text-align:center\">PACCHETTI IN SCADENZA: 1 UTILIZZO RESIDUO<h3><!-- <table style=\"margin-left:auto; margin-right:auto; border: 3px solid #015d6e;border-radius:20px;-moz-border-radius:20px;-webkit-border-radius:20px\"> -->"
        . "     <tbody>"
        . "     <table style=\"font-family:Verdana;font-size:14px;border: 3px solid #ff7700;color:black;background:#ffffff;opacity:0.85;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;border-spacing: 10px;border-collapse: separate;\">"
        . "     <TR style=\"background:#eeeeee;font-weight: bold\"><TH>Codice</TH><TH>Cliente</TH><TH style=\"text-align:center\">Quantit&agrave;</TH><TH style=\"text-align:center\">Usi</TH><TH style=\"text-align:center\">Restanti</TH><TH style=\"text-align:center\">Tipo di pacchetto</TH><TH style=\"text-align:center\">Data di scadenza</TH><TH>Email</TH>";

while($row = $resultscadenza->fetch_assoc()) {

$corpo = $corpo . "<TR style=\"border-bottom: 1px solid #ff7700;\"><TD>".$row['codice']."</TD><TD>".$row['nome_cliente']."</TD><TD style=\"text-align:center\">".$row['quantita']."</TD><TD style=\"text-align:center\">".$row['usi']."</TD><TD style=\"text-align:center;font-weight:bold\">".$row['restante']."</TD><TD style=\"text-align:center\">".$row['tipo_pacchetto']."</TD><TD style=\"text-align:center\">".date('d/m/Y', strtotime($row['data_fine']))."</TD><TD>".$row['email']."</td>";

$corpodeltestotxt = "Il messaggio e' formattato in HTML, attivare tale modalita'.";
}

//------------------------------------------------------------------------------------------------------------------------------------

//pacchetti in scadenza per data

$sql_scadenza2 = "SELECT *, book_account.id, book_account.email FROM pacchetti_scadenza, book_account WHERE (data_fine between curdate() and (curdate() + interval 28 day)) AND data_fine > CURDATE() AND id_account = book_account.id and restante >1 ORDER BY data_fine ASC";
$resultscadenza2 = $conn_prod_booking->query($sql_scadenza2); 

$intestazione2 = "<h3 style=\"text-align:center\">PACCHETTI IN SCADENZA: SCADE ENTRO 4 SETTIMANE<h3><!-- <table style=\"margin-left:auto; margin-right:auto; border: 3px solid #015d6e;border-radius:20px;-moz-border-radius:20px;-webkit-border-radius:20px\"> -->"
        . "     <tbody>"
        . "     <table style=\"font-family:Verdana;font-size:14px;border: 3px solid #ff7700;color:black;background:#ffffff;opacity:0.85;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;border-spacing: 10px;border-collapse: separate;\">"
        . "     <TR style=\"background:#eeeeee;font-weight: bold\"><TH>Codice</TH><TH>Cliente</TH><TH style=\"text-align:center\">Quantit&agrave;</TH><TH style=\"text-align:center\">Usi</TH><TH style=\"text-align:center\">Restanti</TH><TH style=\"text-align:center\">Tipo di pacchetto</TH><TH style=\"text-align:center\">Data di scadenza</TH><TH>Email</TH>";

while($row2 = $resultscadenza2->fetch_assoc()) {

$corpo2 = $corpo2 . "<TR style=\"border-bottom: 1px solid #ff7700;\"><TD>".$row2['codice']."</TD><TD>".$row2['nome_cliente']."</TD><TD style=\"text-align:center\">".$row2['quantita']."</TD><TD style=\"text-align:center\">".$row2['usi']."</TD><TD style=\"text-align:center\">".$row2['restante']."</TD><TD style=\"text-align:center\">".$row2['tipo_pacchetto']."</TD><TD style=\"text-align:center;font-weight:bold\">".date('d/m/Y', strtotime($row2['data_fine']))."</TD><TD>".$row2['email']."</td>";

$corpodeltestotxt = "Il messaggio e' formattato in HTML, attivare tale modalita'.";
}

//------------------------------------------------------------------------------------------------------------------------------------

$testo_email = $intestazione . $corpo . "</TR></tbody></table></table>" . $intestazione2 . $corpo2 . "</TR></tbody></table></table>";

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
$mail->FromName = "Pacchetti in scadenza - Notifica dalla Intranet";
$mail->AddReplyTo("info@pickcenter.com", "Informazioni");
$mail->AddAddress("max@swhub.io","MS");
$mail->AddAddress("bucci@pickcenter.com","MB");
$mail->AddAddress("segreterie@pickcenter.com","SPC");
$mail->AddAddress("roberta@pickcenter.com","RG");
$mail->AddAddress("cea@pickcenter.com","LC");
$mail->AddAddress("agnese@pickcenter.com","AM");

$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Subject = $oggetto;
$mail->Body    = $testo_email;
$mail->AltBody = $corpodeltestotxt;
$mail->send();

//------------------------------------------------------------------------------------------------------------------------------------

//pacchetti in scadenza per data

$sql_scaduti = "SELECT *, book_account.id, book_account.email FROM pacchetti_scadenza, book_account WHERE ((data_fine > CURDATE() and restante = 0) OR data_fine < curdate()) AND id_account = book_account.id  ORDER BY data_fine ASC";
$resultscaduti = $conn_prod_booking->query($sql_scaduti); 

if ($resultscaduti->num_rows > 0) {

$intestazione3 = "<h3 style=\"text-align:center\">PACCHETTI SCADUTI<h3><!-- <table style=\"margin-left:auto; margin-right:auto; border: 3px solid #015d6e;border-radius:20px;-moz-border-radius:20px;-webkit-border-radius:20px\"> -->"
        . "     <tbody>"
        . "     <table style=\"font-family:Verdana;font-size:14px;border: 3px solid #ff7700;color:black;background:#ffffff;opacity:0.85;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;border-spacing: 10px;border-collapse: separate;\">"
        . "     <TR style=\"background:#eeeeee;font-weight: bold\"><TH>Codice</TH><TH>Cliente</TH><TH style=\"text-align:center\">Quantit&agrave;</TH><TH style=\"text-align:center\">Usi</TH><TH style=\"text-align:center\">Restanti</TH><TH style=\"text-align:center\">Tipo di pacchetto</TH><TH style=\"text-align:center\">Data di scadenza</TH><TH>Email</TH>";

while($row3 = $resultscaduti->fetch_assoc()) {

$corpo3 = $corpo3 . "<TR style=\"border-bottom: 1px solid #ff7700;\"><TD>".$row3['codice']."</TD><TD>".$row3['nome_cliente']."</TD><TD style=\"text-align:center\">".$row3['quantita']."</TD><TD style=\"text-align:center\">".$row3['usi']."</TD><TD style=\"text-align:center\">".$row3['restante']."</TD><TD style=\"text-align:center\">".$row3['tipo_pacchetto']."</TD><TD style=\"text-align:center;font-weight:bold\">".date('d/m/Y', strtotime($row3['data_fine']))."</TD><TD>".$row3['email']."</td>";

$corpodeltestotxt = "Il messaggio e' formattato in HTML, attivare tale modalita'.";
}

//------------------------------------------------------------------------------------------------------------------------------------

$testo_email = $intestazione3 . $corpo3 . "</TR></tbody></table></table>";

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
$mail->FromName = "Pacchetti scaduti - Notifica dalla Intranet";
//$mail->AddReplyTo("info@pickcenter.com", "Informazioni");
$mail->AddAddress("max@swhub.io","MS");
//$mail->AddAddress("bucci@pickcenter.com","MB");
//$mail->AddAddress("segreterie@pickcenter.com","SPC");
//$mail->AddAddress("roberta@pickcenter.com","RG");
//$mail->AddAddress("cea@pickcenter.com","LC");
$mail->AddAddress("raffaella@pickcenter.com","RN");

$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Subject = $oggetto;
$mail->Body    = $testo_email;
$mail->AltBody = $corpodeltestotxt;
$mail->send();

}

$conn_prod_booking->close();


?>