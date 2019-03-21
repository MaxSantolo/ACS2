<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 20/11/2018
 * Time: 14:34
 */

$delid = $_POST['delid'];

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
$db = new DB();
$conn_vb = $db->getPBXConn('asterisk');
$conn_acs = $db->getPBXConn('asteriskcdrdb');
Log::wLog('Eliminato il contatto #'.$delid.': '.Log::pUser($conn_vb,$delid).' da ACS','CONTATTO-ELIMINATO');
$conn_acs->query("delete from acs_phoneb_doors where phoneb_id = '{$delid}'");
$conn_vb->query("delete from visual_phonebook where id = '{$delid}'");


