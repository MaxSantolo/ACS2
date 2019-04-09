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

isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'sfondo.jpg';
isset($_GET['role']) ? $role = $_GET['role'] : $role = '';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['center']) ? $centro = $_GET['center'] : $center = '';
isset($_GET['grpclm']) ? $grpclm = $_GET['grpclm'] : $grpclm = '';
isset($_GET['vbid']) ? $vbid = $_GET['vbid'] : $vbid = '';
isset($_GET['phonenum']) ? $phonenum = $_GET['phonenum'] : $phonenum = '';

builder::startSession();
builder::Header('ACCESSO AGLI UFFICI',$bg);
builder::Navbar('OffAccTable');

?>

<p class="titolo">ACCESSO AGLI UFFICI</p>
<div style="width: 25%; margin: auto">
    <form method="post">
        <div class="form-row">
            <div class="col">
                <input name="from" type="text" id="fromdate" class="form-control" placeholder="Dal" value="<?php echo isset($_POST['from']) ? $_POST['from'] : '' ?>">
            </div>
            <div class="col">
                <input name="to" type="text" id="todate" class="form-control" placeholder="Al" value="<?php echo isset($_POST['to']) ? $_POST['to'] : '' ?>" >
            </div>

                <div class="col">
                    <button class="btn btn-indigo" type="submit" name="search" onclick="showloader()" style="margin: auto">Cerca</button>
                </div>
        </div>
    </form>
</div>
<?php

$db = new DB();
$conn_addrbook = $db->getPBXConn('asterisk');
$conn_acs = $db->getPBXConn('asteriskcdrdb');

if (isset($_POST['search'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
} else {
    $from = date('Y-m-d', strtotime('first day of this month'));
    $to = date("Y-m-d", strtotime("last day of this month"));
}

$sql = DB::sqlOffAcc($from,$to,$vbid,$phonenum);

$acc_offices_data = $conn_acs->query($sql);


    echo '<div id="risultati" class="tableContainer" style="font-size: medium">';
    echo " <table id='OffAccTable' class=\"table table-bordered table-hover table-sm datatableIntegration display compact\">
                    <thead style='background-color: #".$thcolor.";color: white'>
                        <th width='7%'>DATA</th>
                        <th>UFFICIO</th>
                        <th>CENTRO</th>
                        <th>PERSONA</th>
                        <th>ACCESSI</th>
                        <th></th>
                    </thead>
                    <tbody>";
while ($ao = $acc_offices_data->fetch_assoc()) {
    $date_format = ACSBase::DateToItalian($ao['accdate'],'l d/m/Y');
    $date_order = strtotime($ao['accdate']);
    echo "
                    <tr>
                        <td data-order={$date_order}>{$date_format}</td>
                        <td>{$ao['name']}</td>
                        <td>{$ao['description']}</td>
                        <td>{$ao['acc_total_info']}</td>
                        <td>{$ao['acc_code_info']}</td>
                        <td width='24'><a href='pinutils/vbdetail.php?vbid={$ao['vbid']}&vbpin={$ao['code']}'><img src='images/view.png' width='24'></a></td>
                    </tr>
    ";
}
echo "</tbody></table></div>";

builder::Scripts();
builder::configGroupedDataTable($grpclm,'OffAccTable','true',50,0,'desc',5);
echo builder::createDatePicker(array('fromdate','todate'));

DB::dropConn($conn_acs);
DB::dropConn($conn_addrbook);
?>
