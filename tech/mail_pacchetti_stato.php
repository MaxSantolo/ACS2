<?php


include 'connect.php';
require 'class/PHPMailerAutoload.php';
// mando le email a tutti quelli che stanno scadendo

$sql_scadenza = "SELECT * FROM acs_pacchetti WHERE cestinato != '1' && (((data_fine_pacchetto - curdate())/(data_fine_pacchetto-data_inizio_pacchetto))< 0.2 || ore_utilizzate / (ore_totali_pacchetto + delta_ore) > 0.8)";
$resultscadenza = $conn->query($sql_scadenza); 
$testo_messaggio = "Il suo contratto &egrave; <strong>in scadenza</strong>:<BR>";

while($row = $resultscadenza->fetch_assoc()) {

$oggetto = "STATO DEL CONTRATTO ".$row['id_pacchetto']." intestato a: ".$row['azienda']."";
$destinatario = $row['email_notifiche'];
$nome = $row['azienda'];

$dal = date("d-m-Y", strtotime($row['data_inizio_pacchetto']));
$al = date("d-m-Y", strtotime($row['data_fine_pacchetto']));


$corpodeltesto = "<table style=\"margin: 0pt auto; border: 3px solid #015d6e; width: 640px;border-radius:20px;-moz-border-radius:20px;-webkit-border-radius:20px\">"
        . "     <tbody>"
        . "     <tr>"
        . "         <td style=\"padding: 10px;\" valign=\"middle\">"
        . "             <table cellspacing=\"2\" cellpadding=\"0\"><tbody><tr><td><a href=\"http://www.bsidecoworking.it\"><img style=\"border: 0;height:150px\" src=\"http://www.pickcenter.it/wp-content/uploads/2017/02/logoBside.jpg\" alt=\"BSide\" /></a></td></tr></tbody>"
        . "             </table>"
        . "         </td>"
        . "         <td style=\"padding: 10px; text-align: right; font-family: verdana; font-size: 11px;\"><span>Sede legale:</span><br /> via Attilio Regolo, 19 Roma, 00192<br />Tel. 06 3280 3408"
        . "                  <div style=\"margin: 4px 0;\"><a href=\"https://twitter.com/Bsidecoworking\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/twitter.png\" alt=\"Twitter\" /></a> <a href=\"https://www.facebook.com/BSIDEcoworking/\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/facebook.png\" alt=\"Facebook\" /></a> <a href=\"https://www.linkedin.com/company/bside-coworking?report%2Esuccess=jhKVKsuT7eDCGn2lUldR1hK3GiS7tOcLrxZAuxKbGmSo00P5rvmFTBjodNS88dJL7w4F6e3Gbw58ZHH\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/linkedin.png\" alt=\"LinkedIn\" /></a></div>"
        . "                  </td></tr>"
        . "      <TD style=\"padding: 30px; font-family: verdana; font-size: 13px;\" colspan=\"2\">".$testo_messaggio.""
        . "      <BR>"
        . "     <table style=\"font-family:Verdana;font-size:14px;color:black;background:#eeeeee;opacity:0.85;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;border-spacing: 10px;border-collapse: separate;\">"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Nome/Azienda: </strong></TD><TD>".$row['azienda']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Codice (6 cifre): </strong></TD><TD>".$row['codice']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Tipo pacchetto: </strong></TD><TD>".$row['tipo']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Data inizio: </strong></TD><TD>".$dal."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Scade il: </strong></TD><TD>".$al."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Ore utilizzate: </strong></TD><TD>".$row['ore_utilizzate']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Ore totali: </strong></TD><TD>".$row['ore_totali_pacchetto']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Ore gratuite: </strong></TD><TD>".$row['delta_ore']."</TD></TR></table><BR>"
        . "     <span style=\"color: #015d6e;\"><a href=\"mailto:info@bsidecoworking.it\" target=_blank><span style=\"color: #ff6600;\">Per qualsiasi altra informazione siamo a tua disposizione dal luned� al venerd� dalle ore 8.30 alle ore 18.30</A></span></span><br />"
        . "     <br /> Cordiali saluti,<br /><br /><B><font color=\"#ff6600\">Lo Staff</B></font><br /><br /> <img style=\"height: 30px;\" src=\"http://www.pickcenter.it/wp-content/uploads/2017/02/logoBside.jpg\"/>"
        . "     </TD></table> ";
$corpodeltestotxt = "Il messaggio &egrave; formattato in HTML, attivare tale modalit&agrave;.";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet="UTF-8";
//$mail->SMTPSecure = 'tls';
$mail->Host = '10.20.20.227';
//$mail->Port = 587;
//$mail->Username = 'info@pickcenter.com';
//$mail->Password = 'fm105pick';
$mail->SMTPAuth = false;
$mail->From = "info@bsidecoworking.it";
$mail->FromName = "BSide sistema notifica";
$mail->AddAddress($destinatario,$nome);
                 // name is optional
$mail->AddReplyTo("info@bsidecoworking.it", "Informazioni");
$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Subject = $oggetto;
$mail->Body    = $corpodeltesto;
$mail->AltBody = $corpodeltestotxt;
$mail->send();

}


