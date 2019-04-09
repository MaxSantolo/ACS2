<?php 

function tempo_decimali_neg($time) {
    $timeArr = explode(':', $time);
    $decTime = ($timeArr[0]*60) - ($timeArr[1]);
 
    return $decTime;
}

function tempo_decimali_pos($time) {
    $timeArr = explode(':', $time);
    $decTime = ($timeArr[0]*60) + ($timeArr[1]);
 
    return $decTime;
}

function calcolaore($stringa) {
$a_ingressi = "";

$stringa = str_replace('in','-',$stringa);
$stringa = str_replace('out','+',$stringa);
$stringa = str_replace('[','',$stringa);
$stringa = str_replace(']','',$stringa);

$a_ingressi = explode("|", $stringa);

$test = 0;

//pulisco doppi ingressi

for ($i = 0; $i < count($a_ingressi); $i++)
{ if ( (substr($a_ingressi[$i],0,1)=="-" ) && (substr($a_ingressi[$i+1],0,1)=="-" )) { array_splice($a_ingressi,$i+1,1); $i--; }} 

//pulisco doppie uscite

for ($i = 0; $i < count($a_ingressi); $i++)
{ if ( (substr($a_ingressi[$i],0,1)=="+" ) && (substr($a_ingressi[$i+1],0,1)=="+" )) { array_splice($a_ingressi,$i,1); $i--; }}


//trovo 1 solo ingresso
if ( (count($a_ingressi)==1) && (substr($a_ingressi[0],0,1)=="-" )) { 
    $test = $test + tempo_decimali_pos("19:30") +tempo_decimali_neg(str_replace(" ","",$a_ingressi[0]));
    return number_format($test/60,2, '.',',');
    
    
}

// trovo 1 sola uscita
if ((count($a_ingressi)==1) && (substr($a_ingressi[0],0,1)=="+" )) { 
    $test = $test + tempo_decimali_pos(str_replace(" ","",$a_ingressi[0])) - tempo_decimali_pos("08:00");
    
    return number_format($test/60,2, '.',',');
}

if (substr($a_ingressi[0],0,1)=="+" ) { array_unshift($a_ingressi,"- 08:00") ;} // se primo valore uscita inserisco all'inizio 08:00
if (substr(end($a_ingressi),0,1)=="-" ) { array_push($a_ingressi,"+ 19:30") ;} // se ultimo valore ingresso inserisco alla fine 19:30


for ($i = 0; $i < count($a_ingressi); $i++)
{ if ($i % 2 == 0)  { $test = $test + tempo_decimali_neg(str_replace(" ","",$a_ingressi[$i])); }
   else {$test = $test + tempo_decimali_pos(str_replace(" ","",$a_ingressi[$i]));}
  }

return number_format($test/60,2, '.',',');

}

function isadmin() {
    //se non c'è utente manda alla pagina di login
   if(!isset($_SESSION['login_user'])){ header("location:index.php"); }
   
   if ($privilegi == 'ADMIN') { return true; }
   else { return false; }
    
}

function listautenti() {

    include('connect.php');
    $elenco = $conn->query("SELECT id_utente, nome_azienda FROM acs_utenti WHERE tipo = 'BSIDE' ORDER BY nome_azienda");
    
    while ($riga = $elenco->fetch_assoc()) {

                  unset($id, $nome);
                  $id = $riga['id_utente'];
                  $name = $riga['nome_azienda']; 
                  echo '<option value="'.$id.'">'.$name.'</option>';
                 
    }
    
}

function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d' ) { 
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);
    while( $current <= $last ) { 
        if (date("D", $current) != "Sun" and date("D", $current) != "Sat")
            $dates[] = date($format, $current);
        $current = strtotime($step, $current);
    }
    return $dates;
}



?>