<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 26/11/2018
 * Time: 15:20
 *
 * Pagina per la visualizzazione degli accessi temporanei agli uffici.
 * Permette di crearne di nuovi e controlla che quelli creati rispettino le regole del centro.
 *
 *
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';

isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'sfondo.jpg';
isset($_GET['role']) ? $role = $_GET['role'] : $role = '';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['vbid']) ? $vbid = $_GET['vbid'] : $vbid = '';
isset($_GET['grpclm']) ? $grpclm = $_GET['grpclm'] : $grpclm = 0;


$title = 'Pin DAY/SALE';
builder::startSession();
builder::Header($title,$bg);
builder::Navbar('checkins');
$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');
$conn_vb = $db->getPBXConn('asterisk');
$acheckins = $conn->query("SELECT * FROM checkinday_off_v WHERE phoneb_id LIKE '%{$vbid}%' AND (status != 'completato' OR status IS NULL OR status = 'attivato')"); //prendo tutta la view filtrata se vbid è settato




echo "<p class=\"titolo\">{$title}</p>";

echo '<div id="risultati" class="tableContainer">';
echo "
<div class='col-md-12 mb-sm-2'>
        <a href='' class='btn m-auto btn-dark-green ciduplicate' data-toggle='modal' data-target='#ciEdDup' data-vbid ={$vbid}>Nuova prenotazione Day/Sala</a>    
</div>
<div class='col-md-12 mb-sm-2'>
        <a class='btn-sm m-auto btn-indigo' href='checkinday.php?bg=sfondo_regolo.jpg&thcolor=590c0f&grpclm=4'>Raggruppa per Cliente</a>
        <a class='btn-sm m-auto btn-indigo' href='checkinday.php?bg=sfondo_regolo.jpg&thcolor=590c0f&grpclm=0'>Raggruppa per Ufficio</a> 
        <a class='btn-sm m-auto btn-indigo' href='checkinday.php?bg=sfondo_regolo.jpg&thcolor=590c0f&grpclm=1'>Raggruppa per Data</a>
</div>

";

?>

    <!-- edit/duplicate modal -->
    <div class="modal fade" id="ciEdDup" tabindex="-1" role="dialog" aria-labelledby="ciEdDup" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: rgba(250,250,250,.85)">
                <div class="modal-header" style="background-color: #529c56;color: white">
                    <h5 class="modal-title" id="exampleModalPreviewLabel">Modifica PIN Day/sale</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="EdDupBody">
                    ...
                </div>
            </div>
        </div>
    </div>


<?php
echo "<table id=\"checkins\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
                  <thead style='background-color: #".$thcolor.";color: white'>
                    <th>UFFICIO</th>
                    <th>DATA</th>
                    <th>DALLE</th>
                    <th>ALLE</th>
                    <th>CLIENTE</th>
                    <th>EMAIL</th>
                    <th>TELEFONO</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </thead>
              <tbody>";

while ($ci = $acheckins->fetch_assoc()) {

        //formatto e mostro la tabella degli accesi temporanei

        $client = $ci['firstname']. ' '.$ci['lastname']. ' | '. $ci['company'];
        $fdate = ACSBase::DateToItalian($ci['date'],'l d-m-Y');
        $fstart = ACSBase::DateToItalian($ci['tstart'],'H:i');
        $fend = ACSBase::DateToItalian($ci['tend'],'H:i');
        $office = $ci['name']. ' | '. $ci['description'];
        $pairstatus = $db::isPairing($conn,$ci['door_id'],$ci['phoneb_id']);
        ($pairstatus == true && $ci['status'] == 'attivato') ? $hidden = '' : $hidden='none';
        echo "
        <tr style='background-color: {$rowcolor}'>
            <td style='width: 7%'>{$office}</td>
            <td style='text-align: right;width: 7%'>{$fdate}</td>
            <td style='text-align: right;width: 3%'>{$fstart}</td>
            <td style='text-align: right;width: 3%'>{$fend}</td>
            <td>{$client}</td>
            <td>{$ci['email']}</td>
            <td>{$ci['phone1']}</td>
            <td width='24'><img src='../images/active.png' width='24' style='display: {$hidden}' title='Attivo'></td>
            <td width='24'><a href='' data-toggle='modal' data-target='#ciEdDup' class='ciedit'
                                        data-ciid = {$ci['id']}
                                        ><img src='../images/edit2.png' width='24' TITLE='MODIFICA'></a></td>
            <td width='24'><a href='' data-toggle='modal' data-target='#ciEdDup' class='ciduplicate'
                                        data-vbid = {$ci['phoneb_id']}
                                        ><img src='../images/duplicate.png' width='24' TITLE='DUPLICA'></a></td>
            <td width='24'><a href='../pinutils/vbdetail.php?vbid={$ci['phoneb_id']}&vbpin={$ci['pin']}'' ><img src='../images/view.png' width='24' TITLE='SCHEDA DETTAGLIO'></a></td>                                        
            <td width='24'><a href='' class='cidelete'
                                        data-ciid = {$ci['id']}
                                        data-doorid = {$ci['door_id']}
                                        data-userid = {$ci['phoneb_id']}
                                        ><img src='../images/delete2.png' width='24' TITLE='ELIMINA'></a></td>
                                            
            
        </tr>
        ";

}

