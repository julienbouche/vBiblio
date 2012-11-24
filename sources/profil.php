<?php
include('accesscontrol.php');
include('scripts/common.php');
//include('scripts/db/db.php');
include('scripts/dateFunctions.php');
checkSecurity();

//connexion    la bd
//dbConnect();

$uid= $_SESSION['uid'];

$message="";

$tuid = getTableUserId($uid);

// avatar


if (isset($_POST['supprim_avatar']) && ($_POST['supprim_avatar']=='on'))
{
$nomavatar = "images/avatars/avatar-".$tuid.".png" ;

unlink($nomavatar);
$nomavatar = "images/avatars/avatar-160-".$tuid.".png" ;

unlink($nomavatar);
}	

//echo "Taille de l'image : ".$_FILES['fichier']['size'] ;
	if ((($_FILES['fichier']['size']!=0)) && ($_FILES['fichier']['size']<(209715*5)))
	{
		$imageok = 0 ;
		switch($_FILES['fichier']['type'])
		{
			case "image/gif" : 	$nomavatar = "images/avatars/avatar-".$tuid.".gif" ;
								$nomavatarmini = "images/avatars/mini-avatar-".$tuid.".gif" ;
								
								$nomtemp = "images/temp/maphoto.gif";
								$imageok = 1 ;
								break ;
			case "image/x-png" : 	
			case "image/png" : 	$nomavatar = "images/avatars/avatar-".$tuid.".png" ;
								$nomavatarmini = "images/avatars/mini-avatar-".$tuid.".jpg" ;
								$nomtemp = "images/temp/maphoto.png";
								$imageok = 1 ;
								break ;
			case "image/pjpeg" :				
			case "image/jpeg" :
			case "image/jpg" : 	$nomavatar = "images/avatars/avatar-".$tuid.".jpg" ;
								$nomavatarmini = "images/avatars/mini-avatar-".$tuid.".jpg" ;
								$nomtemp = "images/temp/maphoto.jpg";
								$imageok = 1 ;
								break ;
			default :			echo "Type d'image non accept  ! (".$_FILES['fichier']['type'].")" ;
								break ;
								
		}
		
		if ($imageok == 1)
		{
			
			
			if (copy($_FILES['fichier']['tmp_name'], $nomtemp))
			{
				
				$tabdim = getimagesize($nomtemp) ;
				$largeur = 0 ;
				$hauteur = 0 ;
			
			
				//////////////////////////////////////
				
				
				
				if ($tabdim[0]>=50 && $tabdim[1]>=50)
				{

				switch($_FILES['fichier']['type'])
				{
					case "image/gif" : 	$imbase = imagecreatefromgif ('images/temp/maphoto.gif') ;
					
										break ;
					case "image/x-png" : 	
					case "image/png" : 	$imbase = imagecreatefrompng ('images/temp/maphoto.png') ;
										break ;
					case "image/jpeg" :
					case "image/pjpeg" :
					case "image/jpg" : 	$imbase = imagecreatefromjpeg ('images/temp/maphoto.jpg') ;
										break ;
					default :			echo "Type d'image non accept  ! (".$_FILES['fichier']['type'].")" ;
										break ;
										
				}
						
				
				
				
				/// CREA PETITE DEB
						
				$nomavatar = "images/avatars/avatar-".$tuid.".png" ;
				$imgrande = imagecreatetruecolor(50,50) ;
				
				imagecopyresampled  ( $imgrande, $imbase, 0, 0, 0, 0, 50, 50, $tabdim[0], $tabdim[1]) ;
				imagejpeg($imgrande,$nomavatar,100); 
					

				// CREA PETITE FIN
				
				/// CREA GRANDE DEB
				
				if ($tabdim[0]<=160 && $tabdim[1]<=160)
				{
						$lar=$tabdim[0] ;
						$hau=$tabdim[1] ;
				}
				else
				{
					if ($tabdim[1]>=$tabdim[0]) // hauteur + grande
					{
						$hau=160 ;
						$lar=$tabdim[0]*160/$tabdim[1] ;
					}
					else
					{
						$lar=160 ;
						$hau=$tabdim[1]*160/$tabdim[0] ;
					}	
				}
				
				
						
				$nomavatar2 = "images/avatars/avatar-160-".$tuid.".png" ;
				$imgrandee = imagecreatetruecolor($lar,$hau) ;
				
				imagecopyresampled  ( $imgrandee, $imbase, 0, 0, 0, 0, $lar, $hau, $tabdim[0], $tabdim[1]) ;
				imagejpeg($imgrandee,$nomavatar2,100); 
					

				// CREA PETITE FIN
				
				unlink($nomtemp);
			
				}	
				else
					echo "Votre image est trop petite !" ;
				

				
			}
		}
		else
		{
			echo "D&eacute;sol&eacute;, ce format n'est pas pris en compte !" ;
		}

		
	}
	
	
	

