<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 30/10/2018
 * Time: 10:46
 */


class DB
{

    function __construct()    {
        $ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/acs2.ini',true);
        $this->PBX = $ini['DB']['PBX'];
        $this->PBXUserName = $ini['DB']['PBXUserName'];
        $this->PBXPassword = $ini['DB']['PBXPassword'];
        $this->Amanda = $ini['DB']['Amanda'];
        $this->AmandaUserName = $ini['DB']['AmandaUserName'];
        $this->AmandaPassword = $ini['DB']['AmandaPassword'];
    }

    //genera connessione al PBX
    function getPBXConn($db) {
        $servername = $this->PBX;
        $username = $this->PBXUserName;
        $password = $this->PBXPassword;
        $conn = mysqli_connect($servername,$username,$password,$db) or die("Impossibile connettersi a: ".$db." - ".mysqli_connect_error());
        return $conn;
    }
//genera connessione ad amanda
    function getProdConn($db) {
        $servername = $this->Amanda;
        $username = $this->AmandaUserName;
        $password = $this->AmandaPassword;
        $conn = mysqli_connect($servername,$username,$password,$db) or die("Impossibile connettersi a: ".$db." - ".mysqli_connect_error());
        return $conn;
    }
//distrugge connessione
    function dropConn($conn) {
        mysqli_close($conn);
    }

//genera il sql per l'estrazione degli accessi ai citofoni principali
    function sqlAllAccesses($role,$company,$pin,$from,$to,$intercoms,$firstname,$lastname) {
        if (is_null($from) || $from == '') $from = date("Y-m-d", strtotime("first day of this month"));
        if (is_null($to) || $to == '') $to = date("Y-m-d", strtotime("last day of this month"));
        $rfrom = date('Y-m-d', strtotime($from));
        $rto = date('Y-m-d', strtotime($to));
        $rolesql = $this->roleCheckSQL($role);
        $sql = "
               SELECT * FROM accessi_boezio_v 
                        WHERE nome_azienda LIKE '%".$company."%' 
                        AND pin LIKE '%".$pin."%' 
                        AND (data_ingresso BETWEEN '".$rfrom."' AND '".$rto."') 
                        AND src ".$intercoms." 
                        ".$rolesql." 
                        AND (firstname LIKE '%".$firstname."%' OR firstname IS NULL) 
                        AND (lastname LIKE '%".$lastname."%' OR lastname IS NULL)
               UNION ALL SELECT * FROM accessi_eur_v
                        WHERE nome_azienda LIKE '%".$company."%' 
                        AND pin LIKE '%".$pin."%' 
                        AND (data_ingresso BETWEEN '".$rfrom."' AND '".$rto."') 
                        AND src ".$intercoms."
                        ".$rolesql."
                        AND (firstname LIKE '%".$firstname."%' OR firstname IS NULL) 
                        AND (lastname LIKE '%".$lastname."%' OR lastname IS NULL)                      
               UNION ALL SELECT * FROM accessi_regolo_v 
                        WHERE nome_azienda LIKE '%".$company."%' 
                        AND pin LIKE '%".$pin."%' 
                        AND (data_ingresso BETWEEN '".$rfrom."' AND '".$rto."') 
                        AND src ".$intercoms." 
                        ".$rolesql." 
                        AND (firstname LIKE '%".$firstname."%' OR firstname IS NULL) 
                        AND (lastname LIKE '%".$lastname."%' OR lastname IS NULL)
               ";
        return $sql;
    }

//controlla la password da CRM
    function checkPassword($password, $user_hash)
    {
        if(empty($user_hash)) return false;
        if($user_hash[0] != '$' && strlen($user_hash) == 32) {
            return strtolower(md5($password)) == $user_hash;
        }
        return crypt(strtolower(md5($password)), $user_hash) == $user_hash;
    }

//genera la stringa di ricerca per ruolo
    private function roleCheckSQL($role) {

        if (is_null($role) || $role =='') {$role_out = " AND (role LIKE '%%' OR role IS NULL) "; }
            else if ($role == 'Clienti') {$role_out = " AND (role LIKE 'Cliente' OR role IS NULL OR role = 'Persona Autorizzata' OR role = 'Contatto Amministrativo') "; }
                else $role_out = " AND role = '".$role."' ";
        return $role_out;
    }

//sql per la ricerca report presenze
    function sqlIngressiReport($pin,$exact_date) {
        $sql = "select *, group_concat(ingressi separator ' | ') as ingressi_totali from (
                    select data_ingresso,pin,firstname,lastname,nota,ingressi from accessi_boezio_v where data_ingresso = '{$exact_date}'
                    union all 
                    select data_ingresso,pin,firstname,lastname,nota,ingressi from accessi_regolo_v where data_ingresso = '{$exact_date}'
                    union all 
                    select data_ingresso,pin,firstname,lastname,nota,ingressi from accessi_eur_v where data_ingresso = '{$exact_date}'
                    ) t
                where  pin = '{$pin}'
                group by pin, data_ingresso 
                ";
        return $sql;
    }
