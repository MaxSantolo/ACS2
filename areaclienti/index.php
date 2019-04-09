<?php
   include("../tech/connect.php");
   
   
   session_start();
   if (isset($_POST["button"])) {
            
      $myusername = $_POST['username'];
      $mypassword = $_POST['password']; 
      
      
      $sql = "SELECT * FROM acs_utenti WHERE email = '".$myusername."' and pin = '".$mypassword."'";
      $result = $conn->query($sql);
      
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($result->num_rows == 1) {
        
        $row = $result->fetch_assoc();
        $id_sessione = $row['id_utente'];
        $_SESSION['login_user'] = $id_sessione;
        header("location: benvenuto.php");
      }else {
         $error = "Login e password errati o password scaduta";
      }
   }
   
   
?>
<html>
   
   <head>
      <title>Area clienti BSide</title>
      
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         
         .box {
            border:#666666 solid 1px;
         }
        #pbianco  {
            color: #ffffff;
            font-family: Verdana;
            font-size: 14px;
            font-weight: bold;
    
        }       

        body {
        background-image: url(../images/sfondobside.jpg);
        background-position: center center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
        background-color: #464646;
}
      </style>
      
   </head>
   
   <body bgcolor = "#FFFFFF">
	
       <div align = "center"><div style='height: 25px'></div><img src='../images/bsidelogo2.png'><div style='height: 25px'></div>
         <div style = "width:500px;height: 190px; border: solid 1px #333333; background-color: #d6d7d1;" align = "center">
            <div style = "background-color:#5780ad; color:#FFFFFF; padding:3px; font-family: Verdana"><b>Login</b></div>
				
            <div style = "margin:30px; font-family: Verdana;background-color: #d6d7d1; width: 450px;">
               
               <form action = "" method = "post">
                   <table width='75%' cellpadding='5'>
                    <TR>
                           <TD><label>Email:</label></TD><td><input type = "text" name = "username" class = "box"/></td>
                    </TR>
                    <tr>
                      <td><label>Password:</label></td><td><input type = "password" name = "password" class = "box" /></td>
                    </tr>
                    <tr>
                      <td colspan="2" align='center'><input type = "submit" name="button" value = " Accedi "/></td>
                    </tr>
                   </table>
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>