// fin avatar



if(isset($_POST['oldPwd'])){
	$oldPwd = $_POST['oldPwd'];
	$newPwd = $_POST['newPwd'];
	
	unset($_POST['oldPwd']);
	unset($_POST['newPwd']);

	$sql= " SELECT fullname FROM vBiblio_user WHERE userid='$uid' AND password=PASSWORD('$oldPwd')";

	$result = mysql_query($sql);
	
	//on a bien trouv © le compte de l'utilisateur
	if($result && mysql_num_rows($result)>0){
		$sql = "UPDATE vBiblio_user SET password = PASSWORD('$newPwd') WHERE userid='$uid' AND password=PASSWORD('$oldPwd')";
		
		mysql_query($sql);		

		$message = "Votre mot de passe a &eacute;t&eacute; mis &agrave; jour";
	}
}

//v ©rifier l'autre formulaire
if(isset($_POST['sexe']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['newemail']) && isset($_POST['dateNaiss']) ){
	$updateNom = $_POST['nom'];
	$updatePrenom = $_POST['prenom'];
	$updateDateNaiss = $_POST['dateNaiss'];
	$updateSexe = $_POST['sexe'];
	$updateEmail = $_POST['newemail'];
	
	$prefBookStyle = $_POST['prefBookStyle']; //mysql_result($result, 0,'prefBookStyle');
	$sitePerso = $_POST['sitePerso']; //mysql_result($result, 0,'website');
	$prefBook = $_POST['prefBook']; //mysql_result($result, 0,'id_pref_book');	
	
	if(isset($_POST['active_public_page']) )$active_public_page = "1";
	else $active_public_page = "0";
	
	if(isset($_POST['notify_me']) )$notify_me = "1";
	else $notify_me = "0";

	//JJ/MM/AAAA  -> AAAA-MM-JJ
	$updateDateNaiss = substr($updateDateNaiss, 6, 4)."-".substr($updateDateNaiss, 3, 2)."-".substr($updateDateNaiss, 0, 2);

	$sql="UPDATE vBiblio_user SET nom='$updateNom', prenom='$updatePrenom', sexe=$updateSexe, email ='$updateEmail', date_naiss='$updateDateNaiss', website='$sitePerso', prefBookStyle='$prefBookStyle', id_pref_book='$prefBook', notification_active='$notify_me', active_public_page='$active_public_page' WHERE userid='$uid'";

	$result = mysql_query($sql);
	$donneesPerso = "<font color=\"red\">Vos informations personnelles ont &eacute;t&eacute; mises &agrave; jour.</font>";	
}


//on r ©cup ¨re enfin les infos de la bdd ( ©ventuellement mis    jour juste au dessus)
$sql = "SELECT email, date_naiss, sexe, nom, prenom, website, prefBookStyle, id_pref_book, notification_active, active_public_page	 FROM vBiblio_user WHERE userid = '$uid'";    

$result = mysql_query($sql);

