<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 28/11/2018
 * Time: 10:01
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';

$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');

//crons per aggiornamento PIN day/sale
$checkins = $conn->query("SELECT * FROM checkinday_off_v WHERE date = curdate()");
$tsnow = strtotime(ACSBase::Now());
$ignore_create_array = array('completato','attivato');
if ($checkins->num_rows>0) {
    while ($ci = $checkins->fetch_assoc()) {
        $sts = strtotime($ci['sts']);
        $ets = strtotime($ci['ets']);
        if ($tsnow >= $sts && !in_array($ci['status'],$ignore_create_array)) { $db::managePairing($conn,$ci['phoneb_id'],$ci['door_id'],'new',$ci['id']); echo 'creato'; } //createPair
        if ($tsnow >= $ets && $ci['status'] == 'attivato') { $db::managePairing($conn,$ci['phoneb_id'],$ci['door_id'],'delete',$ci['id']); echo 'distrutto'; } //destroypair
     }
}



