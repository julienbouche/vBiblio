<?php // common.php  

function error($msg) {  
   ?>  
   <html>  
   <head>  
   <script language="JavaScript">  
   <!--  
       alert("<?=$msg?>");  
       history.back();  
   //-->  
   </script>  
   </head>  
   <body>  
   </body>  
   </html>  
   <?  
   exit;  
}

function getTableUserId($pseudo){
  $getTUIDSqlRequest = "SELECT tableuserid FROM vBiblio_user WHERE userid='$pseudo'";
  $resTUID = mysql_query($getTUIDSqlRequest);
  
  if($resTUID && mysql_num_rows($resTUID)){
    $TUID = mysql_result($resTUID, 0, 'tableuserid');
  }
  else $TUID = "";
  
  return $TUID;
}

function notifyUser($userid, $sujet, $message){
	$sqlMail = "SELECT email FROM vBiblio_user WHERE tableuserid='$userid'";
	$resMail = mysql_query($sqlMail);
	if($resMail && mysql_num_rows($resMail)>0){
		$email = mysql_result($resMail, 0, 'email');
		envoyermail($email, $sujet, $message, "vBiblio");
	}
}

function envoyermail($usermail, $sujet, $message, $fromName){
	$headers = "From:$fromName <vbiblio@free.fr>";
	$headers .= "Content-Type: text/plain;charset=\"iso-8859-1\"\n";
	$message = str_replace("\\", "", $message);
	mail($usermail, $sujet, $message, "From: vbiblio@free.fr");
}

  
?>
