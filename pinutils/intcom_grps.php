
<?php

/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 12/11/2018
 * Time: 10:08
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';

isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'sfondo.jpg';
isset($_GET['role']) ? $role = $_GET['role'] : $role = '';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['center']) ? $centro = $_GET['center'] : $center = '';
isset($_GET['rawdata']) ? $rawdata = $_GET['center'] : $rawdata = '';


builder::startSession();
builder::Header('PORTE - GESTIONE GRUPPI',$bg);
builder::Navbar('DataTable');
$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');



echo "<p class=\"titolo\">PORTE - GESTIONE GRUPPI</p>";


//intercoms edit modal
builder::modalIntercoms('AssInt','MODIFICA GRUPPO','white','rgba(191,45,44,0.9)','grpform');
//intercoms new modal
builder::modalIntercoms('NewGrp','NUOVO GRUPPO','black','rgba(82,156,86,0.9)','newgrpform');


$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');

$aicgroups = $conn->query("SELECT * FROM icgroups_v");

if ($aicgroups->num_rows>0) {

    echo '<div id="risultati" class="tableContainer">';

        echo "<a id=\"newgrp\" href=\"\" class=\"btn btn-green\" data-toggle=\"modal\" data-target=\"#NewGrp\">Nuovo Gruppo</a> ";

		echo "<table id=\"RisTable\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
                  <thead style='background-color: #".$thcolor.";color: white'>
                    <th>NOME</th>
                    <th>PORTE ASSOCIATE</th>
                    <th hidden>SEDE</th>
                    <th hidden>DATA</th>
                    <th hidden>AUTORE</th>
                    <th hidden>ARRAY</th>
                    <th></th>
                    <th></th>
                  </thead>
              <tbody>";

		        while($icgroup = $aicgroups->fetch_assoc()) {
                    $date = date('d-m-Y',strtotime($icgroup['data']));
                    echo "
                    <tr>
                        <td>{$icgroup['name']}</td>
                        <td>{$icgroup['intercoms']}</td>
                        <td hidden>{$icgroup['center']}</td>
                        <td hidden>{$date}</td>
                        <td hidden>{$icgroup['creator']}</td>
                        <td hidden>{$icgroup['icarray']}</td>
                        <td width='24'>
                            <a href=\"\" data-toggle=\"modal\" data-target=\"#AssInt\" class='grpdata' 
                                         data-array='{$icgroup['icarray']}'
                                         data-grpname='{$icgroup['name']}'
                                         >
                                <img src='../images/edit2.png' title='MODIFICA' width='24'>
                            </a>
                        </td>
                        <td width='24'>
                            <a href='' class='grpdeldata' 
                                data-name='{$icgroup['name']}'
                                >
                                <img src='../images/delete2.png' title='CANCELLA' width='24'>
                            </a>
                        </td>
                    </tr>
                ";
                }
    echo "</tbody></table></div>";

}

if (isset($_POST['newgrpic'])) {
    $intercoms = $_POST['intercoms'];
    $name = $_POST['grpname'];
    $error = $db::checkNewGrpCreate($conn,$intercoms,$name);
    if ($error != '') {
        echo "<script>window.alert('".$error."')</script>";
    } else {
        $db::insertIntercomsGroup($conn,$_POST['grpname'],$intercoms);
        Log::wLog('Gruppo '.$_POST['grpname'].' creato ('.Log::pAssIntercoms($conn,$intercoms).')','GRUPPO-PIN-CREATO');
        echo "<meta http-equiv='refresh' content='0'>";}
            }

if (isset($_POST['grpic'])) {

    $intercoms = $_POST['intercoms'];
    $db::updateIntercomsGroup($conn,$intercoms,$_POST['grpname']);
    Log::wLog('Gruppo '.$_POST['grpname'].' aggiornato ('.Log::pAssIntercoms($conn,$intercoms).')','GRUPPO-PIN-MODIFICATO');
    echo "<meta http-equiv='refresh' content='0'>";
}


builder::Scripts();
builder::configDataTable('RisTable','true',25,0,'asc');

echo "
    <script>    
                $(document).on('click', '.grpdata', function(e){
                            e.preventDefault();
                            var raw = $(this).data('array');
                            var grpname = $(this).data('grpname');
                $.ajax({
                    type: 'POST',
                    url: 'intcom_grps.ajax.php',
                    data: {rawarray: raw, 
                           grpname: grpname,
                           command: 'editgroup'},
                    success: function(data){
                        $('#grpform').html(data);}
                    });
                });
    </script>
    <script>
                    $(document).on('click','#newgrp', function(e) {
                        e.preventDefault();
                    $.ajax({
                    type: 'POST',
                    url: 'intcom_grps.ajax.php',
                    data: {command: 'newgrp'},
                    success: function(data){
                        $('#newgrpform').html(data);}
                   }); 
                });
    </script>
    <script>    
               $(document).on('click', '.grpdeldata', function(e){ 
                   e.preventDefault();
                        var delgrp = $(this).data('name');
                          console.log(delgrp);
                          if(confirm('Elimino il gruppo '+delgrp+'?')) {
                $.ajax({
                    type: 'POST',
                    url: 'intcom_grps.ajax.php',
                    data: {delgrp: delgrp,
                           command: 'deletegrp'},
                    success: function () {
                              window.location.reload();
                    }
                    });
                }
                });                 
    </script>
    ";


DB::dropConn("$conn");
