<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 28/11/2018
 * Time: 10:01
 *
 * Cron eseguito ogni 10 minuti per l'attivazione e disattivazione degli accessi temporanei agli uffici.
 *
 *
 *
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PickLog.php';

$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');

//crons per aggiornamento PIN day/sale

//leggo tutte gli accessi temporanei di oggi

$sql = "SELECT * FROM checkinday_off_v WHERE date = curdate()";
$checkins = $conn->query($sql);



$tsnow = strtotime(ACSBase::Now());
$ignore_create_array = array('completato','attivato');

$plog = new PickLog();

//se gli accessi temporanei non sono vuoti
if ($checkins->num_rows>0) {
    while ($ci = $checkins->fetch_assoc()) {

        $sts = strtotime($ci['sts']);
        $ets = strtotime($ci['ets']);

        //se il timestamp di adesso (now) è maggiore dell'inizio e lo stato dell'accesso non è completato o già attivo
        //l'accesso viene attivato e lo stato modificato in attivato + logs

        if ($tsnow >= $sts && !in_array($ci['status'],$ignore_create_array)) {

            $db::managePairing($conn,$ci['phoneb_id'],$ci['door_id'],'new',$ci['id']);

            //.logs
            $content = $plog->sql2Text($sql) . PHP_EOL . "Codice temporaneo " . $ci['phoneb_id'] . "->" . $ci['door_id'] . " creato." . PHP_EOL . "Numero di righe: " . $conn->affected_rows;
            $params = array(
                'app' => 'ACS',
                'action' => 'COD_TEMP_ON',
                'content' => $content,
                'user' => $_SESSION['user_name'],
                'description' => 'Attivazione codice temporaneo',
                'origin' => 'PBX.asteriskcdrdb.checkinday_off_v',
                'destination' => 'PBX.asteriskcdrdb.acs_phoneb_doors',
            );
            $plog->sendLog($params);

        } //createPair

        //se il timestamp è oltre la file dell'accesso temporaneo e l'accesso è attivo lo disattiva + logs
        if ($tsnow >= $ets && $ci['status'] == 'attivato') {

            $db::managePairing($conn,$ci['phoneb_id'],$ci['door_id'],'delete',$ci['id']);

            //.logs
            $content = $plog->sql2Text($sql) . PHP_EOL . "Codice temporaneo " . $ci['phoneb_id'] . "->" . $ci['door_id'] . " distrutto.";
            $params = array(
                'app' => 'ACS',
                'action' => 'COD_TEMP_OFF',
                'content' => $content,
                'user' => $_SESSION['user_name'],
                'description' => 'Disattivazione codice temporaneo',
                'origin' => 'PBX.asteriskcdrdb.checkinday_off_v',
                'destination' => 'PBX.asteriskcdrdb.acs_phoneb_doors',
            );
            $plog->sendLog($params);

        } //destroypair
     }
}



