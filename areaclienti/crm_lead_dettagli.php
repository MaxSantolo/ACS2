<?php 

    include ("session.php");
?>
<html>
    <head>
        <title>Pagina contatto</title>
        <style>
 #customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    font-size:small;
    border-collapse: collapse;
    width: 100%;
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
    padding-top: 1px;
    padding-bottom: 1px;
    text-align: left;
    background-color: rgba(17,14,94,.7);
    color: white;
}    
            
            
    body {
    background-image: url(../../images/sfondobside.jpg);
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
    background-color: #464646;
    }            
    
    input, select { 
    background: rgba(17,14,94,.7); 
    border: 1px solid white; 
    color: #fff; 
    height: 30px; 
    line-height: 30px;
    width: 300px;
    padding: 0 10px;
    font-family: Verdana;
    font-size: 12pt;
    } 
        </style>
        <link rel="stylesheet" type="text/css" href="../../css/baseline.css">
    </head>
    
    
    <body>
        
<?php 
        
        require ("../tech/functions.php");
        include ("../tech/connect.php");
        include('menu/menu.php'); 
        
        

$id_leadcrm = $_GET["id_leadcrm"];
$result = $conn->query("SELECT * FROM acs_crm WHERE id = '".$id_leadcrm."'")->fetch_assoc()  ;              

            
    if (isset($_POST["salva"])) {
        
                $fdata = date('Y-m-d', strtotime($_POST['f_data']));
                $fcognome = $_POST['f_cognome'];
                $fnome = $_POST['f_nome'];
                $ftel1 = $_POST['f_tel1'];
                $ftel2 = $_POST['f_tel2'];
                $femail1 = $_POST['f_email1'];
                $femail2 = $_POST['f_email2'];
                $nome_azienda = $_POST['f_azienda'];
                $indazienda = $_POST['f_indazienda'];
                $fpiva = $_POST['f_piva'];
                $fcf = $_POST['f_cf'];
                $service = $_POST['f_servizio'];
                $fnote = $_POST['f_note'];
                $fonte_primaria = $_POST['f_fonte1'];
                $fcanale = $_POST['f_canale'];
                $ffase = $_POST['f_fase'];
                $fcodice = $_POST['f_codice'];
                $fidcontratto = $_POST['f_idcontratto'];
                
        if (isset($_GET["id_leadcrm"])) {
    

            $location = "Refresh:0";
            
            $update =    "UPDATE acs_crm SET data_contatto = '".$fdata."', cognome = '".$fcognome."', "
                .       "nome = '".$fnome."', "
                .       "telefono1 = '".$ftel1."', telefono2 = '".$ftel2."', email1 = '".$femail1."', email2 = '".$femail2."', "
                .       "nome_azienda = '".$nome_azienda."', indirizzo_azienda = '".$indazienda."', partita_iva = '".$fpiva."', codice_fiscale = '".$fcf."',"
                .       "servizio_int = '".$service."', note = '".$fnote."', fonte_primaria = '".$fonte_primaria."', canale_fonte = '".$fcanale."',"
                .       "fase_contatto = '".$ffase."', codice_accesso = '".$fcodice."', id_contratto = '".$fidcontratto."' "
                .       "where id = '".$id_leadcrm."'";
            $update_agg = $conn->query($update);
            
            } else { 
                             
                    
                    $update = "INSERT INTO acs_crm (data_contatto, cognome, nome, telefono1, telefono2, email1, email2, nome_azienda, indirizzo_azienda, partita_iva, codice_fiscale, "
                    . "servizio_int, note, fonte_primaria, canale_fonte, fase_contatto, codice_accesso, id_contratto) VALUES "
                    . "('".$fdata."', '".$fcognome."', '".$fnome."', '".$ftel1."', '".$ftel2."', '".$femail1."', '".$femail2."', '".$nome_azienda."', '".$indazienda."', '".$fpiva."',"
                    . "'".$fcf."', '".$service."', '".$fnote."', '".$fonte_primaria."', '".$fcanale."', '".$ffase."', '".$fcodice."', '".$fidcontratto."')";
                    $update_agg = $conn->query($update);
                    
                    $wheretogo = $conn->query("SELECT * FROM acs_crm ORDER BY id DESC LIMIT 1")->fetch_assoc();
                    $location = "Location: crm_lead_dettagli.php?id_leadcrm=".$wheretogo["id"];
                    
                   }
                
                
                header($location);
                 echo "<script type='text/javascript'>alert('Contatto inserito/aggiornato')</script>";
                }
        
                    
    if (isset($_POST["dom2"])) {
        
        $fcognome = $_POST['f_cognome'];
        $fnome = $_POST['f_nome'];
        
        include '../tech/mail_config.php';
        
        $mail->Subject = "Il contatto ID: ".$id_leadcrm." modificato, aggiornarlo in DOM2";
        $mail->Body    = $login_session. " ha richiesto l'aggiornamento su DOM2 del contatto ID: ".$id_leadcrm." (".$fcognome." ".$fnome.") <BR>Per raggiungere direttamente la pagina sull'area clienti <A HREF='http://192.168.1.40/areaclienti/crm_lead_dettagli.php?id_leadcrm=".$id_leadcrm."'>cliccare qui.</A>";
        $mail->AltBody = "La mail è in formato HTML.";
        $mail->AddAddress("max@swhub.io","MS");
        $mail->AddAddress("boezio@pickcenter.com","SB");
        $mail->AddAddress("marta@bsidecoworking.it","MC");
        $mail->AddAddress("flavia@bsidecoworking.it","FS");
        $mail->AddAddress("roberta.ghilardelli@pickcenter.com","RG");
        $mail->AddAddress("cea@pickcenter.com","LC");
        $mail->AddAddress("bucci@pickcenter.com","MB");
        $mail->send();
        
         echo "<script type='text/javascript'>alert('Richiesta inviata')</script>";
        
        
        
   }
                
       
            
