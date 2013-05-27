<?php
include('accesscontrol.php');
//include('scripts/db/db.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');

//dbConnect();
checkSecurity();

$uid = $_SESSION['uid'];

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

	
	//récupération de la recherche de la searchBar et/ou des liens de recherche si on a navigué (Utilisateurs/Emprunts)
	if(isset($_POST['searchText']) ){
		$searchText = $_POST['searchText'];
	}else{
		$searchText = $_GET['q'];
	}
?>
	<div id="vBibDisplay">
		<div align="center">
			<div class="MessagerieMenuItem"><a href="addBooks.php?q=<?=$searchText?>" class="vBibLink" ><input class="vert" value="Livres" type="button" /></a></div>
			<div class="MessagerieMenuItem"><a href="emprunts.php?q=<?=$searchText?>" class="vBibLink" ><input value="Emprunts" type="button" /></a></div>
			<div class="MessagerieMenuItem"><a href="addFriends.php?q=<?=$searchText?>&attribut=fullname" class="vBibLink" ><input value="Utilisateurs" type="button" /></a></div>
		</div>

	Rechercher parmi notre r&eacute;f&eacute;rentiel un livre que vous souhaitez ajouter &agrave; votre biblioth&egrave;que :
	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
		<fieldset>
			<input type="text" max-length="100" size="100" name="searchText" value="<? echo str_replace("\\", "" , $searchText);?>"/>
			<input type="submit" value="Rechercher" style="float:right;" />
		</fieldset>
	</form>
	<br/>
<?
	$livresAAjouter = $utilisateur->rechercherLivresAAjouter($searchText);
	/*if(isset($searchText) ){
		$utilisateur->afficherRechercheLivresAAjouter($searchText);	
	}*/
?>

<?php if(count($livresAAjouter)>0) : ?>
			<form name="addingBookList" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
				<table style="font-size:inherit;">
					<tr>
						<td></td>
						<td></td>
						<td style="text-align:center;">Dans votre vBiblio<br/><a href="#" class="vBibLink" onclick="javascript:selectAllBooks();">Tous</a> / <a href="#" class="vBibLink" onclick="javascript:unselectAllBooks();">Aucun</a>
						</td>
						<td style="text-align:center;">Dans votre ToRead List<br/><a href="#" class="vBibLink" onclick="javascript:selectAllBooksTRL();">Tous</a> / <a href="#" class="vBibLink" onclick="javascript:unselectAllBooksTRL();">Aucun</a>
						</td>
					</tr>
			
			<?php foreach($livresAAjouter as $bouquin) : $auteur = $bouquin->retournerAuteur(); ?>
				
					<tr>
						<td></td>
						<td>
							<a href="ficheLivre.php?id=<?=$bouquin->getID()?>" class="vBibLink"><?=$bouquin->titreLong()?></a> de <a href="ficheAuteur.php?id=<?=$auteur->getID()?>" class="vBibLink" ><?=$auteur->fullname()?>
						</td>
						<td style="text-align:center;">
							<input type="checkbox" name="booksToAdd[]" value="<?=$bouquin->getID()?>"/>
						</td>
						<td style="text-align:center;">
							<input type="checkbox" name="booksToAddTRL[]" value="<?=$bouquin->getID()?>"/>
						</td>		
					</tr>
			<?php endforeach; ?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td style="text-align:center;"><input type="submit" value="Ajouter" /></td>
					</tr>
			</table>
		</form>
<?php endif; ?>


	Note: Les livres &eacute;tant d&eacute;j&agrave; pr&eacute;sents dans vos listes ne sont pas affich&eacute;s dans cette recherche.
<?
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
<? mysql_close(); ?>

</div>

<? include('footer.php'); ?>

</div>
</body>
</html>
