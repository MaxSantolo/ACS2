 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
 <html>
 <head>
 <title>Modifica Prenotazione</title>
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
#curvorosso {
    border-radius: 10px;
    background: #cc9400;
    opacity: 1;
    padding: 10px; 
    width: 700px;
    margin: auto;
    font-size:10pt;
    font-family:Verdana;
    font-weight:bold;
    color:#ffffff;
    text-align: center;
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
 <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 

<script type="text/javascript">
       $(function() {
               $("#i_al").datepicker({ dateFormat: "yy-mm-dd" }).val()
       });
</script>

  </head>
 
 <?php
 
 include('../tech/connect.php');
 include('session.php');

 

$id = $_GET['id'];
$sql = "SELECT * FROM acs_prenotazioni WHERE id_prenotazione='".$id."'";
$result = $conn->query($sql);
 

if ($result->num_rows > 0) { 
 while($row = $result->fetch_assoc()) {
// leggo i dati da acs_prenotazioni
    $id_utente = $row['id_utente'];
    $id_oggetto = $row['id_oggetto'];
    $data_inizio = $row['data_inizio'];
    $data_fine = $row['data_fine'];
    $note = $row['note'];
    $titolo = $row['titolo'];
    }
 }
 else { echo "La prenotazione non esiste"; }
 
 if (isset($_POST["button"])) {
 
    $i_titolo = $_POST['i_titolo'];
    $i_note = $_POST['i_note'];
    $data_da_str = date('Y-m-d', strtotime($_POST['i_data'])) . " ". $_POST['i_da_ore'];
    $data_a_str = date('Y-m-d', strtotime($_POST['i_data'])) . " ". $_POST['i_a_ore'];
    //$i_data_inizio = date('Y-m-d H:i', strtodate($data_da_str));
    //$i_data_fine = date('Y-m-d H:i', strtodate($data_a_str));
    
    //variabili per controlli
    
    $cdata_da = strtotime($_POST['i_data']. " ". $_POST['i_da_ore']);
    $cdata_a = strtotime($_POST['i_data']. " ". $_POST['i_a_ore']);       
    $cdata = strtotime ($_POST['i_data]']);
 
    
    //controllo sovrapposizioni
    $sqlsovrapp = "SELECT * FROM acs_prenotazioni WHERE ('".date('Y-m-d H:i', $cdata_da)."' BETWEEN data_inizio AND data_fine OR '".date('Y-m-d H:i', $cdata_a)."' BETWEEN data_inizio AND data_fine) AND id_utente <> '".$id_utente."'";
    $sovrapposti = $conn->query($sqlsovrapp);
    
    $errori = "";
   
    if ( date('Y-m-d', $cdata_da) < date('Y-m-d')) { $errori  = $errori . "Non puoi prenotare in data precedente ad oggi!<BR>"; }
    elseif ( (date('H:i', $cdata_da) < date('H:i') and date('Y-m-d', $cdata_da) == date('Y-m-d')) ) { $errori  = $errori . "Non puoi prenotare una sala con inizio precedente all'ora attuale.<BR>"; }
    elseif ( ($cdata_a - $cdata_da) / 3600 != 1 ) { $errori  = $errori . "La sala pu&ograve essere prenotata solo 1 ora alla volta, controllate gli orari<BR>"; }
    elseif ( $sovrapposti->num_rows > 0) { $errori  = $errori . "La sala &egrave prenotata da altro utente<BR>"; } //sovrapposizioni con altri utenti
    elseif ( (strpos($tipo_utente, 'NOMAD') !== false) && (date('H:i', $cdata_a) > '19:30'))  { $errori  = $errori . "L'orario di termine supera l'accesso massimo consentito dal vostro abbonamento <BR>";}
    elseif ( (strpos($tipo_utente, 'RESIDENT') !== false) && (date('H:i', $cdata_a) > '21:30'))  { $errori  = $errori . "L'orario di termine supera l'accesso massimo consentito dal vostro abbonamento <BR>";}
    
    if ($errori == "") {
    
    $updatesql =  "UPDATE acs_prenotazioni SET data_fine='".$data_a_str."', data_inizio='".$data_da_str."', titolo='".$titolo."', note='".$i_note."' WHERE id_prenotazione='".$id."'"; 
    $conn->query($updatesql);
    header('Location:crea_calendario.php');
 
    } else { echo "<div id=\"curvorosso\"><P>".$errori."</P></div>"; $errori = "";}
    }
 $conn->close();
?>

<body>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 

<script type="text/javascript">
       $(function() {
               $("#i_al").datepicker({ dateFormat: "yy-mm-dd" }).val()
       });
</script>
     
<div class="hit-the-floor">Modifica pacchetto</div><BR>
 <form action="" method="post">
 
       <table id="tabellamod">
        
                <TR><TD colspan="2" width="400"><P ALIGN="center"><STRONG>Modifica la prenotazione (ID: <?php echo $id; ?>)</STRONG><BR><font size="2">i campi indicati sono tutti obbligatori</font></P></TD></TR>
       <TR><TD><strong>Titolo: </strong></TD><TD><input name="i_titolo" style="color:black" value="<?php echo $titolo; ?>" disabled></TD></TR>                
                <TR><TD width="200"><strong>Data: </strong></TD><TD width="200"><input id ="i_al" name="i_data" style="color:black" value="<?php echo date('Y-m-d',strtotime($data_inizio)); ?>" required></TD></TR>
                <TR><TD><strong>Ora inizio: </strong></TD><TD><input name="i_da_ore" style="color:black" value="<?php echo date('H:i',strtotime($data_inizio)); ?>" required></TD></TR>
                <TR><TD><strong>Ora fine: </strong></TD><TD><input name="i_a_ore" style="color:black" value="<?php echo date('H:i',strtotime($data_inizio)+3600); ?>" required></TD></TR>
                <TR><TD><strong>Note: </strong></TD><TD><input name="i_note" style="color:black" value="<?php echo $note; ?>"></TD></TR>
                <TR><TD colspan="2" style="color:black;text-align:center"><input type="submit" name="button" value="APPLICA"></TD></TR>
        </table> 
 </form> <BR>
    <div id="curvochiaro"><a href="crea_calendario.php">TORNA INDIETRO</a></div>
 </body>
 </html> 
 
 