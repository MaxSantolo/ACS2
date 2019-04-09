<?php
   include('../tech/connect.php');
   session_start();
   
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = $conn->query("select * from acs_utenti where id_utente = '".$user_check."'");
   
   $row = $ses_sql->fetch_assoc();
   
   $login_session = $row['nome_azienda'];
   $tipo_utente = $row['specimen'];
   $pin_check = $row['pin'];
   $privilegi = $row['livello'];
   
   $usi_mr = 'illimitati';
   $copie = 'illimitati';
   $scadeil = '';
   $oreutil = '';
   $pren_mr = '';
   
   //cerco ore utilizzate e scadenza per pacchetti
   $oreutilq=$conn->query("SELECT ore_utilizzate, data_fine_pacchetto FROM acs_pacchetti WHERE codice = '".$pin_check."'");
   $risultati = $oreutilq->fetch_assoc();
   $oreutil = $risultati['ore_utilizzate'];
   $scadenza_pacchetto = $risultati['data_fine_pacchetto'];
   
   //cerco usi miniroom nel mese in corso
   $pren_mrq=$conn->query("SELECT data_inizio, COUNT(*) AS num_volte FROM acs_prenotazioni WHERE id_utente = '".$user_check."' AND MONTH(data_inizio) = MONTH(CURDATE())");
   $risultati2 = $pren_mrq->fetch_assoc();
   $pren_mr = $risultati2['num_volte'];
   
   
   switch ($tipo_utente) {
    case "RESIDENT":
        $copie = '30/mese';
        $usi_mr = $usi_mr . " (Utilizzate: ".$pren_mr.")";
        break;
    case "NOMAD":
        $copie= '30/mese';
        $usi_mr = $usi_mr . " (Utilizzate: ".$pren_mr.")";
        break;
    case "PACK100":
        $tipo_utente = "CARNET NOMAD 100 ore (Utilizzate: ".$oreutil." con scadenza: ".date('d/m/Y',strtotime($scadenza_pacchetto)).")";
        $usi_mr = "5/mese (Utilizzate: ".$pren_mr.")";
        $copie = 'nessuna inclusa';
        break;
    case "PACK50":
        $tipo_utente = "CARNET NOMAD 50 ore (Utilizzate: ".$oreutil." con scadenza: ".date('d/m/Y',strtotime($scadenza_pacchetto)).")";
        $usi_mr = "€ 5,00 / uso (Utilizzate: ".$pren_mr.")";
        $copie = 'nessuna inclusa';
        break;
    case "PACK10":
        $tipo_utente = "CARNET NOMAD 10 ore (Utilizzate: ".$oreutil." con scadenza: ".date('d/m/Y',strtotime($scadenza_pacchetto)).")";
        $usi_mr = '€ 5,00 / uso (Utilizzate: ".$pren_mr.")';
        $copie = 'nessuna inclusa';
        break;
       }

   if(!isset($_SESSION['login_user'])){
      header("location:index.php");
   }
?>