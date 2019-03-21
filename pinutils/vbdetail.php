<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 12/11/2018
 * Time: 14:35
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';

isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'sfondo.jpg';
isset($_GET['role']) ? $role = $_GET['role'] : $role = '';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['center']) ? $centro = $_GET['center'] : $center = '';
isset($_GET['vbid']) ? $vbid = $_GET['vbid'] : $vbid = '';
isset($_GET['vbpin']) ? $vbpin = $_GET['vbpin'] : $vbpin = '';

builder::startSession();
builder::Header('RUBRICA COMPLETA',$bg);
builder::Navbar('DataTable');

$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');
$connvb = $db->getPBXConn('asterisk');
$btntxt = 'Inserisci';



if ($vbid != '') {
    $avb = $connvb->query("SELECT * FROM visual_phonebook WHERE id = '{$vbid}'");
    $vb = $avb->fetch_assoc();
    $fn = $vb['firstname'];
    $ln = $vb['lastname'];
    $cm = $vb['company'];
    $p1 = $vb['phone1'];
    $p2 = $vb['phone2'];
    $p3 = $vb['phone3'];
    $pin = $vb['pin'];
    $pe = $db->dateFormat($vb['scadenza_pin'],'d/m/Y');
    $tp = $vb['tipo'];
    $em = $vb['email'];
    $em1 = $vb['email2'];
    $tcm = $vb['tcm'];
    $sr = $vb['servizi'];
    $nt = $vb['note'];
    $an = $vb['note2'];
    $in = $vb['note3'];
    $crm = $vb['crm_id'];
    $btntxt = 'Salva';
    $pua = DB::printPairings($conn,$vbid,'button');
    ($pin=='') ? $hidden = 'hidden' : $hidden = '';

}
if (isset($_POST["vbedit"])) {
    $fpin = $_POST['pin'];
    $_POST['expdate'] == '' ? $fep = '0000-00-00' : $fep = date('Y-m-d',strtotime($_POST['expdate']));
    $fp1 = $_POST['telefono1'];
    $fp2 = $_POST['telefono2'];
    $fp3 = $_POST['telefono3'];
    $fem1 = $_POST['email1'];

    if (!$db::isPin($connvb,$fpin,$vbpin)) {
        $db::registerPinChange($conn,$vbpin,$vbid);
        $connvb->query($db::vbUpdate($vbid,$fpin,$fep,$fp1,$fp2,$fp3,$fem1));
        Log::wLog('Il contatto #:'.$vbid.': '.Log::pUser($connvb,$vbid).' aggiornato (PIN: '.$fpin.', SCADENZA: '.$fep.', TEL1: '.$fp1.', TEL2: '.$fp2.', TEL2: '.$fp1.', ALT EMAIL: '.$fem1.' )','CONTATTO-AGGIORNATO');
        echo "<meta http-equiv='refresh' content='0;URL=vbdetail.php?vbid={$vbid}&vbpin={$fpin}'>"; } //http://acs.pickcenter.com/pinutils/vbdetail.php?vbid=2050&vbpin=99799283
    else echo "<SCRIPT>window.alert('PIN duplicato')</SCRIPT>";
}

if (isset($_POST["optic"])) {
    $intercoms = $_POST['intercoms'];
    $db::updateIntercoms($conn,$intercoms,$vbid);
    Log::wLog(Log::pAssIntercoms($conn,$intercoms).' a '.Log::pUser($connvb,$vbid). ' - '.$_SESSION['user_name'],'CONTATTO-PIN');
    echo "<meta http-equiv='refresh' content='0'>";
}

if (isset($_POST["setgrp"])) {
    $intercomsgrp = explode('-|-',$_POST['fgrpname']);
    $db::updateIntercoms($conn,$intercomsgrp,$vbid);
    Log::wLog(Log::pAssIntercoms($conn,$intercomsgrp).' a '.Log::pUser($connvb,$vbid). ' | '.$_SESSION['user_name'],'CONTATTO-PIN-GRUPPO');
    echo "<meta http-equiv='refresh' content='0'>";
}


?>

<p class="titolo">CONTATTO <?php echo ACSBase::isCRM($crm,'<a href="http://crm.pickcenter.com/index.php?module=Leads&action=DetailView&record='.$crm.'" target="_blank"><img src="../../images/swc_icon.png" width="24"></a>','') ?></p>

<!-- add groups modal -->

