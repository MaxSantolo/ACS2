<?php 

include (".../tech/functions.php");
include ("../tech/connect.php");
include ("session.php");

?>

<html>
<head>
    <title>Elenco Utenti AreaClienti BSide</title>    
    <link rel="stylesheet" type="text/css" href="../css/baseline.css" />


<style>

#overlay {
    color:#ffffff;
    height:450px;
  }
div.contentWrap {
    height:441px;
    overflow-y:auto;
  }

#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:small;
    border-collapse: collapse;
    width: 75%;
	margin-left:auto;
	margin-right:auto;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 4px;
	
}


#customers tr:nth-child(even){background-color:#d2d2d2;opacity:0.9;}
#customers tr:nth-child(odd){background-color:#c2c2c2;opacity:0.9;}

#customers tr:hover {background-color: #bbb;}

#customers th {
    padding-top: 4px;
    padding-bottom: 4px;
    text-align: left;
    background-color: #ff791f;
	opacity:0.9;
    color: white;
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

</head>

<body>
<?php include ('menu/menu.php'); ?>
    <div class="hit-the-floor">Utenti Area Clienti</div></h3><BR>

    <form action="" method="post">

        <table id="tabellaricerca" >
        
        
                <TR><TD colspan="8" width="400"><P ALIGN="center"><STRONG>Strumenti di ricerca</STRONG><BR><font size="2">lasciare un campo vuoto equivale ad estrarne tutti i valori</font></P></TD></TR>
                <TR style="font-family:Georgia, Garamond, Serif;font-size:small;">
                        <TD><strong>Nome: </strong></TD>
                        <TD><input name="azienda" style="color:black" value="<?php echo isset($_POST['nome_azienda']) ? $_POST['nome_azienda'] : '' ?>"></TD>
                        <TD><strong>Email: </strong></TD>
                        <TD><input name="email" style="color:black" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>"></TD>
                        <TD><strong>Privilegi: </strong></TD>
                        <TD><select name="tipo" style="color:black"><option value="">Tutti</option><option value="UTENTE">Utente Normale</option><option value="ADMIN">Amministratore</option><option value="DISABILITATO">Disabilitato</option></select></TD>
                </TR>
                <TR><TD colspan="8" style="color:black;text-align:center"><input type="submit" name="button" value="CERCA"></TD></TR>
        </table>
    </form>
    <P>
        

<?php

if (isset($_POST["button"])) {
    
	$azienda = $_POST["azienda"];
	$email = $_POST["email"];
	$livello = $_POST["tipo"];
	
	$sql = "SELECT * FROM acs_utenti WHERE nome_azienda LIKE '%".$azienda."%' AND email LIKE '%".$email."%' AND livello LIKE '%".$livello."%' AND tipo = 'BSIDE' ORDER BY id_utente DESC" ;
	$result = $conn->query($sql);
	$num_righe = $result->num_rows;
        

		if ($result->num_rows > 0) {
		echo "<P><table id=customers><tr><th>ID</th><th>NOME/AZIENDA</th><th>Email/UserName</th><th>Password</th><th>Tipo</th><th>Livello</th><th colspan=4 style=\"text-align:right\"></th>";
		// output
		while($row = $result->fetch_assoc()) {
                
                echo "<tr><td>".$row["id_utente"]."</td><td>".$row["nome_azienda"]."</td><td>".$row["email"]."</td><td>".$row["pin"]."</td><td>".$row["specimen"]."</td><td>".$row["livello"]."</td>"
                . "<td width=24><a href=\"mod_utente.php?id=".$row['id_utente']."\"><IMG SRC=\"../images/file_edit.png\" border=0 width=24 title=\"Modifica\"></a></td>"
                . "<td width=24><a href=\"ric_pacchetti.php?codice=".$row['pin']."\"><IMG SRC=\"../images/ingressi.png\" border=0 width=24 title=\"Archivio accessi\"></a></td>"
                . "<td width=24><a href=\"ins_prenotazione_admin.php?id=".$row['id_utente']."&nome=".$row['nome_azienda']."\"><IMG SRC=\"../images/booking_conto3.png\" border=0 width=24 title=\"Modifica\"></a></td>"
                . "<td width=24><a href='canc_ut_ac.php?id=".$row['id_utente']."' onclick='return confirm(\"Sicuro di voler disabilitare ".$row["nome_azienda"]." (ID # ".$row['id_utente'].")?\")'><IMG SRC=\"../images/remove_user.png\" border=0 width=24 title=\"Disabilita Utente\"></a></td></tr>";
		}
		
		} else {
			echo "<p align=center>Nessun risultato</P>";
				}
	}
else{  
        $sql2 = "SELECT * FROM acs_utenti WHERE tipo = 'BSIDE' ORDER BY id_utente DESC";
        $result = $conn->query($sql2);
        echo "<P><table id=customers><tr><th>ID</th><th>NOME/AZIENDA</th><th>Email/UserName</th><th>Password</th><th>Tipo</th><th>Livello</th><th colspan=4 style=\"text-align:right\"></th>";
		// output
	while($row = $result->fetch_assoc()) {
              
        echo "<tr><td>".$row["id_utente"]."</td><td>".$row["nome_azienda"]."</td><td>".$row["email"]."</td><td>".$row["pin"]."</td><td>".$row["specimen"]."</td><td>".$row["livello"]."</td>"
                . "<td width=24><a href=\"mod_utente.php?id=".$row['id_utente']."\"><IMG SRC=\"../images/file_edit.png\" border=0 width=24 title=\"Modifica\"></a></td>"
                . "<td width=24><a href=\"ric_pacchetti.php?codice=".$row['pin']."\"><IMG SRC=\"../images/ingressi.png\" border=0 width=24 title=\"Archivio accessi\"></a></td>"
                . "<td width=24><a href=\"ins_prenotazione_admin.php?id=".$row['id_utente']."&nome=".$row['nome_azienda']."\"><IMG SRC=\"../images/booking_conto3.png\" border=0 width=24 title=\"Modifica\"></a></td>"
                . "<td width=24><a href='canc_ut_ac.php?id=".$row['id_utente']."' onclick='return confirm(\"Sicuro di voler disabilitare ".$row["nome_azienda"]." (ID # ".$row['id_utente'].")?\")'><IMG SRC=\"../images/remove_user.png\" border=0 width=24 title=\"Disabilita Utente\"></a></td></tr>";
	}
    
}

$conn->close();
?>

<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script src="http://cdn.jquerytools.org/1.2.6/full/jquery.tools.min.js"></script>

                                            
         
</body>
</html>