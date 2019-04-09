<?php
include ('../tech/connect.php');
include ('session.php');
?>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<title>Calendario</title>
<link rel="stylesheet" href="calendario/dhtmlxscheduler_flat.css" type="text/css" charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/baseline.css">

<style type="text/css" >

            #curvochiaro {
                border-radius: 15px;
                background: #5b9be0;
                opacity: 1;
                padding: 10px; 
                width: 400px;
                margin: auto;
                font-size:12pt;
                font-family:Verdana;
                font-weight:bold;
                color:#ffffff;
                text-align: center;
            }

            #container { 
                width: 1100px; 
                margin: 0px auto; 
                
            }

            #pbianco  {
                color: #ffffff;
                font-family: Verdana;
                font-size: 14px;
                font-weight: bold;
            }	

            #customers {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                    font-size:small;
                border-collapse: collapse;
                width: 85%;
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
                background-color: #5780ad;
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

    /*event in day or week view*/
    .dhx_cal_event.admin_event div{
        background-color: #009966 !important;
        border-collapse: collapse;
        z-index: -1;
        color: white !important;
    }
    .dhx_cal_event.user_event div{
        background-color: #FF9933 !important;
        border-collapse: collapse;
                z-index: -1;
        color: white !important;
    }
 
    /*multi-day event in month view*/
    .dhx_cal_event_line.admin_event{
        background-color: #009966 !important;
        
        color: white !important;
    }
    .dhx_cal_event_line.user_event{
        background-color: #FF9933 !important;
        color: black !important;
    }
 
    /*event with fixed time, in month view*/
    .dhx_cal_event_clear.admin_event{
        color: #009966 !important;
    }
    .dhx_cal_event_clear.user_event{
        color: #FF9933 !important;
    }


</style>	
<script src="calendario/dhtmlxscheduler.js" type="text/javascript" charset="utf-8"></script>
<script src="calendario/ext/dhtmlxscheduler_minical.js" type="text/javascript" charset="utf-8"></script>
<script src="calendario/sources/locale/locale_it.js" charset="utf-8"></script>
<script src="calendario/ext/dhtmlxscheduler_multisource.js" type="text/javascript" charset="utf-8"></script>
<script src="calendario/ext/dhtmlxscheduler_recurring.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">
	function init() {
		
		scheduler.config.readonly = false;
                scheduler.config.show_loading = true;
                scheduler.config.update_render = true;
                scheduler.ignore_week = function(date){if (date.getDay() == 0) return true;};
                scheduler.ignore_month = function(date){if (date.getDay() == 0) return true;};
                scheduler.ignore_day = function(date){if (date.getDay() == 0) return true;};
                scheduler.config.xml_date="%Y-%m-%d %H:%i:%s";
		scheduler.config.prevent_cache = true;
		
		scheduler.config.lightbox.sections=[	
			{name:"description", height:130, map_to:"text", type:"textarea" , focus:true},
			{name:"location", height:43, type:"textarea", map_to:"details" },
			{name:"time", height:72, type:"time", map_to:"auto"}
		];
		scheduler.config.first_hour = 8;
                scheduler.config.last_hour = 22;
		scheduler.config.limit_time_select = true;
		scheduler.locale.labels.section_location="Location";
		scheduler.config.details_on_create=true;
		scheduler.config.details_on_dblclick=true;

                scheduler.templates.event_class = function (start, end, event) {
                        if (event.tipo == '') return "admin_event";
                        if (event.tipo == 'USER') return "user_event";
                        return ''; 
                };                
		
		scheduler.init('scheduler_here',new Date(),"week");
		scheduler.setLoadMode("month");
		scheduler.load(["dati_calendario_intranet.php","dati_calendario.php"]);
		
                
		var dp = new dataProcessor("dati_calendario.php");
		dp.init(scheduler);
                

	}
</script>
</head>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>


