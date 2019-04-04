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


builder::startSession();
builder::Header('RUBRICA COMPLETA',$bg);
builder::Navbar('DataTable');

?>

<p class="titolo">RUBRICA COMPLETA</p>

<?php

$db = new DB();
$conn_addrbook = $db->getPBXConn('asterisk');
$conn_acs = $db->getPBXConn('asteriskcdrdb');
$addrbook_data = $conn_addrbook->query("SELECT * FROM visual_phonebook ORDER BY id DESC");

if ($addrbook_data->num_rows > 0) {
    echo '<div id="risultati" class="tableContainer">';
    echo '<a href="http://crm.pickcenter.com/index.php?module=Leads&action=EditView&return_module=Leads&return_action=DetailView" target=\'_blank\' class="btn btn-primary btn-indigo m-auto">Nuovo Contatto</a>';
    echo "<table id=\"DataTable\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\" >
              <thead style='background-color: #" . $thcolor . ";color: white'>
                <th hidden>ID</th>
                <th>AZIENDA</th>
                <th>NOME</th>
                <th>COGNOME</th>
                <th>PIN</th>
                <th>SCADENZA</th>
                <th>TIPO</th>
                <th>UFFICI</th>
                <th></th>
                <th></th>
                <th></th>
              </thead>
              <tbody>";


    while ($addr = $addrbook_data->fetch_assoc()) {

        ($addr['scadenza_pin'] == '0000-00-00') ? $expdate = '' : $expdate = $db->dateFormat($addr['scadenza_pin'],'d/m/Y');

        $type = $db->showType($conn_acs,$addr['tipo']);
        $realpin = substr($addr['pin'],2);
        $offices = $db->printPairings($conn_acs,$addr['id'],'text');
        ($realpin=='') ? $hidden = 'hidden' :  $hidden = '';

        echo "
            <tr>
                <td hidden>{$addr['id']}</td>
                <td>{$addr['company']}</td>
                <td>{$addr['firstname']}</td>
                <td>{$addr['lastname']}</td>
                <td style='text-align: right'>{$realpin}</td>
                <td style='text-align: right'>{$expdate}</td>
                <td>{$type}</td>
                <td style='width: 20%;'>
                        {$offices}
                </td>
                <td><a href='vbdetail.php?vbid={$addr['id']}&vbpin={$addr['pin']}'><img src='../images/edit2.png' width='24'></a></td>
                <td><a  href='../checkinday/checkinday.php?bg=sfondo_regolo.jpg&thcolor=590c0f&vbid={$addr['id']}' ><img src='../images/book2.png' width='24' title='PRENOTA DAY/SALE' {$hidden}></a></td>
                <td data-order='{$addr['crm_id']}'>".ACSBase::isCRM($addr['crm_id'],
                        '<a href="http://crm.pickcenter.com/index.php?module=Leads&action=DetailView&record='.$addr['crm_id'].'" target="_blank" TITLE="MODIFICA NEL CRM"><img src=\'../images/swc_icon.png\' width=\'24\'></a>',
                        '<a href="#" TITLE="ELIMINA" class="deleteid" data-id="'.$addr['id'].'"><img src=\'../images/delete2.png\' width=\'24\'></a>')." <!-- <a href=\"#\" TITLE=\"MANDA AL CRM\"><img src='../images/pushto.png' width='24'></a> -->
                </td>
            </tr>
        ";
    }
}
echo '</tbody></table></div>';
builder::Scripts();

echo "
    <script>    
                $(document).on('click', '.deleteid', function(){ 
                          var delid = $(this).data('id');
                          console.log(delid)
                          if(confirm('Elimino il contatto #'+delid+'?')) {
                $.ajax({
                    type: 'POST',
                    url: 'addrbook.ajax.php',
                    data: {delid: delid },
                    success: function () {
                              window.location.reload();
                    }
                    });
                }
                });
    </script>

";



builder::configDataTable('DataTable','true',25,0,'desc');
DB::dropConn($conn_acs);
DB::dropConn($conn_addrbook);
?>
