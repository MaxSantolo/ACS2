
<?php 

include (".../tech/functions.php");
include ("../tech/connect.php");
// include ("../tech/connect_prod.php");
include ("session.php");

?>
<html>
<head>
    <title>Elenco LEAD</title>    
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
    padding: 2px;
	
}


#customers tr:nth-child(even){background-color:#d2d2d2;opacity:0.9;}
#customers tr:nth-child(odd){background-color:#c2c2c2;opacity:0.9;}

#customers tr:hover {background-color: #bbb;}

#customers th {
    padding-top: 4px;
    padding-bottom: 4px;
    text-align: left;
    background-color: #bf2d2c;
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
<div class="se-pre-con"></div>
    <?php include ('menu/menu.php'); ?>
<div class="hit-the-floor">Elenco LEAD</div><BR>
    <form action="" method="post">

        <table id="tabellaricerca">
                <TR style="font-size:small;">
                        <TD><input name="variabile" height="48" style="color:black" value="<?php echo isset($_POST['variabile']) ? $_POST['variabile'] : '' ?>"></TD><TD><input type="submit" name="button" value="CERCA"></TD>
                        <td style="text-align: center"> <a href="crm_elenco_mailchimp.php"><img src="../images/mailchimp.png" width="40" hspace="10"></A></td>
                </TR>
                
        </table>
    
    </form> 
    <P>
        
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="../tech/assets/js/jquerypp.custom.js"></script>
        <script src="../tech/assets/framewarp/framewarp.js"></script>
        <script src="../tech/assets/js/script.js"></script>

<?php



if (isset($_POST["button"])) {
    
        $variabile = $_POST["variabile"];
	$sql = "SELECT * FROM acs_crm WHERE nome LIKE '%".$variabile."%' OR cognome LIKE '%".$variabile."%' OR email1 LIKE '%".$variabile."%' OR telefono1 LIKE '%".$variabile."%' OR fase_contatto LIKE '%".$variabile."%'"
                . " OR fonte_primaria LIKE '%".$variabile."%' OR canale_fonte LIKE '%".$variabile."%' OR note LIKE '%".$variabile."%'"  ;
	$result = $conn->query($sql);
        
}       else {
                $sql2 = "SELECT * FROM acs_crm ORDER BY data_contatto DESC" ;
                $result = $conn->query($sql2);
            }        

$num_righe = $result->num_rows;
                                
if ($result->num_rows > 0) {
echo "<P><table id=customers><tr><th>ID</th><th>DATA CONTATTO</th><th>COGNOME</th><th>NOME</th><th>EMAIL</th><th>TELEFONO</th><th>NOTE</th><th>FASE</th><th>FONTE PRIMARIA</th><th>CANALE</th><th colspan=5 style=\"text-align:right\"><A HREF=\"crm_lead_dettagli.php\"><IMG SRC=\"../images/file_new.png\" border=0 height=32 title=\"Nuovo\"></A></th>";

    while($row = $result->fetch_assoc()) {
                
                echo "<tr><td>".$row["id"]."</td><td>".date("d/m/Y", strtotime($row["data_contatto"]))."</td><td>".$row["cognome"]."</td><td>".$row["nome"]."</td><td>".$row["email1"]."</td><td>".$row["telefono1"]."</td><td>".$row["note"]."</td><td>".$row["fase_contatto"]."</td><td>".$row["fonte_primaria"]."</td><td>".$row["canale_fonte"]."</td>"
                        . "<td width=24><a href='crm_lead_dettagli.php?id_leadcrm=".$row['id']."'><IMG SRC=\"../images/dettagli.png\" border=0 width=24 title=\"Dettagli\"></a></td>"
                        //. "<td width=24><a href='elenco_pacchetti_scaduti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/file_archive.png\" border=0 width=24 title=\"Pacchetti scaduti\"></a></td>"
                        //. "<td width=24><a href='ric_pacchetti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/ingressi.png\" border=0 width=24 title=\"Ingressi\"></a></td>"
                        . "<td width=24><a href='mailto:".$row["email1"]."'><IMG SRC=\"../images/file_send.png\" border=0 width=24 title=\"Invia dettaglio per email\"></a></td>"
                        . "<td width=24><a href='cancella.php?id_leadcrm=".$row['id']."' onclick='return confirm(\"Sicuro di voler eliminare il lead ".$row["cognome"]." ".$row["nome"]."  (ID # ".$row['id'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 title=\"Elimina pacchetto\"></a></tr>";
		}
		echo "</table>";
		} else { echo "<p align=center>Nessun risultato</P>"; }
	

$conn->close();
?>
<P>

<script>
	$(document).ready(function() {
                $(".se-pre-con").fadeOut("slow");
       });
</script>

</body>
</html>