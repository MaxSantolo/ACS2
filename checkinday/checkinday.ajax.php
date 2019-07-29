<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 26/11/2018
 * Time: 16:35
 *
 * Pagina Ajax per gli accessi a tempo. Cancella, modifica o duplica un accesso temporaneo in base al comando
 * passato nel post da JS.
 *
 *
 */

//leggo i post dall'URL

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

//costruisco e mostro il modale per la modifica di un accesso a tempo
if (isset($_POST['command']) && $_POST['command'] == 'edit') {
    echo $db::buildFormCheckInDay($conn,'',$ciid,'ciedit','Salva');
}

//costruisco e mostro il modale per la duplicazione di un accesso a tempo
if (isset($_POST['command']) && $_POST['command'] == 'duplicate') {
    echo $db::buildFormCheckInDay($conn,$vbid,'','cidup','Crea');
}

//cancello un accesso temporaneo e lo loggo
if (isset($_POST['command']) && $_POST['command'] == 'delete') {
    Log::wLog('Accesso temporaneo eliminato','DAY/SALE-PIN-ELIMINATO');
    $conn->query("DELETE FROM acs_checkday WHERE id = '{$ciid}'");
    $conn->query("DELETE FROM acs_phoneb_doors WHERE phoneb_id = '{$userid}' and door_id = '{$doorid}'");


}

