<?php
require_once('accesscontrol.php');
require_once('scripts/common.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');

checkSecurity();

$uid = $_SESSION['uid'];
//$myTUID = getTableUserId($uid);
$utilisateur = new Utilisateur($uid);

?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Vos Livres</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
		<script type="text/javascript" src="js/filter.js"></script>
		
		<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
		<script type="text/javascript" src="js/gui/trl_gui.js"></script>
		<script type="text/javascript" src="js/core/user_functions.js"></script>

</head>
<body>
<div id="vBibContenu">
<?php
	include('header.php');
?>

	<div id="vBibDisplay">

<?php

	$sql = "SELECT vBiblio_book.titre As titre, numero_cycle, vBiblio_author.nom as nom, vBiblio_author.prenom as prenom, vBiblio_toReadList.id_book as id_book, vBiblio_author.id_author FROM vBiblio_author, vBiblio_book, vBiblio_toReadList, vBiblio_user WHERE vBiblio_toReadList.id_user = vBiblio_user.tableuserid AND vBiblio_user.tableuserid='".$utilisateur->getID()."' AND vBiblio_toReadList.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author ORDER BY vBiblio_author.nom ASC, id_cycle, numero_cycle ASC"; 
	
	$result = mysql_query($sql);
	$cpt=0;
?>

<?php if ($result && mysql_num_rows($result)>0) : ?>


	<input type="text" name="filtreSaisie" title="Filtrer..." placeholder="Filtrer..." onkeyup="javascript:filter();" style="float:left;"/>
	<br/>
	<table class="vBiblioBooksTable">
	<thead>
		<tr>
			<td onclick="javascript:sortTRLByTitle();" style="width:70%">Titre</td>
			<td style="width:30%" onclick="javascript:sortTRLByAuthor();">Auteur</td>
		</tr>
	</thead>
	<tbody name="vBiblioBookList">

	<?php while ($row=mysql_fetch_assoc($result)) : $bouquin = new Livre($row['id_book']); $auteur = $bouquin->retournerAuteur(); ?>

	<?php
		if($cpt%2==0){
			$style = "vBiblioBookEven";
		}
		else{
			$style="vBiblioBookOdd";
		}
		$cpt++;
	?>
		<tr class="<?=$style?> infobulle">
			<td class="vBibBookTitle">
				<a href="ficheLivre.php?id=<?=$bouquin->getID()?>" class="vBibLink" name="bookTitle"><?=$bouquin->titreLong()?></a>
				<span class="menuContextuel">
					<img class="ImgAction" onclick="addToMyVBiblio(<?=$bouquin->getID()?>, <?=$utilisateur->getID()?>);" src="images/addToList2.png" title="Ajouter &agrave; ma vBiblio" width="20px" height="20px" />
					<img class="ImgAction" onclick="suppBookFromList(<?=$bouquin->getID()?>, <?=$utilisateur->getID()?>);" src="images/supp.png" title="Supprimer" width="20px" height="20px" />
					<a target="_blank" href="emprunts.php?q=<?=$bouquin->titreLong()?>">
						<img class="ImgAction" onclick="" src="images/recherche.png" title="Rechercher qui peut vous pr&ecirc;ter ce livre" width="20px" height="20px" />
					</a>
				</span>
			</td>
			<td>
				<a href="ficheAuteur.php?id=<?=$auteur->getID()?>" class="vBibLink" name="authorName"><?=$auteur->fullname()?></a>
			</td>
		</tr>
	<?php endwhile; ?>
	</tbody>
	</table>

	<?php else : ?>
	Vous n'avez pas encore ajout&eacute; de livres &agrave; votre liste de livres &agrave; lire.
	<?php endif; ?>
</div>
</div>

<?php
	include('footer.php');
?>


</body>
</html>
