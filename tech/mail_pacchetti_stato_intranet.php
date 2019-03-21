<?php


include 'connect_prod.php';
require 'class/PHPMailerAutoload.php';
// mando le email a tutti quelli che stanno scadendo

$sql_scadenza = "SELECT *, book_account.id, book_account.email FROM pacchetti_scadenza, book_account WHERE restante = 1 AND data_fine > CURDATE() AND id_account = book_account.id ORDER BY restante ASC";
$resultscadenza = $conn_prod_booking->query($sql_scadenza); 


while($row = $resultscadenza->fetch_assoc()) {
    
    

$oggetto = "STATO DEL PACCHETTO ".$row['codice']." intestato a: ".$row['nome_cliente']."";
$destinatario = $row['email'];
$nome = $row['nome_cliente'];
$testo_messaggio = "Gentile <strong>".$nome."</strong>,<BR><BR>il suo pacchetto &egrave; <strong>in scadenza</strong>:<BR>";

$fine = date("d-m-Y", strtotime($row['data_fine']));


$corpodeltesto = "<table style=\"margin: 0pt auto; border: 3px solid #015d6e; width: 640px;border-radius:20px;-moz-border-radius:20px;-webkit-border-radius:20px\">"
        . "     <tbody>"
        . "     <!-- <tr>"
        . "          <td style=\"padding: 10px;\" valign=\"middle\">"
        . "              <table cellspacing=\"2\" cellpadding=\"0\"><tbody><tr><td><a href=\"https://www.pickcenter.it\"><img style=\"border: 0;\" src=\"https://www.pickcenter.it/wp-content/uploads/2017/05/Logoweb-oldfont-nero-bianco.png\" alt=\"Pick Center Logo\" /></a></td></tr></tbody>"
        . "             </table> "
        . "         </td> "
        . "         <td style=\"padding: 10px; text-align: right; font-family: verdana; font-size: 11px;\"><span>Sede legale:</span><br /> via Attilio Regolo, 19 Roma, 00192<br />Tel. 06 3280 3408"
        . "                  <div style=\"margin: 4px 0;\"><a href=\"https://twitter.com/pickcenter\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/twitter.png\" alt=\"Twitter\" /></a> <a href=\"https://www.facebook.com/pickcenter.uffici.saleriunioni\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/facebook.png\" alt=\"Facebook\" /></a> <a href=\"http://www.linkedin.com/company/pick-center?trk=fc_badge\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/linkedin.png\" alt=\"LinkedIn\" /></a></div>"
        . "                  </td></tr> -->"
        . "      <TD style=\"padding: 30px; font-family: verdana; font-size: 13px;\" colspan=\"2\">".$testo_messaggio.""
        . "      <BR>"
        . "     <table style=\"font-family:Verdana;font-size:14px;color:black;background:#eeeeee;opacity:0.85;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;border-spacing: 10px;border-collapse: separate;\">"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Codice pacchetto: </strong></TD><TD>".$row['codice']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Quantità: </strong></TD><TD>".$row['quantita']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Usi effettuati: </strong></TD><TD>".$row['usi']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Usi rimanenti: </strong></TD><TD><strong>".$row['restante']."</strong></TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Tipo pacchetto: </strong></TD><TD>".$row['tipo_pacchetto']."</TD></TR>"
        . "     <TR><TD style=\"background:#cccccc;\"><strong>Data scadenza: </strong></TD><TD><strong>".$fine."</strong></TD></TR>"
        . "     <tr><td colspan=2><span style=\"color: #015d6e;\"><a href=\"mailto:info@pickcenter.com\" target=_blank><span style=\"color: #ff6600;\">Per qualsiasi altra informazione siamo a tua disposizione dal luned&igrave; al venerd&igrave; dalle ore 8.30 alle ore 18.30</A></span></span></td></tr>"
        . "     <tr><td colspan=2><br /> Cordiali saluti,<br /><br /><B><font color=\"#ff6600\">Lo Staff</B></td></tr>"
        . "     </TD></table> ";
$corpodeltestotxt = "Il messaggio &egrave; formattato in HTML, attivare tale modalit&agrave;.";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet="UTF-8";
//$mail->SMTPSecure = 'tls';
//$mail->SMTPSecure = 'tls';
$mail->Host = '10.20.20.227';
//$mail->Port = 587;
//$mail->Username = 'info@pickcenter.com';
//$mail->Password = 'fm105pick';
$mail->SMTPAuth = false;
$mail->From = "info@pickcenter.com";
$mail->FromName = "Pick Center sistema di notifica";
$mail->AddAddress($destinatario,$nome); //$destinatario,$nome
                 // name is optional
$mail->AddReplyTo("info@pickcenter.com", "Informazioni");
$mail->WordWrap = 50;
$mail->IsHTML(true);
$mail->Subject = $oggetto;
$mail->Body    = $corpodeltesto;
$mail->AltBody = $corpodeltestotxt;
$mail->send();

}

$conn_prod_booking->close()

?>