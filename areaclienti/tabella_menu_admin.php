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
    background-image: url(../images/sfondobside.jpg);
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
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
   <DIV style="text-align: center"><img src="../images/bsidelogo2.png"></div> 
   <hr>
   
<!--   <div id="curvochiaro">
       <strong>UTENTE</strong>: <?php echo $login_session; ?><BR>
       <strong>CONTRATTO</strong>: <?php echo $tipo_utente; ?><BR>
       <strong>USI DELLA MINIROOM INCLUSI</strong>: <?php echo $usi_mr; ?><BR>
       <strong>STAMPE INCLUSE</strong>: <?php echo $copie; ?></div>-->
       <table style="width:800px;height:400px;margin: 0 auto;text-align: center;">
           <tr>
               <td style="width: 200;"><a href="ins_pacchetto.php"><img src="../images/check-in.png" height="100" style="text-decoration: none;"><div style="height: 5;"></div>
                       <div id="vocemenu"><P id="pbianco">
                               Check-In</p></a></DIV></td>
               <td width="200" ><a href="edit_pacchetto.php"><img src="../images/contratti.png" height="100" style="text-decoration: none;"><div style="height: 5;"></div>
                       <div id="vocemenu"><P id="pbianco">
                       Contratti Attivi</p></DIV></a></td>
               <td width="200" ><a href="elenco_pacchetti_scaduti.php"><img src="../images/archivio_borsa.png" height="100" style="text-decoration: none;"><div style="height: 5;"></div>
                       <div id="vocemenu"><P id="pbianco">
                       Contratti Scaduti</p></DIV></a></td>
               <td width="200" ><a href="ric_pacchetti.php"><img src="../images/ingressi.png" height="100" style="text-decoration: none;"><div style="height: 5;"></div>
                       <div id="vocemenu"><P id="pbianco">
                       Accessi</p></DIV></a></td>
               
           </tr>
           <tr>
               <td width="200"><a href="calendario_generale.php"><img src="../images/calendario_generale.png" height="100" style="text-decoration: none;"><div style="height: 5;"></div>
                       <div id="vocemenu"><P id="pbianco">
                               Calendario Generale</p></DIV></a></td>
               <td width="200"><a href="elenco_utenti_ac.php"><img src="../images/users.png" height="100" style="text-decoration: none;"><div style="height: 5;"></div>
                       <div id="vocemenu"><P id="pbianco">
                               Utenti</p></DIV></a></td>
               <td width="200"><a href="crm_lead_elenco.php"><img src="../images/crm.png" height="100" style="text-decoration: none;"><div style="height: 5;"></div>
                       <div id="vocemenu"><P id="pbianco">
                               CRM</p></DIV></a></td>
               <td width="200"><a href = "logout.php"><img src="../images/disable_user.png" height="100"><div style="height: 5;"></div>
                       <div id="vocemenu"><P id="pbianco">    
                               Esci</p></a></div></td>
           </tr>
       </table>
       
      
   
   
</html>

