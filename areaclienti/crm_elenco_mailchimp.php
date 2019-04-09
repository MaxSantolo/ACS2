
<?php 

include (".../tech/functions.php");
include ("../tech/connect.php");
// include ("../tech/connect_prod.php");
include ("session.php");

?>
<html>
<head>
    <title>Elenco LEAD per Mailchimp</title>    
<link rel="stylesheet" type="text/css" href="../css/baseline.css" />


<style>


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
    padding: 2px;
	
}


#customers tr:nth-child(even){background-color:#d2d2d2;opacity:0.9;}
#customers tr:nth-child(odd){background-color:#c2c2c2;opacity:0.9;}

#customers tr:hover {background-color: #bbb;}

#customers th {
    padding-top: 4px;
    padding-bottom: 4px;
    text-align: left;
    background-color: #bf2d2c;
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
<div class="se-pre-con"></div>
    <?php include ('menu/menu.php'); ?>
<div class="hit-the-floor">Elenco LEAD per Mail Chimp</div><BR>
    <form action="" method="post">

        <table id="tabellaricerca">
                <TR style="font-size:small;">
                    <TD><input type="date" name="dal" value="<?php echo isset($_POST['dal']) ? $_POST['dal'] : '' ?>"></TD>
                    <TD><input type="date" name="al" value="<?php echo isset($_POST['al']) ? $_POST['al'] : '' ?>"></TD>
                    <TD><input type="submit" name="button" value="CERCA"></TD>
                </TR>
        </table>
    </form>
    <P>
        
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        <script src="../tech/assets/js/jquerypp.custom.js"></script>
        <script src="../tech/assets/framewarp/framewarp.js"></script>
        <script src="../tech/assets/js/script.js"></script>

<?php



if (isset($_POST["button"])) {
    
        $dal = $_POST["dal"];
        $al = $_POST["al"];
        
	$sql = "SELECT * FROM acs_crm WHERE data_contatto BETWEEN '".$dal."' AND '".$al."'"  ;
	$result = $conn->query($sql);
        
}       else {
                $sql2 = "SELECT * FROM acs_crm ORDER BY data_contatto DESC" ;
                $result = $conn->query($sql2);
            }        

$num_righe = $result->num_rows;
                                
if ($result->num_rows > 0) {
echo "<P><table id=customers><tr><th>EMAIL</th><th>COGNOME</th><th>NOME</th><th>AZIENDA</th><th>SERVIZIO RICHIESTO</th>";

    while($row = $result->fetch_assoc()) {
                
                echo "<tr><td>".$row["email1"]."</td><td>".$row["cognome"]."</td><td>".$row["nome"]."</td><td>".$row["nome_azienda"]."</td><td>".$row["servizio_int"]."</td>";
		}
		echo "</table>";
		} else { echo "<p align=center>Nessun risultato</P>"; }
	

$conn->close();
?>
<P>

<script>
	$(document).ready(function() {
                $(".se-pre-con").fadeOut("slow");
       });
</script>

</body>
</html>