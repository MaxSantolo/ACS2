 
 <html>
 <head>
 <title>Inserisci Prenotazione</title>
 <link rel="stylesheet" type="text/css" href="../css/baseline.css">
 <style>
#curvochiaro {
    border-radius: 15px;
    background: #dddddd;
    opacity: 1;
    padding: 20px; 
    margin: auto;
    width: 350px;
    font-size:12pt;
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




</head>
 
 <?php
 
 include('../tech/connect.php');
 include('session.php');
 require '../tech/class/PHPMailerAutoload.php';

    $id_leadcrmevento = $_GET["id_leadcrmevento"];


 if (isset($_GET["id_eventocrm"]))  {
// 
    $idcheck =  $_GET['id_eventocrm'];
    $datievento = $conn->query("SELECT * FROM acs_crm_eventi WHERE id_evento = '".$idcheck."'")->fetch_assoc();
         
    } 
       
    if (isset($_POST["button"]))  {
// 
    $ftipo = $_POST["f_tipo"];
    $fdata = $_POST["f_data"];
    
    $fdescrizione =  mysqli_real_escape_string($conn, $_POST["f_descrizione"]);

    
    
    
    $finseritoda =  $_POST["f_inserito"];
    
        if (isset($_GET["id_eventocrm"]))  { $insertsql = "UPDATE acs_crm_eventi SET tipo = '".$ftipo."', data_evento = '".$fdata."', descrizione = '".$fdescrizione."', inserita_da = '".$finseritoda."' WHERE id_evento = '".$idcheck."'";
        } else { $insertsql =  "INSERT INTO acs_crm_eventi (tipo, data_evento, descrizione, inserita_da, id_utente) VALUES ('".$ftipo."', '".$fdata."', '".$fdescrizione."', '".$finseritoda."', '".$id_leadcrmevento."')"; }
       
    $conn->query($insertsql);
        
    header('location: crm_lead_dettagli.php?id_leadcrm='.$id_leadcrmevento);
 
    } 
    
     
 
    
 $conn->close();
?>

<body>

  <?php include('menu/menu.php'); ?>
         <div class="hit-the-floor">Inserisci evento</div><BR>

<form action="" method="post">
 
       <table id="tabellains">

                <TR><TD colspan="2"><P ALIGN="center"><font size="2">i campi indicati sono tutti obbligatori</font></P></TD></TR>
                <TR><TD><strong>ID UTENTE: </strong></TD><TD><input type="text" name="f_id" style="color:black" value="<?php echo $datievento['id_evento'] != NULL ? $datievento['id_evento'] : $id_leadcrmevento; ?>" disabled></TD></TR>
                <TR><TD><strong>Data: </strong></TD><TD><input type="date" name="f_data" style="color:black" value="<?php echo $datievento['data_evento'] != NULL ? $datievento['data_evento'] : date('Y-m-d'); ?>" required></TD></TR>
                <TR><TD><strong>Tipo: </strong></TD><TD><select name="f_tipo" style="color:black"  required><option value="<?php echo $datievento['tipo'] != NULL ? $datievento['tipo'] : ''; ?>"><?php echo $datievento['tipo'] != NULL ? $datievento['tipo'] : ''; ?></option><option value="TELEFONATA">TELEFONATA</option><option value="EMAIL">EMAIL</option><option value="PERSONA">PASSATO DI PERSONA</option></select></TD></TR>                
                <TR><TD><strong>Descrizione: </strong></TD><TD><textarea name="f_descrizione" style="color:black" cols="50" rows="7" required><?php echo $datievento['descrizione'] != NULL ? $datievento['descrizione'] : ''; ?></textarea></TD></TR>
                <TR><TD><strong>Inserito da: </strong></TD><TD><input type="text" name="f_inserito" style="color:black" value="<?php echo $datievento['inserita_da'] != NULL ? $datievento['inserita_da'] : $login_session ; ?>" required></TD></TR>
                <TR><TD colspan="2" style="color:black;text-align:center"><input type="submit" name="button" value="<?php if (isset($_GET["id_eventocrm"])) { echo 'AGGIORNA';} else { echo 'INSERISCI'; } ?>"></TD></TR>
        </table> 
 </form> <BR>
    <div id="curvochiaro"><a href="crm_lead_dettagli.php?id_leadcrm=<?php echo $id_leadcrmevento ?>">TORNA INDIETRO</a></div>
 </body>
 </html> 
 
 