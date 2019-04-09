 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
 <html>
 <head>
 <title>Modifica Utente</title>
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
<script type="text/javascript"> $(function() { $("#i_al").datepicker({ dateFormat: "yy-mm-dd" }).val() }); </script>

</head>
 
 <?php
 
 include('../tech/connect.php');
 include('session.php');

 

$id = $_GET['id'];
$sql = "SELECT * FROM acs_utenti WHERE id_utente='".$id."'";
$result = $conn->query($sql);
 

if ($result->num_rows > 0) { 
 while($row = $result->fetch_assoc()) {
     
    // leggo i dati da acs_utenti
    $id_utente = $row['id_utente'];
    $nome = $row['nome_azienda'];
    $email = $row['email'];
    $auth = $row['auth'];
    $pin = $row['pin'];
    $tipo = $row['tipo'];
    $specimen = $row['specimen'];
    $livello = $row['livello'];
    
    }
 }
 else { echo "L'utente non esiste"; }
 
 if (isset($_POST["button"])) {
 

    $i_livello = $_POST['i_livello'];
    $updatesql =  "UPDATE acs_utenti SET livello = '".$i_livello."' WHERE id_utente = '".$id."'";
    $conn->query($updatesql);
    header('Location:elenco_utenti_ac.php');
 
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
     
<div class="hit-the-floor">Modifica utente</div><BR>
 <form action="" method="post">
 
       <table id="tabellamod">
        
                <TR><TD colspan="2" width="400"><P ALIGN="center"><STRONG>Modifica l'utente (ID: <?php echo $id; ?>)</STRONG><BR><font size="2">i campi indicati sono tutti obbligatori</font></P></TD></TR>
        <TR><TD><strong>Nome: </strong></TD><TD><input name="i_nome" style="color:black" value="<?php echo $nome; ?>" disabled></TD></TR>                
        <TR><TD><strong>Email: </strong></TD><TD><input name="i_email" style="color:black" value="<?php echo $email; ?>" disabled></TD></TR>
        <TR><TD><strong>Pin Code: </strong></TD><TD><input name="i_pin" style="color:black" value="<?php echo $pin; ?>" disabled></TD></TR>
        <TR><TD><strong>Tipo: </strong></TD><TD><input name="i_specimen" style="color:black" value="<?php echo $specimen; ?>" disabled></TD></TR>
        <TR><TD><strong>Privilegi: </strong></TD><TD><select name="i_livello" style="color:black"><option value="UTENTE">Utente Standard</option><option value="ADMIN">Amministratore</option><option value="DISABILITATO">Disabilitato</option></select></TD></TR>
        
                <TR><TD colspan="2" style="color:black;text-align:center"><input type="submit" name="button" value="APPLICA"></TD></TR>
        </table> 
 </form> <BR>
    <div id="curvochiaro"><a href="elenco_utenti_ac.php">TORNA INDIETRO</a></div>
 </body>
 </html> 
 
 