<?php


include 'connect.php';
require 'class/PHPMailerAutoload.php';
// mando le email a tutti quelli che stanno scadendo

$sql_scadenza = "SELECT * FROM acs_pacchetti WHERE cestinato != '1' AND ( data_fine_pacchetto < curdate() OR ( ore_utilizzate >= (ore_totali_pacchetto + delta_ore) AND ore_totali_pacchetto > 0) )";
$resultscadenza = $conn->query($sql_scadenza); 
$testo_messaggio = "Il suo contratto &egrave; <strong>SCADUTO</strong>:<BR>";

while($row = $resultscadenza->fetch_assoc()) {

$oggetto = "STATO DEL PACCHETTO ".$row['id_pacchetto']." intestato a: ".$row['azienda']."";
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
$mail->AddAddress('max@swhub.io','MS');
$mail->AddReplyTo("info@bsidecoworking.it", "Informazioni");
$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Subject = $oggetto;
$mail->Body    = $corpodeltesto;
$mail->AltBody = $corpodeltestotxt;
$mail->send();

}


?>