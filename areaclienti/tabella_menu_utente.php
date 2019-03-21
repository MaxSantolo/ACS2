<html>
      <head>
      <title>Area Clienti BSide</title>
   <link rel="stylesheet" type="text/css" href="../css/baseline.css">
      
   
   <STYLE>
  #pbianco  {
    color: #ffffff;
    font-family: Verdana;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
     
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
  #curvochiaro {
    border-radius: 10px;
    border: 1px solid white;
    background: #5b9be0;
    opacity: 1;
    padding: 10px; 
    width: 600px;
    margin: auto;
    font-size:10pt;
    font-family:Verdana;
    font-weight:NORMAL;
    color:#ffffff;
    text-align: left;
    
    }   
   </STYLE>
   </head>
   <body>

       <div style="text-align: center;"><img src="../images/bsidelogo2.png"></div>
  
   
   <div id="curvochiaro">
       <strong>UTENTE</strong>: <?php echo $login_session; ?><BR>
       <strong>CONTRATTO</strong>: <?php echo $tipo_utente; ?><BR>
       <strong>USI DELLA MINIROOM INCLUSI</strong>: <?php echo $usi_mr; ?><BR>
       <strong>STAMPE INCLUSE</strong>: <?php echo $copie; ?></div>
       <table style="width:800px;height:400px;margin: 0 auto;text-align: center">
           <tr style="height: 200px">
               <td style="width: 200;"><a href="crea_calendario.php"><img src="../images/calendar.png" height="100" style="text-decoration: none;"><BR>
                        <div id="vocemenu">
                           <P id="pbianco">Prenota la MINIROOM</p></a>
                        </DIV>
               </td>
               <td width="200" ></td>
               <td width="200" ></td>
               <td width="200" ></td>
           
           </tr>
           <tr height="200">
               <td width="200"></td>
               <td width="200"></td>
               <td width="200"></td>
               <td width="200"><h3><a href = "logout.php"><img src="../images/disable_user.png" height="80"><br>
                       <div id="vocemenu"><P id="pbianco">    
                       Esci</p></a></td>
           </tr>
       </table>
       
   </body>  
   
   
</html>

