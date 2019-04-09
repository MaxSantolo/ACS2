<?php

include ("functions.php");

date_default_timezone_set('Europe/Rome');
$servername = "10.8.0.10";
$username = "pick";
$password = "Pick.2017";
$db = "asteriskcdrdb";

$oggi = date("Y-m-d");

// creo connessione
$conn = new mysqli($servername, $username, $password,$db);

// controllo connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
        $sql = "SELECT * FROM cdr_accessi_sum WHERE auth like '%9%' && data_ingresso = curdate()"; //SELECT * FROM cdr_accessi_sum WHERE auth like '%9%' && 
	$result = $conn->query($sql);
	
        echo "<table name=risultati>";
	// output
	while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["nome_azienda"]."</td><td>".date('Y-m-d', strtotime($row["data_ingresso"]))."</td><td>".$row["ingressi"]."</td><td>".$row["pin"]."</td><td>".$row["auth"]."</td><td>".calcolaore($row["ingressi"])."</td></tr>";
	}
		

$conn->close();

?>