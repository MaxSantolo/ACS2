<!DOCTYPE html>
<html>
<head>
<style>
.element { 
    top:0;
    width: 100%;
    font-family: Verdana;
    font-size: 14px;
    font-variant: small-caps;

}    
    
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #5780ad;
    opacity: .85;
}

li {
    float: left;
    border-right: 1px solid #bbb;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 10px 12px;
    text-decoration: none;
}

li a:hover:not(.active) {
    background-color: #5b9be0;
}

.active {
    background-color: #5b9be0;
}
</style>
</head>
<body>
    <div class="element">
<ul>
    <li><a href="../areaclienti/benvenuto.php"><strong>Menu</strong></a></li>
    <?php if ($privilegi == "ADMIN"){ echo("<li><a href=\"../areaclienti/ins_pacchetto.php\"><strong>Check-In</strong></a></li>");  }?>
    <?php if ($privilegi == "ADMIN"){ echo("<li><a href=\"../areaclienti/edit_pacchetto.php\"><strong>Contratti</strong></a></li>");  }?>
    <?php if ($privilegi == "ADMIN"){ echo("<li><a href=\"../areaclienti/elenco_pacchetti_scaduti.php\"><strong>Contratti Scaduti</strong></a></li>");  }?>
    <?php if ($privilegi == "ADMIN"){ echo("<li><a href=\"../areaclienti/ric_pacchetti.php\"><strong>Accessi</strong></a></li>");  }?>
    <li><a href="../areaclienti/calendario_generale.php"><strong>Calendario</strong></a></li>
    <?php if ($privilegi == "ADMIN"){ echo("<li><a href=\"../areaclienti/elenco_utenti_ac.php\"><strong>Utenti</strong></a></li>");  }?>
    <?php if ($privilegi == "ADMIN"){ echo("<li><a href=\"../areaclienti/crm_lead_elenco.php\"><strong>CRM</strong></a></li>");  }?>
    <!-- <li><a href="#news">News</a></li>
  <li><a href="#contact">Contact</a></li> -->
  <li style="float:right"><a class="active" href="../areaclienti/logout.php"><?php echo $login_session; ?> (<strong>Logout</strong>)</a></li>
  <li style="float:right"><a class="active" href="mailto:max@swhub.io?subject=Segnalazione Area Clienti BSIDE" target="_blank"><img src="../images/email_segnala.png" title = "Segnala problemi o richiedi modifiche" width="17"></a></li>
  <?php if ($privilegi == "ADMIN"){ echo("<li style=\"float:right\"><a class=\"active\" href=\"\" target=\"_blank\"><img src=\"../images/impostazioni.png\" title = \"Impostazioni\" width=\"17\"></a></li>");  }?>
</ul>
</div>
</body>
</html>