//sql per elenco impiegati
    function sqlEmployees($roles,$firstname,$lastname) {
        $sql = "select * from (
                  select distinct(pin) as pin, nome_azienda, firstname, lastname from accessi_boezio_v where role ".$roles." and firstname like '%{$firstname}%' and lastname like '%{$lastname}%' 
                  union all
                  select distinct(pin) as pin, nome_azienda, firstname, lastname from accessi_regolo_v where role ".$roles." and firstname like '%{$firstname}%' and lastname like '%{$lastname}%'
                  union all
                  select distinct(pin) as pin, nome_azienda, firstname, lastname from accessi_eur_v where role ".$roles." and firstname like '%{$firstname}%' and lastname like '%{$lastname}%'
                ) t
                group by pin 
                order by lastname";
        return $sql;
    }

//genera elenco date nel periodo saltando i festivi
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

//da creare
    function log($username,$userid,$module,$sql,$date) {
        //log azioni da ACS
    }

//aggiorna o inserisce nota ad ingresso
    public static function addNote($conn,$noteid,$code,$center,$date,$note) {
        if ($noteid == '' || !isset($noteid) ) { $sql = "INSERT INTO acs_note_ingressi (pin, data, sede, nota) VALUES ('".$code."', '".$date."', '".$center."', '".$note."')";  }
        else { $sql = "UPDATE acs_note_ingressi SET nota = '".$note."' where ID_nota_ingresso = '{$noteid}'"; }
        $conn->query($sql);
}

//formatta la data per correggere gli errori del visual phonebook dove alcune date di scadenza invece di null solo '0000-00-00'
    public static function dateFormat($date,$format) {
        if (strtotime($date) != '-62169995212' && $date != '')  {
            $dateout = date($format,strtotime($date));
        } else $dateout = '';
        return $dateout;
    }

//trascodifica i valori del CRM in quelli leggibili dal CRM
    public static function showType($conn,$value) {
        $data = $conn->query("SELECT role FROM acs_roles_codes WHERE code = '{$value}'");
        $rdata = $data->fetch_assoc();
        return $rdata['role'];
    }

//genera le options per la select del ruolo
    public static function showOptValue($conn,$value) {
        $adata = $conn->query("SELECT * FROM acs_roles_codes ORDER BY role asc");
        while ($data = $adata->fetch_assoc()) {
            if ($data['code'] == $value) {
                $string .= "<OPTION value='{$data['code']}' selected>{$data['role']}</OPTION>";
            } else $string .= "<OPTION value='{$data['code']}'>{$data['role']}</OPTION>";
        }
        return $string;
    }

//sql per aggiornamento del visual_phonebook
    public static function vbUpdate($vbid,$pin,$expdate,$phone1,$phone2,$phone3,$email) {
        return $sql = "
                     UPDATE visual_phonebook SET
                      pin = '{$pin}',
                      scadenza_pin = '{$expdate}',
                      phone1 = '{$phone1}',
                      phone2 = '{$phone2}',
                      phone3 = '{$phone3}',
                      email2 = '{$email}'
                    WHERE id = '{$vbid}'         
        ";
    }

