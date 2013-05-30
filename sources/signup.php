<?php // signup.php
include 'scripts/common.php';   
include 'scripts/db/db.php';

if(!isset($_POST['submitok'])){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Formulaire d'inscription</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="scripts/datepickercontrol/datepickercontrol.js"></script>
	<link type="text/css" rel="stylesheet" href="scripts/datepickercontrol/datepickercontrol.css">
 </head>    
 <body>    

<div id="vBibContenu"> 
<?
	include('header.php');
?>
<br/>
<br/>
<h3>Formulaire d'inscription</h3>  

<!-- define parameters for the date picker control -->
	<input type="hidden" id="DPC_TODAY_TEXT" value="Aujourd'hui"/>
	<input type="hidden" id="DPC_SUBMIT_FORMAT" value="YYYY-MM-DD"/>
	<input type="hidden" id="DPC_BUTTON_TITLE" value="Ouvrir le calendrier..."/>
	<input type="hidden" id="DPC_MONTH_NAMES" value="['Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre']"/>
	<input type="hidden" id="DPC_DAY_NAMES" value="['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']" />




<form method="post" action="<?=$_SERVER['PHP_SELF']?>" style="font-size:small;">  
<table border="0" cellpadding="0" style="font-size:inherit;border-spacing: 20px 5px;">  
   <tr>  
       <td align="right">  
           <p>Votre pseudo</p>  
       </td>  
       <td>  
           <input name="newid" type="text" maxlength="100" size="25" value="<?=$_SESSION['uid']?>" style="-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;"/>  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>
   <tr style="display:none">  
       <td align="right">  
           <p>Nickname</p>  
       </td>  
       <td>  
           <input name="nick" class="required" type="text" maxlength="100" size="25" value="" />  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>
   <tr>  
       <td align="right">  
           <p>Votre nom</p>  
       </td>  
       <td>  
           <input name="nom" type="text" maxlength="100" size="25" style="-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;"/>  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>  
   <tr>  
       <td align="right">  
           <p>Votre pr&eacute;nom</p>  
       </td>  
       <td>  
           <input name="prenom" type="text" maxlength="100" size="25" style="-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;"/>  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>
   <tr>  
       <td align="right">  
           <p>Votre adresse e-mail</p>  
       </td>  
       <td>  
           <input name="newemail" type="text" maxlength="100" size="25" style="-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;"/>  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>
   <tr style="display: none">  
       <td align="right">  
           <p>Votre adresse e-mail</p>  
       </td>  
       <td>  
           <input name="email" type="text" maxlength="100" size="25" value="" />  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>  
  <tr>  
       <td align="right">  
           <p>Votre date de naissance</p>  
       </td>  
       <td>
		<input type="text" name="dateNaiss" id="DPC_edit1_DD/MM/YYYY" placeholder="jj/mm/aaaa" value="" style="-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;"/>   
       </td>  
   </tr>
     <tr style="display: none">  
       <td align="right">  
           <p>Votre anniversaire</p>  
       </td>  
       <td>  
           <input name="bday" class="required" type="text" maxlength="100" size="25" value="" />  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>  
  <tr>  
       <td align="right">  
           <p>Votre sexe</p>  
       </td>  
       <td>  
       	<INPUT type=radio name="sexe" value="0" checked /> Homme
	<br><INPUT type=radio name="sexe" value="1" /> Femme
    
       </td>  
   </tr>
       <tr style="display: none">  
       <td align="right">  
           <p>Votre adresse</p>  
       </td>  
       <td>  
           <input name="address" class="required" type="text" maxlength="100" size="25" value="" />  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>  

   <tr>  
       <td align="right" colspan="2">
	<br/>
	<br/>
	<p style="font-size:small" ><font color="orangered" ><tt><b>*</b></tt></font> champs obligatoires</p>  

	<br/>
	<br/>
	<input type="reset" value="Remettre &agrave; z&eacute;ro" style="margin-right:50px" />  
	<input class="vert" type="submit" name="submitok" value="   OK   " />  
       </td>  
   </tr>
   
</table>  
</form>
<div style="font-size:small;color:red;">Votre adresse e-mail sera utilis&eacute;e pour vous envoyer votre mot de passe. 
Vous pourrez par la suite changer votre mot de passe dans la page de votre profil.</div>

<? include('footer.php'); ?>

</div>

</div>
</body>    
</html>

<?

}
else{//process sign up submission
	//la date de naissance n'est pas obligatoire...
	if (ftrim($_POST['newid'])=='' or ftrim($_POST['nom'])=='' or ftrim($_POST['prenom'])=='' or ftrim($_POST['newemail'])=='') {
		if($_POST['newid']=='' or ftrim($_POST['newid'])=='' )
			$errorMsg = "Identifiant\\n";
		if($_POST['nom']=='' or ftrim($_POST['nom'])=='' )
			$errorMsg = $errorMsg."Nom\\n";
		if($_POST['prenom']=='' or ftrim($_POST['prenom'])=='' )
			$errorMsg = $errorMsg."Prenom\\n";
		if($_POST['newemail']=='' or ftrim($_POST['newemail'])=='' )
			$errorMsg = $errorMsg."E-mail\\n";		
		error("Vous n'avez pas saisi le(s) champ(s) suivant(s):\\n".$errorMsg);
	}
	
	if($_POST['email']!='' || $_POST['nick']!='' || $_POST['bday']!='' || $_POST['address']!=''){
		//si l'un des champs cachés est rempli, robot spam !
		die("Une erreur est survenue. Merci de ré-essayer plus tard.");
	}
	
	dbConnect();
	
	// Check for existing user with the new id  
	$sql = "SELECT COUNT(*) FROM vBiblio_user WHERE userid = '$_POST[newid]'";  
   	$result = mysql_query($sql);  
   	if (!$result) {  
		error('A database error occurred.\\nIf this error persists, please '.  
             'contact you@example.com.');  
   	}
   	if (@mysql_result($result,0,0)>0) {  
   	    error('Ce pseudo est déjà utilisé.\\nEssayez-en un autre...');  
   	}
	$newpass = substr(md5(time()),0,6);
	$updateDateNaiss=ftrim($_POST[dateNaiss]);
	$fmtDateNaiss = substr($updateDateNaiss, 6, 4)."-".substr($updateDateNaiss, 3, 2)."-".substr($updateDateNaiss, 0, 2);
	$sql = "INSERT INTO vBiblio_user SET  
	     userid = '$_POST[newid]',  
	     password = PASSWORD('$newpass'),
	     prenom = '$_POST[prenom]',
	     nom = '$_POST[nom]',
	     fullname = '$_POST[prenom] $_POST[nom]',  
	     email = '$_POST[newemail]',
	     date_naiss = '$fmtDateNaiss',
	     sexe =$_POST[sexe]";


	if (!mysql_query($sql))  
       		error('A database error occurred in processing your submission.\\nIf this error persists, please contact Webmaster .');


	   $message = "Cher ".$_POST['prenom'].",

Tout d'abord, bienvenue et merci de vous être inscrit sur notre site.
Votre compte utilisateur vient d'être créé. Pour vous connecter, veuillez vous rendre sur : http://vbiblio.free.fr/

Votre compte utilisateur et votre mot de passe sont les suivants:  
   Utilisateur: $_POST[newid]  
   mot de passe: $newpass  
 
Votre mot de passe a été généré automatiquement. Nous vous conseillons de le modifier dans la page profil de votre compte.

Si vous rencontrez des problèmes, n'hésitez pas à me contacter à l'adresse suivante: vbiblio@free.fr 

En espérant que vous apprécierez nos services,
 
Cordialement,
Julien, votre Webmaster  
";  
 
	/*mail($_POST['newemail'],"Confirmation d'inscription sur vBiblio", $message, "From:Webmaster vBiblio <vbiblio@free.fr>");
	  mail("vbiblio@free.fr","[vBiblio] Nouvelle inscription", "Bonjour,
  
  Un nouvel utilisateur vient de s'inscrire :
    Utilisateur: $_POST[newid]  
	
  Cordialement,
  Julien, votre Webmaster.
  ", "From:Notification vBiblio <vbiblio@free.fr>");

	
	*/
	header('Location:formLogin.php');

}

?>
