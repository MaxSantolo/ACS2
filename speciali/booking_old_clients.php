<head>
    <title>Sale del mese in corso per booking</title>
    <link rel="stylesheet" type="text/css" href="../css/baseline.css">
    
<style>

#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:medium;
    border-collapse: collapse;
    width: 75%;
	margin-left:auto;
	margin-right:auto;
    text-align: center;
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
    background-color: #ff9900;
	opacity:0.9;
    color: white;
    text-align: center;
}


body {
  /* Location of the image */
  background-image: url(../images/sfondo.jpg);
  
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

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>  
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>  
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 
<script type="text/javascript" src="../tech/jexport/tableExport.js"></script>
<script type="text/javascript" src="../tech/jexport/jquery.base64.js"></script>


<body>

    
<form action="" method="post">

        <table id="tabellaricerca">
        <div class="hit-the-floor">Clienti che non prenotano da </div></h3><BR>
        
                <TR><TD colspan="6" width="400"><P ALIGN="center"><STRONG>Strumenti di ricerca</STRONG><BR><font size="2">lasciare un campo vuoto equivale ad estrarne tutti i valori</font></P></TD></TR>
                <TR style="font-family:Georgia, Garamond, Serif;font-size:small;">
                        <TD><strong>A partire dal: </strong></TD>
                        <TD><input type="date" name="dal" style="color:black" value="<?php echo isset($_POST['dal']) ? $_POST['dal'] : '' ?>"></TD>
                        <TD><strong>Periodo di "assenza": </strong></TD>
                        <TD><select name="periodo" style="color:black"><option value="1MESE" selected>1 Mese</option><option value="3MESI">3 Mesi</option><option value="6MESI">6 Mesi</option></select></TD>
                        <TD><strong>Sede: </strong></TD>
                        <TD><select name="sede" style="color:black"><option value="%" selected>TUTTI</option><option value="1">EUR</option><option value="2">BOEZIO</option><option value="3">REGOLO</option></select></TD>
                </TR>
<!--                <TR style="font-family:Georgia, Garamond, Serif;font-size:small;">
                        <TD><strong>Ingresso: </strong></TD>
                        <TD><select name="ingresso" id="ingresso" style="color:black" value="<?php echo isset($_POST['ingresso']) ? $_POST['ingresso'] : '' ?>"><option value="">Tutti</option><option value="3531">Boezio 4c - BSide</option><option value="3532">Via Boezio, 6</option><option value="3533">Via Boezio, 6 - I piano</option></select></TD>
                        <TD><strong>Codice: </strong></TD>
                        <TD><input name="codice" style="color:black" value="<?php echo isset($_POST['codice']) ? $_POST['codice'] : '' ?>"></TD>
                        <TD><strong>Tipo: </strong></TD>
                        <TD>Tutti <input type="radio" name="tipo" value="ch" value="<?php echo isset($_POST['tipo']) ? $_POST['tipo'] : '' ?>" checked="checked"/>Ingresso  <input type="radio" name="tipo" value="checkin"/>Uscita <input type="radio" name="tipo" value="checkout"/></TD>
                </TR> -->
               <TR><TD colspan="6" style="color:black;text-align:center"><input type="submit" name="button" value="CERCA"></TD></TR>
        </table>
    </form>

<?php

include ('../tech/connect_prod.php');

        if (isset($_POST["button"])) { 
            
            if ($_POST["dal"] == "") { $dal = '2015-01-01'; } else { $dal = $_POST["dal"]; }
            
            
            $sede = $_POST["sede"];
            
            switch ($_POST['periodo']) {
                case "1MESE":
                    $periodo = 'INTERVAL 1 MONTH';
                break;
                case "3MESI":
                    $periodo = 'INTERVAL 3 MONTH';
                break;
                case "6MESI":
                    $periodo = 'INTERVAL 6 MONTH';
                break;
                }
 
        $sql = "SELECT max(data) AS datamax, book_account.nome AS nomecli, book_account.email AS email, book_risorsa.nome AS nomeris, book_risorsa.id_sede as idsede "
                . "FROM book_occupazione, book_account, book_risorsa "
                . "WHERE "
                . "data_annullamento IS NULL and data_conferma IS NOT NULL and book_occupazione.id_account = book_account.id and book_occupazione.id_risorsa = book_risorsa.id AND book_risorsa.id_sede like '".$sede."' "
                . "GROUP BY book_occupazione.id_account "
                . "HAVING datamax between '".$dal."' AND curdate() - ".$periodo." "
                . "ORDER BY id_sede asc, datamax desc, book_account.nome asc";
            
                
                
        } else {

	$sql = "SELECT max(data) AS datamax, book_account.nome AS nomecli, book_account.email AS email, book_risorsa.nome AS nomeris, book_risorsa.id_sede as idsede "
                . "FROM book_occupazione, book_account, book_risorsa "
                . "WHERE "
                . "data_annullamento IS NULL and data_conferma IS NOT NULL and book_occupazione.id_account = book_account.id and book_occupazione.id_risorsa = book_risorsa.id "
                . "GROUP BY book_occupazione.id_account "
                . "HAVING datamax between '2017-01-01' and curdate() - INTERVAL 3 MONTH "
                . "ORDER BY id_sede asc, datamax desc, book_account.nome asc";
        }
	
        $result = $conn_prod_booking->query($sql);
	$num_righe = $result->num_rows;

		if ($result->num_rows > 0) {
                    
		echo "<table id=customers><thead><tr><th>DATA</th><th>NOME</th><TH>EMAIL</th><th>ULTIMA RISORSA PRENOTATA</th><th>SEDE</th></thead></tr>";
		// output
		while($row = $result->fetch_assoc()) {
                
                switch ($row['idsede']) {
                case 1:
                    $centro = 'EUR';
                break;
                case 2:
                    $centro = 'BOEZIO';
                break;
                case 3:
                    $centro = 'REGOLO';
                break;
                }
                    
                    
                echo "<tr><td>".date('d/m/y', strtotime($row["datamax"]))."</td><td>".$row["nomecli"]."</td><td>".$row["email"]."</td><td>".$row["nomeris"]."</td><td>".$centro."</td></tr>";
				
                    }
                } else { echo "<DIV style=\"font-family:Georgia, Garamond, Serif;font-size:12pt;background-color:rgba(200,200,200,0.7);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:auto;margin-right:auto; width: 40%;\"><p align=center><b>Nessun risultato</b></P></div>";
			}
   


$conn->close();
$conn2->close();
$conn_prod_booking->close();
?>

