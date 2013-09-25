<?php
include('accesscontrol.php');

checkSecurity();

//ajout du bouquin si l'utilisateur a décidé d'ajouter un livre
if(isset($_POST['addBookCycle']) && isset($_POST['nbTomes']) && $_POST['addBookCycle']!='' && $_POST['nbTomes']!=''){
	
	$title = $_POST['addBookCycle'];
	$id_auteur =  $_POST['auteur'];
	$nbtomes = $_POST['nbTomes'];
	$sql = "INSERT INTO vBiblio_cycle (titre, id_author, nb_tomes) VALUES ('$title', '$id_auteur', '$nbtomes');";
	mysql_query($sql);
	
	
	
	$sql = "SELECT prenom, nom FROM vBiblio_author WHERE id_author=$id_auteur";
	$result = mysql_query($sql);
	if($result){
    		$row = mysql_fetch_assoc($result);
    		$nomAuteurPourNotif = $row['nom'];
    		$prenomAuteurPourNotif = $row['prenom'];
  	}
  
	mail("vbiblio@free.fr","[vBiblio] Nouveau cycle saisi", "Bonjour,
  
  L'utilisateur $uid a inséré un nouveau cycle dans la base de données:
    Titre : $title
    Auteur : $prenomAuteurPourNotif $nomAuteurPourNotif 
    Nombre de tomes: $nbtomes
    
	
  Cordialement,
  Julien, votre Webmaster.
  ", "From:Notification vBiblio <vbiblio@free.fr>");
}

$uid = $_SESSION['uid'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Ajout de cycle</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
<script language="javascript">
<!-- 

function validateTomes(){
	obj = document.getElementsByName('nbTomes')[0];
	chaine= obj.value;
	return validateNum(chaine);
	
}

function validateNum(chaine){
	retour = false;
	if(chaine !=''){
		reg = /^[0-9]+$/;
		if(reg.test(chaine))retour = true;
		else alert("Vous ne devez entrer que des chiffres pour le nombre de tomes");
	}
	else{
		alert('Vous devez renseigner toutes les valeurs.');
		retour = false;
	}
	return retour;
}

//>
</script>
</head>
<body>
<div id="vBibContenu">
	<? include('header.php'); ?>

	<div id="vBibDisplay">
	<? include('ssmenuHelpUs.php'); ?>

Vous avez la possibilit&eacute; d'ajouter un titre de cycle directement si celui-ci n'est pas d&eacute;j&agrave; pr&eacute;sent dans notre r&eacute;f&eacute;rentiel:
	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="return validateTomes();">
		<fieldset>
		<table style="font-size:inherit">
		<tr>
			<td>Auteur :</td>
			<td>
				<select name="auteur">
<?
			$sql = "SELECT nom, prenom, id_author FROM vBiblio_author ORDER BY nom ASC";
			$result = mysql_query($sql);
?>
	<?php if($result && mysql_num_rows($result)>0) : ?>
		<?php while($row = mysql_fetch_assoc($result)) : ?>
			<option value="<?=$row['id_author']?>" ><?=$row['nom']?>, <?=$row['prenom']?></option>
		<?php endwhile; ?>
	<?php endif; ?>
				</select>
			</td>
		</tr>

			<tr>
				<td>Titre :</td>
				<td> <input type="text" max-length="100" size="25" name="addBookCycle" /></td>
			</tr>
			<tr>
				<td>Nombre de tomes:</td>
				<td> <input type="text" max-length="10" size="10" name="nbTomes" /></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Ajouter" /></td>
			</tr>
		</table>
		</fieldset>
	</form>
<?
	//faire l'ajout du livre et afficher un message si reussi...
	
	mysql_close();
?>

	</div>
	<? include('footer.php'); ?>

</div>
</body>
</html>

	