if (mysql_num_rows($result) == 0) {
	unset($_SESSION['uid']);    
	unset($_SESSION['pwd']);
	header('Location:LoginError.php');    
}
else{
	$userEmail = mysql_result($result,0,'email');
	$dateNaissance = mysql_result($result, 0, 'date_naiss');
	$sexe = mysql_result($result, 0,'sexe');
	$nom =  mysql_result($result, 0,'nom');
	$prenom =  mysql_result($result, 0,'prenom');
	$prefBookStyle = mysql_result($result, 0,'prefBookStyle');
	$sitePerso = mysql_result($result, 0,'website');
	$prefBook = mysql_result($result, 0,'id_pref_book');
	$notify_me = mysql_result($result, 0,'notification_active');
	if($notify_me=="1")$notify_me = "checked";
	else $notify_me = "";
	$active_public_page = mysql_result($result, 0,'active_public_page');
	if($active_public_page == "1")$active_public_page = "checked";
	else $active_public_page = "";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Vos informations personnelles</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="scripts/datepickercontrol/datepickercontrol.js"></script>
	<link type="text/css" rel="stylesheet" href="scripts/datepickercontrol/datepickercontrol.css">
<script language="javascript">
<!--
function verifyPwd(){
	$pwd1 = document.form2.newPwd.value;
	$pwd2 = document.form2.newPwd2.value;
	if($pwd1.length>0 && $pwd1==$pwd2){
		document.form2.submitButton.disabled='';
	}
	else{
		document.form2.submitButton.disabled='disabled';
	}
}

//-->
</script>

</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">

<?
	//include('ssmenuProfil.php');
?>

	<b>Vos informations personnelles</b><br/>

<!-- define parameters for the date picker control -->
	<input type="hidden" id="DPC_TODAY_TEXT" value="Aujourd'hui"/>
	<input type="hidden" id="DPC_SUBMIT_FORMAT" value="YYYY-MM-DD"/>
	<input type="hidden" id="DPC_BUTTON_TITLE" value="Ouvrir le calendrier..."/>
	<input type="hidden" id="DPC_MONTH_NAMES" value="['Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre']"/>
	<input type="hidden" id="DPC_DAY_NAMES" value="['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']" />

<?

	if(isset($donneesPerso) ){
		echo "$donneesPerso";
	}
?>

	<form method="post" action="<?=$_SERVER['PHP_SELF']?>"  enctype='multipart/form-data'>  
<table border="0" cellpadding="0" cellspacing="5" style="font-size:inherit;">  
   <tr>  
       <td align="right">  
           <p>Identifiant:</p>  
       </td>  
       <td>  
           <input name="newid" type="text" maxlength="100" size="25" value="<?=$_SESSION['uid']?>" readonly/>  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>
   <tr>  
       <td align="right">  
           <p>Votre pr&eacute;nom:</p>  
       </td>  
       <td>  
           <input name="prenom" type="text" maxlength="100" size="25" value="<?=$prenom?>"/>  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>  
   <tr>  
       <td align="right">  
           <p>Votre nom:</p>  
       </td>  
       <td>  
           <input name="nom" type="text" maxlength="100" size="25" value="<?=$nom?>"/>  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font>  
       </td>  
   </tr>  
   <tr>  
       <td align="right">  
           <p>Votre adresse e-mail:</p>  
       </td>  
       <td>  
           <input name="newemail" type="text" maxlength="100" size="25" value="<?=$userEmail?>" />  
           <font color="orangered" size="+1"><tt><b>*</b></tt></font> 
       </td>  
   </tr> 
   <tr>  
       <td align="right">  
           <p>Date de naissance:</p>  
       </td>  
       <td>  
           <input type="text" name="dateNaiss" id="DPC_edit1_DD/MM/YYYY" value="<?=displayForm($dateNaissance)?>"/>
       </td>  
   </tr>
  <tr>  
       <td align="right">  
           <p>Sexe:</p>  
       </td>  
       <td>  
           <select name="sexe"><option value="0" 
<?
	if($sexe=="0") echo " selected ";
?>
>Homme</option><option value="1"
<?
	if($sexe=="1") echo " selected ";
?>
>Femme</option></select>
       </td>  
   </tr>

<!-- RAJOUT DU 14/03-->
 <tr>  
       <td align="right">  
           <p>Votre style de livres pr&eacute;f&eacute;r&eacute;:</p>  
       </td>  
       <td>  
           <input name="prefBookStyle" type="text" maxlength="100" size="25" value="<?=$prefBookStyle?>" />  
       </td>  
   </tr> 
 <tr>  
       <td align="right">  
           <p>Votre livre pr&eacute;f&eacute;r&eacute;:</p>  
       </td>  
       <td>
	   <select name="prefBook">
<?
	$sql = "SELECT vBiblio_book.titre As titre, numero_cycle, vBiblio_author.nom as nom, vBiblio_author.prenom as prenom, vBiblio_poss.lu, vBiblio_poss.possede, vBiblio_poss.pret, vBiblio_poss.id_book as id_book, vBiblio_author.id_author FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user WHERE vBiblio_poss.userid = vBiblio_user.tableuserid AND vBiblio_user.userid='$uid' AND vBiblio_poss.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author ORDER BY vBiblio_author.nom ASC, id_cycle, numero_cycle ASC"; 

	$result = mysql_query($sql);

	if($result && mysql_num_rows($result)>0 ){
		if($prefBook=="0")
			echo "<option value=\"0\" selected></option";
		else 
			echo "<option value=\"0\"></option";

		while($row=mysql_fetch_assoc($result)){
			$titre = $row['titre'];
			$nom_auteur = $row['nom'];
			$prenom_auteur = $row['prenom'];
			$possede = $row['possede'];
			$lu = $row['lu'];
			$prete = $row['pret'];
			$idbook = $row['id_book'];
			$idAuthor = $row['id_author'];
			$num_in_cycle = $row['numero_cycle'];
			
			$sql_cycle= "SELECT vBiblio_cycle.titre, nb_tomes FROM vBiblio_cycle, vBiblio_book WHERE vBiblio_book.id_cycle=vBiblio_cycle.id_cycle AND vBiblio_book.id_book=$idbook";
			

//			echo "<li>\n<span class=\"vBibBookTitle $style\"><a href=\"ficheLivre.php?id=$idbook\" class=\"vBibLink\">";			

			$cycles = mysql_query($sql_cycle);
			if($cycles && mysql_num_rows($cycles) > 0) {
				$cycle = mysql_result($cycles, 0, 'titre');
				$nb_tomes= mysql_result($cycles, 0, 'nb_tomes');
				$titre = $cycle.", Tome $num_in_cycle: ".$titre;
				//echo "$cycle, Tome $num_in_cycle ($nb_tomes): ";
			}
			if ($idbook==$prefBook) echo "<option value=\"$idbook\" selected>$titre</option>";
			else echo "<option value=\"$idbook\" >$titre</option>";
		}
	}

?>
		
	   </select>  
           <!--input name="prefBook" type="text" maxlength="100" size="25" value="<?=$prefBook?>" /-->  
       </td>  
   </tr> 
 <tr>  
       <td align="right">  
           <p>Votre site internet:</p>  
       </td>  
       <td>  
           <input name="sitePerso" type="text" maxlength="100" size="25" value="<?=$sitePerso?>" />  
       </td>  
   </tr> 
 <tr>  
       <td align="right">  
           <p>Activer les notifications par mail:</p>  
       </td>  
       <td>  
           <input name="notify_me" type="checkbox" <?=$notify_me?> />  
       </td>  
   </tr> 



<!-- FIN RAJOUT DU 14/03-->

<tr>  
       <td align="right">  
           <p>Activer ma page publique:</p>  
       </td>  
       <td>  
           <input name="active_public_page" type="checkbox" <?=$active_public_page?> />
           <?
           if($active_public_page== "checked"){
            ?>
            <a href="/public/user/<?=$uid?>" target="_blank" class="vBibLink">Voir ma page publique</a>
            <?
           }
           ?>
       </td>  
   </tr> 

<!-- RAJOUT ANTONY-->
 <tr>  
       <td align="right">  
           <p>Votre avatar:</p>  
       </td>  
       <td>  
           <input name="fichier" type="file" size="25" />  
       </td>  
   </tr> 
   
   <tr>  
       <td align="right">  
           <p>Image actuelle:</p>  
       </td>  
       <td>  
            <?php

$nom_image = "images/avatars/avatar-160-".$tuid.".png" ;
if (file_exists($nom_image))
echo "<img src='".$nom_image."'><br/>
<input type='checkbox' name='supprim_avatar' id='supprim_avatar' style='width:25px'> Supprimer l'image";
else
echo "Pas d'image." ;
?>
       </td>  
   </tr> 
   
  
   
<!-- FIN RAJOUT ANTONY-->

   <tr>  
       <td align="right" colspan="2">  

           <!--input type="reset" value="Reset Form" /-->  
           <input type="submit" name="submitok" value="   Mettre &agrave; jour   " />  
       </td>  
   </tr>  
</table>  
</form>  


	<form name="form2" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<b>Changement de mot de passe</b><br/>
	
	<fieldset>
	<table>
		<tr>
		<td>Ancien mot de passe:</td><td><input name="oldPwd" type="password" maxlength="100" size="25" value=""/></td>
		</tr>
		<tr>
		<td>Nouveau mot de passe:</td><td><input name="newPwd" id="newPwd" type="password" maxlength="100" size="25" onkeyup="javascript:verifyPwd();" /></td>
		</tr>
		<tr>
		<td>Confirmation:</td><td><input name="newPwd2" id="newPwd2" type="password" maxlength="100" size="25" onkeyup="javascript:verifyPwd();"/></td>
		</tr>
		<tr>
		<td></td><td><input name="submitButton" type="submit" disabled value="Mettre &agrave; jour" />
		</tr>
	</table>
	<div name="feedback"><?=$message?></div>
	</fieldset>	
	</form>
</div>

<?
	include('footer.php');
?>
</div>

</body>
</html>
