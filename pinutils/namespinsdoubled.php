
<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/ACSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';

isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'sfondo.jpg';
isset($_GET['role']) ? $role = $_GET['role'] : $role = '';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['center']) ? $centro = $_GET['center'] : $center = '';


builder::startSession();
builder::Header('PIN/NOMI REPLICATI',$bg);
builder::Navbar('DataTable');

$db = new DB();
$conn = $db->getPBXConn('asterisk');

?>

<div class="titolo">PIN duplicati</div>

<?php
$sql = "select group_concat('<a href=\"vbdetail.php?vbid=',id,'&vbpin=',pin,'\">', id,'</A> | ', lastname, ' ', firstname, ' | ', company, ' | ', email, ' | ', email2 order by id separator '<BR>') as duplicati, count(pin) c, pin from visual_phonebook where pin!='' group by pin having c>1" ;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo '<div class="tableContainer" style="font-size: small">';
    echo "<table id=\"DbdPins\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\" >
                <thead  style='background-color: #" . $thcolor . ";color: white'>
                    <th>DUPLICATI</th>
                    <th>PIN</th>
                </thead>";
    while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["duplicati"]."</td>
                        <td>".$row["pin"]."</td>";
		}
		echo "</table></div>";
		} else { echo "<p align=center>Nessun risultato</P>"; }
?>
    <div class="titolo">NOMI DUPLICATI SUL FOP</div><BR>
    <?php
    $sql2 = "select group_concat('<a href=\"vbdetail.php?vbid=',id,'&vbpin=',pin,'\">', id,'</A> | ', lastname, ' ', firstname, ' | ', company, ' | ', email, ' | ', email2 order by id separator '<BR>') as duplicati, count(lastname) c, lastname, count(firstname) d, firstname, count(email) e, email from visual_phonebook where lastname != '' and firstname !='' and email !='' group by lastname, firstname having (c>1 and d>1 and e>1)" ;
    $result2 = $conn->query($sql2);
    if ($result2->num_rows > 0) {
        echo '<div class="tableContainer" style="font-size: small">';
        echo "<table id=\"DbdNames\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\" >
                    <thead style='background-color: #" . $thcolor . ";color: white'>
                        <th>DUPLICATI</th>
                    </thead>";
        while($row2 = $result2->fetch_assoc()) {
            echo "<tr>
                        <td>".$row2["duplicati"]."</td>
                  </tr>";
        }
        echo "</table></div>";
    } else { echo "<p align=center>Nessun risultato</P>"; }

    builder::Scripts();
    builder::configDataTable('DbdPins','false',25,0,'asc');
    builder::configDataTable('DbdNames','false',25,0,'asc');
    DB::dropConn("$conn"); ?>