// mando le email a tutti i normali

$sql_normali = "SELECT * FROM acs_pacchetti WHERE cestinato != '1' && NOT (((data_fine_pacchetto - curdate())/(data_fine_pacchetto-data_inizio_pacchetto))< 0.2 || ore_utilizzate / (ore_totali_pacchetto + delta_ore) > 0.8)";
$result = $conn->query($sql_normali); 
$testo_messaggio2 = "Questi sono i dettagli del suo contratto:<BR>";

while($row = $result->fetch_assoc()) {

$oggetto = "STATO DEL CONTRATTO ".$row['id_pacchetto']." intestato a: ".$row['azienda']."";
$destinatario = $row['email_notifiche'];
$nome = $row['azienda'];

$dal = date("d-m-Y", strtotime($row['data_inizio_pacchetto']));
$al = date("d-m-Y", strtotime($row['data_fine_pacchetto']));

$corpodeltesto = "<table style=\"margin: 0pt auto; border: 3px solid #015d6e; width: 640px;border-radius:20px;-moz-border-radius:20px;-webkit-border-radius:20px\">"
        . "     <tbody>"
        . "     <tr>"
        . "         <td style=\"padding: 10px;\" valign=\"middle\">"
        . "             <table cellspacing=\"2\" cellpadding=\"0\"><tbody><tr><td><a href=\"http://www.bsidecoworking.it\"><img style=\"border: 0;height:150px\" src=\"http://www.pickcenter.it/wp-content/uploads/2017/02/logoBside.jpg\" alt=\"BSide\" /></a></td></tr></tbody>"
        . "             </table>"
        . "         </td>"
        . "         <td style=\"padding: 10px; text-align: right; font-family: verdana; font-size: 11px;\"><span>Sede legale:</span><br /> via Attilio Regolo, 19 Roma, 00192<br />Tel. 06 3280 3408"
        . "                  <div style=\"margin: 4px 0;\"><a href=\"https://twitter.com/Bsidecoworking\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/twitter.png\" alt=\"Twitter\" /></a> <a href=\"https://www.facebook.com/BSIDEcoworking/\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/facebook.png\" alt=\"Facebook\" /></a> <a href=\"https://www.linkedin.com/company/bside-coworking?report%2Esuccess=jhKVKsuT7eDCGn2lUldR1hK3GiS7tOcLrxZAuxKbGmSo00P5rvmFTBjodNS88dJL7w4F6e3Gbw58ZHH\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/linkedin.png\" alt=\"LinkedIn\" /></a></div>"
        . "                  </td></tr>"
        . "      <TD style=\"padding: 30px; font-family: verdana; font-size: 13px;\" colspan=\"2\">".$testo_messaggio2.""
        . "      <BR>"
        . "     <table style=\"font-family:Verdana;font-size:14px;color:black;background:#eeeeee;opacity:0.85;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;border-spacing: 10px;border-collapse: separate;\">"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Nome/Azienda: </strong></TD><TD>".$row['azienda']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Codice (6 cifre): </strong></TD><TD>".$row['codice']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Tipo pacchetto: </strong></TD><TD>".$row['tipo']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Data inizio: </strong></TD><TD>".$dal."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Scade il: </strong></TD><TD>".$al."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Ore utilizzate: </strong></TD><TD>".$row['ore_utilizzate']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Ore totali: </strong></TD><TD>".$row['ore_totali_pacchetto']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Ore gratuite: </strong></TD><TD>".$row['delta_ore']."</TD></TR></table><BR>"
        . "     <span style=\"color: #015d6e;\"><a href=\"mailto:info@bsidecoworking.it\" target=_blank><span style=\"color: #ff6600;\">Per qualsiasi altra informazione siamo a tua disposizione dal luned&igrave; al venerd&igrave; dalle ore 8.30 alle ore 18.30</A></span></span><br />"
        . "     <br /> Cordiali saluti,<br /><br /><B><font color=\"#ff6600\">Lo Staff</B></font><br /><br /> <img style=\"height: 30px;\" src=\"http://www.pickcenter.it/wp-content/uploads/2017/02/logoBside.jpg\"/>"
        . "     </TD></table> ";
$corpodeltestotxt = "Il messaggio &egrave; formattato in HTML, attivare tale modalit&agrave;.";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet="UTF-8";
//$mail->SMTPSecure = 'tls';
$mail->Host = '10.20.20.227';
//$mail->Port = 587;
//$mail->Username = 'info@pickcenter.com';
//$mail->Password = 'fm105pick';
$mail->SMTPAuth = false;
$mail->From = "info@bsidecoworking.it";
$mail->FromName = "BSide sistema notifica";
$mail->AddAddress($destinatario,$nome);
                 // name is optional
$mail->AddReplyTo("info@bsidecoworking.it", "Informazioni");
$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Subject = $oggetto;
$mail->Body    = $corpodeltesto;
$mail->AltBody = $corpodeltestotxt;
$mail->send();


}

