<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 26/11/2018
 * Time: 16:35
 */

$ciid = $_POST['ciid'];
$vbid = $_POST['vbid'];
$userid = $_POST['userid'];
$doorid = $_POST['doorid'];

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
builder::startSession();
$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');

if (isset($_POST['command']) && $_POST['command'] == 'edit') {
    echo $db::buildFormCheckInDay($conn,'',$ciid,'ciedit','Salva');
}

if (isset($_POST['command']) && $_POST['command'] == 'duplicate') {
    echo $db::buildFormCheckInDay($conn,$vbid,'','cidup','Crea');
}

if (isset($_POST['command']) && $_POST['command'] == 'delete') {
    Log::wLog('Accesso temporaneo eliminato','DAY/SALE-PIN-ELIMINATO');
    $conn->query("DELETE FROM acs_checkday WHERE id = '{$ciid}'");
    $conn->query("DELETE FROM acs_phoneb_doors WHERE phoneb_id = '{$userid}' and door_id = '{$doorid}'");


}

