<?php include 'session.php' ?>
 <html>
 <head>

     <title>Modifica Contratto</title>
 <link rel="stylesheet" type="text/css" href="../css/baseline.css">
 <style>
#curvochiaro {
    border-radius: 15px;
    background: #dddddd;
    opacity: 1;
    padding: 20px; 
    width: 400px;
    margin: auto;
    font-size:10pt;
    font-family:Verdana;
    font-weight:bold;
    color:#23238e;
    text-align: center;
}
  
body {
  background-image: url(../images/sfondobside.jpg);
  background-position: center center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
  background-color: #464646; 
    }
     
 </style>
 <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 

<script type="text/javascript">
       $(function() {
               $("#i_al").datepicker({ dateFormat: "yy-mm-dd" }).val()
       });
</script>

 
 </head>
 
<body>
<?php include ('menu/menu.php'); ?>
    
    <?php
 
 include('../tech/connect.php');
 include('../tech/connect_prod.php');

 

$id = $_GET['id'];
$sql = "SELECT * FROM acs_pacchetti WHERE id_pacchetto='".$id."' && cestinato != '1'";
$result = $conn->query($sql);
 

if ($result->num_rows > 0) { 
 while($row = $result->fetch_assoc()) {
// leggo i dati da acs_pacchetti
    $azienda = mysqli_real_escape_string($conn, $row['azienda']);
    $codice = $row['codice'];
    $cod_auth = $row['cod_auth'];
    $ore_utilizzate = $row['ore_utilizzate'];
    $ore_totali_pacchetto = $row['ore_totali_pacchetto'];
    $data_inizio = $row['data_inizio_pacchetto'];
    $data_fine = $row['data_fine_pacchetto'];
    $tipo = $row['tipo'];
    $email_notifiche = $row['email_notifiche'];
    $delta_ore = $row['delta_ore'];
    $postazione = $row['postazione'];
    }
 }
 else { echo "Il pacchetto non esiste!"; }
 
 if (isset($_POST["button"])) {
 
    
    if ($_POST['i_azienda'] != '') { $i_az = mysqli_real_escape_string($conn, $_POST['i_azienda']); } else { $i_az = $azienda; }
    if ($_POST['i_oretotali'] !='') { $i_oret = $_POST['i_oretotali']; } else { $i_oret = $ore_totali_pacchetto; }
    if ($_POST['i_dal'] !='') { $i_dal = $_POST['i_dal']; } else { $i_dal = $data_inizio; }
    if ($_POST['i_al'] !='') { $i_al = $_POST['i_al']; } else { $i_al = $data_fine; }    
    if ($_POST['i_email'] != '') { $i_email = $_POST['i_email']; } else { $i_email = $email_notifiche; }
    if ($_POST['i_pacchetto'] != '') { $i_tipo = $_POST['i_pacchetto']; } else { $i_pacchetto = $tipo; }    
    if ($_POST['i_deltaore'] != '') { $i_delta = $_POST['i_deltaore']; } else { $i_delta = $delta_ore; } 
    if ($_POST['i_oreusate'] != '') { $i_oreusate = $_POST['i_oreusate']; } else { $i_oreusate = $ore_utilizzate; }
    if ($_POST['i_codice'] != '') { $i_codice = $_POST['i_codice']; } else { $i_codice = $codice; }
    
    $updatesql =  "UPDATE acs_pacchetti SET codice = '".$i_codice."', ore_utilizzate = '".$i_oreusate."', data_inizio_pacchetto = '".date('Y-m-d', strtotime($i_dal))."', azienda='".$i_az."', ore_totali_pacchetto='".$i_oret."', data_fine_pacchetto='".date('Y-m-d', strtotime($i_al))."', tipo='".$i_tipo."', email_notifiche='".$i_email."', delta_ore='".$i_delta."', postazione='".$_POST['i_postazione']."' WHERE id_pacchetto='".$id."' "; 
    $conn->query($updatesql);
    
    $conn_prod_radius->query("UPDATE radcheck SET value ='".date('M d Y', strtotime($i_al))."' WHERE username = '".$i_email."' AND attribute = 'Expiration'");
    
    header('Location: edit_pacchetto.php');
 }
 
 $conn->close();
 $conn2->close();
 $conn_prod_radius->close();
 
?>
    
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 

 <div class="hit-the-floor">Modifica pacchetto</div><BR>   

 <form action="" method="post">
 
       <table id="tabellamod">

                <TR><TD colspan="2" width="400"><P ALIGN="center"><STRONG>Modifica il pacchetto (ID: <?php echo $id; ?>)</STRONG><BR><font size="2">i campi indicati sono tutti obbligatori</font></P></TD></TR>
                <TR><TD width="200"><strong>Nome/Azienda: </strong></TD><TD width="200"><input type="text" name="i_azienda" style="color:black" value="<?php echo stripslashes($azienda); ?>" ></TD></TR>
                <TR><TD><strong>Codice Autorizzazione: </strong></TD><TD><input name="i_codauth" style="color:black" value="<?php echo $cod_auth; ?>" ></TD></TR>
                <TR><TD><strong>Codice (6 cifre): </strong></TD><TD><input name="i_codice" style="color:black" value="<?php echo $codice; ?>" ></TD></TR>
                <TR><TD><strong>Tipo pacchetto: </strong></TD><TD><select name="i_pacchetto" style="color:black"><option value="<?php echo $tipo; ?>" selected><?php echo $tipo; ?></option><option value="BSIDE10">BSide 10 ore</option><option value="BSIDE50">BSide 50 ore</option><option value="BSIDE100">BSide 100 ore</option><option value="RESIDENT">Resident</option><option value="NOMAD">Nomad</option></select></TD></TR>
                <TR><TD><strong>Postazione: </strong></TD><TD><input type= "text" name="i_postazione" style="color:black" value="<?php echo $postazione; ?>" ></TD></TR>
                <TR><TD><strong>Data inizio: </strong></TD><TD><input type= "date" name="i_dal" style="color:black" value="<?php echo $data_inizio; ?>" ></TD></TR>
                <TR><TD><strong>Scade il: </strong></TD><TD><input type="date" name="i_al" style="color:black" value="<?php echo $data_fine; ?>" ></TD></TR>
                <TR><TD><strong>Ore utilizzate: </strong></TD><TD><input name="i_oreusate" style="color:black" value="<?php echo $ore_utilizzate; ?>" ></TD></TR>
                <TR><TD><strong>Ore totali: </strong></TD><TD><input name="i_oretotali" style="color:black" value="<?php echo $ore_totali_pacchetto; ?>" ></TD></TR>
                <TR><TD><strong>Ore gratuite: </strong></TD><TD><input name="i_deltaore" style="color:black" value="<?php echo $delta_ore; ?>"></TD></TR>
                <TR><TD><strong>Email: </strong></TD><TD><input type="email" name="i_email" style="color:black" value="<?php echo $email_notifiche; ?>" readonly="yes"></TD></TR>
                <TR><TD colspan="2" style="color:black;text-align:center"><input type="submit" name="button" value="APPLICA"></TD></TR>
        </table> 
 </form> <BR>
    <div id="curvochiaro"><a href="edit_pacchetto.php">TORNA INDIETRO</a></div>
    
 </body>
 </html> 
 
 