<?php 

include("../tech/connect.php");
require("calendario/connector/scheduler_connector.php");
require("calendario/connector/db_mysqli.php");

$gridConn = new SchedulerConnector($conn,"MySQLi"); 
$gridConn->render_table("acs_prenotazioni","id_prenotazione","data_inizio,data_fine,titolo,tipo");

?>