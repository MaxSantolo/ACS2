<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PickLog.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';

builder::startSession();
builder::Header('ACS 2.0 - Menu Principale','sfondo.jpg');
builder::Navbar('DataTable');

$db = new DB();
$conn_vb = $db->getPBXConn('asteriskcdrdb');






$plog = new PickLog();
$text = 'cippitimerlo';
$params = array(
    'app' => 'PERT',
    'action' => 'COD_SICUR',
    'content' => "Test",
    'user' => $_SESSION['user_name'],
    'description' => "Inviata Mail Pin",
    'origin' => 'PBX.asterisk.visual_phonebook',
    'destination' => 'Email',
);
$plog->sendLog($params);


/*$text = "<table>
<tr style='font-weight: bold'>
<td style='width: 200px'>Titolo1</td>
<td style='width: 200px'>Titolo2</td>
<td style='width: 200px'>Titolo3</td>
</tr>
<tr>
<td style='width: 200px'>Pirulo RRRRRR</td>
<td style='width: 200px'>Paperino RR</td>
<td style='width: 200px'>Pippo</td>
</tr>
<tr>
<td>Pluto</td>
<td>Topolino</td>
<td>Archimede</td>
</tr>
<tr>
<td>Gastone</td>
<td>Paperoga</td>
<td>Nonna Papera</td>
</tr>
      </tbody></table>";

$now = date('Y-m-d');
$yesterday = date('Y-m-d',strtotime("-1 days"));
$actual_filename = 'tech/logs/pinlist/pinlist_'.$now.'.html';
$previous_filename = 'tech/logs/pinlist/pinlist_'.$yesterday.'.html';
echo $actual_filename;
echo $previous_filename;
$fileyesterday = fopen($previous_filename, 'w');
$filetoday = fopen($actual_filename, 'w');


fwrite($fileyesterday, $text);
fwrite($filetoday, $text);
fclose($fileyesterday);
fclose($filetoday);



if (Log::compareFiles($fileyesterday,$filetoday)) { echo 'UGUALI!';} else echo 'DIVERSI!';

*/?>





<div style="width: 600px;margin: auto;padding: 10px"><br><br><br><br><img src="images/logo_acs2.png" width="550"> </div>


<!--<div class="tableContainer">
<form method="post">
    <select class="form-control form-control-chosen autocomplete" name="select">
<?PHP /*echo DB::optClients($conn_vb,'1')*/?>
    </select>
<button class="btn-primary" type="submit" name="button">Piripicchio</button>
</form>
</div>-->




<?php
if (isset($_POST['button'])) echo $_POST['select'];
builder::Scripts();
echo strtotime($now = (new DateTime('Europe/Rome'))->format('Y-m-d H:i:s'));
?>

<script type="text/javascript">
    $(".autocomplete").chosen();
    $(".chzn-container").css({"left":"20%"});
</script>

</html>