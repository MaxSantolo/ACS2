<?php 

include (".../tech/functions.php");
include ("../tech/connect.php");
include ("session.php");

?>

<html>
<head>
    <title>Elenco Contratti Scaduti AreaClienti BSide</title>    
    <link rel="stylesheet" type="text/css" href="../css/baseline.css" />

  <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.js"></script>

<style>

#overlay {
    color:#ffffff;
    height:450px;
  }
div.contentWrap {
    height:441px;
    overflow-y:auto;
  }

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
    background-color: rgba(191,45,44,0.9);
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

<script type='text/javascript'>//<![CDATA[
$(window).load(function(){
jQuery(document).ready(function(){
    $("#content").hide();
    jQuery('#hideshow').live('click', function(event) {        
         jQuery('#content').toggle('show');
    });
});
});//]]> 

</script>


</head>

<body>
<?php include ('menu/menu.php'); ?>
        <div class="hit-the-floor">Contratti Scaduti</div><BR>

    <form action="" method="post">

        <table id="tabellaricerca" >
        
                <TR><TD colspan="8" width="400"><P ALIGN="center"><STRONG>Strumenti di ricerca</STRONG><BR><font size="2">lasciare un campo vuoto equivale ad estrarne tutti i valori</font></P></TD></TR>
                <TR>
                        <td colspan="2"></td>
                        <TD><strong>Nome: </strong></TD>
                        <TD><input name="fazienda" style="color:black" value="<?php echo isset($_POST['fazienda']) ? $_POST['fazienda'] : '' ?>"></TD>
                        <TD><strong>Codice: </strong></TD>
                        <TD><input name="fcodice" style="color:black" value="<?php echo isset($_POST['fcodice']) ? $_POST['fcodice'] : $_GET['codice'] ?>"></TD>
                        <td colspan="2"></td>
                </TR>
                <TR><TD colspan="4" style="text-align: center; font-weight: bold; font-size: 9pt; background: rgba(59,89,152,0.8);">INTERVALLO DI INIZIO PACCHETTO</TD><TD colspan="4" style="text-align: center; font-weight: bold; font-size: 9pt;  background: rgba(59,89,152,0.8);">INTERVALLO DI FINE PACCHETTO</TD></TR>
                        <TD><strong>Dal: </strong></TD>
                        <TD><input type="date" name="fdal" style="color:black" value="<?php echo isset($_POST['fdal']) ? $_POST['fdal'] : '' ?>"></TD>
                        <TD><strong>Al: </strong></TD>
                        <TD><input type="date" name="fal" style="color:black" value="<?php echo isset($_POST['fal']) ? $_POST['fal'] : '' ?>"></TD>
                        <TD><strong>Dal: </strong></TD>
                        <TD><input type="date" name="fdalf" style="color:black" value="<?php echo isset($_POST['fdalf']) ? $_POST['fdalf'] : '' ?>"></TD>
                        <TD><strong>Al: </strong></TD>
                        <TD><input type="date" name="falf" style="color:black" value="<?php echo isset($_POST['falf']) ? $_POST['falf'] : '' ?>"></TD>
                        
                </TR>
                <TR><TD colspan="8" style="color:black;text-align:center"><input type="submit" name="button" value="CERCA"></TD></TR>
        </table>
    </form>
         

<?php

