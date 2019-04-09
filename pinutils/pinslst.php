
<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'sfondo.jpg';
isset($_GET['role']) ? $role = $_GET['role'] : $role = '';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['center']) ? $centro = $_GET['center'] : $center = '';
isset($_GET['notpins']) ? $notpins = $_GET['notpins'] : $notpins = '';


builder::startSession();
builder::Header('LISTA PIN',$bg);
builder::Navbar('PinsLst');

$db = new DB();
$conn = $db->getPBXConn('asterisk');

($notpins == '5') ? $titlespec = 'Attivi' : $titlespec = 'Scaduti';

echo "<div class=\"titolo\">LISTA PIN {$titlespec}</div>";

$sql2 = "SELECT firstname, lastname, company, pin from visual_phonebook where pin != '' and pin not like '{$notpins}%' order by lastname" ;
$result = $conn->query($sql2);
$num_righe = $result->num_rows;
                                
if ($result->num_rows > 0) {
    echo '<div class="tableContainer" style="font-size: small">';
    $table .= "<table id=\"PinsLst\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
                <thead style='background-color: #" . $thcolor . ";color: white'>
                    <th>COGNOME</th>
                    <th>NOME</th>
                    <th>AZIENDA</th>
                    <th>PIN</th>
                </thead>";
    while($row = $result->fetch_assoc()) {
                $table .= "<tr>
                            <td>".htmlentities(utf8_decode($row["lastname"]))."</td>
                            <td>".htmlentities(utf8_decode($row["firstname"]))."</td>
                            <td>".htmlentities(utf8_decode($row["company"]))."</td>
                            <td>".$row["pin"]."</td>
                      </tr>";
		}
		$table .= "</table></div>";
		} else { echo "<p align=center>Nessun risultato</P>"; }

    echo $table; //stampa tabella pin

/*    if ($notpins==5) {
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $yesterdayfile = '../tech/logs/pinlist/pinlist_' . $yesterday . '.html';
        $todayfile = Log::wTodayPinFile($table);
        $date2check = ACSBase::Now();
        $time2check = strtotime(date('H:i',strtotime($date2check)));

        $ini = builder::readIniFile();
        $time = strtotime($ini['DateTime']['NotificaPinOrario']);


        if ((md5_file($todayfile) != md5_file($yesterdayfile)) && $time2check>=$time) {
          // echo $date2check.'<br>'.$time2check.'<br>'.$time23; //test
          $mail = new Mail();
          $smail = $mail->sendEmail($ini['DB']['NotificaPin'],
                                    $ini['DB']['NomeNotificaPin'],
                                    $ini['DB']['From'],
                                    $ini['DB']['FromName'],
                               'Pick Center - Lista persone autorizzate con PIN - '.$date2check,
                                    $table,
                                    array($ini['DB']['NotificaPinCC'],$ini['DB']['NotificaPinCCPC']));

          Log::wLog('Inviata Mail Pin a '.$_SESSION['pinnotify'],'SISTEMA');
        }
    }*/


builder::Scripts();
builder::configDataTable('PinsLst','false',25,0,'asc');
DB::dropConn("$conn");


