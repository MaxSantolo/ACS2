<?php
require_once 'struct/classes/builder.php';
require_once 'struct/classes/ACSBase.php';
require_once 'struct/classes/DB.php';
require_once 'struct/classes/Log.php';


isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'sfondo.jpg';
isset($_GET['role']) ? $role = $_GET['role'] : $role = '';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['center']) ? $centro = $_GET['center'] : $center = '';

$title = 'Visualizzazione accessi '.strtoupper($role);
builder::startSession();
builder::Header($title,$bg);
builder::Navbar('DataTable')
?>


<p class="titolo"><?php echo $title; ?></p>
<div class="text-sm-center">
    <a href="" class="btn btn-indigo" data-toggle="modal" data-target="#AdvRs">Ricerca Avanzata</a>
</div>
<!-- search modal -->
<div class="modal fade" id="AdvRs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: rgba(250,250,250,.85)">
            <div class="modal-header" style="background-color: #AF4c50;color: white;font-weight: bold">
                <h5 class="modal-title" id="exampleModalLabel">RICERCA AVANZATA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="text-center border border-light p-2" action="" method="post">

                     <div class="form-row mb-2">
                        <div class="col">
                            <input name="azienda" type="text" id="company" class="form-control" placeholder="Azienda" value="<?php echo isset($_POST['azienda']) ? $_POST['azienda'] : '' ?>">
                        </div>
                        <div class="col">
                            <input  name="codice" type="text" id="pin" class="form-control" placeholder="Codice" value="<?php echo isset($_POST['codice']) ? $_POST['codice'] : '' ?>">
                        </div>
                    </div>
                    <div class="form-row mb-2">
                        <div class="col">
                            <input name="firstname" type="text" id="firsname" class="form-control" placeholder="Nome" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>">
                        </div>
                        <div class="col">
                            <input  name="lastname" type="text" id="lastname" class="form-control" placeholder="Cognome" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>">
                        </div>
                    </div>
                    <div class="form-row mb-2">
                        <div class="col">
                            <input name="dal" type="text" id="fromdate" class="form-control" placeholder="Dal" value="<?php echo isset($_POST['dal']) ? $_POST['dal'] : '' ?>">
                        </div>
                        <div class="col">
                            <input name="al" type="text" id="todate" class="form-control" placeholder="Al" value="<?php echo isset($_POST['al']) ? $_POST['al'] : '' ?>" >
                        </div>
                    </div>
                    <label>Centro</label>
                    <select name = "centro" class="browser-default custom-select mb-4" placeholder="Centro">
                        <?php
                            if (!isset($_GET['center']) || $_POST['center'] == '') echo '<option value="" selected>Scegli</option>'
                        ?>
                        <option value="BOE" <?php if ($_POST['centro']=='BOE')  echo 'selected'; ?>>Boezio</option>
                        <option value="REG" <?php if ($_POST['centro']=='REG')  echo 'selected'; ?>>Regolo</option>
                        <option value="EUR" <?php if ($_POST['centro']=='EUR')  echo 'selected'; ?>>Eur</option>
                    </select>
                    <button class="btn btn-indigo" type="submit" name="button" onclick="showloader()">Cerca</button>
                </form>

            </div>
        </div>
    </div>
</div>
    <!-- note modal -->
    <div class="modal fade" id="Notes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: rgba(250,250,250,.85)">
                <div class="modal-header" style="background-color: #<?php echo $thcolor;?>;color: white;font-weight: bold">
                    <h5 class="modal-title" id="exampleModalLabel">AGGIUNGI UNA NOTA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="text-center border border-light p-5" action="" method="post">
                        <input class="form-control" id="fnoteid" name="fnoteid" value="" hidden />
                        <input class="form-control" id="fcode" name="fcode" value="" hidden />
                        <input class="form-control" id="fdate" name="fdate" value="" hidden />
                        <input class="form-control" id="fcenter" name="fcenter" value="" hidden />
                        <div class="form-group">
                            <textarea class="form-control rounded-0" id="note" name="note" rows="5" value=""></textarea>
                        </div>
                        <button class="btn btn-indigo" type="submit" name="addnote">Aggiorna</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php

