<?php
include('accesscontrol.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');
require_once('classes/Tag.php');

checkSecurity();


$uid = $_SESSION['uid'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Recherche de livres par &eacute;tiquette</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="js/ergofunctions.js">
	</script>
</head>
<body>
<div id="vBibContenu">
	<?php include('header.php'); ?>

	<div id="vBibDisplay">
<?php

//partie d'ajout des livres sélectionnés
if(isset($_POST['booksToAdd']) ){
	$bouquins = $_POST['booksToAdd'];

	while($bouquin = array_shift($bouquins) ){
		$sql = "INSERT INTO vBiblio_poss (id_book, userid, possede, lu, pret, date_ajout) VALUES ('$bouquin', '$mytableId', '1', '0', '0', '".date('Y-m-d H:i:s')."')";
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

//initialisation du tag : si la variable n'est pas presente alors la fonction exists renverra faux
$tag = new Tag($_GET['idtag']);
?>
<br/>
<br/>
	
<?php if($tag->exists()) : $bouquins = $tag->recupererListeLivreTaggueNonDansUneListeUtilisateur($utilisateur)?>
	Tous les livres correspondant &agrave; l'&eacute;tiquette "<?=$tag->getName()?>" :
	<br/>
	<br/>	
	<?php if(count($bouquins)>0) : ?>
	<form name="addingBookList" method="POST" action="<?=$_SERVER['PHP_SELF']?>?idtag=<?=$tag->getID()?>">
		<table style="font-size:inherit;width:100%;">
			<tr>
				<td></td>
				<td></td>
				<td style="text-align:center;">Dans votre vBiblio<br/><a href="#" class="vBibLink" onclick="javascript:selectAllBooks();">Tous</a> / <a href="#" class="vBibLink" onclick="javascript:unselectAllBooks();">Aucun</a></td>
				<td style="text-align:center;">Dans votre ToRead List<br/><a href="#" class="vBibLink" onclick="javascript:selectAllBooksTRL();">Tous</a> / <a href="#" class="vBibLink" onclick="javascript:unselectAllBooksTRL();">Aucun</a></td>
			</tr>

		<?php foreach($bouquins as $bouquin) : $auteur=$bouquin->retournerAuteur() ?>
			<tr>
				<td></td>
				<td>
					<a href="ficheLivre.php?id=<?=$bouquin->getID()?>" class="vBibLink"><?=$bouquin->titreLong()?></a>
					de <a href="ficheAuteur.php?id=<?=$auteur->getID()?>" class="vBibLink" ><?=$auteur->fullname()?></a>
				</td>
				<td style="text-align:center;"><input type="checkbox" name="booksToAdd[]" value="<?=$bouquin->getID()?>"/></td>
				<td style="text-align:center;"><input type="checkbox" name="booksToAddTRL[]" value="<?=$bouquin->getID()?>"/></td>
			</tr>
		<?php endforeach; ?>
			<tr>
				<td colspan="4"></td>
				<td><input type="submit" value="Ajouter" /></td>
			</tr>
		</table>
	</form>
	<?php else : ?>
		Aucun livre n'a &eacute;t&eacute; trouv&eacute; qui ne soit d&eacute;j&agrave; dans votre biblioth&egrave;que.
	<?php endif; ?>
<?php else : ?>
	L'&eacute;tiquette n'a pas &eacute;t&eacute; trouv&eacute;e.
<?php endif; ?>

	<br/>
	<?php mysql_close(); ?>
	</div>
	<?php include('footer.php'); ?>

</div>
</body>
</html>
