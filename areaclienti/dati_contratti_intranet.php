
<?php 

include (".../tech/functions.php");
include ("../tech/connect.php");
include ("../tech/connect_prod.php");
include ("session.php");

?>
<html>
<head>
    <title>Gestione Contratti BSIDE</title>    
<link rel="stylesheet" type="text/css" href="../css/baseline.css" />


<style>


#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:small;
    border-collapse: collapse;
    width: 75%;
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
    background-color: #504Cff;
	opacity:0.9;
    color: white;
}


body {
  /* Location of the image */
  background-image: url(../images/sfondobside.jpg);
  
  /* Background image is centered vertically and horizontally at all times */
  background-position: center center;
  
  /* Background image doesn't tile */
  background-repeat: no-repeat;
  
  /* Background image is fixed in the viewport so that it doesn't move when 
     the content's height is greater than the image's height */
  background-attachment: fixed;
  
  /* This is what makes the background image rescale based
     on the container's size */
  background-size: cover;
  
  /* Set a background color that will be displayed
     while the background image is loading */
  background-color: #464646;
}

</style>

</head>

<body>
<!--
<div class="hit-the-floor">Contratti attivi</div><BR>
    <form action="" method="post">

        <table id="tabellaricerca">
        
        
                <TR><TD colspan="8" width="400"><P ALIGN="center"><STRONG>Strumenti di ricerca</STRONG><BR><font size="2">lasciare un campo vuoto equivale ad estrarne tutti i valori</font></P></TD></TR>
                <TR style="font-size:small;">
                        <TD><strong>Nome: </strong></TD>
                        <TD><input name="azienda" style="color:black" value="<?php echo isset($_POST['azienda']) ? $_POST['azienda'] : '' ?>"></TD>
                        <TD><strong>Codice: </strong></TD>
                        <TD><input name="codice" style="color:black" value="<?php echo isset($_POST['codice']) ? $_POST['codice'] : '' ?>"></TD>
                        <TD><strong>Tipo: </strong></TD>
                        <TD><input name="tipo" style="color:black" value="<?php echo isset($_POST['tipo']) ? $_POST['tipo'] : '' ?>"></TD>
                </TR>
                <TR><TD colspan="8" style="color:black;text-align:center"><input type="submit" name="button" value="CERCA"></TD></TR>
        </table>
    </form>
    <P>
        
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="../tech/assets/js/jquerypp.custom.js"></script>
        <script src="../tech/assets/framewarp/framewarp.js"></script>
        <script src="../tech/assets/js/script.js"></script>
</body>
</html> -->
<?php



//if (isset($_POST["button"])) {
//    
//	$azienda = $_POST["azienda"];
//	$codice = $_POST["codice"];
//	$tipo = $_POST["tipo"];
//	
//	$sql = "SELECT * FROM acs_pacchetti WHERE azienda LIKE '%".$azienda."%' && codice LIKE '%".$codice."%' && (tipo LIKE '%".$tipo."%' || tipo IS NULL) && cestinato != '1'" ;
//	$result = $conn->query($sql);
//	$num_righe = $result->num_rows;
//        
//
//		if ($result->num_rows > 0) {
//		echo "<P><table id=customers><tr><th>ID</th><th>NOME/AZIENDA</th><th>AUTH</th><th>CODICE</th><th>TIPO</th><th>DATA INIZIO</th><th>DATA TERMINE</th><th>ORE UTILIZZATE</th><th>ORE GRATUITE</th><th>EMAIL NOTIFICA</th><th colspan=5 style=\"text-align:right\"><A HREF=\"ins_pacchetto.php\"><IMG SRC=\"../images/file_new.png\" border=0 height=32 title=\"Nuovo\"></A></th>";
//		// output
//		while($row = $result->fetch_assoc()) {
//                    
//
//                
//                echo "<tr><td>".$row["id_pacchetto"]."</td><td>".$row["azienda"]."</td><td>".$auth."</td><td>".$pin."</td><td>".$row["tipo"]."</td><td>".date('d/m/y', strtotime($row["data_inizio_pacchetto"]))."</td><td>".date('d/m/y', strtotime($row["data_fine_pacchetto"]))."</td><td>".$row["ore_utilizzate"]."</td><td>".$row["delta_ore"]."</td><td>".$row["email_notifiche"]."</td>"
//                        . "<td width=24><a href='mod_pacchetto.php?id=".$row['id_pacchetto']."'><IMG SRC=\"../images/file_edit.png\" border=0 width=24 title=\"Modifica\"></a></td>"
//                        . "<td width=24><a href='elenco_pacchetti_scaduti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/file_archive.png\" border=0 width=24 title=\"Pacchetti scaduti\"></a></td>"
//                        . "<td width=24><a href='ric_pacchetti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/ingressi.png\" border=0 width=24 title=\"Ingressi\"></a></td>"
//                        . "<td width=24><a href='../mail_pacchetto.php?id=".$row['id_pacchetto']."' onclick='return confirm(\"Inviare dettaglio a ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"../images/file_send.png\" border=0 width=24 title=\"Invia dettaglio per email\"></a></td>"
//                        . "<td width=24><a href='../canc_pacchetto.php?id=".$row['id_pacchetto']."&pin=".$row['codice']."&auth=".$row['cod_auth']."' onclick='return confirm(\"Sicuro di voler eliminare il pacchetto di ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 title=\"Elimina pacchetto\"></a></tr>";
//		}
//		
//		} else {
//			echo "<p align=center>Nessun risultato</P>";
//				}
//	}
//else{  
        $sql2 = "SELECT * FROM contratti_bside ORDER BY data_fine desc" ;
        $result = $conn_prod_intranet->query($sql2);
        echo "<P><table id=customers><tr><th>ID</th><th>ID CONTRATTO</th><th>NOME/AZIENDA</th><th>AUTH</th><th>CODICE</th><th>DATA INIZIO</th><th>DATA TERMINE</th><th>EMAIL NOTIFICA</th><th colspan=2 style=\"text-align:right\"></th>";
		// output
	while($row = $result->fetch_assoc()) {
            
                $dati_pbx = $conn->query("SELECT * FROM acs_utenti WHERE email = '".$row["EMAIL"]."' ")->fetch_assoc(); 
                $auth = $dati_pbx["auth"];
                $pin = $dati_pbx["pin"];
                
                if ( ($dati_pbx["id_utente"] == NULL) || ($dati_pbx["email"] == NULL) || ($dati_pbx["pin"] == NULL) ) { 
                    $id = "DA FARE"; 
                   } else {
                        if ( $dati_pbx["livello"] == 'DISABILITATO'  ) { $id = "DISABILITATO"; } 
                            else { $id = $dati_pbx["id_utente"]; }
                   }
                      
        echo "<tr><td>".$id."</td><td>".$row["id"]."</td><td>".$row["nome"]."</td><td>".$auth."</td><td>".$pin."</td><td>".date('d/m/y', strtotime($row["DATA_INIZIO"]))."</td><td>".date('d/m/y', strtotime($row["data_fine"]))."</td><td>".$row["EMAIL"]."</td>"
                . "<td width=24><a href='ins_pacchetto.php?dal=".$row["DATA_INIZIO"]."&al=".$row["data_fine"]."&nome=".$row["nome"]."&email=".$row["EMAIL"]."'><IMG SRC=\"../images/file_new.png\" border=0 width=24 title=\"FAI CHECK IN\"></a></td>"
                ;
	}
    

$conn->close();
$conn_prod_intranet->close();
$conn_prod_booking->close();
$conn_prod_radius->close();
?>