<div class="modal fade right" id="AssGrp" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true" data-backdrop='false'>
    <div class="modal-dialog modal-side modal-top-right" role="document">
        <div class="modal-content" style="background-color: rgba(250,250,250,.85)">
            <form method="post" action="" onsubmit="showloader()">
            <div class="modal-header" style="background-color: rgba(11,20,29,0.9);color: white;font-weight: bold">
                <h5 class="modal-title" id="exampleModalPreviewLabel" >ASSEGNA GRUPPO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: antiquewhite">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                        <select name="fgrpname" id="fgrpname" class="browser-default custom-select">
                            <option value="" selected="">Scegli...</option>
                            <?php echo DB::showGroupsOpts($conn) ?>
                        </select>
                </div>
            </div>
            <div class="modal-footer">
                <a href="intcom_grps.php" class="btn btn-green">CREA GRUPPO</a>
                <button type="submit" class="btn btn-indigo" name="setgrp">ASSEGNA</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Pin Change Modal -->
<div class="modal fade right" id="chPin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-side modal-bottom-right" role="document">
        <div class="modal-content" style="background-color: rgba(250,250,250,.85)">
            <div class="modal-header" style="background-color: rgba(11,20,29,0.9);color: white;font-weight: bold">
                <h5 class="modal-title" id="exampleModalPreviewLabel">Modifiche PIN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: antiquewhite">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="chPinTable" style="font-size: small">
                    ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-indigo" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- intercoms modal -->
<div class="modal fade right" id="AssInt" tabindex="-1" role="dialog" aria-labelledby="AssInt" aria-hidden="true"">
    <div class="modal-dialog modal-full-height modal-right" role="document" >
        <div class="modal-content" style="background-color: rgba(250,250,250,.85)">
            <div class="modal-header" style="background-color: rgba(11,20,29,0.9);color: white;font-weight: bold">
                <h5 class="modal-title" id="exampleModalLabel">ASSEGNA CITOFONO UFFICIO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="font-size: small;">
                <?php echo $db::formIntercomsCB($conn,'UFF','','optic',$vbid,'','','ASSEGNA','DataTable'); ?>
            </div>
        </div>
    </div>
</div>

<!-- search form -->
<div style="width: 95%;background-color: rgba(11,20,29,0.9);padding: 10px;margin: auto;">
<form method="post" action = "" onsubmit="showloader()">
        <div class="row form-row">
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="firstname" style="color:antiquewhite;font-weight: bold">Nome</label>
                        <input type="text" id="firstname" class="form-control disabled text-white" value="<?php echo $fn ?>" >
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="lastname" style="color:antiquewhite;font-weight: bold" >Cognome</label>
                        <input type="text" id="lastname" class="form-control disabled text-white" value="<?php echo $ln ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="company" style="color:antiquewhite;font-weight: bold">Azienda</label>
                        <input type="text" id="company" class="form-control disabled text-white" value="<?php echo $cm ?>" >
                    </div>
                </div>
            </div>
        </div>

        <div class="row form-row">
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <div class="input-group">
                            <label for="pin" style="color:antiquewhite;font-weight: bold">Pin</label>
                            <input type="text" name ="pin" id="pin" class="form-control text-white border-info" value="<?php echo $pin ?>">
                            <span class="input-group-addon">
                                <a href="#" onclick="random(); return false;"><img src="../images/random.png" width="32"></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="expdate" style="color:antiquewhite;font-weight: bold">Scadenza Pin</label>
                        <input type="text" name="expdate" id="expdate" class="form-control text-white border-info" value="<?php echo $pe ?>">
                    </div>
                </div>
            </div>

            <div class="form-group form-inline">
                <label for="type"  class="form-inline" style="color:antiquewhite;font-weight: bold;font-size: medium;padding-right: 10px"> Tipo</label>
                <select id="type" class="form-inline form-control disabled " style="width: auto;background: rgba(11,20,29,0.5);color: white;opacity: .9">
                    <option value="" selected="">Scegli...</option>
                    <?php echo $optionvalue = $db->showOptValue($conn,$tp); ?>
                    </select>
                </div>
        </div>

        <div class="row form-row">
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="telefono1" style="color:antiquewhite;font-weight: bold">Telefono</label>
                        <input type="text" name="telefono1" id="telefono1" class="form-control border-info text-white" value="<?php echo $p1 ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="telefono2" style="color:antiquewhite;font-weight: bold">Altro telefono</label>
                        <input type="text" name="telefono2" id="telefono2" class="form-control border-info text-white" value="<?php echo $p2 ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="telefono3" style="color:antiquewhite;font-weight: bold">Selezione passante</label>
                        <input type="text" name="telefono3" id="telefono3" class="form-control text-white border-info" value="<?php echo $p3 ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="row form-row">
            <div class="col-md-6">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="email" style="color:antiquewhite;font-weight: bold">Email</label>
                        <input type="email" id="email" class="form-control disabled text-white" value="<?php echo $em ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="email1" style="color:antiquewhite;font-weight: bold">Altra Email</label>
                        <input type="email" name="email1" id="email1" class="form-control text-white border-info" value="<?php echo $em1 ?>" >
                    </div>
                </div>
            </div>
        </div>

        <div class="row form-row">
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="tcm" style="color:antiquewhite;font-weight: bold">TCM / Codici</label>
                        <input type="text" id="tcm" class="form-control disabled text-white" value="<?php echo $tcm ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="servizi" style="color:antiquewhite;font-weight: bold">Servizi</label>
                        <input type="text" id="servizi" class="form-control disabled text-white" value="<?php echo $sr ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-form form-sm">