//genera contenuto form per assegnazione citofoni
    public static function formIntercomsCB($conn,$type,$center,$btnname,$vbid,$aicgrp,$namevalue,$btnlabel,$tableid) {
        $idata = $conn->query("SELECT * FROM acs_doors WHERE type LIKE '%{$type}%' AND center LIKE '%{$center}%' AND phone_num !='0000'");
        ($namevalue == '') ? $vis = 'hidden' : $vis = '';
        $output =  "
                       <form method='post' action = \"\" onsubmit=\"showloader()\">
                       
                       <div class=\"row form-row\" {$vis}>
                            <div class=\"col-md-12\">
                                <div class=\"md-form form-sm\">
                                    <!-- <div class=\"form-group\"> -->
                                    <!-- <label class='custom-control-label' for=\"grpname\">Nome </label> -->
                                    <input type=\"text\" name=\"grpname\" class=\"form-control selected\" value=\"{$namevalue}\" placeholder='Nome'>
                                <!-- </div> -->
                                </div>
                       </div>
                       </div>
                       
                       <table id='{$tableid}' class='table table-bordered table-striped table-hover table-sm datatableIntegration display compact'>
                       <thead>
                           <tr>
                              <th>
                              <div class=\"custom-control custom-checkbox\">
                                <input type=\"checkbox\" class=\"custom-control-input\" id=\"selectall\" title=\"SELEZIONA TUTTI\" onchange=\"checkAllIntercoms(this.checked);\">
                                <label class=\"custom-control-label\" for=\"selectall\"></label>
                              </div>
                              </th>
                              <th>TEL</th>
                              <!-- th>NOME</th> -->
                              <th>TIPO</th>
                              <th>CENTRO</th>
                           </tr>
                       </thead>";
        while ($intercom = $idata->fetch_assoc()) {
            $label = $intercom['name'];
            (DB::isPairing($conn,$intercom['id'],$vbid) || in_array($intercom['id'],$aicgrp)) ? $cladd = 'checked' : $cladd = '';

            $output .= "
                        <tr>
                        <td>    
                        <div class=\"custom-control custom-checkbox\">
                            <input type=\"checkbox\" class=\"custom-control-input custom-checkbox\" id=\"intercom{$intercom['id']}\" name='intercoms[]' value='{$intercom['id']}' {$cladd}>
                            <label class=\"custom-control-label\" for=\"intercom{$intercom['id']}\">{$label}</label>
                        </div>
                        </td>
                        <td>{$intercom['phone_num']}</td>
                        <!--<td>{$intercom['name']}</td>-->
                        <td>{$intercom['type']}</td>
                        <td>{$intercom['description']}</td>                        
                        </tr>
            ";
        }
        $output .= '</table>
                        <div style="width: 25%;text-align: center;margin: auto">
                            <button class="btn btn-indigo align-self-center" type="submit" name="'.$btnname.'">'.$btnlabel.'</button>
                        </div>
                    </form>';
        return $output;
    }

//aggiorna e salva le selezioni
    public static function updateIntercoms($conn,$intercoms,$user) {
        $now = ACSBase::Now();
		$aicdel = DB::getUncheckedIntercoms($conn,$intercoms);
        if (!empty($aicdel)) {
            foreach ($aicdel as $icdel) {
                //echo "DELETE FROM acs_phoneb_doors WHERE door_id ='{$icdel}' AND phoneb_id ='{$user}'"."<br>"; //for testing
                $conn->query("DELETE FROM acs_phoneb_doors WHERE door_id ='{$icdel}' AND phoneb_id ='{$user}'");
            }
        } else $conn->query("DELETE FROM acs_phoneb_doors WHERE phoneb_id ='{$user}'");
        foreach ($intercoms as $ic) {
            (DB::isPairing($conn,$ic,$user)) ?:
            $conn->query("INSERT INTO acs_phoneb_doors (phoneb_id,door_id,edit_date,user) VALUES ('{$user}','{$ic}','{$now}','{$_SESSION['user_name']}')");
        }
    }

    //controlla se esiste la coppia citofono utente
    public static function isPairing($conn,$intercom,$user) {
            $pairing = $conn->query("SELECT id FROM acs_phoneb_doors WHERE phoneb_id = '{$user}' AND door_id = '{$intercom}'");
            if ($pairing->num_rows == 0) return false; else return true;
        }
