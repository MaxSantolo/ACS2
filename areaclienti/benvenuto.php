<?php include('session.php');

include('menu/menu.php');

echo("<div style=\"height: 10px;\"></div>");

if ($privilegi == "ADMIN"){ include ('tabella_menu_admin.php');  }
else { include ('tabella_menu_utente.php'); }

?>



