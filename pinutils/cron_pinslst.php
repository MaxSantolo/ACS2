
<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PickLog.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';


$db = new DB();
$conn = $db->getPBXConn('asterisk');

$sql2 = "SELECT firstname, lastname, company, pin from visual_phonebook where pin != '' and pin not like '5%' order by lastname" ;
$result = $conn->query($sql2);
$num_righe = $result->num_rows;
                                
if ($result->num_rows > 0) {
    echo '<div class="tableContainer" style="font-size: small">';
    $table .= "<table id=\"PinsLst\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
                <thead style='background-color: #000;color: white'>
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

		//echo $table;
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $yesterdayfile = 'tech/logs/pinlist/pinlist_' . $yesterday . '.html';
        echo $yesterdayfile.'<br>';
        $todayfile = Log::wTodayPinFile($table);
        echo $todayfile;
        $ini = builder::readIniFile();
        $now = ACSBase::Now();

        if (!Log::compareFiles($todayfile,$yesterdayfile) || !file_exists($yesterday)) {

          $mail = new Mail();
          $smail = $mail->sendEmail($ini['Email']['NotificaPin'],
                                    $ini['Email']['NomeNotificaPin'],
                                    $ini['Email']['From'],
                                    $ini['Email']['FromName'],
                               'Pick Center - Lista persone autorizzate con PIN - '.$now,
                                    $table,
                                    array($ini['Email']['NotificaPinCC'],$ini['Email']['NotificaPinCC2'],$ini['Email']['NotificaPinCCPC2'],$ini['Email']['NotificaPinCCPC']));

        //log locale dell'app
          Log::wLog('Inviata Mail Pin a '.$ini['Email']['NotificaPin'].', '.$ini['Email']['NotificaPinCC'].', '.$ini['Email']['NotificaPinCCPC2'].', '.$ini['Email']['NotificaPinCCPC'], 'SISTEMA');

        //.logs
            $plog = new PickLog();
            $content = "";
            $params = array(
                'app' => 'ACS',
                'action' => 'COD_SICUR',
                'content' => $content,
                'user' => $_SESSION['user_name'],
                'description' => "Inviata Mail Pin a {$ini['Email']['NotificaPin']}, {$ini['Email']['NotificaPinCC']}, {$ini['Email']['NotificaPinCCPC2']}, {$ini['Email']['NotificaPinCCPC']}",
                'origin' => 'PBX.asterisk.visual_phonebook',
                'destination' => 'Email',
            );
            $plog->sendLog($params);

        }



builder::Scripts();

DB::dropConn("$conn");


