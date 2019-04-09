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

    
<!--    <form action="" method="post">

        <table id="tabellaricerca">
        <div class="hit-the-floor">Visualizzazione accessi - BOEZIO</div></h3><BR>
        
                <TR><TD colspan="6" width="400"><P ALIGN="center"><STRONG>Strumenti di ricerca</STRONG><BR><font size="2">lasciare un campo vuoto equivale ad estrarne tutti i valori</font></P></TD></TR>
                <TR style="font-family:Georgia, Garamond, Serif;font-size:small;">
                        <TD><strong>Azienda: </strong></TD>
                        <TD><input name="azienda" style="color:black" value="<?php echo isset($_POST['azienda']) ? $_POST['azienda'] : '' ?>"></TD>
                        <TD><strong>Dal: </strong></TD>
                        <TD><input type="date" name="dal" style="color:black" value="<?php echo isset($_POST['dal']) ? $_POST['dal'] : '' ?>"></TD>
                        <TD><strong>Al: </strong></TD>
                        <TD><input type="date" name="al" style="color:black" value="<?php echo isset($_POST['al']) ? $_POST['al'] : '' ?>"></TD>
                </TR>
                <TR style="font-family:Georgia, Garamond, Serif;font-size:small;">
                        <TD><strong>Ingresso: </strong></TD>
                        <TD><select name="ingresso" id="ingresso" style="color:black" value="<?php echo isset($_POST['ingresso']) ? $_POST['ingresso'] : '' ?>"><option value="">Tutti</option><option value="3531">Boezio 4c - BSide</option><option value="3532">Via Boezio, 6</option><option value="3533">Via Boezio, 6 - I piano</option></select></TD>
                        <TD><strong>Codice: </strong></TD>
                        <TD><input name="codice" style="color:black" value="<?php echo isset($_POST['codice']) ? $_POST['codice'] : '' ?>"></TD>
                        <TD><strong>Tipo: </strong></TD>
                        <TD>Tutti <input type="radio" name="tipo" value="ch" value="<?php echo isset($_POST['tipo']) ? $_POST['tipo'] : '' ?>" checked="checked"/>Ingresso  <input type="radio" name="tipo" value="checkin"/>Uscita <input type="radio" name="tipo" value="checkout"/></TD>
                </TR> 
                <TR><TD colspan="6" style="color:black;text-align:center"><input type="submit" name="button" value="CERCA"></TD></TR>
        </table>
    </form>-->

<?php

include ('../tech/connect_prod.php');



	
	$sql = "select *, time(sum(TIMEDIFF(ora_fine, ora_inizio))) as durata from book_occupazione, book_risorsa where data between curdate() + INTERVAL 1 DAY and curdate() + INTERVAL 15 DAY and id_risorsa = book_risorsa.id and (tipo_risorsa = 'SALA' or tipo_risorsa = 'EXCLUSIVE') and data_annullamento IS NULL and allestimento != 'DAY' group by date(data), vors_codice having time(sum(TIMEDIFF(ora_fine, ora_inizio))) <= '05:00:00' and DAYOFWEEK(data) NOT IN ('1','7') order BY data asc";
	$result = $conn_prod_booking->query($sql);
	$num_righe = $result->num_rows;

		if ($result->num_rows > 0) {
		echo "<table id=customers><thead><tr><th>DATA</th><th>SALA</th><TH>CENTRO</th><th>ORA INIZIO</th><th>ORA FINE</th><th>ALLESTIMENTO</th><th>PERSONE</th><th>LAVORAZIONE</th></thead></tr>";
		// output
		while($row = $result->fetch_assoc()) {
                
                switch ($row['id_sede']) {
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
                    
                    
                echo "<tr><td>".date('d/m/y', strtotime($row["data"]))."</td><td>".$row["nome"]."</td><td>".$centro."</td><td>".date('H:i', strtotime($row["ora_inizio"]))."</td><td>".date('H:i', strtotime($row["ora_fine"]))."</td><td>".$row['allestimento']."</td><td>".$row['numero_persone']."</td><td><input type='checkbox' /></td></tr>";
		}
		
		} else {
			echo "<DIV style=\"font-family:Georgia, Garamond, Serif;font-size:12pt;background-color:rgba(200,200,200,0.7);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;margin-left:auto;margin-right:auto; width: 40%;\"><p align=center><b>Nessun risultato</b></P></div>";
				}
   


$conn->close();
$conn2->close();
$conn_prod_booking->close();
?>