echo '</tbody></table></div>';

if (isset($_POST['ciedit']) || isset($_POST['cidup'])) {
    $ciid = $_POST['idcheckin'];
    $userid = $_POST['fclient'];
    $doorid = $_POST['foffice'];
    $date = $_POST['fdate'];
    $stime = $_POST['fstime'];
    $etime = $_POST['fetime'];
    $rdate = $_POST['rdate'];
    $days = $_POST['repdays'];

    //leggo le ripetizioni se ci sono altrimenti passo array con la data singola
    if ($rdate !='') {
        $repdates = DB::dateRangeRecurring($date,$rdate,$days);
    } else $repdates = array($date);

    foreach ($repdates as $date) {
        $check = $db::checkAvalCorr($conn, $doorid, $date, $stime, $etime, $ciid, $userid);
        $message .= $check['message'];
        if ($check['result']) {
            $db::updCheckInDay($conn, $userid, $doorid, $date, $stime, $etime, $ciid);
            Log::wLog('Accesso temporaneo programmato '.Log::pAssIntercoms($conn,array($doorid)).' cliente: '.Log::pUser($conn_vb,$userid).', data: '.$date.', dalle: '.$stime.' alle '.$etime.'','DAY/SALE-PIN');
        }
    }

    // se c'è messaggio di errore lo visualizzo in un alert
   if($message!='') {      echo "<script>alert('" . $message . "');</script>"; }

    echo "<meta http-equiv='refresh' content='0'>";
}

builder::Scripts();
//builder::configDataTable('checkins','true',25,0,'asc');
builder::configGroupedDataTable($grpclm,'checkins',true,25,0,'asc',12);

//script per l'ajax
echo "
    <script>    
                $(document).on('click', '.ciedit', function(e){
                            e.preventDefault();
                            var ciid = $(this).data('ciid');
                            console.log(ciid);
                $.ajax({
                    type: 'POST',
                    url: 'checkinday.ajax.php',
                    data: {ciid: ciid, command: 'edit'},
                    success: function(data){
                        $('#EdDupBody').html(data);}
                    });
                });
    </script>
    <script>    
                $(document).on('click', '.cidelete', function(e){
                            e.preventDefault();
                            var ciid = $(this).data('ciid');
                            var userid = $(this).data('userid');
                            var doorid = $(this).data('doorid');
                            console.log(ciid);
                            if(confirm('Elimino la prenotazione #'+ciid+'?')) {
                                $.ajax({
                                    type: 'POST',
                                    url: 'checkinday.ajax.php',
                                    data: {ciid: ciid, command: 'delete', doorid: doorid, userid: userid},
                                    success: function () {
                                              window.location.reload();
                                                }
                                    });
                                }
                            });
    </script>

    <script>    
                $(document).on('click', '.ciduplicate', function(e){
                            e.preventDefault();
                            var vbid = $(this).data('vbid');
                            console.log(vbid);
                $.ajax({
                    type: 'POST',
                    url: 'checkinday.ajax.php',
                    data: {vbid: vbid, command: 'duplicate'},
                    success: function(data){
                        $('#EdDupBody').html(data);}
                    });
                });
    </script>
<!--    <script>
            var url = new URL(window.location.href);
            var vbid = url.searchParams.get(\"vbid\");

            if (vbid != null) {
                               $.ajax({
                                    type: 'POST',
                                    url: 'checkinday.ajax.php',
                                    data: {vbid: vbid, command: 'duplicate'},
                                    success: function(data){
                                        $('#EdDupBody').html(data);
                                        jQuery('#ciEdDup').modal('show');
                                        }
                                    });
            } 
            
    </script>-->

";



DB::dropConn($conn);





