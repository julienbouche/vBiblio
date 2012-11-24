<?php
include('accesscontrol.php');
//include('scripts/db/db.php');

checkSecurity();
//dbConnect();

//ajout du bouquin si l'utilisateur a décidé d'ajouter un livre
if(isset($_POST['addBookTitle']) && $_POST['addBookTitle'] && isset($_POST['auteur']) && $_POST['auteur'] ){
	$title = $_POST['addBookTitle'];
	$id_auteur =  $_POST['auteur'];
	$desc = trim($_POST['desc']);
	
	if (isset($_POST['seriesEnabled']) and isset($_POST['idTome']) and isset($_POST['series']) and $_POST['idTome']!=''  ){
		$idtome = $_POST['idTome'];
		$serie = $_POST['series'];
		$sql = "INSERT INTO vBiblio_book (titre, id_author, id_cycle, numero_cycle, description) VALUES ('$title', '$id_auteur', '$serie', '$idtome', '$desc');";
	}
	else $sql = "INSERT INTO vBiblio_book (titre, id_author, description) VALUES ('$title', '$id_auteur', '$desc');";

	mysql_query($sql);
}
else {
	if (isset($_POST['addBookTitle']) ) {
		$error = "<a style=\"color:red;\">Vous n'avez pas correctement saisi l'un des champs.</a>";
	}
}


$uid = $_SESSION['uid'];
$sql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$row = mysql_fetch_assoc($result);
	$mytableId = $row['tableuserid'];
}



header('Access-Control-Allow-Origin: http://xisbn.worldcat.org/');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>

	<title>vBiblio - Formulaire d'ajout de livres</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
	<script type="text/javascript" src="js/gui/bookForm_gui.js"></script>
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">
<?
	include('ssmenuHelpUs.php');
?>

Vous avez la possibilit&eacute; d'ajouter un livre directement si celui-ci n'est pas d&eacute;j&agrave; pr&eacute;sent dans notre r&eacute;f&eacute;rentiel:
	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="return validateFORM();">
		<fieldset>
			<table style="font-size:inherit;">
			<tr><td>ISBN :</td><td><input type="text" size="25" name="addBookISBN" onchange="javascript:validateISBNandPopulateBookInformations(this);" /></td></tr>
			<tr><td></td><td></td></tr>
			<tr><td>Auteur :</td><td> <select name="auteur" onchange="javascript:reloadBookTitles(this);">

<?
	$sql = "SELECT nom, prenom, id_author FROM vBiblio_author ORDER BY nom ASC";
	$result = mysql_query($sql);
	if($result && mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)){
			$nom = $row['nom'];
			$prenom = $row['prenom'];
			$idAut = $row['id_author'];
			echo "<option value=\"$idAut\" >$prenom $nom</option>";
		}

	}

?>
			</select></td></tr>
			<tr><td>Titre :</td><td> <input type="text" max-length="100" size="25" name="addBookTitle" /></td></tr>
			<tr><td valign="top">Description :</td><td> <textarea  cols="50" rows="10" name="desc" ></textarea></tr>
			<tr><td>S&eacute;rie:</td><td> <input type="checkbox" onchange="javascript:switchSeriesState()" name="seriesEnabled"/>
			Titre : <select id="seriesList" name="series" disabled><option></option><option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option></select> 
			Num&eacute;ro du tome: <input type="text" name="idTome" disabled/></td></tr>
			<tr><td></td><td><input type="submit" value="Ajouter"/><input type="button" value="R&eacute;initialiser le formulaire" onclick="resetISBNBookForm();"/></td></tr>
			</table>
		</fieldset>
	</form>

<?
	

	mysql_close();
?>
	
</div>
<?
	include('footer.php');
?>

</div>

</body>
</html>