$db = new DB();
$conn = $db->getPBXConn('asteriskcdrdb');

    if (isset($_POST["button"])) {
        $azienda = $_POST["azienda"];
        $codice = $_POST["codice"];
        $dal = $_POST["dal"];
        $al = $_POST["al"];
        $centro = $_POST["centro"];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
    }

    $intercoms = ACSBase::FetchIntercoms($conn, 'CIT', $centro);
    $intcit = ACSBase::GenerateInString($intercoms);
    $newsql = $db->sqlAllAccesses($role,$azienda,$codice,$dal,$al,$intcit,$firstname,$lastname);
    //echo $newsql; //for tests
    $result = $conn->query($newsql);

	if ($result->num_rows > 0) {
        echo '<div id="risultati" class="tableContainer" style="font-size: medium">';
		echo "<table id=\"DataTable\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
              <thead style='background-color: #".$thcolor.";color: white'>
                <th>AZIENDA</th>
                <th>NOME</th>
                <th>COGNOME</th>
                <th>DATA</th>
                <th>ACCESSI</th>
                <th>CODICE</th>
                <th>COD. AUT.</th>
                <th>SEDE</th>
                <th>TIPO</th>
                <TH>NOTE</th><th></th>
              </thead>
              <tbody>";

		        while($row = $result->fetch_assoc()) {

		        $sede = ACSBase::ReturnCenter($conn,$row['src']);
		        $data_ingresso = ACSBase::DateToItalian($row['data_ingresso'],'l d/m/Y');
		        $date_order = strtotime($row['data_ingresso']);
                $centermodal = substr($sede,0,3);

                echo "<tr>
                           <td>".$row["nome_azienda"]."</td>
                           <td>".$row["firstname"]."</td>
                           <td>".$row["lastname"]."</td>
                           <td data-order=\"{$date_order}\">".$data_ingresso."</td>
                           <td>".str_replace('|',' | ',$row["ingressi"])."</td>
                           <td>".$row["pin"]."</td>
                           <td>".$row["auth"]."</td>
                           <td>".$sede."</td>
                           <td>".$row['role']."</td>
                           <td>".$row["nota"]."</td>
                           <td width='24'><A HREF='#' data-toggle=\"modal\" data-target=\"#Notes\" class='notedata' 
                                                data-id='{$row['id_nota']}' 
                                                data-text=\"{$row['nota']}\" 
                                                data-code =  '{$row['pin']}'
                                                data-date = '{$row['data_ingresso']}'
                                                data-center = '{$centermodal}'
                                                >
                                <IMG SRC='../images/edit2.png' width='24' title='MODIFICA/INSERISCI NOTA'></a>
                           </TD>
                      </tr>";
		        }
				echo "</tbody></table></div>";

	            } else {
			        echo "<DIV class='emptyResults'>Nessun risultato</div>";
	    	        }

echo '<BR>';

if (isset($_POST["addnote"])) {

    $noteid = $_POST['fnoteid'];
    $code = $_POST['fcode'];
    $date = $_POST['fdate'];
    $note = str_replace("'","''",$_POST['note']);
    $center = $_POST['fcenter'];

    DB::addNote($conn,$noteid,$code,$center,$date,$note);
    Log::wLog('Aggiornata una nota (PIN: '.$code.') per ingresso del '.$date,'ACCESSO-NOTA');
    echo "<meta http-equiv='refresh' content='0'>";
    }

   builder::Scripts();
   builder::configDataTable('DataTable','true',25,3,'desc');
   echo builder::createDatePicker(array('fromdate','todate'));


    //pass data to modal for notes
    echo "
    <script>    
                $(document).on('click', '.notedata', function(){ 
                            $('.modal-body #fnoteid').val($(this).data('id'));
                            $('.modal-body #fcode').val($(this).data('code'));
                            $('.modal-body #fdate').val($(this).data('date'));
                            $('.modal-body #fcenter').val($(this).data('center'));
                            $('.modal-body #note').val($(this).data('text'));
                       });
    </script>";

    DB::dropConn($conn);