//controlla se esiste la coppia citofono gruppo
    function isGroup($conn,$intercom,$groupname) {
        $grouping = $conn->query("SELECT id FROM acs_doors_grps WHERE acs_doors_grps.group ='{$groupname}' AND door_id='{$intercom}'");
        if ($grouping->num_rows == 0) return false; else return true;
    }

    //mostra a quali porte è abilitato un utente
    public static function printPairings($conn,$user,$mode) {
        $pairings = $conn->query("SELECT acs_doors.name as oname, acs_doors.phone_num as num, acs_phoneb_doors.user as cuser FROM acs_phoneb_doors LEFT JOIN acs_doors ON door_id = acs_doors.id WHERE phoneb_id = '{$user}' ORDER BY acs_doors.name");
        while ($pair = $pairings->fetch_assoc()) {

            if ($mode=='button') {
                ($pair['cuser'] == 'Sistema Automatico') ? $color = 'btn-warning' : $color = 'btn-indigo';
                $op .= '<a href="../off_whole_search.php?grpclm=1&thcolor=2e3951&bg=officebg.png&vbid=' . $user . '&phonenum=' . $pair['num'] . '" class="btn '.$color.' btn-sm" style="width: 100px" onclick="showloader()">' . $pair['oname'] . '</a>';
            } else $op .= '[' . $pair['oname'] . ']';
        }
        return $op;
    }

    //seleziona quali citofoni non sono selezionati
    function getUncheckedIntercoms($conn,$checkic) {
        $aintercoms = $conn->query("SELECT id from acs_doors WHERE phone_num!='0000' AND type !='CIT'");
        while ($intercom = $aintercoms->fetch_assoc()) {
            $total_intercoms[] = $intercom['id'];
        }
            $intercoms = array_diff($total_intercoms,$checkic);
        return $intercoms;
    }

//inserisce il gruppo citofoni
    public static function insertIntercomsGroup($conn,$name,$intercoms) {
        $now = ACSBase::Now();
        foreach ($intercoms as $ic) {
            //echo "INSERT INTO acs_doors_grps (acs_doors_grps.group,door_id,created,created_by) VALUES ('{$name}','{$ic}','{$now}','{$_SESSION['user_name']}')";
            $conn->query("INSERT INTO acs_doors_grps (acs_doors_grps.group,door_id,created,created_by) VALUES ('{$name}','{$ic}','{$now}','{$_SESSION['user_name']}')");
        }
    }

    //aggiorna il gruppo citofoni
    public static function updateIntercomsGroup($conn,$checkedintercoms,$grpname) {
    $now = ACSBase::Now();
    $aicdel = DB::getUncheckedIntercoms($conn,$checkedintercoms);
    if (!empty($aicdel)) {
        foreach ($aicdel as $icdel) {
            //echo "DELETE FROM acs_doors_grps WHERE door_id ='{$icdel}' AND acs_doors_grps.group ='{$grpname}'"; //for testing
            $conn->query("DELETE FROM acs_doors_grps WHERE door_id ='{$icdel}' AND acs_doors_grps.group ='{$grpname}'");
        }
    } else $conn->query("DELETE FROM acs_doors_grps WHERE acs_doors_grps.group ='{$grpname}'");
    foreach($checkedintercoms as $cic) {
        (DB::isGroup($conn,$cic,$grpname)) ?:
        $conn->query("INSERT INTO acs_doors_grps (acs_doors_grps.group,door_id,created,created_by) VALUES ('{$grpname}','{$cic}','{$now}','{$_SESSION['user_name']}')");
    }
    }

    //genera le option con l'elenco dei gruppi per assegnazione
    public static function showGroupsOpts($conn) {
        $adata = $conn->query("SELECT distinct name, icarray FROM asteriskcdrdb.icgroups_v");
        while ($data = $adata->fetch_assoc()) {
                $string .= "<OPTION value='{$data['icarray']}'>{$data['name']}</OPTION>";
        }
        return $string;
    }

    /*public static function getIntercomsGroup($conn,$grpname) {
        $aintercoms = $conn->query("SELECT door_id FROM acs_doors_grps WHERE acs_doors_grps.group = '{$grpname}'");
        while ($ic = $aintercoms->fetch_assoc()) {
            $grpintercoms[] = $ic['door_id'];
        }
        return $grpintercoms;
    }*/

//controll d'errore creando un nuovo gruppo
    function checkNewGrpCreate($conn,$intercoms,$grpname) {
        if (count($intercoms) <=1 ) { $erromsg .='Scegli almeno due Uffici.\n'; }
        if ($grpname == 'INSERISCI NOME QUI') { $erromsg .= 'Scegli un nome per il gruppo\n';}
        $isname = $conn->query("SELECT id FROM acs_doors_grps WHERE acs_doors_grps.group = '{$grpname}'");
        if ($isname->num_rows !=0 ) { $erromsg .= 'Nome del gruppo duplicato, scegliere un altro nome';}
        return $erromsg;
    }

