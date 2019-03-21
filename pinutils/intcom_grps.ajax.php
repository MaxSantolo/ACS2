<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 16/11/2018
 * Time: 09:26
 */

$rawarray = $_POST['rawarray'];
$grpname = $_POST['grpname'];
$delgrp = $_POST['delgrp'];


require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';

$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');
builder::startSession();

$icarray = explode('-|-',$rawarray);

if ($_POST['command']=='editgroup')
{ echo $db::formIntercomsCB($conn,'UFF','','grpic','',$icarray,$grpname,'SALVA','Grp');
       builder::configDataTable('Grp','false',10,0,'asc');
  };

if ($_POST['command']=='newgrp')
{ echo $db::formIntercomsCB($conn,'UFF','','newgrpic','','','INSERISCI NOME QUI','SALVA','New');
    builder::configDataTable('New','false',10,0,'asc');}

if ($_POST['command']=='deletegrp') {
    //$db->query("DELETE FROM acs_doors_grps WHERE acs_doors_grps.group = '{$grpname}'"); //for testing
    Log::wLog('Gruppo '.$delgrp.' eliminato','GRUPPO-PIN-ELIMINATO');
    $conn->query("DELETE FROM acs_doors_grps WHERE acs_doors_grps.group = '{$delgrp}'");


}

