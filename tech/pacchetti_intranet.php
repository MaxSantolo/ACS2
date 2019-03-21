<?php 

include("connect_prod.php"); 

//aggiorna data di scadenza dei pacchetti importati
$conn_prod_booking->query("UPDATE book_pacchetto SET data_fine = data_fine + INTERVAL 364 DAY, agg_annuale = 'Y', data_agg_annuale = curdate() WHERE agg_annuale = 'N' AND tipo_pacchetto != 'SHA'");
// STR_TO_DATE(SUBSTR(note,32,11),'%d/%m/%Y') = data_fine
mysql_close($conn_prod_booking);

?>
