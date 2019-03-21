<?php 

include (".../tech/functions.php");
include ("../tech/connect.php");
include ("../tech/connect_prod.php");
include ("session.php");
require ("../tech/class/PHPMailerAutoload.php");

?>
<head>
    <title>Inserisci Nuovo Contratto</title>
      <link rel="stylesheet" type="text/css" href="../css/baseline.css">
<style>
#curvochiaro {
    border-radius: 15px;
    background: #dddddd;
    opacity: 1;
    padding: 20px; 
    width: 800px;
    margin: auto;
    font-size:10pt;
    font-family:Verdana;
    font-weight:bold;
    color:#23238e;
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

    <script type="text/javascript">
        function random() {
        document.getElementById('codice').value = (Math.floor(Math.random() * 900000)+100000); }
    </script>


</head>

<?php include ('menu/menu.php'); ?>
<body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 

<div class="hit-the-floor">CHECK-IN</div><BR>

    <form action="" method="post" name="inserisci_pacchetto">

        <table id="tabellains">
        
                <TR><TD colspan="2"><P ALIGN="center"><STRONG>Inserisci il contratto da attivare</STRONG><BR><font size="2">i campi indicati sono tutti obbligatori</font></P></TD></TR>
                <TR><TD><strong>Azienda: </strong></TD><TD width="200"><input name="azienda" style="color:black" value="<?php echo isset($_POST['azienda']) ? $_POST['azienda'] : $_GET['nome'] ?>" required></TD></TR>
                <TR><TD><strong>A partire dal: </strong></TD><TD><input type="date" name="dal" style="color:black" value="<?php echo isset($_POST['dal']) ? $_POST['dal'] : $_GET['dal'] ?>" required></TD></TR>
                <TR><TD><strong>Scade il: </strong></TD><TD><input type="date" name="al" style="color:black" value="<?php echo isset($_POST['al']) ? $_POST['al'] : $_GET['al'] ?>" required></TD></TR>
                <TR><TD><strong>Codice (6 cifre): </strong></TD><TD><input id="codice" name="codice" style="color:black" value="<?php echo isset($_POST['codice']) ? $_POST['codice'] : $_GET['codice'] ?>" required><a id='linkrnd' title='GENERA CASUALMENTE' href="#" onclick="random(); return false;"><img src='../images/dado.png' align="middle" height='24' hspace='10' title='GENERA CASUALMENTE'></a></TD></TR>
                <TR><TD><strong>Tipo contratto: </strong></TD><TD>
                                <select name="pacchetto" style="color:black" required>
                                    <option value="<?php echo isset($_POST['pacchetto']) ? $_POST['pacchetto'] : $_GET['pacchetto'] ?>"><?php echo isset($_POST['pacchetto']) ? $_POST['pacchetto'] : $_GET['pacchetto'] ?></option>
                                    <option value="10">B-Side 10 ore</option>
                                    <option value="50">B-Side 50 ore</option>
                                    <option value="100">B-Side 100 ore</option>
                                    <option value="RESIDENT">Resident</option>
                                    <option value="NOMAD">Nomad</option>
                </TD></TR>
                <TR><TD><strong>Postazione (solo il numero): </strong></td><td><input name="postazione" style="color:black"></TD></TR>
                <TR><TD><strong>Email: </strong></TD><TD><input name="email" style="color:black" value="<?php echo isset($_POST['email']) ? $_POST['email'] : $_GET['email'] ?>" required></TD></TR>
                <TR><TD colspan="2" style="color:black;text-align:center"><input type="submit" name="button" value="INSERISCI"></TD></TR>
        </table>
    </form>
    <div id="curvochiaro"><a href="edit_pacchetto.php">TORNA INDIETRO</a></div>
</body>
<?php


include ('../tech/connection.php');