//controlla che il pin non esista
    function isPin($conn,$pin,$oldpin) {
        if (is_null($pin) || $pin == '') return false;
        if ($pin == $oldpin ) return false; else {
            $apins = $conn->query("SELECT id FROM visual_phonebook WHERE pin = '{$pin}'");
            if ($apins->num_rows > 0) return true; else return false;
        }
    }

    //registra i pin modificati
    function registerPinChange($conn,$oldpin,$vbid) {

        require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PickLog.php';

        $now = ACSBase::Now();
        if ($oldpin != '') {

            $sql = "INSERT INTO acs_pinchange (phoneb_id,old_pin,cdate,cuser) VALUES ('{$vbid}','{$oldpin}','{$now}','{$_SESSION['user_name']}')";
            $conn->query($sql);

            $plog = new PickLog();
            $content = $sql;
            $params = array(
                'app' => 'ACS',
                'action' => 'MOD_PIN',
                'content' => $content . PHP_EOL . "Numero di righe: " . $conn->affected_rows,
                'user' => $_SESSION['user_name'],
                'description' => "Registro la modifica al PIN",
                'origin' => 'PBX.asterisk.visual_phonebook',
                'destination' => 'PBM.asteriskcdrdb.acs_pinchange',
            );
            $plog->sendLog($params);

        }
    }
    //se utente ha dei cambiamenti di pin registrati mostra pulsante sulla pagina vbdetail.php
    function hasChangedPin($conn,$vbid) {
        $result = $conn->query("SELECT id FROM acs_pinchange WHERE phoneb_id = '{$vbid}'");
        if ($result->num_rows == 0) return $return = 'hidden';
    }

    //modale per inserimento e modifica di un nuovo citofono
    public static function showICForm($conn,$icid,$btnname) {
        $btntitle = 'Inserisci';
        $typestring = DB::showICOpt($conn,'','type','acs_doors');
        $centerstring = DB::showICOpt($conn,'','center','acs_doors');
        if ($icid != '') {
            $intercoms = $conn->query("SELECT * FROM acs_doors WHERE id ='{$icid}' ");
            $ic = $intercoms->fetch_assoc();
            $icname = $ic['name'];
            $phonen = $ic['phone_num'];
            $type = $ic['type'];
            $center = $ic['center'];
            $btntitle = 'Aggiorna';
            $typestring = DB::showICOpt($conn,$ic['type'],'type','acs_doors');
            $centerstring = DB::showICOpt($conn,$ic['center'],'center','acs_doors');
        }
        echo "
                <form class=\"text-center border border-light p-5\" action=\"\" method=\"post\">
                     <input type='text' name='icid' id='icid' value='$icid' hidden>
                     <div class=\"form-row mb-4\">
                        <div class=\"col\">
                            <input name=\"ficname\" type=\"text\" id=\"ficname\" class=\"form-control\" placeholder=\"Nome\" value=\"{$icname}\">
                        </div>
                        <div class=\"col\">
                            <input  name=\"fphonen\" type=\"text\" id=\"fphonen\" class=\"form-control\" placeholder=\"Interno Telefonico\" value=\"{$phonen}\">
                        </div>
                    </div>
                    <div class=\"form-row mb-4\">
                        <div class=\"col\">
                            <label for='typeselect' style='font-size: small;align-content: left'>Tipo</label>
                            <select name='typeselect' id='typeselect' class=\"form-inline form-control\">".$typestring."</select>
                        </div>
                        <div class=\"col\">
                            <label for='centerselect' style='font-size: small;align-content: left'>Centro</label>
                            <select name='centerselect' id='centerselect' class=\"form-inline form-control\">".$centerstring."</select>
                        </div>
                    </div>
                    <button class=\"btn btn-black\" type=\"submit\" name=\"{$btnname}\" onclick=\"showloader()\">$btntitle</button>
                </form>
            ";
    }

    //genera le option per campo e tabella
    function showICOpt($conn,$pvalue,$field,$table) {
        $array = $conn->query("SELECT distinct {$field} FROM {$table}");

        while ($value = $array->fetch_assoc()) {
            $fvalue = DB::strToDescription($value[$field]);
            ($value[$field]== $pvalue) ? $selected = 'selected' : $selected = '';
            $string .= "<OPTION value='{$value[$field]}' {$selected}>{$fvalue}</OPTION>";
        }
        return $string;
    }

//converte valore in descrizione per diversi campi e valori
    function strToDescription($value) {
        switch ($value) {
            case 'UFF':
                $fvalue = 'UFFICIO';
                break;
            case 'CIT':
                $fvalue = 'CITOFONO';
                break;
            case 'BOE':
                $fvalue = 'BOEZIO';
                break;
            case 'REG':
                $fvalue = 'REGOLO';
                break;
            case 'EUR':
                $fvalue = 'EUR';
                break;
        }
        return $fvalue;
    }

