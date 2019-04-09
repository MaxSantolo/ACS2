<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 26/11/2018
 * Time: 11:25
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
$chid = $_POST['chid'];

echo "<table class=\"table table-sm text-sm-left\" style='border-bottom-color: #0B1022'><thead  style='background-color:rgba(11,20,29,0.9);color: white'>
        <th>DATA</th>
        <th>VECCHIO PIN</th>
        <th>MODIFICATO DA</th>
        
          </thead>";


    $db = new DB();
    $conn = $db->getPBXConn('asteriskcdrdb');
    $results = $conn->query("SELECT * FROM acs_pinchange WHERE phoneb_id = '{$chid}'");

    while ($result = $results->fetch_assoc()) {
        $fdate = ACSBase::DateToItalian($result['cdate'],'d-m-y');
        echo "
        <tr>
            <td>{$fdate}</td>
            <td>{$result['old_pin']}</td>
            <td style='font-size: smaller'>{$result['cuser']}</td>
        </tr>
        ";
   }
   echo "</table>";


