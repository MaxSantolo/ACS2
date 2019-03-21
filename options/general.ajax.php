<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 20/11/2018
 * Time: 17:22
 */

$icid = $_POST['icid'];
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';

$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');

echo DB::showICForm($conn,$icid,'insUpdIC');