//inserisce o aggiorna citofoni (opzioni)
    function insUpdIC($conn,$icid,$icname,$ictype,$icphonen,$iccenter) {
        $description = DB::strToDescription($iccenter);
        if ($icid == '') {
            //echo "INSERT INTO acs_doors (name,phone_num,type,center,description) VALUES ('{$icname}','{$icphonen}','{$ictype}','{$iccenter}','{$description}') ";
            $conn->query("INSERT INTO acs_doors (name,phone_num,type,center,description) VALUES ('{$icname}','{$icphonen}','{$ictype}','{$iccenter}','{$description}') ");
        } else echo //"UPDATE acs_doors SET name='{$icname}', phone_num='{$icphonen}', type='{$ictype}', center='{$iccenter}', description = '{$description}' WHERE id='{$icid}'";
            $conn->query("UPDATE acs_doors SET name='{$icname}', phone_num='{$icphonen}', type='{$ictype}', center='{$iccenter}', description = '{$description}' WHERE id='{$icid}'");
    }

//genera il sql per la selezione di accesso agli uffici
    public static function sqlOffAcc($from,$to,$vbid,$phonenum) {
        $rfrom = date('Y-m-d', strtotime($from));
        $rto = date('Y-m-d', strtotime($to));
        $sql = "SELECT vbid,accdate,name,description,company,firstname,lastname,code,
                     concat(company , ' - ' ,firstname, ' ', lastname, '</a>') as acc_total_info,
                     group_concat(concat('[',cast(acchour as char(5)),']') order by acchour separator ' | ') as acc_code_info
                     FROM asteriskcdrdb.acc_offices_reg_v
                     where vbid is not null and vbid like '%{$vbid}%' and userfield NOT LIKE '%ko%' and (accdate between '{$rfrom}' and '{$rto}') and phone_num LIKE '%{$phonenum}%' 
                     group by accdate, name, code
                     order by accdate desc, name asc, acchour asc";
        return $sql;
    }

    //genera il form per modificare / duplicare il checkin
    public static function buildFormCheckInDay($conn,$vbid,$ciid,$btnname,$btntext) {
        $db = new DB();
        $conn_vb = $db->getPBXConn('asterisk');
        $optname = $db::optClients($conn_vb,$vbid);
        if($ciid != '') {
        $ci = $conn->query("SELECT * FROM checkinday_off_v WHERE id like '%{$ciid}%'")->fetch_assoc();
            $vbid = $ci['phoneb_id'];
            $door_id = $ci['door_id'];
            $optname = $db::optClients($conn_vb,$vbid);
            $fdate = ACSBase::DateToItalian($ci['date'],'d-m-Y');
            $fstart = ACSBase::DateToItalian($ci['tstart'],'H:i');
            $fend = ACSBase::DateToItalian($ci['tend'],'H:i');
            $office = $ci['name']. ' | '. $ci['description'];
        }


        $form = "
                     <form class=\"text-center border border-light p-2\" action=\"\" method=\"post\">
                     <input id='idcheckin' name='idcheckin' value='{$ciid}' hidden/><input id='vbid' name='vbid' value='{$vbid}' hidden/>  
                    <div class=\"form- row mb-12\">
                        <div class=\"col mb-sm-2\">
                            <select name=\"fclient\" type=\"text\" id=\"fclient\" class=\"form-control form-control-chosen autocomplete\" placeholder=\"Cliente\">
                            <option value='0' selected>Scegli un cliente...</option> 
                                {$optname}
                            </select>
                        </div>
                    </div>
                     <div class=\"form-row mb-12\">
                        <div class=\"col mb-sm-2\">
                            <select name=\"foffice\" type=\"text\" id=\"foffice\" class=\"form-control form-control-chosen autocomplete \" placeholder=\"Ufficio\" value=\"{$office}\" required>
                            <option value='0' selected>Scegli una risorsa...</option> 
                            ".DB::optDoors($conn,$door_id)."
                            </select>
                        </div>
                    </div>
                    
                    <div class=\"form-row mb-12\">
                        <div class=\"col mb-sm-2\">
                            <label class='mb-sm-0'>Data</label>
                            <input name=\"fdate\" type=\"text\" id=\"fdate\" class=\"form-control \" placeholder=\"Data\" value=\"{$fdate}\" required>
                        </div>
                        <div class=\"col mb-sm-2\">
                            <label class='mb-sm-0'>Dalle</label>
                            <input  name=\"fstime\" type=\"text\" id=\"fstime\" class=\"form-control\" placeholder=\"Dalle\" value=\"{$fstart}\" required>
                        </div>
                        <div class=\"col mb-sm-2\">
                            <label class='mb-sm-0'>Alle</label>
                            <input  name=\"fetime\" type=\"text\" id=\"fetime\" class=\"form-control\" placeholder=\"Alle\" value=\"{$fend}\" required>
                        </div>
                    </div>
                    <div style='padding: 2px;background-color: rgba(250,250,250,65%);'>
                        <div>Ripeti nei giorni:</div>
                        <div class='form-row mb-12 mb-sm-2'>
                        <div class='col'>
                            <div class=\"custom-control custom-checkbox\">
                                <input type=\"checkbox\" class=\"custom-control-input custom-checkbox\" id='lun' name='repdays[]' value='Mon'>
                                <label class=\"custom-control-label\" for='lun'>Lun</label>
                            </div>
                        </div>
                        <div class='col'>
                            <div class=\"custom-control custom-checkbox\">
                                <input type=\"checkbox\" class=\"custom-control-input custom-checkbox\" id='mar' name='repdays[]' value='Tue'>
                                <label class=\"custom-control-label\" for='mar'>Mar</label>
                            </div>
                        </div>
                        <div class='col'>
                            <div class=\"custom-control custom-checkbox\">
                                <input type=\"checkbox\" class=\"custom-control-input custom-checkbox\" id='mer' name='repdays[]' value='Wed'>
                                <label class=\"custom-control-label\" for='mer'>Mer</label>
                            </div>       
                        </div>
                        <div class='col'>                 
                            <div class=\"custom-control custom-checkbox\">
                                <input type=\"checkbox\" class=\"custom-control-input custom-checkbox\" id='gio' name='repdays[]' value='Thu'>
                                <label class=\"custom-control-label\" for='gio'>Gio</label>
                            </div>       
                        </div>
                        <div class='col'>                 
                            <div class=\"custom-control custom-checkbox\">
                                <input type=\"checkbox\" class=\"custom-control-input custom-checkbox\" id='ven' name='repdays[]' value='Fri'>
                                <label class=\"custom-control-label\" for='ven'>Ven</label>
                            </div>      
                        </div>
                        <div class='col'>                  
                            <div class=\"custom-control custom-checkbox\">
                                <input type=\"checkbox\" class=\"custom-control-input custom-checkbox\" id='sab' name='repdays[]' value='Sat'>
                                <label class=\"custom-control-label\" for='sab'>Sab</label>
                            </div>       
                         </div>
                         <div class='col'>                 
                            <div class=\"custom-control custom-checkbox\">
                                <input type=\"checkbox\" class=\"custom-control-input custom-checkbox\" id='dom' name='repdays[]' value='Sun'>
                                <label class=\"custom-control-label\" for='dom'>Dom</label>
                            </div>
                        </div>
                        <div class=\"form-row mb-12 m-auto\">
                            <div class=\"col mb-sm-2\">
                                <label class='mb-sm-0 m-auto'>Fino al</label>
                                <input name=\"rdate\" type=\"text\" id=\"rdate\" class=\"form-control\" placeholder=\"Fino al\">
                            </div>
                        </div>
                        
                        </div>
                    </div>
                    
                    <div class=\"form-row mb-12 mb-sm-2\">
                            <button class=\"btn btn-green m-auto mb-sm-2\" type=\"submit\" name=\"{$btnname}\" onclick=\"showloader()\">{$btntext}</button>
                    </div>                
                </form>
        ";
        $form .= builder::createDatePicker(array('fdate','rdate')).builder::createTimePicker(array('fstime','fetime'));
        $form .= "<script>$(\".autocomplete\").chosen();</script>";
        $db::dropConn($conn_vb);
        return $form;
    }

