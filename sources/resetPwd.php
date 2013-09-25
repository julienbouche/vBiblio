<?php
include 'scripts/common.php';   
include 'scripts/db/db.php';

if(!isset($_POST['submitok']) ){

}
else{
  if( isset($_POST['uid']) && isset($_POST['umail']) && strlen($_POST['umail'])>0 && strlen($_POST['uid'])>0 ){
    $uid = $_POST['uid'];
    $umail = $_POST['umail'];
    dbConnect();
    
    $sql = "SELECT vBiblio_user.fullname FROM vBiblio_user WHERE  userid='$uid' AND email='$umail' ";
    $result = mysql_query($sql);

	if($result and mysql_num_rows($result)>0){
	    $newpass = substr(md5(time()),0,6);
	    $sql="UPDATE vBiblio_user SET password = PASSWORD('$newpass') WHERE userid='$uid' AND email='$umail' ";
	    
	    $result = mysql_query($sql) or $feedback = "Une erreur est survenue dans le traitement de votre demande.";
	    
	    $message ="Cher $uid,
Vous avez choisi de réinitialiser votre mot de passe. 
Voici donc vos nouveaux identifiants de connexion : 
    Utilisateur : $uid
    mot de passe : $newpass
    
Ce mot de passe a été généré automatiquement. Nous vous conseillons de le changer sur la page de votre profil utilisateur.

Si vous rencontrez des problèmes, n'hésitez pas à nous contacter à l'adresse suivante: vbiblio@free.fr 

En espérant que vous apprécierez nos services,
 
Cordialement,
Julien, votre Webmaster
    ";
	    //$message = utf8_decode($message);
	    mail($umail,"Réinitialisation de vos identifiants", $message, "From:Notification vBiblio <vbiblio@free.fr>");
	    //header('Location:formLogin.php');
	    if($result){$feedback = "R&eacute;initialisation effectu&eacute;e. Merci de consulter votre boite mail.";}
	}	
	else {
		$feedback = "Il semblerait que vous ayez mal renseign&eacute; un champs. Merci de r&eacute;-essayer. <br>Si le probl&egrave; persiste, merci de nous contacter à l'adresse : <a href=\"mailto:vbiblio@free.fr\">vbiblio@free.fr</a>";
	}	
  }
  else {
    $feedback= "Vous n'avez pas correctement renseigner les champs. Merci de recommencer.";
    //header("Location:".$_SERVER['PHP_SELF']);
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">


<html>  
<head>  
	<title>vBiblio - Oubli de mot de passe</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />  
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
</head>  
<body>

<div id="vBibContenu">
	<div id="header">
		<div id="vBibHeader">

			<ul id="vBibMenu">
			<li><div id="enseigne"><a href="formLogin.php">vBiblio</a></div></li>
			</ul>
		</div>
	</div>
	<div id="vBibDisplay">

<br/><br/><br/><br/>
	<br/><br/><br/><br/>

<?=$feedback?>
	
  <div name="resetBox" style="margin:auto;width:350px;border:1px black solid;">
	<div style="background-color:#AAF;color:white;position:relative;top:0px;text-align:center;font-weight:bold;">R&eacute;initialisation du mot de passe</div>
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>" style="display:table-cell;position:relative;padding-left:40px;text-align: right;"> 
		<p>Login:
		<input name="uid" type="text" maxlength="100" size="25" /></p>

		<p>E-mail:
		<input name="umail" type="text" maxlength="100" size="25" title="Adresse que vous avez enregistrée dans votre profil"/></p>
		
    <input type="submit" name="submitok" value="Réinitialiser" />
     
		<br/>
	</form>
	
   
	</div>
	<br/>
	<div style="color:red">Votre nouveau mot de passe vous sera envoyé par mail sur votre adresse.</div> 

	
	
</div>
<div id="vBibFooter">
Site r&eacute;alis&eacute; par <i>Shifty</i>. En cas de besoin, <a href="mailto:vBiblio@free.fr" class="vBibLink">contactez-moi</a>.

</div>

</div>
</body>  
</html>

?>
