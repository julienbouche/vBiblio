<?php
include('accesscontrol.php');
include('scripts/db/db.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');

dbConnect();
checkSecurity();

$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Enrichissez votre biblioth&egrave;que</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="js/ergofunctions.js"/>
	</script>
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">

	Rechercher parmi notre r&eacute;f&eacute;rentiel un livre que vous souhaitez ajouter &agrave; votre biblioth&egrave;que :
<?


	if(isset($_POST['searchText']) ){
		$searchText = $_POST['searchText'];
	}
?>
	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
		<fieldset>
			<input type="text" max-length="100" size="100" name="searchText" value="<? echo str_replace("\\", "" , $searchText);?>"/>
			<input type="submit" value="Rechercher" style="float:right;" />
		</fieldset>
	</form>
	<br/>
<?

	if(isset($_POST['searchText']) ){
		$utilisateur->afficherRechercheLivresAAjouter($_POST['searchText']);	
	}

	//gestion de l'enregistrement des livres sélectionnés
	if(isset($_POST['booksToAdd']) ){
		$bouquins = $_POST['booksToAdd'];

		while($bouquin = array_shift($bouquins) ){
			$sql = "INSERT INTO vBiblio_poss (id_book, userid, possede, lu, pret, date_ajout) VALUES ('$bouquin', '".$utilisateur->getID()."', '1', '0', '0', '".date('Y-m-d H:i:s')."')";
			$result = mysql_query($sql);
		}
	}
	
	if(isset($_POST['booksToAddTRL']) ){
		$bouquins = $_POST['booksToAddTRL'];

		while($bouquin = array_shift($bouquins) ){
			$sql = "INSERT INTO vBiblio_toReadList (id_book, id_user, date_ajout) VALUES ('$bouquin', '".$utilisateur->getID()."', '".date('Y-m-d H:i:s')."')";
			$result = mysql_query($sql);
		}
	}


?>


<br/>
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
