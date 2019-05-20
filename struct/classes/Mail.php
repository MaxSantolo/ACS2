<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 05/12/2018
 * Time: 16:38
 *
 * Classe semplificativa del modulo PHPMailer che estende.
 *
 *
 */

class Mail extends PHPMailer
{
    //funzione eseguita sul comando NEW con i parametri obbligatori necessari all'invio  di un nuovo messaggio
    public function __construct($exceptions=true)
    {
        $this->CharSet= 'UTF-8';
        $this->Host = 'smtp.gmail.com';
        $this->SMTPAuth = true;
        $this->Port = 587;
        $this->SMTPSecure = 'tls';
        $this->Username = "info@pickcenter.com";
        $this->Password = "fm105pick";
        $this->isSMTP();
        parent::__construct($exceptions);
    }

    //invia una email, il parametro $ccarray è l'array delle carbon copies dell'email
    public function sendEmail($to,$toname,$from,$fromname,$subject,$body,$ccarray = NULL) {

        $this->From = $from;
        $this->FromName = $fromname;
        $this->AddAddress($to,$toname);

        if (!is_null($ccarray)) {
            foreach ($ccarray as $cc) {
                $this->AddCC($cc);
            }
        }

        $this->AddReplyTo("info@pickcenter.com", "Informazioni");
        $this->WordWrap = 50;
        $this->IsHTML(true);
        $this->Subject = $subject;
        $this->Body    = $body;
        $this->AltBody = 'Il messaggio è in formato HTML si prega di attivare questa modalità';
        return $this->send();

    }
}