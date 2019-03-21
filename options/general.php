<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 20/11/2018
 * Time: 15:14
 */


require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';

isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'sfondo.jpg';
isset($_GET['role']) ? $role = $_GET['role'] : $role = '';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['center']) ? $centro = $_GET['center'] : $center = '';

builder::startSession();
builder::Header('OPZIONI',$bg);
builder::Navbar('DataTable');

echo "<p class='titolo'>ELENCO APRIPORTA UFFICI</p>";

$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');
$aintercoms = $conn->query("SELECT * FROM acs_doors where phone_num != '0000'");
?>

    <!-- Insert/Edit Modal -->
    <div class="modal fade" id="InsEdit" tabindex="-1" role="dialog" aria-labelledby="InsEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"  style="background-color: rgba(235,235,235,0.9);">
                <div class="modal-header" style="background-color: black;color: antiquewhite">
                    <h5 class="modal-title" id="exampleModalPreviewLabel" >Inserisci/Modifica Citofono</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="InsEditForm" class="modal-body">
                </div>
            </div>
        </div>
    </div>



<?php

if (isset($_POST['insUpdIC'])) {

    //echo $_POST['icid'].$_POST['ficname'].$_POST['fphonen'].$_POST['typeselect'].$_POST['centerselect'];
    DB::insUpdIC($conn,$_POST['icid'],$_POST['ficname'],$_POST['typeselect'],$_POST['fphonen'],$_POST['centerselect']);
    echo "<meta http-equiv='refresh' content='0'>";

}

if($aintercoms->num_rows>0) {
    echo '<div id="risultati" class="tableContainer">
          <a id="newic" href="" class="btn btn-purple" data-toggle="modal" data-target="#InsEdit" data-id="">Nuovo Citofono</a>   
        ';
    echo "<table id=\"IcTable\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\" >
            <thead style='background-color: #" . $thcolor . ";color: white'>
                <th>ID</th>
                <th>NOME</th>
                <th>INTERNO</th>
                <th>TIPO</th>
                <th>CENTRO</th>
                <th></th>
            </thead>
            <tbody>";
    while ($ic = $aintercoms->fetch_assoc()) {
           echo "
                <tr>
                    <td width='24' style='background-color: #ccc ;text-align: right'>{$ic['id']}</td>
                    <td>{$ic['name']}</td>
                    <td>{$ic['phone_num']}</td>
                    <td>{$ic['type']}</td>
                    <td>{$ic['description']}</td>
                    <td width='24'><a href='' data-toggle='modal' data-target='#InsEdit' class='icdata'
                         data-id='{$ic['id']}'>
                         <img src='../images/edit2.png' width='24'></a></td>
                </tr>";
    }
    echo "</tbody></table></div><hr>";

}

builder::Scripts();
echo "
    <script>
            $(document).on('click', '.icdata', function(){ 
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: 'general.ajax.php',
                    data: {icid: id},
                    success: function(data){
                        $('#InsEditForm').html(data);}
                    });
                });
       
</script>
    <script>
            $(document).on('click', '#newic', function(){ 
                
                $.ajax({
                    type: 'POST',
                    url: 'general.ajax.php',
                    data: {icid: ''},
                    success: function(data){
                        $('#InsEditForm').html(data);}
                    });
                });
       
</script>
";
builder::configDataTable('IcTable','true',10,1,'asc');
DB::dropConn($conn);
