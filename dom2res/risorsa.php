
<html>
<head>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  

<style>
    #customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:small;
    border-collapse: collapse;
    width: 90%;
	margin-left:auto;
	margin-right:auto;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 4px;
	
}


#customers tr:nth-child(even){background-color:#d2d2d2;opacity:0.9;}
#customers tr:nth-child(odd){background-color:#c2c2c2;opacity:0.9;}

#customers tr:hover {background-color: #bbb;}

#customers th {
    padding-top: 4px;
    padding-bottom: 4px;
    text-align: left;
    background-color: #AF4c50;
	opacity:0.9;
    color: white;
}

</style>




</head>
<body>
<P align="center" style="font-family:Georgia, Garamond, Serif;color:blue;font-style:bold;font-size:16px">Riservato a Raffaella<BR>INSERIMENTO RISORSE DOM2</P>

<form action="" method="post" onkeypress="return event.keyCode != 13;">
<table style="font-family:Georgia, Garamond, Serif;color:white;font-style:bold;background: #ff791f;opacity:0.9;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;">
<TR>
	<TD><strong>Nome risorsa (Es. "AIPS"): </strong></TD><TD><input name="risorsa" style='text-transform:uppercase'></TD>
</TR>
<TR>
	<TD><strong>Codice Dom2 (Es. "SEDE LEGAL"): </strong></TD><TD><input name="dom2" style='text-transform:uppercase'></TD>
</TR>
<TR>
	<TD><strong>Codice eSolver (Es. "100314"): </strong></TD><TD><input name="esolver" style='text-transform:uppercase'></TD>
</TR>
<TR>
<TD><P ALIGN="center"><input type="submit" name="button" value="INSERISCI"></P></TD><TD><P ALIGN="center"><input type="submit" name="cerca" value="CERCA"></P></TD>
</TR>
</table>	
</form>
<?php
$servername = "192.168.1.10";
$username = "root";
$password = "fm105pick";
$dbname = "intranet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if (isset($_POST["button"])) {
    
	$risorsa = strtoupper($_POST["risorsa"]);
	$dom2 = strtoupper($_POST["dom2"]);
	$esolver = strtoupper($_POST["esolver"]);

	$sql = "INSERT INTO esolver_trans (RISE_CODICE, VORS_CODICE, ESOLVER_CODE) VALUES ('$dom2', '$risorsa', '$esolver')";

if ($conn->query($sql) === TRUE) {
    echo "<P align=\"center\" style=\"font-family:Georgia, Garamond, Serif;color:blue;font-style:bold;font-size:16px\">Inserimento effettuato</P>";
} else {
    echo "<P align=\"center\" style=\"font-family:Georgia, Garamond, Serif;color:blue;font-style:bold;font-size:16px\">Errore: " . $sql . "<br>" . $conn->error . "</P>";
}
}

if (isset($_POST["cerca"])) {
    
	$risorsa = strtoupper($_POST["risorsa"]);
	$dom2 = strtoupper($_POST["dom2"]);
	$esolver = strtoupper($_POST["esolver"]);
        
        $results = $conn->query("SELECT * FROM esolver_trans where RISE_CODICE LIKE '%".$dom2."%' AND VORS_CODICE LIKE '%".$risorsa."%' AND ESOLVER_CODE LIKE '%".$esolver."%'");
        
        	

		if ($results->num_rows > 0) {
		echo "<table id=customers><tr><thead><th>NOME RISORSA</th><th>DOM2</th><th>ESOLVER</th></thead></tr>";
		// output
		while($row = $results->fetch_assoc()) {
                
                              
                echo "<tr><td>".$row["VORS_CODICE"]."</td><td>".$row["RISE_CODICE"]."</td><td>".$row["ESOLVER_CODE"]."</td></tr>";
		}
				echo "</table>";
                
		} else {
			echo "<DIV style=\"font-family:Georgia, Garamond, Serif;font-size:12pt;background-color:rgba(200,200,200,0.7);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:auto;margin-right:auto; width: 40%;\"><p align=center><b>Nessun risultato</b></P></div>";
				}

}




$conn->close();
?>