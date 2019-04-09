<?php 


include ("../tech/connect.php");
// include ("../tech/connect_prod.php");
include ("session.php");


?>

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
 
 

<body>

  <?php include('menu/menu.php'); ?>
         <div class="hit-the-floor">Inserisci prenotazione</div><BR>
<?php
 

require '../tech/functions.php';

// include ("../tech/connect_prod.php");

 require '../tech/class/PHPMailerAutoload.php';

 


 if (isset($_POST["button"])) {
 
    $selecttitolo = $conn->query("SELECT nome_azienda,email FROM acs_utenti WHERE id_utente = '".$_POST['i_titolo']."'")->fetch_assoc();
//    $riga = $selecttitolo ->fetch_assoc();
    
    $i_titolo = $selecttitolo['nome_azienda'];
    $destinatario = $selecttitolo['email'];

    $i_id = $_POST['i_titolo']; // il value dei campi option del campo select Ã¨ l'id utente
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
    $sqlsovrapp = "SELECT * FROM acs_prenotazioni WHERE '".date('Y-m-d H:i', $cdata_da)."' BETWEEN data_inizio AND data_fine OR '".date('Y-m-d H:i', $cdata_a)."' BETWEEN data_inizio AND data_fine" ;
    $sovrapposti = $conn->query($sqlsovrapp);
    
    //non c'è controllo di presenza di altra prenotazione, gli amministratori non hanno questa limitazione
    
    $errori = "";
   
    if ( date('Y-m-d', $cdata_da) < date('Y-m-d')) { $errori  = $errori . "Non puoi prenotare in data precedente ad oggi!<BR>"; }
    elseif ( (date('H:i', $cdata_da) < date('H:i') and date('Y-m-d', $cdata_da) == date('Y-m-d')) ) { $errori  = $errori . "Non puoi prenotare una sala con inizio precedente all'ora attuale.<BR>"; }
    elseif ( ($cdata_a - $cdata_da) / 3600 != 1 ) { $errori  = $errori . "La sala pu&ograve essere prenotata solo 1 ora alla volta, controllate gli orari<BR>"; }
    elseif ( $sovrapposti->num_rows > 0) { $errori  = $errori . "La sala &egrave prenotata da altro utente<BR>"; } //sovrapposizioni con altri utenti
    elseif ( (strpos($tipo_utente, 'NOMAD') !== false) && (date('H:i', $cdata_a) > '19:30'))  { $errori  = $errori . "L'orario di termine supera l'accesso massimo consentito dal vostro abbonamento <BR>";}
    elseif ( (strpos($tipo_utente, 'RESIDENT') !== false) && (date('H:i', $cdata_a) > '21:30'))  { $errori  = $errori . "L'orario di termine supera l'accesso massimo consentito dal vostro abbonamento <BR>";}
    
    if ($errori == "") {
    
    $updatesql =  "INSERT INTO acs_prenotazioni (data_fine, data_inizio, titolo, note, id_utente, id_oggetto, tipo) VALUES ('".$data_a_str."', '".$data_da_str."', '".$i_titolo."', '".$i_note."', '".$i_id."', '1', 'USER')";
    $conn->query($updatesql);
        
    //manda mail prenotazione
        
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet="UTF-8";
    //$mail->SMTPSecure = 'tls';
    $mail->Host = '10.20.20.227';
    //$mail->Port = 587;
    //$mail->Username = 'info@pickcenter.com';
    //$mail->Password = 'fm105pick';
    $mail->SMTPAuth = false;
    $mail->From = "info@bsidecoworking.it";
    $mail->FromName = "BSide sistema notifica";
    $mail->AddReplyTo("info@bsidecoworking.it", "Informazioni");
    $mail->AddAddress("max@swhub.io","MS");
    $mail->AddAddress("marta@bsidecoworking.it","MC");
    $mail->AddAddress("flavia@bsidecoworking.it","FS");
    $mail->AddAddress("boezio@pickcenter.com","BOE");
    $mail->AddAddress("cea@pickcenter.com","LC");
    $mail->AddAddress($destinatario,"");
//
    $mail->WordWrap = 50;
    $mail->IsHTML(true);
    $mail->Subject = "Prenotazione MINIROOM per ".$i_titolo." (".date('d/m/Y', $cdata_da)." ".date('H:i', $cdata_da)."/".date('H:i', $cdata_a).")";
    $mail->Body    = "L'utente <STRONG>".$i_titolo."</STRONG> ha prenotato la MINIROOM per il giorno <STRONG>".date('d/m/Y', $cdata_da)."</STRONG> dalle <STRONG>".date('H:i', $cdata_da)."</STRONG> alle <STRONG>".date('H:i', $cdata_a)."</STRONG><BR><STRONG>NOTE</STRONG>: ".$i_note." La prenotazione è stata inserita dall'operatore: ".$login_session;
    $mail->AltBody = "Email in formato html.";
    $mail->send();
    
    header('Location:calendario_generale.php');
 
    } else { echo "<div id=\"curvorosso\"><P>".$errori."</P></div>"; }
    }
 $conn->close();
?>
<form action="" method="post">
 
       <table id="tabellains">

                <TR><TD colspan="2"><P ALIGN="center"><STRONG>Inserisci una prenotazione</STRONG><BR><font size="2">i campi indicati sono tutti obbligatori</font></P></TD></TR>
                <TR><TD><strong>Titolo: </strong></TD><TD><select name="i_titolo" style="color:black">
                    <?php 
                        if ( isset($_GET['nome']) ) {
                            echo ('<option value="'.$_GET['id'].'">'.$_GET['nome'].'</option>');
                        } else { 
                            echo listautenti();
                        }
                    ?>
                </select></TD></TR>                
                <TR><TD><strong>Data: </strong></TD><TD><input type="date" name="i_data" style="color:black" value="" required></TD></TR>
                <TR><TD><strong>Ora inizio: </strong></TD><TD><input type="time" name="i_da_ore" style="color:black" value="" required></TD></TR>
                <TR><TD><strong>Ora fine: </strong></TD><TD><input type="time" name="i_a_ore" style="color:black" value="" required></TD></TR>
                <TR><TD><strong>Note: </strong></TD><TD><input name="i_note" style="color:black" value="<?php echo $note; ?>"></TD></TR>
                <TR><TD colspan="2" style="color:black;text-align:center"><input type="submit" name="button" value="INSERISCI"></TD></TR>
        </table> 
 </form> <BR>
    <div id="curvochiaro"><a href="calendario_generale.php">TORNA INDIETRO</a></div>
 </body>
 </html> 
 
 