?>
        
        <div class="hit-the-floor">SCHEDA DETTAGLIO DEL LEAD</div>
        
        <div style="height: 20px"></div>
        
        <form action="" method="post">
        
            <table id="tabellains" style="width: 95%; border: 1px solid black;">
            <tr><TD colspan="4"><p id="biancomaiusc">Dati Anagrafici</p></TD></tr>
            <tr>
                <td>Nome: </td><td><input type="text" name="f_nome" value="<?php echo $result["nome"] ?>"></td> <td>Cognome: </td><td><input type="text" name="f_cognome" value="<?php echo $result["cognome"] ?>"></td>
                <td rowspan="6">
                    Data contatto<BR><input type="date" name="f_data" value="<?php if (isset($_GET["id_leadcrm"])) { echo $result["data_contatto"];} else { echo date('Y-m-d');} ?>"><BR>
                    Stato Attuale<BR><select name="f_fase"><option value="<?php echo $result["fase_contatto"] ?>" selected=""><?php echo $result["fase_contatto"] ?></option><option value="CONTATTO">CONTATTO</option><option value="INTERESSATO">INTERESSATO</option><option value="QUOTAZIONE">QUOTAZIONE</option><option value="CLIENTE">CLIENTE</option><option value="CONVENZIONE">CONVENZIONE</option></select><HR>
                    Codice Accesso<BR><input type="text" name="f_codice" value="<?php echo $result["codice_accesso"] ?>"><BR>
                    ID Contratto<BR><input type="text" name="f_idcontratto" value="<?php echo $result["id_contratto"] ?>">
                </td>
                
            </tr>
            <tr>
               <td>Telefono: </td><td><input type="text" name="f_tel1" value="<?php echo $result["telefono1"] ?>"></td><td>Altro telefono: </td><td><input type="text" name="f_tel2" value="<?php echo $result["telefono2"] ?>"></td>
            </tr>
            <!-- <tr>
                <td>Telefono3</td><td>|CAMPO TELEFONO3| </td>
            </tr> -->
            <tr>
                <td>Email: </td><td><input type="text" name="f_email1" value="<?php echo $result["email1"] ?>"></td><td>Altra Email: </td><td><input type="text" name="f_email2" value="<?php echo $result["email2"] ?>"></td>
            </tr>
            <!-- <tr>
                <td>Email 3</td><td>|CAMPO EMAIL3| </td>
            </tr> -->
            <TR></tr>
            <tr><TD  colspan="4"><p id="biancomaiusc">Dati Fiscali</p></TD></tr>
            <tr>
                <td>Nome Azienda: </td><td><input type="text" name="f_azienda" value="<?php echo $result["nome_azienda"] ?>"></td><td>Indirizzo: </td><td><input type="text" name="f_indazienda" value="<?php echo $result["indirizzo_azienda"] ?>"></td>    
            </tr>
            <tr>
                <td>Codice Fiscale: </td><td><input type="text" name="f_cf" value="<?php echo $result["codice_fiscale"] ?>"></td><td>Partita IVA: </td><td><input type="text" name="f_piva" value="<?php echo $result["partita_iva"] ?>"></td>
            </tr><TR></tr>
            <tr><TD colspan="4"><p id="biancomaiusc">Dati commerciali</p></TD></tr>    
            <TR>
                <td>Servizio interesse: </td><td><select name="f_servizio"><option value="<?php echo $result["servizio_int"] ?>"><?php echo $result["servizio_int"] ?></option><option value="NOMAD">NOMAD</option><option value="RESIDENT">RESIDENT</option><option value="CARNET NOMAD">CARNET NOMAD</option><option value="CONSUMO">CONSUMO</option></select></td><td>Note: </td><td><input type="text" name="f_note" value="<?php echo $result["note"] ?>"></td>
            </TR>
            <TR>
                <td>Fonte Primaria: </td><td><SELECT name="f_fonte1"><option value="<?php echo $result["fonte_primaria"] ?>"><?php echo $result["fonte_primaria"] ?></option><option value="PICK_CENTER">PICK CENTER</option><option value="BSIDE">B-SIDE</option><option value="WORK_IT_OUT">WORK IT OUT</option></select></td><td>Fonte Canale: </td><td><input type="text" name="f_canale" value="<?php echo $result["canale_fonte"] ?>"></td>
            </TR><TR></tr>
            <tr><TD colspan="4"><p id="biancomaiusc">Eventi</p></TD></tr> 
            
            
            <tr><TD  colspan="5">
                 <?php 
                 $eventi = $conn->query("SELECT * FROM acs_crm_eventi WHERE id_utente = '".$id_leadcrm."' ORDER BY id_evento DESC");
                 if ($eventi->num_rows > 0) {
                    echo "<P><table id=customers><tr><th>ID</th><th>DATA</th><th>TIPO</th><th>DESCRIZIONE</th><th>INSERITA DA</th><th colspan=2 style=\"text-align:right\"><a href='crm_ins_evento.php?id_leadcrmevento=".$id_leadcrm."'><IMG SRC=\"../images/piu.png\" border=0 height=32 title=\"Nuovo\"></A></th>";
                            while($row = $eventi->fetch_assoc()) {
                                        echo "<tr><td>".$row["id_evento"]."</td><td>".date("d/m/Y", strtotime($row["data_evento"]))."</td><td>".$row["tipo"]."</td><td>".$row["descrizione"]."</td><td>".$row["inserita_da"]."</td>"
                                        . "<td width=28><a href='crm_ins_evento.php?id_eventocrm=".$row['id_evento']."&id_leadcrmevento=".$id_leadcrm."'><IMG SRC=\"../images/file_edit.png\" border=0 width=24 title=\"Modifica\"></a></td>"
                                        //. "<td width=24><a href='elenco_pacchetti_scaduti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/file_archive.png\" border=0 width=24 title=\"Pacchetti scaduti\"></a></td>"
                                        //. "<td width=24><a href='ric_pacchetti.php?codice=".$row['codice']."&dal=".$row["data_inizio_pacchetto"]."&al=".$row["data_fine_pacchetto"]."'><IMG SRC=\"../images/ingressi.png\" border=0 width=24 title=\"Ingressi\"></a></td>"
                                        //. "<td width=24><a href='mailto:".$row["email1"]."'><IMG SRC=\"../images/file_send.png\" border=0 width=24 title=\"Invia dettaglio per email\"></a></td>"
                                        . "<td width=28><a href='cancella.php?id_eventocrm=".$row['id_evento']."&utente=".$row['id_utente']."' onclick='return confirm(\"Sicuro di voler eliminare l'evento?\"  (ID # ".$row['id_evento'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 title=\"Elimina evento\"></a></tr>";
		}
		echo "</table>";
		} else { if (isset($_GET["id_leadcrm"])) { 
                            echo "<p id=\"biancomaiusc\">Nessun evento registrato. Aggiungi un evento<A HREF='crm_ins_evento.php?id_leadcrmevento=".$id_leadcrm."'><IMG SRC=\"../images/piu.png\" border=0 height=32 title=\"Nuovo\" align=\"center\"></a></P>"; 
                                                } else { 
                            echo "<p id=\"biancomaiusc\">L'utente non è ancora salvato. Non è possibile aggiungere eventi in questo momento.</P>"; 
                            
                        }
                    }

                
                ?>
                </td></tr>
            <TR><TD colspan="5" align='center'><input type="submit" name="salva" value="SALVA" style="color: #110e5e; background-color: rgba(255,255,255,.75); width: auto;font-weight: bold;">
                <input type="submit" name="dom2" value="RICHIEDI AGGIORNAMENTO DOM2" style="color: #110e5e; background-color: rgba(255,255,255,.75); width: auto;font-weight: bold;"></td>
            </TR>
        </table>
        
        
        </form>
 
    
    
    </body>
    
    <?php $conn->close(); $conn2->close(); ?>
</html>