//crea options per modale checkin day/sale con descrizione estesa
        public static function optDoors($conn,$selectedid) {
            $array = $conn->query("select distinct(concat(name, ' | ', description)) as description, id from acs_doors where type = 'uff' and phone_num != '0000'");
            while ($value = $array->fetch_assoc()) {
                ($value['id'] == $selectedid) ? $selected = 'selected' : $selected = '';
                $string .= "<OPTION value='{$value['id']}' {$selected}>{$value['description']}</OPTION>";
            }
            return $string;
        }

//controlla validità e disponibilità del periodo di check in
          public static function checkAvalCorr($conn,$office,$date,$stime,$etime,$ciid,$userid) {
            $cdate = strtotime(date('Y-m-d',strtotime($date)));
            $now = strtotime(date('Y-m-d')); //,strtotime(ACSBase::Now()));
            $sts = strtotime($date. ' '.$stime);
            $ets = strtotime($date . ' '.$etime);
            //echo $sts.'<br>'.$ets.'<br>';
            $out = false;
            $check = $conn->query("SELECT * FROM checkinday_off_v WHERE door_id = '{$office}' and status IS NULL and date = '{$cdate}' and id != '{$ciid}'");
            //echo "SELECT * FROM checkinday_off_v WHERE door_id = '{$office}' and status IS NULL and date = '{$cdate}' and id != '{$ciid}'".'<br>';
            if($check->num_rows > 0) {

                while ($sc = $check->fetch_assoc()) {
                    //prendo dalla riga inizio e fine prenotazione
                    $csts = strtotime($sc['sts']);
                    $cets = strtotime($sc['ets']);
                    //echo $csts.'<br>'.$cets.'<br>';
                    if (($ets < $csts) || ($sts > $cets)) {
                        $out = true;
                    } else $outmessage .= 'La prenotazione si sovrappone ad altra.\\n';
                }
            } else $out = true;
            if ($sts > $ets || $cdate < $now) { $out = false; $outmessage .= 'Non ho ancora inventato la macchina del tempo.\\n';}
            if ($office == 0) {$out = false; $outmessage .= 'Devi selezionare la porta da aprire.\\n';}
            if (DB::isPairing($conn,$office,$userid)) {$out = false; $outmessage .= 'Ufficio associato permanentemente ad utente.\\n';}

           return array('result' => $out, 'message' => $outmessage);
          }

          public static function updCheckInDay($conn,$userid,$doorid,$date,$start,$end,$ciid) {

            $dbdate = date('Y-m-d',strtotime($date));
            $dbstart = date('H:i:s',strtotime($start));
            $dbend = date('H:i:s',strtotime($end));

            if ($ciid == '') {
                            $conn->query("INSERT INTO acs_checkday (phoneb_id,door_id,date,tstart,tend) VALUES ('{$userid}','{$doorid}','{$dbdate}','{$dbstart}','{$dbend}')");
                }
                else {
                    $conn->query("UPDATE acs_checkday SET phoneb_id = '{$userid}', door_id = '{$doorid}', date = '{$dbdate}', tstart = '{$dbstart}', tend = '{$dbend}' WHERE id = '{$ciid}'");
                    //echo "UPDATE acs_checkday SET phoneb_id = '{$userid}', door_id = '{$doorid}', date = '{$dbdate}', tstart = '{$dbstart}', tend = '{$dbend}' WHERE id = '{$ciid}'";
                }
            }

