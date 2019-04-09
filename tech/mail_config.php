<?php
require 'class/PHPMailerAutoload.php';

$mail = new PHPMailer();

$mail->IsSMTP();   
$mail->CharSet="UTF-8"; // set mailer to use SMTP
$mail->Host = "10.20.20.227";  // specify main and backup server
$mail->SMTPAuth = false;     // turn on SMTP authentication
//$mail->Port = 587;
//$mail->Username = "info@pickcenter.com";  // SMTP username
//$mail->Password = "fm105pick"; // SMTP password

$mail->From = "info@bsidecoworking.it";
$mail->FromName = "BSide sistema notifica";

                 // name is optional
$mail->AddReplyTo("info@bsidecoworking.it", "Informazioni");

$mail->WordWrap = 50;                                 // set word wrap to 50 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
$mail->IsHTML(true);                                  // set email format to HTML



//if(!$mail->Send())
//{
//   echo "Non sono riuscito a mandare il messaggio.<BR>";
//   echo "Errore di invio: " . $mail->ErrorInfo;
   
//} else { $mail->ClearAllRecipients(); $mail->ClearAttachments(); }

?>