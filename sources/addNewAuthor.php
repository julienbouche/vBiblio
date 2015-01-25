<?php
include('accesscontrol.php');

checkSecurity();

$uid = $_SESSION['uid'];

//ajout du bouquin si l'utilisateur a décidé d'ajouter un livre
if(isset($_POST['addAuthorName']) && trim($_POST['addAuthorName']) !='' && isset($_POST['addAuthorFirstname']) && $_POST['addAuthorFirstname']!='' ){
	$nom = $_POST['addAuthorName'];
	$prenom =  $_POST['addAuthorFirstname'];
	$desc = $_POST['addAuthorDesc'];
	$sql = "INSERT INTO vBiblio_author (nom, prenom, description) VALUES ('$nom', '$prenom', '$desc');";

	mysql_query($sql) or $error = "<a style=\"color:red;\">Une erreur est survenue pendant l'ajout.</a>";
	
	//TODO trouver un moyen de ne pas faire attendre l'utilisateur pour l'envoi du mail
  mail("vbiblio@free.fr","[vBiblio] Nouvel auteur saisi", "Bonjour,
  
  L'utilisateur $uid a ins&eacute;r&eacute; un nouvel auteur dans la base de donn&eacute;es:
    Nom: $nom
    Prenom : $prenom
    Description:
    $desc
	
  Cordialement,
  Julien, votre Webmaster.
  ", "From:Notification vBiblio <vbiblio@free.fr>");
	
}else{
	if(isset($_POST['addAuthorName'])) $error ="<a style=\"color:red;\">Vous n'avez pas correctement saisi les champs</a>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Formulaire d'ajout d'un auteur</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
</head>
<body>
<div id="vBibContenu">
	<?php include('header.php'); ?>

	<div id="vBibDisplay">
	<?php include('ssmenuHelpUs.php'); ?>

Vous avez la possibilit&eacute; d'ajouter un auteur directement si celui-ci n'est pas d&eacute;j&agrave; pr&eacute;sent dans notre r&eacute;f&eacute;rentiel:
	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
		<fieldset>
			<table style="font-size:inherit">
			<tr><td>Nom de l'auteur :</td><td> <input type="text" max-length="100" size="25" name="addAuthorName" /> </td></tr>
			<tr><td>Pr&eacute;nom de l'auteur: </td><td><input type="text" max-length="100" size="25" name="addAuthorFirstname" /> </td></tr>
			<tr><td valign="top">Description : </td><td><textarea  cols="50" rows="10" name="addAuthorDesc" ></textarea> </td></tr>
			<tr><td></td><td><input type="submit" value="Ajouter" /> </td></tr>
			</table>
		</fieldset>
	</form>
<?php
	//faire l'ajout du livre et afficher un message si reussi...

	mysql_close();
	if (isset($error) ) echo "$error";
?>
	
	</div>
</div>	
	<?php include('footer.php'); ?>
</body>
</html>

