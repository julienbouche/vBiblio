<?php
include('../../accesscontrol.php');
require_once('../../scripts/db/db.php');
include('../../scripts/common.php');
require_once("../../classes/Utilisateur.php");
require_once("../../classes/Livre.php");


checkSecurity();

dbConnect();

$uid = $_SESSION['uid'];
$myTUID = getTableUserId($uid);
$userProfilId = mysql_real_escape_string($_GET['u']);
?>

	
	
<?php
	if(isset($_GET['sort']) && $_GET['sort']=="Title" && isset($_GET['sortOrder']) ){
		$sortOrder=mysql_real_escape_string($_GET['sortOrder']);
		$sql = "SELECT vBiblio_book.titre As titre, numero_cycle, vBiblio_author.nom as nom, vBiblio_author.prenom as prenom, vBiblio_toReadList.id_book as id_book, vBiblio_author.id_author
			FROM vBiblio_author, vBiblio_book, vBiblio_toReadList, vBiblio_user
			WHERE vBiblio_toReadList.id_user = vBiblio_user.tableuserid
			AND vBiblio_user.tableuserid='$userProfilId'
			AND vBiblio_toReadList.id_book = vBiblio_book.id_book
			AND vBiblio_book.id_author=vBiblio_author.id_author
			ORDER BY vBiblio_book.titre $sortOrder, vBiblio_author.nom, id_cycle, numero_cycle ASC"; 
	
	}
	else{
		if(isset($_GET['sort']) && $_GET['sort']=="Author" && isset($_GET['sortOrder']) ){
			$sortOrder=mysql_real_escape_string($_GET['sortOrder']);
			$sql = "SELECT vBiblio_book.titre As titre, numero_cycle, vBiblio_author.nom as nom, vBiblio_author.prenom as prenom, vBiblio_toReadList.id_book as id_book, vBiblio_author.id_author
				FROM vBiblio_author, vBiblio_book, vBiblio_toReadList, vBiblio_user
				WHERE vBiblio_toReadList.id_user = vBiblio_user.tableuserid
				AND vBiblio_user.tableuserid='$userProfilId'
				AND vBiblio_toReadList.id_book = vBiblio_book.id_book
				AND vBiblio_book.id_author=vBiblio_author.id_author
				ORDER BY vBiblio_author.nom $sortOrder,vBiblio_author.prenom $sortOrder, id_cycle, numero_cycle ASC"; 
		}

	}
	
	$result = mysql_query($sql);

	if($result && mysql_num_rows($result)>0 ){
		$cpt=0;
		$returnMsg ="";		
		while($row=mysql_fetch_assoc($result)){
			if($cpt%2==0){
				$style = "vBiblioBookEven infobulle";
			}
			else{
				$style="vBiblioBookOdd infobulle";
			}
			$cpt++;

			$titre = $row['titre'];
			$nom_auteur = $row['nom'];
			$prenom_auteur = $row['prenom'];
			$idbook = $row['id_book'];
			$idAuthor = $row['id_author'];
			$num_in_cycle = $row['numero_cycle'];
			
			$sql_cycle= "SELECT vBiblio_cycle.titre, nb_tomes FROM vBiblio_cycle, vBiblio_book WHERE vBiblio_book.id_cycle=vBiblio_cycle.id_cycle AND vBiblio_book.id_book=$idbook";
			
			$returnMsg = $returnMsg."<tr class=\"$style\">";
			$returnMsg = $returnMsg .  "<td class=\"vBibBookTitle\">";
			$returnMsg = $returnMsg .  "<a href=\"ficheLivre.php?id=$idbook\" class=\"vBibLink\" name=\"bookTitle\">";
			$cycles = mysql_query($sql_cycle);
			if($cycles && mysql_num_rows($cycles) > 0) {
				$cycle = mysql_result($cycles, 0, 'titre');
				$nb_tomes= mysql_result($cycles, 0, 'nb_tomes');
				$returnMsg = $returnMsg . "$cycle, Tome $num_in_cycle : ";
			}

			$returnMsg = $returnMsg .  "$titre</a>";
			$utilisateur = new Utilisateur($uid);
			$bouquin = new Livre($idbook);
			$buddy = new Utilisateur("");
			$buddy->initializeByID($userProfilId);
			
			if(!$utilisateur->aDansUneListe($bouquin)){
			//ajout du menu contextuel (au survol)
			
			$returnMsg = $returnMsg . "<span class=\"menuContextuel\"><img class=\"ImgAction\" onclick=\"addToMyVBiblio($idbook, $myTUID);\" src=\"images/addToList2.png\" title=\"Ajouter &agrave; ma vBiblio\" width=\"20px\" height=\"20px\"/>
				<img class=\"ImgAction\" onclick=\"javascript:addBookToMyTRL(this, ".$bouquin->getID().", ".$utilisateur->getID().");\" src=\"images/AddToTRL2.png\" title=\"Ajouter &agrave; ma ToRead List\" width=\"20px\" height=\"20px\" />
				<img class=\"ImgAction\" onclick=\"\" src=\"images/recherche.png\" title=\"Rechercher qui peut vous pr&ecirc;ter ce livre\" width=\"20px\" height=\"19px\" /></a></span>";


				
			}
			
			$returnMsg = $returnMsg .  "</td>";
			$returnMsg = $returnMsg .  "<td><a href=\"ficheAuteur.php?id=$idAuthor\" class=\"vBibLink\" name=\"authorName\">$prenom_auteur $nom_auteur</a></td>";

			$returnMsg = $returnMsg .  "</tr>";
		}
	}
	echo utf8_encode($returnMsg);
?>