<body onload="init();">
    
    
    <?php include ('menu/menu.php'); ?>
    
            <div class="se-pre-con"></div>
    
    
    <div class="hit-the-floor">Calendario Generale</div><BR>
    
    
    <div style='width: 95%;text-align: center; padding :0px; background-color: #d1d1d0; opacity: .9; margin: auto;'><a href='ins_prenotazione_admin.php'><img src='../images/booking_conto3.png' height='50' title = 'PRENOTA PER IL CLIENTE'></A></div>
	<div id="scheduler_here" class="dhx_cal_container" style='width: 95%; height:720px; padding:0px; background-color: #d1d1d0; opacity: .9; margin: auto;'>
                <div class="dhx_cal_navline">
			<div class="dhx_cal_prev_button">&nbsp;</div>
			<div class="dhx_cal_next_button">&nbsp;</div>
			<div class="dhx_cal_today_button"></div>
			<div class="dhx_cal_date"></div>
			<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
			<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
			<div class="dhx_cal_tab" name="month_tab" style="right:60px;"></div>
		</div>
		<div class="dhx_cal_header"></div>
		<div class="dhx_cal_data"></div>		
        </div>
    
    <?php
    
    //tutte le prenotazioni presenti scadute
    /*
    $sql = "SELECT * FROM acs_prenotazioni WHERE data_inizio <= now()";
	$result = $conn->query($sql);
	$num_righe = $result->num_rows;
        

		if ($result->num_rows > 0) {
		echo "<BR><div class=\"hit-the-floor\">Prenotazioni passate</div><BR>"
                    . "<table id=customers><tr><th>DATA</th><th>DALLE ORE</th><th>ALLE ORE</th><th>Note</th>";
		
		while($row = $result->fetch_assoc()) {
                
                echo "<tr><td>".date('d/m/y', strtotime($row["data_inizio"]))."</td><td>".date('h:i', strtotime($row["data_inizio"]))."</td><td>".date('h:i', strtotime($row["data_fine"]))."</td><td>".$row["note"]."</td>";
                        //. "<td width=32><a href='mod_pacchetto.php?id=".$row['id_pacchetto']."'><IMG SRC=\"../images/file_edit.png\" border=0 width=24 alt=\"Modifica\"></a></td>"
                        //. "<td width=32><a href='canc_pacchetto.php?id=".$row['id_pacchetto']."&pin=".$row['codice']."' onclick='return confirm(\"Sicuro di voler eliminare il pacchetto di ".$row["azienda"]." (ID # ".$row['id_pacchetto'].")?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 alt=\"Elimina pacchetto\"></a></tr>";
		}
		echo "</table><HR width = 85%>";
                } else {
			echo "</table><div style=\"width: 50%; margin-left: auto; margin-right: auto; text-align: center; -webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;border:1px solid #ffffff; background-color: #5780ad;\"><P id=\"pbianco\">Non ci sono prenotazioni passate.</P></div>";
				}
        //prenotazioni future
                                
        $sql2 = "SELECT * FROM acs_prenotazioni WHERE data_inizio > now()";
	$result2 = $conn->query($sql2);
	$num_righe2 = $result2->num_rows;
        

		if ($result2->num_rows > 0) {
		echo "<div style=\"width: 50%; margin-left: auto; margin-right: auto; text-align: center; -webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;border:1px solid #ffffff; background-color: #5780ad;\"><P id=\"pbianco\">Prenotazioni future</P></div><BR>"
                    . "<table id=customers><tr><th>DATA</th><th>DALLE ORE</th><th>ALLE ORE</th><th>Note</th><th colspan=2></th>";
		
		while($row = $result2->fetch_assoc()) {
                
                echo "<tr><td>".date('d/m/y', strtotime($row["data_inizio"]))."</td><td>".date('H:i', strtotime($row["data_inizio"]))."</td><td>".date('H:i', strtotime($row["data_fine"]))."</td><td>".$row["note"]."</td>"
                        . "<td width=24><a href='mod_prenotazione.php?id=".$row['id_prenotazione']."'><IMG SRC=\"../images/file_edit.png\" border=0 width=24 title=\"Modifica\"></a></td>"
                        . "<td width=24><a href='canc_prenotazione.php?id=".$row['id_prenotazione']."' onclick='return confirm(\"Sicuro di voler eliminare la prenotazione ?\")'><IMG SRC=\"../images/file_delete.png\" border=0 width=24 title=\"Elimina pacchetto\"></a></tr>";
		}
		echo "</table>";
		} else {
			echo "</table><P align=\"center\"><a href=\"ins_prenotazione.php\"><IMG src=\"../images/book.png\" height=\"100\" title=\"PRENOTA ORA!\"></a></p>";
				}    */                        
        ?>
    
<script>
	$(document).ready(function() {
//            //$(window).load(function() {
                $(".se-pre-con").fadeOut("slow");
//            //};    
        });


</script>
         
</body>