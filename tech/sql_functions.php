<?php 

include ("functions.php");
require_once ('simple_html_dom.php');

copy("http://192.168.1.40/tech/elenco_pacchetti.php", "dati_ore.html");
copy("http://192.168.1.40/tech/elenco_pacchetti.php", "esportazioni/dati_ore_".date("Ymd").".html");

include("connect.php"); 
include("connect_prod.php");

$table = file_get_html('dati_ore.html');

//importa a somma gli ingressi di oggi (query sul file elenco_pacchetti.php

foreach($table ->find('tr') as $tr){ 
    
    $azienda = $tr->find('td', 0)->plaintext; 
    $data = $tr->find('td', 1)->plaintext; 
    $accessi = $tr->find('td', 2)->plaintext; 
    $codice = $tr->find('td', 3)->plaintext; 
    $auth = $tr->find('td', 4)->plaintext; 
    $ore = $tr->find('td', 5)->plaintext; 
   
    $result = $conn->query("INSERT INTO acs_dati_ore (azienda, codice, auth, data_ingressi, ore) VALUES ('".$azienda."', '".$codice."', '".$auth."', '".$data."', '".$ore."')"); // importa dati badge
      
if (!$result) {
    echo "ERRORE NELLA CONNESSIONE AL DATABASE\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
    }
}

//raggruppa codici 010672 (Maretto) e 030973 (Fornario)
$conn->query("UPDATE acs_dati_ore SET codice = '010672' WHERE codice = '030973'");

//raggruppa codici 001976 (Glowreous) e 001977 (Glowreous)
$conn->query("UPDATE acs_dati_ore SET codice = '001976' WHERE codice = '001977'");

//raggruppa codici 321123 (IKBrokers) e 720567 (IKBrokers)
$conn->query("UPDATE acs_dati_ore SET codice = '326205 ' WHERE codice = '720567'");

//raggruppa codici Gandini 
$conn->query("UPDATE acs_dati_ore SET codice = '041089' WHERE codice = '110677'");


//importa dati badge
$conn->query("UPDATE acs_pacchetti destinazione, (SELECT sum(ore) as sommaore, acs_dati_ore.codice, data_ingressi, data_inizio_pacchetto, acs_pacchetti.codice as codetotest FROM acs_dati_ore, acs_pacchetti WHERE data_ingressi >= data_inizio_pacchetto and acs_dati_ore.codice = acs_pacchetti.codice group BY acs_dati_ore.codice) origine SET destinazione.ore_utilizzate = origine.sommaore WHERE origine.codice = destinazione.codice"); //aggiorna i pacchetti

//aggiorna dati fop
	$sql3 = "SELECT * FROM acs_pacchetti WHERE cestinato != '1' AND ( data_fine_pacchetto < curdate() OR ( ore_utilizzate >= (ore_totali_pacchetto + delta_ore) AND ore_totali_pacchetto > 0))";
        $result3 = $conn->query($sql3);
        if ($result3->num_rows > 0) {
            while($row = $result3->fetch_assoc()) {
                $pintocheck = $row['cod_auth'].$row['codice'];
                $codice = $row['codice'];
                $email = $row['email_notifiche'];
                $conn2->query("DELETE FROM visual_phonebook WHERE pin = '".$pintocheck."'");
                //cancella account area clienti
                $conn->query("DELETE FROM acs_utenti WHERE  pin = '".$codice."'");
                
                //cancella account wi-fi
                $conn_prod_radius->query("DELETE FROM radcheck WHERE username = '".$email."' ");
                $conn_prod_radius->query("DELETE FROM radreply WHERE username = '".$email."' ");
                }
        }
include ('mail_contratto_scaduto.php');

//archivia pacchetti scaduti
$conn->query("INSERT INTO acs_pacchetti_scaduti (acs_pacchetti_scaduti.azienda, acs_pacchetti_scaduti.codice, acs_pacchetti_scaduti.cod_auth, acs_pacchetti_scaduti.ore_utilizzate, acs_pacchetti_scaduti.ore_totali, acs_pacchetti_scaduti.tipo, acs_pacchetti_scaduti.email_notifiche, acs_pacchetti_scaduti.delta_ore, acs_pacchetti_scaduti.data_inizio, acs_pacchetti_scaduti.data_fine)
    SELECT acs_pacchetti.azienda, acs_pacchetti.codice, acs_pacchetti.cod_auth, acs_pacchetti.ore_utilizzate, acs_pacchetti.ore_totali_pacchetto, acs_pacchetti.tipo, acs_pacchetti.email_notifiche, acs_pacchetti.delta_ore, acs_pacchetti.data_inizio_pacchetto, acs_pacchetti.data_fine_pacchetto
    FROM acs_pacchetti
    WHERE cestinato != '1' AND ( data_fine_pacchetto < curdate() OR ( ore_utilizzate >= (ore_totali_pacchetto + delta_ore) AND ore_totali_pacchetto > 0) )");

//cancella pacchetti scaduti da acs_pacchetti
$conn->query("DELETE FROM acs_pacchetti WHERE cestinato != '1' AND ( data_fine_pacchetto < curdate() OR ( ore_utilizzate >= (ore_totali_pacchetto + delta_ore) AND ore_totali_pacchetto > 0))");



$conn->close();
$conn2->close();
$conn_prod_radius->close();

?>
