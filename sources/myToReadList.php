<?php
include('scripts/common.php');
include('accesscontrol.php');
//include('scripts/db/db.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');

//dbConnect();
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
<?
	include('header.php');
?>

	<div id="vBibDisplay">

<?

	$sql = "SELECT vBiblio_book.titre As titre, numero_cycle, vBiblio_author.nom as nom, vBiblio_author.prenom as prenom, vBiblio_toReadList.id_book as id_book, vBiblio_author.id_author FROM vBiblio_author, vBiblio_book, vBiblio_toReadList, vBiblio_user WHERE vBiblio_toReadList.id_user = vBiblio_user.tableuserid AND vBiblio_user.tableuserid='".$utilisateur->getID()."' AND vBiblio_toReadList.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author ORDER BY vBiblio_author.nom ASC, id_cycle, numero_cycle ASC"; 
	
	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result)>0 ){
		$cpt=0;		
		echo "<input type=\"text\" name=\"filtreSaisie\" title=\"Filtrer...\" placeholder=\"Filtrer...\" style=\"-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;margin-left:10px;\" onkeyup=\"javascript:filter();\" style=\"float:left;\"/><br/>";

		echo "<table class=\"vBiblioBooksTable\">";
		echo "<thead>";
		echo "<td onclick=\"javascript:sortTRLByTitle();\" style=\"width:75%\">Titre</td><td style=\"width:20%\" onclick=\"javascript:sortTRLByAuthor();\">Auteur</td>";
		

		echo "</thead>";
		echo "<tbody name=\"vBiblioBookList\">";

		while($row=mysql_fetch_assoc($result)){
			if($cpt%2==0){
				$style = "vBiblioBookEven";
			}
			else{
				$style="vBiblioBookOdd";
			}
			$cpt++;
			
			$bouquin = new Livre($row['id_book']);
			
			echo "<tr class=\"$style infobulle\">";
			echo "<td class=\"vBibBookTitle\">";

			echo "<a href=\"ficheLivre.php?id=".$bouquin->getID()."\" class=\"vBibLink\" name=\"bookTitle\">";
			echo $bouquin->titreLong()."</a>";
			
			
			//menu contextuel
			?>
			<span class="menuContextuel">
			<img class="ImgAction" onclick="addToMyVBiblio(<?=$bouquin->getID()?>, <?=$utilisateur->getID()?>);" src="images/AddToList.png" title="Ajouter &agrave; ma vBiblio" width="20px" height="20px" />&nbsp;<img class="ImgAction" onclick="suppBookFromList(<?=$bouquin->getID()?>, <?=$utilisateur->getID()?>);" src="images/supp.png" title="Supprimer" width="20px" height="20px"/>
			<a target="_blank" href="emprunts.php?q=<?=$bouquin->titreLong()?>"><img class="ImgAction" onclick="" src="images/recherche.png" title="Rechercher qui peut vous pr&ecirc;ter ce livre" width="20px" height="19px" /></a>
			</span>
			<?
			
			echo "</td>";
			$auteur = $bouquin->retournerAuteur();
			
			echo "<td><a href=\"ficheAuteur.php?id=".$auteur->getID()."\" class=\"vBibLink\" name=\"authorName\">".$auteur->fullname()."</a>";

			echo "</td>";
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";

	}else echo "Vous n'avez pas encore ajout&eacute; de livres &agrave; votre liste de livres &agrave; lire.";
?>

</div>
<?
	include('footer.php');
?>

</div>
</body>
</html>