<!--                    <div class="form-group">
                        <label for="pua" style="color:antiquewhite;font-weight: bold">Porte ufficio assegnate</label>-->
                        <div id="pua" style="font-size: small;overflow-scrolling: auto;border-color: white;border-style: solid;border-width: 1px;color: white;padding: 3px" <?php echo $hidden ?>><?php echo $pua ?></div>
                        <div style="height: 10px" ></div>
                        <span class="input-group-addon">
                                <a  href="" data-toggle="modal" data-target="#AssInt" <?php echo $hidden ?>><img src="../images/add2.png" width="32" title="ASSOCIA/VEDI UFFICI"></a>
                                <a  href="" data-toggle="modal" data-target="#AssGrp" <?php echo $hidden ?>><img src="../images/add_group.png" width="32" title="ASSOCIA GRUPPO DI UFFICI"></a>
                                <a  href="../off_whole_search.php?grpclm=3&thcolor=2e3951&bg=officebg.png&vbid=<?php echo $vbid ?>" <?php echo $hidden ?>><img src="../images/view_detail.png" width="32" title="VEDI ACCESSI"></a>
                                <a  href="../checkinday/checkinday.php?bg=sfondo_regolo.jpg&thcolor=590c0f&vbid=<?php echo $vbid ?>" <?php echo $hidden ?>><img src="../images/book2.png" width="32" title="PRENOTA DAY/SALE"></a>
                                <a href="" data-toggle="modal" data-target="#chPin" data-chid = "<?php echo $vbid ?>" class="changedPin">
                                    <img src="../images/pinchange.png" width="32" title="MODIFICHE AL PIN" <?php echo $db::hasChangedPin($conn,$vbid) ?>>
                                 </a>
                            </span>
                    </div>
    <!--            </div>-->
            </div>

        </div>

        <div class="row form-row">
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="note" style="color:antiquewhite;font-weight: bold">Note</label>
                        <textarea id="note" class="form-control disabled text-white"><?php echo $nt ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="risposta" style="color:antiquewhite;font-weight: bold">Risposta</label>
                        <textarea id="risposta" class="form-control disabled text-white"><?php echo $an ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-form form-sm">
                    <div class="form-group">
                        <label for="istruzioni" style="color:antiquewhite;font-weight: bold">Istruzioni</label>
                        <textarea id="istruzioni" class="form-control disabled text-white"><?php echo $in ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row form-row">
            <button class="btn btn-indigo" type="submit" name="vbedit" style="width: 25%;margin: auto"><?php echo $btntxt ?></button>

        </div>
</form>
</div>



<?php


builder::Scripts();

echo '

    <script type="text/javascript">
        function random() {
            document.getElementById(\'pin\').value = \'99\' + (Math.floor(Math.random() * 900000)+100000);
            document.getElementById(\'pin\').focus()}
    </script>
    <script type="text/javascript">
        function isPin(modal) {
            if (document.getElementById(\'pin\').value == \'\') {
                window.alert(\'Devi prima associare un PIN all\\\'utente e salvare\');
                location.reload();
            }
           }
    </script>
    <script>    
                $(document).on(\'click\', \'.changedPin\', function(){ 
                            var chid = $(this).data(\'chid\');
                            console.log(chid);
                $.ajax({
                    type: \'POST\',
                    url: \'vbdetail.ajax.php\',
                    data: {chid: chid},
                    success: function(data){
                        $(\'#chPinTable\').html(data);}
                    });
                });
    </script>

';


builder::configDataTable('DataTable','false',10,0,'asc');
echo builder::createDatePicker(array('expdate'));




DB::dropConn($conn);
DB::dropConn($connvb);
?>