//aggiunge e rimuove le associazioni codice - ufficio
          public static function managePairing($conn,$user,$ic,$command,$ciid) {

            $date = ACSBase::Now();
            if ($command == 'new') {
                $conn->query("INSERT INTO acs_phoneb_doors (phoneb_id,door_id,edit_date,user) VALUES ('{$user}','{$ic}','{$date}','Sistema Automatico')");
                $conn->query("UPDATE acs_checkday SET status = 'attivato' where id = '{$ciid}'");

            } else {
                $conn->query("DELETE FROM acs_phoneb_doors WHERE phoneb_id = '{$user}' and door_id = '{$ic}'");
                $conn->query("UPDATE acs_checkday SET status = 'completato' where id = '{$ciid}'");
            }
          }

//genera date per ripetizione
    public function dateRangeRecurring($first, $last, $days, $step = '+1 day', $format = 'Y-m-d') {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
        while( $current <= $last ) {

            $needle = date("D", $current);

            if (in_array($needle,$days))
                $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    public static function optClients($conn,$selectedid) {
        if (!isset($selectedid)) $selectedid = 0;
        $array = $conn->query("SELECT id, concat(coalesce(company,''), ' | ', coalesce(firstname,''), ' ', coalesce(lastname,'')) as vbname
                               FROM asterisk.visual_phonebook 
                               where pin != '' and pin like '9%' order by id");
        while ($value = $array->fetch_assoc()) {
            ($value['id'] == $selectedid) ? $selected = 'selected' : $selected = '';
            $string .= "<OPTION value='{$value['id']}' {$selected}>{$value['vbname']}</OPTION>";
        }
        return $string;
    }

}