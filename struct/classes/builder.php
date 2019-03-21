<?php

class builder {

//costruisce l'intestazione di pagina (parametri sono il titolo della pagina e lo sfondo dalla cartella immagini)
public static function Header($title,$bg) {

    $bgurl = "../images/". $bg;
    header('Content-Type: text/html; charset=ISO-8859-1');
    echo '<html>
    <head>
        <title>'.$title. '</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="stylesheet" href="../../css/font-awesome/css/font-awesome.min.css">
        <link href="../../mdbootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../mdbootstrap/css/mdb.min.css" rel="stylesheet">
        <link href="../../mdbootstrap/css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="../../datatables/datatables.min.css"/>
        <link rel="stylesheet" type="text/css" href="../../css/baseline.css"/>
        <link rel="stylesheet" type="text/css" href="../../tech/datepicker/bootstrap-datepicker3.min.css"/>
        <link rel="stylesheet" type="text/css" href="../../tech/timepicker/jquery.timepicker.min.css"/>
        <link rel="stylesheet" type="text/css" href="../../tech/chosen/component-chosen.min.css"/>
        <style> body {
                background-image: url('.$bgurl.');
                background-position: center center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                background-color: #464646;
                }
        </style>
        <script>function showloader() {
                $("#loader").show();
              }
        </script>
    </head>
    <body>
    <div id="loader" class="loadingdiv">
        <img src="../../images/color_cube.gif" class="loadingimagediv">
    </div>
';


}
//include tutti gli script necessari
public static function Scripts() {


    echo '<script type="text/javascript" src="../../mdbootstrap/js/jquery-3.3.1.min.js"></script>
          <script type="text/javascript" src="../../mdbootstrap/js/bootstrap.min.js"></script>
          <script type="text/javascript" src="../../mdbootstrap/js/popper.min.js"></script>
          <script type="text/javascript" src="../../mdbootstrap/js/mdb.min.js"></script>
          <script type="text/javascript" src="../../datatables/datatables.min.js"></script>
          <script type="text/javascript" src="../../tech/datepicker/bootstrap-datepicker.min.js"></script>
          <script type="text/javascript" src="../../tech/datepicker/bootstrap-datepicker.it.min.js"></script>
          <script type="text/javascript" src="../../tech/timepicker/jquery.timepicker.min.js"></script>
          <script type="text/javascript" src="../../tech/chosen/chosen.jquery.min.js"></script>';


    echo '<script type="text/javascript" src="../../tech/jexport/tableExport.js"></script>
          <script type="text/javascript" src="../../tech/jexport/jquery.base64.js"></script>';


    echo '<script>
            function stampa() {
                   window.print();
                }
        </script>';
    echo "
    <script type=\"text/javascript\">
    
        function checkAllIntercoms(e) {
            var aa = document.querySelectorAll(\"input[type=checkbox]\");
            for (var i = 0; i < aa.length; i++){
                aa[i].checked = e;
            }
        }    
    </script>
";

    echo '</body>';

}

//configura la DataTable con il nome della tabella, la paginazione, la lunghezza della paginazione, la colonna da ordinare (0 è la prima) e l'ordinamento
public static function configDataTable($tablename,$paginate,$lenght,$ordcol,$ascdesc) {
    echo '<script type="text/javascript" class="init">
                $(document).ready( function () {
                    $(\'#'.$tablename.'\').DataTable({
                        paging: '.$paginate.',    
                        "pageLength": '.$lenght.',
                        "order": [[ '.$ordcol.', "'.$ascdesc.'" ]],
                        "language": {
                            "decimal": ",",
                            "emptyTable": "Nessun risultato",
                            "info": "da _START_ a _END_ di _TOTAL_",
                            "infoEmpty": "Nessun Risultato",
                            "infoFiltered": "(filtrato da un totale di _MAX_ accessi)",
                            "infoPostFix": "",
                            "thousands": ".",
                            "lengthMenu": "Mostra _MENU_ risultati",
                            "loadingRecords": "Caricamento...",
                            "processing": "Elaborazione...",
                            "search": "Ricerca rapida:",
                            "zeroRecords": "Nessuna corrispondenza",
                            "paginate": {
                            "first": "Primo",
                            "last": "Ultimo",
                            "next": "Prossimo",
                            "previous": "Precedente"
                        },
                    "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                    }
                }
            });                   
    });
    </script >';
}

public static function configGroupedDataTable($grpclmn,$tablename,$paging,$lenght,$ordercolumn,$ascdesc,$grpcolspan) {
    echo "<script>
            $(document).ready(function() {
                var groupColumn = ".$grpclmn.";
                var table = $('#".$tablename."').DataTable({
                    
                paging: '".$paging."',    
                \"pageLength\": '".$lenght."',
                \"order\": [[ ".$ordercolumn.", '".$ascdesc."' ]],
                \"language\": {
                               \"decimal\": \",\",
                               \"emptyTable\": \"Nessun risultato\",
                               \"info\": \"da _START_ a _END_ di _TOTAL_\",
                               \"infoEmpty\": \"Nessun Risultato\",
                               \"infoFiltered\": \"(filtrato da un totale di _MAX_)\",
                               \"infoPostFix\": \"\",
                               \"thousands\": \".\",
                               \"lengthMenu\": \"Mostra _MENU_ risultati\",
                               \"loadingRecords\": \"Caricamento...\",
                               \"processing\": \"Elaborazione...\",
                               \"search\": \"Ricerca rapida:\",
                               \"zeroRecords\": \"Nessuna corrispondenza\",
                               \"paginate\": {
                                              \"first\": \"Primo\",
                                              \"last\": \"Ultimo\",
                                              \"next\": \"Prossimo\",
                                              \"previous\": \"Precedente\"
                                            },
                               \"aria\": {
                                           \"sortAscending\": \": activate to sort column ascending\",
                                           \"sortDescending\": \": activate to sort column descending\"
                                         }
                            },
                \"columnDefs\": [
                        { \"visible\": false, \"targets\": groupColumn }
                    ],
                    \"order\": [[ groupColumn, 'asc' ]],
                    \"displayLength\": 25,
                    \"drawCallback\": function ( settings ) {
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
             
                        api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                            if ( last !== group ) {
                                $(rows).eq( i ).before(
                                    '<tr class=\"DataTableGroup\" style=\"background-color: #bf2d2c\"><td colspan=\"".$grpcolspan."\">'+group+'</td></tr>'
                                );
            
                                last = group;
                            }
                        } );
                    }
                } );
             
                // Order by the grouping
                $('#example tbody').on( 'click', 'tr.group', function () {
                    var currentOrder = table.order()[0];
                    if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                        table.order( [ groupColumn, 'desc' ] ).draw();
                    }
                    else {
                        table.order( [ groupColumn, 'asc' ] ).draw();
                    }
                } );
            } );
            
            </script>";
        }



