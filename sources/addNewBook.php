<?php
include('accesscontrol.php');

checkSecurity();

$uid = $_SESSION['uid'];

//ajout du bouquin si l'utilisateur a décidé d'ajouter un livre
if(isset($_POST['addBookTitle']) && $_POST['addBookTitle'] && isset($_POST['auteur']) && $_POST['auteur'] && $_POST['addBookISBN']){
	$title = $_POST['addBookTitle'];
	$id_auteur =  $_POST['auteur'];
	$desc = trim($_POST['desc']);
	$isbn_number = $_POST['addBookISBN'];
	
	if (isset($_POST['seriesEnabled']) and isset($_POST['idTome']) and isset($_POST['series']) and $_POST['idTome']!=''  ){
		$idtome = $_POST['idTome'];
		$serie = $_POST['series'];
		$sql = "INSERT INTO vBiblio_book (titre, id_author, id_cycle, numero_cycle, description, isbn) VALUES ('$title', '$id_auteur', '$serie', '$idtome', '$desc', '$isbn_number');";
	}
	else $sql = "INSERT INTO vBiblio_book (titre, id_author, description, isbn) VALUES ('$title', '$id_auteur', '$desc', '$isbn_number');";

	mysql_query($sql);
	
	$sql = "SELECT prenom, nom FROM vBiblio_author WHERE id_author=$id_auteur";
	$result = mysql_query($sql);
	if($result){
    $row = mysql_fetch_assoc($result);
    $nomAuteurPourNotif = $row['nom'];
    $prenomAuteurPourNotif = $row['prenom'];
  }
	
	$message ="Bonjour,
  
  L'utilisateur $uid a inséré un nouveau livre dans la base de données:
    Titre: ".utf8_encode(str_replace('\\', '',$title))."
  	Auteur : $prenomAuteurPourNotif $nomAuteurPourNotif
    Description : 
    ".utf8_encode(str_replace('\\', '',$desc))."
	
  Cordialement,
  Julien, votre Webmaster.
  ";
  $message = utf8_decode($message);
  //mail("vbiblio@free.fr","[vBiblio] Nouveau livre saisi", $message, "From:Notification vBiblio <vbiblio@free.fr>");
  
}
else {
	if (isset($_POST['addBookTitle']) ) {
		$error = "<a style=\"color:red;\">Vous n'avez pas correctement saisi l'un des champs.</a>";
	}
}


//$uid = $_SESSION['uid'];
$sql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$row = mysql_fetch_assoc($result);
	$mytableId = $row['tableuserid'];
}




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
	<? include('header.php'); ?>

	<div id="vBibDisplay">
	<? include('ssmenuHelpUs.php'); ?>

Vous avez la possibilit&eacute; d'ajouter un livre directement si celui-ci n'est pas d&eacute;j&agrave; pr&eacute;sent dans notre r&eacute;f&eacute;rentiel:
	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="return validateForm();">
		<fieldset>
			<table style="font-size:inherit;">
			<tr>
				<td>Auteur :</td>
				<td>
					<select name="auteur" onchange="javascript:reloadBookTitles(this);">

<?
	$sql = "SELECT nom, prenom, id_author FROM vBiblio_author ORDER BY nom ASC";
	$result = mysql_query($sql);
	if($result && mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)){
			$nom = $row['nom'];
			$prenom = $row['prenom'];
			$idAut = $row['id_author'];
			if( isset($_POST['auteur']) && $_POST['auteur'] == "$idAut" )
 			  echo "<option value=\"$idAut\" selected>$nom, $prenom</option>";
 			else echo "<option value=\"$idAut\" >$nom, $prenom</option>";
		}

	}

?>
					</select>
				</td>
			</tr>

			<tr>
				<td>Titre :</td>
				<td><input type="text" max-length="100" size="25" name="addBookTitle" /></td>
			</tr>
			<tr>
				<td>ISBN :</td>
				<td><input type="text" max-length="13" size="25" name="addBookISBN" onchange="javascript:validateISBN(this);"/></td>
			</tr>
			<tr>
				<td valign="top">Description :</td>
				<td><textarea  cols="50" rows="10" name="desc" ></textarea>
			</tr>
			<tr>
				<td>S&eacute;rie:</td>
				<td><input type="checkbox" onchange="javascript:switchSeriesState()" name="seriesEnabled"/>
				Titre : <select id="seriesList" name="series" disabled>
						<option></option>
						<option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
					</select> 
				Num&eacute;ro du tome: <input type="text" name="idTome" disabled/>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Ajouter" /></td>
			</tr>
			</table>
		</fieldset>
	</form>
<?

	$reqTotalCount = "SELECT COUNT(*) as nbTotalBook FROM vBiblio_book;";
	$resTotalCount = mysql_query($reqTotalCount);

	if ($resTotalCount && mysql_num_rows($resTotalCount) ) {
		$row = mysql_fetch_assoc($resTotalCount);
		$combienDeLivres = $row['nbTotalBook'];
?>
	<div class="vBibInfo">Nous avons actuellement <?=$combienDeLivres?> livres r&eacute;f&eacute;renc&eacute;s dans notre syst&egrave;me. Avez-vous bien v&eacute;rifi&eacute; que celui que vous voulez ajouter, n'y est pas d&eacute;j&agrave;?</div>
<?
	}

	mysql_close();
?>
	</div>
	<? include('footer.php'); ?>

</div>

</body>
</html>

