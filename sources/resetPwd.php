<?php
include 'scripts/common.php';   
include 'scripts/db/db.php';

if(!isset($_POST['submitok']) ){
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


	
  <div name="resetBox" style="margin:auto;width:350px;border:1px black solid;">
	<div style="background-color:#AAF;color:white;position:relative;top:0px;text-align:center;font-weight:bold;">R&eacute;initialisation du mot de passe</div>
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>" style="display:table-cell;position:relative;padding-left:40px;text-align: right;"> 
		<p>Login:
		<input name="uid" type="text" maxlength="100" size="25" /></p>

		<p>E-mail:
		<input name="umail" type="text" maxlength="100" size="25" title="Adresse que vous avez enregistr�e dans votre profil"/></p>
		
    <input type="submit" name="submitok" value="R�initialiser" />
     
		<br/>
	</form>
	
   
	</div>
	<br/>
	<div style="color:red">Votre nouveau mot de passe vous sera envoy� par mail sur votre adresse.</div> 

	
	
</div>
<div id="vBibFooter">
Site r&eacute;alis&eacute; par <i>Shifty</i>. En cas de besoin, <a href="mailto:vBiblio@free.fr" class="vBibLink">contactez-moi</a>.

</div>

</div>
</body>  
</html>
<?
}
else{
  if( isset($_POST['uid']) && isset($_POST['umail']) ){
    $uid = $_POST['uid'];
    $umail = $_POST['umail'];
  
	
    $newpass = substr(md5(time()),0,6);
    $sql="UPDATE vBiblio_user SET password = PASSWORD('$newpass') WHERE userid='$uid' AND email='$umail' ";
    dbConnect();
    mysql_query($sql);
    
    $message ="Cher $uid,
Vous avez choisi de r�initialiser votre mot de passe. 
Voici donc vos nouveaux identifiants de connexion : 
    Utilisateur : $uid
    mot de passe : $newpass
    
Ce mot de passe a �t� g�n�r� automatiquement. Nous vous conseillons de le changer sur la page de votre profil utilisateur.

Si vous rencontrez des probl�mes, n'h�sitez pas � me contacter � l'adresse suivante: vbiblio@free.fr 

En esp�rant que vous appr�cierez nos services,
 
Cordialement,
Julien, votre Webmaster
    ";
    //$message = utf8_decode($message);
    mail($umail,"R�initialisation de vos identifiants", $message, "From:Notification vBiblio <vbiblio@free.fr>");
    header('Location:formLogin.php');
  }
  else {
    header("Location:".$_SERVER['PHP_SELF']);
  }
}
?>