//crea la barra di navigazione, la tabella dei parametri è quella scaricabile
public static function Navbar($table) {

    echo '<nav class="navbar navbar-expand-lg navbar-dark indigo">

    <a class="navbar-brand" href="..\menu.php"><img src="../../images/logo_acs2.png" width="100"></a>
    <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Clienti</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="..\whole_search.php?center=BOE&role=Clienti&bg=sfondo.jpg&thcolor=4CAF50" onclick="showloader()">BOEZIO</a>
          <a class="dropdown-item" href="..\whole_search.php?center=EUR&role=Clienti&bg=sfondoeur.jpg&thcolor=C44646" onclick="showloader()">EUR</a>
          <a class="dropdown-item" href="..\whole_search.php?center=REG&role=Clienti&bg=sfondo_regolo.jpg&thcolor=4CAF50" onclick="showloader()">REGOLO</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pick Center</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="..\whole_search.php" onclick="showloader()">Tutti gli Accessi</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="..\whole_search.php?role=Dipendente">Dipendenti</a>
          <a class="dropdown-item" href="..\whole_search.php?role=SmartWorkingHub&bg=sfondo2.jpg&thcolor=006273" onclick="showloader()">SmartWorkingHub</a>
          <a class="dropdown-item" href="..\whole_search.php?role=Fornitore&bg=sfondo_fornitori.jpg&thcolor=d01add" onclick="showloader()">Fornitori</a>
          <a class="dropdown-item" href="..\whole_search.php?role=Manutentore&bg=sfondo_pulizie.jpg&thcolor=455114" onclick="showloader()">Personale Pulizie</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Controllo presenze</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="..\work_attendance\search.php?role[]=Dipendente&role[]=SmartWorkingHub" onclick="showloader()">Dipendenti</a>
          <a class="dropdown-item" href="..\work_attendance\search.php?role[]=Fornitore&bg=sfondo_fornitori.jpg" onclick="showloader()">Fornitori</a>
          <a class="dropdown-item" href="..\work_attendance\search.php?role[]=Manutentore&bg=sfondo_pulizie.jpg" onclick="showloader()">Personale Pulizie</a>
        </div>
      </li>
      
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Accessi Uffici</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="../off_whole_search.php?grpclm=1&thcolor=2e3951&bg=officebg.png" onclick="showloader()">Report per ufficio</a>
          <a class="dropdown-item" href="../off_whole_search.php?grpclm=0&thcolor=2e3951&bg=officebg.png" onclick="showloader()">Report per data</a>
          <a class="dropdown-item" href="../off_whole_search.php?grpclm=3&thcolor=2e3951&bg=officebg.png" onclick="showloader()">Report per persona</a>
          <a class="dropdown-item" href="../off_whole_search.php?grpclm=2&thcolor=2e3951&bg=officebg.png" onclick="showloader()">Report per centro</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Gestione PIN</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="..\pinutils\addrbook.php?thcolor=42413f" onclick="showloader()">Rubrica Completa</a>
          <!--<a class="dropdown-item" href="..\pinutils\vbdetail.php" onclick="showloader()">Gestione contatto</a>-->
          <div class="dropdown-divider"></div>          
          <a class="dropdown-item" href="..\pinutils\intcom_grps.php?bg=techbg.jpg&thcolor=ff5500" onclick="showloader()">Gruppi di Uffici</a>
          <a class="dropdown-item" href="..\checkinday\checkinday.php?bg=sfondo_regolo.jpg&thcolor=590c0f" onclick="showloader()">Pin Day/Sale</a>
          <div class="dropdown-divider"></div>          
          <a class="dropdown-item" href="..\pinutils\pinslst.php?thcolor=015d6e&notpins=5" onclick="showloader()">PIN attivi</a>
          <a class="dropdown-item" href="..\pinutils\pinslst.php?thcolor=000&notpins=9" onclick="showloader()">PIN scaduti</a>
          <a class="dropdown-item" href="..\pinutils\namespinsdoubled.php?thcolor=110e5e" onclick="showloader()">PIN duplicati</a>
        </div>
      </li>

      </ul>
      <ul class="navbar-nav pull-right">';

      if ($_SESSION["user_id"] != NULL) {echo '<li class="nav-item"><a class="nav-link" href="..\..\logout.php" title="Click per logout">'.$_SESSION["user_name"].'</a></li>';}

      echo '<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 150px">Strumenti</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a href="" class="dropdown-item" data-toggle="modal" onclick="$(\'#'.$table.'\').tableExport({type:\'excel\',escape:\'false\'});">Esporta</a>
          <a href="" class="dropdown-item" data-toggle="modal" onclick="stampa()">Stampa</a>
          <a class="dropdown-item" href="../options/general.php?thcolor=000&bg=optbg.jpg">Opzioni</a>
          <a class="dropdown-item" href="../options/log.php?thcolor=000&bg=optbg.jpg">LOG</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="http://crm.pickcenter.com" target="_blank">Smart.Work.CRM</a>
          <a class="dropdown-item" href="http://10.8.0.10\fop2" target="_blank">FOP</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="mailto:max@swhub.io?subject=Segnalazione A.C.S" target="_blank">Segnala Problemi</a>
        </div>
      </li>  
       
      ';

    echo '</ul></div></nav>';

}
//avvia la sessione
function startSession() {
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("location:https://acs.pickcenter.com/index.php");
    }
}
//prepara il datepicker per tutte i campi nominati nell'array
public static function createDatePicker($array) {

    $script = '';
    $number = count($array);
    for($i=0;$i<$number;$i++) {
        $script .= "
        <script>
            $('#".$array[$i]."').datepicker({
                language: \"it\",
                daysOfWeekDisabled: \"0,6\",
                autoclose: true,
                format: 'dd-mm-yyyy'
            });
          </script>
        ";
    }
    return $script;
}
//prepara il timepicker per tutte i campi nominati nell'array
    public static function createTimePicker($array) {

        $script = '';
        $number = count($array);
        for($i=0;$i<$number;$i++) {
            $script .= "
        <script>
                $('#".$array[$i]."').timepicker({
                'timeFormat': 'H:i',
                'minTime': '07:00am',
                'maxTime': '10:00pm'
                
                });
        </script>
        ";
        }
        return $script;
    }



//crea il modale con l'elenco dei citofoni
public static function modalIntercoms($id,$title,$xclr,$hclr,$bodyid) {

    echo "
        <div class=\"modal fade left\" id=\"{$id}\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"AssInt\" aria-hidden=\"true\"\">
              <div class=\"modal-dialog modal-full-height modal-left\" role=\"document\" >
                    <div class=\"modal-content\" style=\"background-color: rgba(250,250,250,.85)\">
                            <div class=\"modal-header\" style=\"background-color: {$hclr};color: white;font-weight: bold\">
                                <h5 class=\"modal-title\" id=\"exampleModalLabel\">{$title}</h5>
                                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\" style=\"color: {$xclr}\">
                                <span aria-hidden=\"true\">&times;</span>
                                </button>
                            </div>
                            <div id=\"{$bodyid}\" class=\"modal-body\" style=\"font-size: small;\">
                            </div>
                    </div>
              </div>
        </div>";
}

public function readIniFile() {

    return $ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/acs2.ini',true);

}




}