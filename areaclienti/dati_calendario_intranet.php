<?php 

include("../tech/connect_prod.php");
require("calendario/connector/scheduler_connector.php");
require("calendario/connector/db_mysqli.php");

$gridConn2 = new SchedulerConnector($conn_prod_booking,"MySQLi"); 
$gridConn2->render_table("acs_eventi_orazio","id","inizio,fine,titolo");

$conn_prod_booking->close();
$conn_prod_intranet->close();
$conn_prod_radius->close();

?>