if ( isset($_POST["button"]) || isset($_GET["codice"]) ) {
    
	
        $azienda = $_POST["fazienda"];
	
        if (isset($_GET['codice'])) { $codice = $_GET['codice']; } else { $codice = $_POST['codice']; } //se viene passato il codice la uso altrimenti la leggo dal form
        
        
	$dal = $_POST["fdal"];
	$al = $_POST["fal"];
        $dalf = $_POST["fdalf"];
	$alf = $_POST["falf"];
	
	$dal = $dal ?: '1900-01-01';
        $al = $al ?: '9999-12-31';
	$dalf = $dal ?: '1900-01-01';
        $alf = $al ?: '9999-12-31';	

	
        $sql = "SELECT * FROM acs_pacchetti_scaduti WHERE azienda LIKE '%".$azienda."%' && codice LIKE '%".$codice."%' && (data_inizio BETWEEN '".$dal."' AND '".$al."') && (data_fine BETWEEN '".$dalf."' AND '".$alf."' ) ORDER BY azienda " ;
	$result2 = $conn->query($sql);
        
        
	        

		if ($result2->num_rows > 0) {
		echo "<P><table id=customers><tr><th>ID</th><th>NOME/AZIENDA</th><th>Email</th><th>Codice</th><th>Cod. Aut.</th><th>Ore Utilizzate</th><th>Ore Totali</th><th>Tipo</th><th>Dal</th><th>Al</th><th colspan=4 style=\"text-align:right\"></th>";
		// output
		while($row2 = $result2->fetch_assoc()) {
                
        echo "<tr><td>".$row2["id"]."</td><td>".$row2["azienda"]."</td><td>".$row2["email_notifiche"]."</td><td>".$row2["codice"]."</td><td>".$row2["cod_auth"]."</td><td>".$row2["ore_utilizzate"]."</td><td>".$row2["ore_totali"]."</td><td>".$row2["tipo"]."</td><td>".date('d/m/Y',strtotime($row2["data_inizio"]))."</td><td>".date('d/m/Y',strtotime($row2["data_fine"]))."</td>"
                . "<td width=24><a href=\"ins_pacchetto.php?id=".$row2['id']."&nome=".$row2['azienda']."&email=".$row2['email_notifiche']."&codice=".$row2['codice']."&auth=".$row2['cod_auth']."\"><IMG SRC=\"../images/file_new.png\" border=0 width=24 title=\"Nuovo pacchetto per questo utente\"></a></td>"
                . "<td width=24><a href=\"ric_pacchetti.php?codice=".$row2['codice']."&dal=".$row2['data_inizio']."&al=".$row2['data_fine']."\"><IMG SRC=\"../images/ingressi.png\" border=0 width=24 title=\"Vedi ingressi\"></a></td>"
                . "<td width=24><a href='mail_scaduto.php?id=".$row2['id']."' onclick='return confirm(\"Inviare dettaglio a ".$row2["azienda"]." (ID # ".$row2['id'].")?\")'><IMG SRC=\"../images/file_send.png\" border=0 width=24 title=\"Invia dettaglio via mail\"></a></td>"
                . "<td width=24><a href='canc_pacch_archiv.php?id=".$row2['id']."' onclick='return confirm(\"Sicuro di voler eliminare il pacchetto archiviato di ".$row2["azienda"]." (ID # ".$row2['id'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 TITLE=\"Elimina pacchetto\"></a></td></tr>"
                . "";		}
		
		} else {
			echo "<p align=center>Nessun risultato</P></table>";
				}
	}
        else {  
        $sql2 = "SELECT * FROM acs_pacchetti_scaduti ORDER BY azienda";
        $result = $conn->query($sql2);
        echo "<P><table id=customers><tr><th>ID</th><th>NOME/AZIENDA</th><th>Email</th><th>Codice</th><th>Cod. Aut.</th><th>Ore Utilizzate</th><th>Ore Totali</th><th>Tipo</th><th>Dal</th><th>Al</th><th colspan=4 style=\"text-align:right\"></th>";
		// output
	while($row = $result->fetch_assoc()) {
              
        echo "<tr><td>".$row["id"]."</td><td>".$row["azienda"]."</td><td>".$row["email_notifiche"]."</td><td>".$row["codice"]."</td><td>".$row["cod_auth"]."</td><td>".$row["ore_utilizzate"]."</td><td>".$row["ore_totali"]."</td><td>".$row["tipo"]."</td><td>".date('d/m/Y',strtotime($row["data_inizio"]))."</td><td>".date('d/m/Y',strtotime($row["data_fine"]))."</td>"
                . "<td width=24><a href=\"ins_pacchetto.php?id=".$row['id']."&nome=".$row['azienda']."&email=".$row['email_notifiche']."&codice=".$row['codice']."&auth=".$row['cod_auth']."\"><IMG SRC=\"../images/file_new.png\" border=0 width=24 title=\"Nuovo pacchetto per questo utente\"></a></td>"
                . "<td width=24><a href=\"ric_pacchetti.php?codice=".$row['codice']."&dal=".$row['data_inizio']."&al=".$row['data_fine']."\"><IMG SRC=\"../images/ingressi.png\" border=0 width=24 title=\"Vedi ingressi\"></a></td>"
                . "<td width=24><a href='mail_scaduto.php?id=".$row['id']."' onclick='return confirm(\"Inviare dettaglio a ".$row["azienda"]." (ID # ".$row['id'].")?\")'><IMG SRC=\"../images/file_send.png\" border=0 width=24 title=\"Invia dettaglio via mail\"></a></td>"
                . "<td width=24><a href='canc_pacch_archiv.php?id=".$row['id']."' onclick='return confirm(\"Sicuro di voler eliminare il pacchetto archiviato di ".$row["azienda"]." (ID # ".$row['id'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 TITLE=\"Elimina pacchetto\"></a></td></tr>"
                . "";
	}
    
}
echo("</table><div style=\"height:5px\"></div>");
$conn->close();
?>
        <div style='text-align: center;'><input type='button' id='hideshow' value='Mostra/Nascondi contratti in scadenza'></div>
    <div id='content'>  
<?php

include ("../tech/functions.php");
include ("../tech/connect.php");
 
  
        $sql = "SELECT * FROM acs_pacchetti WHERE cestinato != '1' && ((data_fine_pacchetto - curdate())/(data_fine_pacchetto-data_inizio_pacchetto))< 0.2 && data_fine_pacchetto > curdate()" ;
        $result = $conn->query($sql);
        echo "<div class=\"hit-the-floor\">Contratti in scadenza (data)</div><BR><table id=customers><table id=customers><tr><th>ID</th><th>NOME/AZIENDA</th><th>AUTH</th><th>CODICE</th><th>TIPO</th><th>DATA INIZIO</th><th>DATA TERMINE</th><th>ORE UTILIZZATE</th><th>ORE GRATUITE</th><th>EMAIL NOTIFICA</th>"; //<th colspan=4 style=\"text-align:right\"></th></TR>";
		// output
	while($row = $result->fetch_assoc()) {
              
        echo "<tr><td>".$row["id_pacchetto"]."</td><td>".$row["azienda"]."</td><td>".$row["cod_auth"]."</td><td>".$row["codice"]."</td><td>".$row["tipo"]."</td><td>".date('d/m/y', strtotime($row["data_inizio_pacchetto"]))."</td><td>".date('d/m/y', strtotime($row["data_fine_pacchetto"]))."</td><td>".$row["ore_utilizzate"]."</td><td>".$row["delta_ore"]."</td><td>".$row["email_notifiche"]."</td>";
                //. "<td width=32><a href='mod_pacchetto.php?id=".$row['id_pacchetto']."'><IMG SRC=\"../images/file_edit.png\" border=0 width=24 alt=\"Modifica\"></a></td>"
                //. "<td width=32><IMG SRC=\"../images/file_archive.png\" border=0 width=24 alt=\"Consumi passati\"></td>"
                //. "<td width=32><a href='mail_pacchetto.php?id=".$row['id_pacchetto']."' onclick='return confirm(\"Inviare dettaglio a ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"../images/file_send.png\" border=0 width=24 alt=\"Invia dettaglio per email\"></a></td>"
                //. "<td width=32><a href='canc_pacchetto.php?id=".$row['id_pacchetto']."&pin=".$row['codice']."' onclick='return confirm(\"Sicuro di voler eliminare il pacchetto di ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 alt=\"Elimina pacchetto\"></a></td></tr>";
	};
        echo "</table><P><HR width=400><p>";
        
        $sql2 = "SELECT * FROM acs_pacchetti WHERE cestinato != '1' && ore_utilizzate / (ore_totali_pacchetto + delta_ore) > 0.8 &&  ore_utilizzate < (ore_totali_pacchetto + delta_ore) && data_fine_pacchetto > curdate()";
        $result = $conn->query($sql2);
        echo "<div class=\"hit-the-floor\">Contratti in scadenza (ore)</div><BR><table id=customers><table id=customers><tr><th>ID</th><th>NOME/AZIENDA</th><th>AUTH</th><th>CODICE</th><th>TIPO</th><th>DATA INIZIO</th><th>DATA TERMINE</th><th>ORE UTILIZZATE</th><th>ORE GRATUITE</th><th>EMAIL NOTIFICA</th>"; //<th colspan=4 style=\"text-align:right\"></th></TR>";
		// output
	while($row = $result->fetch_assoc()) {
              
        echo "<tr><td>".$row["id_pacchetto"]."</td><td>".$row["azienda"]."</td><td>".$row["cod_auth"]."</td><td>".$row["codice"]."</td><td>".$row["tipo"]."</td><td>".date('d/m/y', strtotime($row["data_inizio_pacchetto"]))."</td><td>".date('d/m/y', strtotime($row["data_fine_pacchetto"]))."</td><td>".$row["ore_utilizzate"]."</td><td>".$row["delta_ore"]."</td><td>".$row["email_notifiche"]."</td>";
                //. "<td width=32><a href='mod_pacchetto.php?id=".$row['id_pacchetto']."'><IMG SRC=\"../images/file_edit.png\" border=0 width=24 alt=\"Modifica\"></a></td>"
                //. "<td width=32><IMG SRC=\"../images/file_archive.png\" border=0 width=24 alt=\"Consumi passati\"></td>"
                //. "<td width=32><a href='mail_pacchetto.php?id=".$row['id_pacchetto']."' onclick='return confirm(\"Inviare dettaglio a ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"../images/file_send.png\" border=0 width=24 alt=\"Invia dettaglio per email\"></a></td>"
                //. "<td width=32><a href='canc_pacchetto.php?id=".$row['id_pacchetto']."&pin=".$row['codice']."' onclick='return confirm(\"Sicuro di voler eliminare il pacchetto di ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 alt=\"Elimina pacchetto\"></a></td></tr>";
	};


$conn->close();
?>
    </div>
<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script src="http://cdn.jquerytools.org/1.2.6/full/jquery.tools.min.js"></script>

                                            
         
</body>
</html>