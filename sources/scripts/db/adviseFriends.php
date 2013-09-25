<?php
include('db.php');
include('../common.php');

if(isset($_POST['idbook']) && isset($_POST['id_from']) ){
	dbConnect();

	$user1 = intval($_POST['id_from']);
	$i=0;
	$idBook = intval($_POST['idbook']);
	$sysdate = date('Y-m-d H:i:s');

	while ( isset($_POST['friend'.$i])){
		
		$user_to = intval($_POST['friend'.$i]);
		if($user_to!=''){	
			$sqlQuery = "INSERT INTO vBiblio_suggest(id_from, id_to, id_book, date_suggest)
					VALUES('$user1', '$user_to', '$idBook', '$sysdate')";
			
			mysql_query($sqlQuery);
			
			$sqlFrom = "SELECT fullname FROM vBiblio_user WHERE tableuserid='$user1'";
			$resFrom = mysql_query($sqlFrom);
			
			if($resFrom && mysql_num_rows($resFrom)){
			  $userFromName = mysql_result($resFrom, 0, 'fullname');
      
    		//si l'utilisateur destinataire a activé les notifications, on lui envoie un mail. (la vérification se fait avant l'envoi...)
    		$sqlNotif = "SELECT notification_active FROM vBiblio_user WHERE tableuserid='$user_to'";
        $resNotif = mysql_query($sqlNotif);
        if($resNotif && mysql_num_rows($resNotif)){
    	    if(mysql_result($resNotif, 0, 'notification_active')=="1"){
    	     $mailMessage="Bonjour,\n\n";
    	  	 $mailMessage.=$userFromName." vous a suggéré un livre. \n\nRendez-vous vite sur notre site pour voir sa suggestion.";
    	  	 $mailMessage.="\n\n\nSi vous ne souhaitez plus recevoir de notifications, vous pouvez les désactiver sur votre page de profil: http://vbiblio.free.fr/profil.php";
    	  	 notifyUser($user_to, "[vBiblio] Un ami vous a suggéré un livre", $mailMessage);
    	  	}
    	  }
      }
				
	 }
	 $i++;
	
  }
}

?>