if (isset($_POST["button"])) {
        
                        if (isset($_GET['nome'])) { $azienda = $_GET['nome']; } else { $azienda = mysqli_real_escape_string($conn, $_POST['azienda']); } //se viene passata l'azienda la uso altrimenti la leggo dal form
                        if (isset($_GET['email'])) { $email = $_GET['email']; } else { $email = $_POST['email']; } //se viene passata la mail la uso altrimenti la leggo dal form
                        if (isset($_GET['codice'])) { $codice = $_GET['codice']; } else { $codice = $_POST['codice']; } //se viene passato il codice la uso altrimenti la leggo dal form
                        if (isset($_GET['auth'])) { $auth = $_GET['auth']; } else { $auth = '97'; } //se viene passato il codice autenticazione lo uso altrimento leggo dal form
                        if (isset($_GET['postazione'])) { $auth = $_GET['postazione']; } else { $postazione = $_POST['postazione']; } //se viene passata la mail la uso altrimenti la leggo dal form


    //campo azienda sostituisce - con _
    if (strpos($azienda, '-') !== false) {
        $azienda = str_replace('-','_', $fazienda);
    }

    //campo azienda sostituisce & con AND
    if (strpos($azienda, '&') !== false) {
        $azienda = str_replace('&',' AND ', $fazienda);
    }
    
	
	$dal = $_POST["dal"];
	$al = $_POST["al"];
	$pacchetto = $_POST["pacchetto"];
	
        

        
        
        $tipo_pacchetto = "";
        $specimen = '';
        $auth = '';
        
        $tcm = substr($codice, 0, 4);
        
        
                switch ($pacchetto) {
                    case "10":
                    $tipo_pacchetto = "BSIDE 10 ore";
                    $specimen = 'PACK10';
                    $auth = '97';
                break;
                    case "50":
                    $tipo_pacchetto = "BSIDE 50 ore";
                    $specimen = 'PACK50';
                    $auth = '97';                    
                break;
                    case "100":
                    $tipo_pacchetto = "BSIDE 100 ore";
                    $specimen = 'PACK100';
                    $auth = '97';                    
                break;
                    case "RESIDENT":
                    $tipo_pacchetto = "RESIDENT";
                    $specimen = 'RESIDENT';
                    $auth = '99';                    
                break;
                    case "NOMAD":
                    $tipo_pacchetto = "NOMAD";
                    $specimen = 'NOMAD';
                    $auth = '97';                    
                break;
            
                }
        
        echo "<DIV id=\"curvochiaro\">";

        $errori = '';
        $messaggio = '<strong>Benvenuto!</strong><br><p>A seguire alcune informazioni per l\'utilizzo dei servizi BSide:</p>';
        $messaggio_interno = '<P>Sono stati creati:</P>';



        if (strlen($codice) != 6) { $errori .= "Controllate la lunghezza del campo codice.<BR>";}


        $checkcodice = $conn2->query("SELECT pin FROM visual_phonebook WHERE pin like '%".$codice."'"); //esiste da fop
        $checkcodice2 = $conn->query("SELECT codice FROM acs_pacchetti WHERE codice like '%".$codice."'"); //esiste come pacchetto
        if ($checkcodice->num_rows > 0) { $errori .= "Codice presente, controllate il <A HREF=\"http://10.8.0.10/fop2/\" target=\"blank\">posto operatore</a> della centrale telefonica.<BR>";}
        if ($checkcodice2->num_rows > 0) { $errori .= "Esiste un contratto con questo codice, controllate la <A HREF=\"edit_pacchetto.php\" target=\"blank\">pagina dei contratti attivi</a>.<BR>";}
        if ((((strtotime($al) - strtotime($dal)) / 86400) < 28)) { $errori .= "Un pacchetto non pu&ograve durare meno di un mese.<BR>";}
       
        echo($errori);
        echo "</div>";    
        
                    
        if ($errori == '') { //inserimento
           
            $conn->query("INSERT INTO acs_pacchetti (azienda, codice, cod_auth, data_inizio_pacchetto, data_fine_pacchetto, tipo, ore_totali_pacchetto, email_notifiche, postazione) VALUES ('".$azienda."', '".$codice."', '".$auth."', '".$dal."', '".$al."', '".$tipo_pacchetto."', '".$pacchetto."', '".$email."', '".$postazione."')");
            $conn->query("INSERT INTO acs_utenti (nome_azienda, pin, auth, email, tipo, specimen, livello) VALUES ('".$azienda."', '".$codice."', '".$auth."', '".$email."', 'BSIDE', '".$specimen."', 'UTENTE')");
            $conn2->query("INSERT INTO visual_phonebook (company, pin, email, tcm) VALUES ('".$azienda."', '".$auth.$codice."', '".$email."', '".$tcm."' )");

            $messaggio .= "<P>Pu&ograve; accedere all'<a href=\"https//192.168.1.40/acreaclienti/\">Area Clienti BSide</a> (raggiungibile dalla rete wifi di PickCenter) con Nome Utente = ". $email . " e Password = ".$codice." per prenotare la miniroom.</P>";
            $messaggio_interno .= "Contratto BSide<BR>Accesso all'Area Clienti BSide (email/codice)<BR>Posizione FOP<BR>";

            //inserimento accesso wi-fi
            if ($tipo_pacchetto == "RESIDENT") {

                include ('../tech/connect_prod.php');

                $conn_prod_radius->query("INSERT INTO radcheck (username, attribute, op, value) VALUES ('".$email."', 'User-Password', ':=', '".$codice."')");
                $conn_prod_radius->query("INSERT INTO radcheck (username, attribute, op, value) VALUES ('".$email."', 'Expiration', ':=', '".date('M d Y', strtotime($al))."')");
                $conn_prod_radius->query("INSERT INTO radreply (username, attribute, op, value) VALUES ('".$email."', 'Idle-Timeout', ':=', '28800')"); //Acct-Interim-Interval
                $conn_prod_radius->query("INSERT INTO radreply (username, attribute, op, value) VALUES ('".$email."', 'Acct-Interim-Interval', ':=', '60')");
                $conn_prod_radius->query("INSERT INTO radreply (username, attribute, op, value) VALUES ('".$email."', 'WISPr-Bandwidth-Max-Up', ':=', '4194304')");
                $conn_prod_radius->query("INSERT INTO radreply (username, attribute, op, value) VALUES ('".$email."', 'WISPr-Bandwidth-Max-Down', ':=', '4194304')");

                $conn_prod_radius->close();

                $messaggio .= "<P><strong>Per accedere alla rete WiFi potete collegarvi alla rete PCKSPOT e, appena appare la maschera di login, usare le seguenti credenziali:<br></strong> nome utente = ". $email . "<br>password = ".$codice."</P>";
                $messaggio_interno .= "Accesso WiFi Resident<BR>";
            }

            if ($tipo_pacchetto != "RESIDENT") {

                $messaggio .= "<P>Per accedere alla rete WiFi potete collegarvi alla rete PCKSPOT e, appena appare la maschera di login, usare le seguenti credenziali:<BR> nome utente = 'cowo_nomad'<BR>password = 'nomad'</P>";
                $messaggio_interno .= "Accesso WiFi Nomad<BR>";

            }

            //inserimento accredito punti

            if ($tipo_pacchetto == "RESIDENT" || $tipo_pacchetto == "NOMAD") {

                //creo contratto nel sistema a punti
                $conn_prod_punti->query("INSERT INTO anagrafica_punti (id_cliente_dom2, data_inizio, data_fine, nome, email, risorse, data_scadenza) VALUES ('B".$codice."','".$dal."','".$al."','".$azienda."','".$email."','[".$tipo_pacchetto."]','".date('Y-m-d', strtotime($al. '+90 days'))."')");

                $messaggio .= "<P>Abbiamo accreditato i punti inclusi nel tuo pacchetto utili a prenotare Day Office e Sale Riunioni sul nostro "
                              ."<a href=\"https://www.pickcenter.it/booking/\" style=\"color: #ff9a00\" target=\"_blank\">sistema di prenotazione online</a><BR><br>Ecco il riepilogo dei punti a tua disposizione:\"</P>";

                $datacc = $dal;

                $trovaid = $conn_prod_punti->query("SELECT id FROM anagrafica_punti WHERE id_cliente_dom2 = 'B".$codice."'")->fetch_assoc();

                while ($datacc < $al) { //finchè la data di accredito è minore della fine del contratto

                    $conn_prod_punti->query("INSERT INTO accrediti (id_cliente, data_accredito, punti, accreditato, scadenza) VALUES ('".$trovaid['id']."', '".$datacc."', '5', 'In attesa', '".date('Y-m-d', strtotime($datacc. '+60 days'))."' )");

                    $datacc = date('Y-m-d', strtotime($datacc . '+30 days'));

                }

                //genera_tabella_accrediti

                $dati_accrediti = $conn_prod_punti->query( "SELECT id_cliente, data_accredito, punti, accreditato, scadenza FROM accrediti where id_cliente = '".$trovaid['id']."' ORDER BY data_accredito ASC");
                if ($dati_accrediti->num_rows>0) {

                    $messaggio .= "<BR><table style=\"font-family:Verdana;font-size:14px;color:black;background:#eeeeee;opacity:0.85;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;margin-left:auto;margin-right:auto;border-spacing: 10px;border-collapse: separate;\">"
                        ."<tr style=\"background:#cccccc;\"><td>Data accredito</td><td>Punti</td><td>Scadenza</td></tr>";

                    while ($tabellacc = $dati_accrediti->fetch_assoc()) {
                        $messaggio .= "<tr><td>".date('d/m/Y', strtotime($tabellacc['data_accredito']))."</td><td>".$tabellacc['punti']."</td><td>".date('d/m//Y', strtotime($tabellacc['scadenza']))."</td></tr>";
                        }

                    $messaggio .= "</table>";
                    $messaggio_interno .= "Contratto per sistema a punti e relativi accrediti;<BR>";

                }
                //info pin
                $messaggio .= "<P>Le ricordiamo che per accedere ai nostri spazi dovete digitare il codice <strong>* ".$auth. " " . $codice ."</strong><BR>Se avete un contratto a consumo &egrave; importante che all'uscita digitiate  il codice <strong>* 90 " . $codice ." per fermare il calcolo del tempo di presenza in BSide.</strong></P>";
            }

            //invia mail con invito a iscriversi al booking e i relativi accrediti

            $dati_mail = $conn->query("SELECT azienda, email_notifiche FROM acs_pacchetti WHERE codice like '%".$codice."'")->fetch_assoc();

            $oggetto_welcome_bside = "Comunicazione Bside: Benvenuto!";
            $destinatario = $dati_mail['email_notifiche'];
            $nome_destinatario = $dati_mail['azienda'];

            $bodytext = "<table style=\"margin: 0pt auto; border: 3px solid #015d6e; width: 60%;border-radius:20px;-moz-border-radius:20px;-webkit-border-radius:20px;padding:5px;\">
            <tbody>
                <tr>
                    <td style=\"padding: 10px;\" valign=\"middle\">
				        <table cellspacing=\"2\" cellpadding=\"0\">
				            <tbody>
				                <tr>
				                    <td><a href=\"http://www.bsidecoworking.it\"><img style=\"border: 0;height:150px\" src=\"http://www.pickcenter.it/wp-content/uploads/2017/02/logoBside.jpg\" alt=\"BSide\" /></a></td>
				                </tr>
				            </tbody>
                        </table>
                    </td>
                    <td style=\"padding: 10px; text-align: right; font-family: verdana; font-size: 11px;\"><span>Sede legale:</span><br /> via Attilio Regolo, 19 Roma, 00192<br />Tel. 06 3280 3408
                        <div style=\"margin: 4px 0;\"><a href=\"https://twitter.com/Bsidecoworking\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/twitter.png\" alt=\"Twitter\" /></a> <a href=\"https://www.facebook.com/BSIDEcoworking/\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/facebook.png\" alt=\"Facebook\" /></a> <a href=\"https://www.linkedin.com/company/bside-coworking?report%2Esuccess=jhKVKsuT7eDCGn2lUldR1hK3GiS7tOcLrxZAuxKbGmSo00P5rvmFTBjodNS88dJL7w4F6e3Gbw58ZHH\"><img style=\"border: 0;\" src=\"http://www.pickcenter.it/img/sn/linkedin.png\" alt=\"LinkedIn\" /></a></div>
                    </td>
                </tr>
                <tr><td>".$messaggio."</td></tr>
			    <tr >
			        <tD>
                        <span style=\"color: #015d6e;\"><a href=\"mailto:info@bsidecoworking.it\" target=_blank><span style=\"color: #ff6600;\">Per qualsiasi altra informazione siamo a tua disposizione dal luned&igrave; al venerd&igrave; dalle ore 08:30 alle ore 18:30.</A></span></span><br />
                        <br /> Cordiali saluti,<br /><br /><B><font color=\"#ff6600\">Lo Staff</B></font><br /><img style=\"height: 50px;\" src=\"http://www.pickcenter.it/wp-content/uploads/2017/02/logoBside.jpg\"/>
			        </TD>
			     </tr>
			 </tbody></table>";

            $corpodeltestotxt = "Il messaggio &egrave; formattato in HTML, attivare tale modalit&agrave;.";

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->CharSet='UTF-8';
            $mail->Host = '10.20.20.227';
            $mail->SMTPAuth = false;
            $mail->From = "info@pickcenter.com";
            $mail->FromName = "BSide sistema notifica";
            $mail->AddAddress($destinatario,$nome_destinatario);
            $mail->AddBCC('boezio@pickcenter.com','Sede Boezio');
            $mail->AddBCC('roberta@pickcenter.com', 'Roberta');
            $mail->AddBCC('direzione@pickcenter.com', 'Direzione');
            $mail->AddReplyTo('info@pickcenter.com', 'Informazioni BSide');
            $mail->WordWrap = 50;
            $mail->IsHTML(true);
            $mail->Subject = $oggetto_welcome_bside;
            $mail->Body    = $bodytext;
            $mail->AltBody = $corpodeltestotxt;

            if(!$mail->Send())
            {
                $messaggio_interno .= $mail->ErrorInfo."<BR>";
            }
            else
            {
                $messaggio_interno .= "Inviata email informativa.<BR>";
            }

            //mostra messaggio interno

            echo("<strong><DIV id=\"curvochiaro\">". $messaggio_interno. "</strong></div>");
            header("Location: edit_pacchetto.php");
            
        }
             
}

$conn->close();
$conn2->close();
$conn_prod_punti->close();

?>