//notifico stato dei pacchetti a tutti

$sql_tutti = "SELECT * FROM acs_pacchetti WHERE cestinato != '1'";
$resultutti = $conn->query($sql_tutti); 

$oggetto = "STATO DEI CONTRATTI BSIDE AGGIORNATO AL ".date("d-m-Y")."";
// $destinatario = "max@swhub.io"; // cea@pickcenter.com, flavia@bsidecoworking.it, marta@bsidecoworking.it, roberta@pickcenter.com";

$intestazione = "<table style=\"margin: 0pt auto; border: 3px solid #015d6e;border-radius:20px;-moz-border-radius:20px;-webkit-border-radius:20px\">"
        . "     <tbody>"
        . "     <tr>"
        . "         <td style=\"padding: 10px;\" valign=\"middle\">"
        . "             <table cellspacing=\"2\" cellpadding=\"0\"><tbody><tr><td><a href=\"http://www.bsidecoworking.it\"><img style=\"border: 0;height:150px\" src=\"http://www.pickcenter.it/wp-content/uploads/2017/02/logoBside.jpg\" alt=\"BSide\" /></a></td></tr></tbody>"
        . "             </table>"
        . "         </td>"
        . "         <td style=\"padding: 10px; text-align: right; font-family: verdana; font-size: 11px;\"><span>Sede legale:</span><br /> via Attilio Regolo, 19 Roma, 00192<br />Tel. 06 3280 3408"
        . "                  <div style=\"margin: 4px 0;\"><a href=\"https://twitter.com/Bsidecoworking\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/twitter.png\" alt=\"Twitter\" /></a> <a href=\"https://www.facebook.com/BSIDEcoworking/\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/facebook.png\" alt=\"Facebook\" /></a> <a href=\"https://www.linkedin.com/company/bside-coworking?report%2Esuccess=jhKVKsuT7eDCGn2lUldR1hK3GiS7tOcLrxZAuxKbGmSo00P5rvmFTBjodNS88dJL7w4F6e3Gbw58ZHH\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/linkedin.png\" alt=\"LinkedIn\" /></a></div>"
        . "                  </td></tr>"
        . "      <TD style=\"padding: 30px; font-family: verdana; font-size: 13px;\" colspan=\"2\">"
        . "      <BR>"
        . "     <table style=\"font-family:Verdana;font-size:14px;color:black;background:#eeeeee;opacity:0.85;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;border-spacing: 10px;border-collapse: separate;\">"
        . "     <TR style=\"background:#cccccc;font-weight: bold\"><TH>Nome/Azienda</TH><TH>Codice</TH><TH>Tipo contratto</TH><TH>Data inizio</TH><TH>Scade il</TH><TH>Ore utilizzate</TH><TH>Ore totali</TH><TH>Ore gratuite</TH>";


while($row = $resultutti->fetch_assoc()) {

$nome = $row['azienda'];
$dal = date("d-m-Y", strtotime($row['data_inizio_pacchetto']));
$al = date("d-m-Y", strtotime($row['data_fine_pacchetto']));

$corpo = $corpo . "<TR><TD>".$row['azienda']."</TD><TD>".$row['codice']."</TD><TD>".$row['tipo']."</TD><TD>".$dal."</TD><TD>".$al."</TD><TD style=\"text-align: right\">".$row['ore_utilizzate']."</TD><TD style=\"text-align: right\">".$row['ore_totali_pacchetto']."</TD><TD style=\"text-align: right\">".$row['delta_ore']."";

$corpodeltestotxt = "Il messaggio e' formattato in HTML, attivare tale modalita'.";
}

$testo_email = $intestazione . $corpo . "</TD></TR></table><BR><P STYLE=\"font-family: verdana; font-size: 14px;text-align: center;\"><A HREF=\"http://192.168.1.40/areaclienti/edit_pacchetto.php\">Cliccate qui per raggiungere la pagina di gestione dei pacchetti.</A></P></table>";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet="UTF-8";
//$mail->SMTPSecure = 'tls';
$mail->Host = '10.20.20.227';
//$mail->Port = 587;
//$mail->Username = 'info@pickcenter.com';
//$mail->Password = 'fm105pick';
$mail->SMTPAuth = false;
$mail->From = "info@bsidecoworking.it";
$mail->FromName = "BSide sistema notifica";
$mail->AddReplyTo("info@bsidecoworking.it", "Informazioni");
$mail->AddAddress("max@swhub.io","MS");
//$mail->AddAddress("marta@bsidecoworking.it","MC");
//$mail->AddAddress("flavia@bsidecoworking.it","FS");
$mail->AddAddress("roberta@pickcenter.com","RG");
$mail->AddAddress("cea@pickcenter.com","LC");
$mail->AddAddress("raffaella@pickcenter.com","RN");

$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Subject = $oggetto;
$mail->Body    = $testo_email;
$mail->AltBody = $corpodeltestotxt;
$mail->send();

$conn->close();
?>