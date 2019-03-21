<html>
<?php

include ('../tech/connect.php');
include ('../tech/functions.php');

echo listautenti();
//    $elenco = $conn->query("SELECT id_utente, nome_azienda FROM acs_utenti WHERE tipo = 'BSIDE'");
//    
//    echo ('<form><select>');
//    while ($riga = $elenco->fetch_assoc()) {
//
//                  unset($id, $nome);
//                  $id = $riga['id_utente'];
//                  $name = $riga['nome_azienda']; 
//                  echo '<option value="'.$id.'">'.$name.'</option>';
//    }
    echo('</select></form>');
?>
</html>