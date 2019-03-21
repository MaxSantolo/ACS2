<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';


isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'sfondo.jpg';
isset($_GET['role']) ? $role = $_GET['role'] : $role = '';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['center']) ? $centro = $_GET['center'] : $center = '';
$role_text = strtoupper($role[0]);
$title = 'Controllo presenze '.$role_text;
builder::startSession();
builder::Header($title,$bg);
builder::Navbar('ReportTable')
?>


<p class="titolo"><?php echo $title; ?></p>

    <div class="text-sm-center">
        <a href="" class="btn btn-red" data-toggle="modal" data-target="#AdvRs">Inizia da qui...</a>
    </div>

    <div class="modal fade" id="AdvRs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: rgba(250,250,250,.85)">
                <div class="modal-header" style="background-color: #AF4c50;color: white;font-weight: bold">
                    <h5 class="modal-title" id="exampleModalLabel">PARAMETRI RICERCA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-sm-left">
                    <form class="text-center border border-light p-2" action="" method="post" onsubmit="showloader()">
                        <div class="form-row mb-2">
                            <div class="col">
                                <input name="firstname" type="text" id="firsname" class="form-control" placeholder="Nome" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>">
                            </div>
                            <div class="col">
                                <input  name="lastname" type="text" id="lastname" class="form-control" placeholder="Cognome" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <div class="col">
                                <label class="mb-sm-0 text-sm-left" style="text-align: left">Dal</label>
                                <input name="dal" type="text" id="fromdate" class="form-control" placeholder="Dal" value="<?php echo isset($_POST['dal']) ? $_POST['dal'] : '' ?>" required>
                            </div>
                            <div class="col">
                                <label class="mb-sm-0 text-sm-left" style="text-align: left">Al</label>
                                <input name="al" type="text" id="todate" class="form-control" placeholder="Al" value="<?php echo isset($_POST['al']) ? $_POST['al'] : '' ?>" required>
                            </div>
                        </div>
                        <button class="btn btn-red" type="submit" name="button" >Vai!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
if (isset($_POST["button"])) {

    $from = $_POST["dal"];
    $to = $_POST["al"];

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    $db = new DB();
    $conn = $db->getPBXConn('asteriskcdrdb');

    $daterange = $db->dateRange($from, $to);
    $in_employees = ACSBase::GenerateInString($role);
    $empl_sql = $db->sqlEmployees($in_employees,$firstname,$lastname);
    // echo $empl_sql; //test purposes
    $empl_array = $conn->query($empl_sql);
    $from_format = date('d/m', strtotime($from));
    $to_format = date('d/m', strtotime($to));

    echo "<div style='width: 75%;background-color: antiquewhite;margin: auto'>";
    echo "<table id='ReportTable' class='table table-hover table-sm'><thead style='background-color: #0e4377;'><tr><th colspan='4' style='color: antiquewhite;font-size: medium'>PRESENZE NEL PERIODO DAL {$from_format} AL {$to_format}</th></tr>
          <tr style='color: antiquewhite;font-weight: bold;font-size: medium' ><th>$role_text</th><th>INGRESSI</th><th>TURNI</th><th>FER/PERM/RITAR/MAL/STRAOR</th></tr></thead>";

    while ($empl = $empl_array->fetch_assoc()) {

        echo "<tr><td style='background-color: #0E64A0;font-size: medium;color:antiquewhite;' colspan='4'>{$empl['firstname']} {$empl['lastname']} (Codice: {$empl['pin']})</td></tr>";
        foreach ($daterange as $date) {

            $date_format = ACSBase::DateToItalian($date,'l d/m');
            $sql = $db->sqlIngressiReport($empl['pin'],$date);
            $report = $conn->query($sql);
            $report_row = $report->fetch_assoc();
            $acc_form = str_replace("|"," | ",$report_row['ingressi_totali']);
            echo "<tr style='font-size: small'>
                    <td style='width: 15%'>{$date_format}</td>
                    <td style='width: 50%'>{$acc_form}</td>
                    <td style='width: 5%'></td>
                    <td style='width: 30%'>{$report_row['nota']}</td>
                  </tr>";

        }

    }
    echo '</table></div>';
}

builder::Scripts();

echo builder::createDatePicker(array('fromdate','todate'));
DB::dropConn($conn);
?>

