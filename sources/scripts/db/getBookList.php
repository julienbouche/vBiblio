<?php
include('../../accesscontrol.php');
require_once('../../scripts/db/db.php');
include('../../scripts/common.php');


checkSecurity();

dbConnect();

$uid = $_SESSION['uid'];
$myTUID = getTableUserId($uid);
$userProfilId = mysql_real_escape_string($_GET['u']);
?>

	
	
<?php
	if(isset($_GET['sort']) && $_GET['sort']=="Title" && isset($_GET['sortOrder']) ){
		$sortOrder = mysql_real_escape_string($_GET['sortOrder']);
		$sql = "SELECT vBiblio_book.titre As titre, numero_cycle, vBiblio_author.nom as nom, vBiblio_author.prenom as prenom, vBiblio_poss.lu, vBiblio_poss.possede, vBiblio_poss.pret, vBiblio_poss.id_book as id_book, vBiblio_author.id_author
			FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user
			WHERE vBiblio_poss.userid = vBiblio_user.tableuserid
			AND vBiblio_user.userid='$uid'
			AND vBiblio_poss.id_book = vBiblio_book.id_book
			AND vBiblio_book.id_author=vBiblio_author.id_author
			ORDER BY vBiblio_book.titre $sortOrder , vBiblio_author.nom, id_cycle, numero_cycle ASC"; 
	
	}
	else{
		if(isset($_GET['sort']) && $_GET['sort']=="Author" && isset($_GET['sortOrder']) ){
			$sortOrder = mysql_real_escape_string($_GET['sortOrder']);
			$sql = "SELECT vBiblio_book.titre As titre, numero_cycle, vBiblio_author.nom as nom, vBiblio_author.prenom as prenom, vBiblio_poss.lu, vBiblio_poss.possede, vBiblio_poss.pret, vBiblio_poss.id_book as id_book, vBiblio_author.id_author
				FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user
				WHERE vBiblio_poss.userid = vBiblio_user.tableuserid
				AND vBiblio_user.userid='$uid'
				AND vBiblio_poss.id_book = vBiblio_book.id_book
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
			$possede = $row['possede'];
			$lu = $row['lu'];
			$prete = $row['pret'];
			$idbook = $row['id_book'];
			$idAuthor = $row['id_author'];
			$num_in_cycle = $row['numero_cycle'];
			
			$sql_cycle= "SELECT vBiblio_cycle.titre, nb_tomes FROM vBiblio_cycle, vBiblio_book WHERE vBiblio_book.id_cycle=vBiblio_cycle.id_cycle AND vBiblio_book.id_book=$idbook";
			
			$returnMsg = $returnMsg."<tr class=\"$style\">";
			//echo $returnMsg;
			$returnMsg = $returnMsg .  "<td class=\"vBibBookTitle\">";
			$returnMsg = $returnMsg .  "<a href=\"ficheLivre.php?id=$idbook\" class=\"vBibLink\" name=\"bookTitle\">";
			$cycles = mysql_query($sql_cycle);
			if($cycles && mysql_num_rows($cycles) > 0) {
				$cycle = mysql_result($cycles, 0, 'titre');
				$nb_tomes= mysql_result($cycles, 0, 'nb_tomes');
				$returnMsg = $returnMsg . "$cycle, Tome $num_in_cycle : ";
			}

			$returnMsg = $returnMsg .  "$titre</a>";
			$returnMsg = $returnMsg . "<span class=\"menuContextuel\">";
			$returnMsg = $returnMsg . "<a target=\"_blank\" href=\"marquer_emprunt.php?q=$idbook\">";
			$returnMsg = $returnMsg . "<img class=\"ImgAction\" src=\"images/bookmark.png\" title=\"Marquer ce livre comme emprunt...\" width=\"20px\" height=\"20px\" />";
			$returnMsg = $returnMsg . "</a>&nbsp;";
			$returnMsg = $returnMsg . "<img class=\"ImgAction\" onclick=\"suppBookFromList($idbook, $myTUID);\" src=\"images/supp.png\" title=\"Enlever de ma liste de livres\" width=\"20px\" height=\"20px\" />";
			$returnMsg = $returnMsg . "</span>";
			
			$returnMsg = $returnMsg .  "</td>";
			$returnMsg = $returnMsg .  "<td><a href=\"ficheAuteur.php?id=$idAuthor\" class=\"vBibLink\" name=\"authorName\">$prenom_auteur $nom_auteur</a></td>";
			$returnMsg = $returnMsg .  "<td style=\"text-align:center;\"><input name=\"".$idbook."Possede\" type=\"checkbox\" title=\"je l'ai\" onchange=\"javascript:updatePossede('$uid', $idbook )\" ";
			if($possede=="1") $returnMsg = $returnMsg .  "checked";
			$returnMsg = $returnMsg .  " /></td>\n";
			
			$returnMsg = $returnMsg .  "<td style=\"text-align:center;\"><input name=\"".$idbook."Lu\" type=\"checkbox\" title=\"je l'ai lu\" onchange=\"javascript:updateLu('$uid', $idbook)\" ";
			if($lu=="1") $returnMsg = $returnMsg .  "checked";
			$returnMsg = $returnMsg .  " /></td>\n";
			$returnMsg = $returnMsg . "<td style=\"text-align:center;\"><input name=\"".$idbook."Pret\" type=\"checkbox\" title=\"je l'ai d&eacute;j&agrave; pr&ecirc;t&eacute;\" onclick=\"return false\" ";
			if($prete=="1") $returnMsg = $returnMsg .  "checked";
			$returnMsg = $returnMsg .  " /></td>\n";

			$returnMsg = $returnMsg .  "</tr>";
			//echo $returnMsg;
		}
	}
	echo utf8_encode($returnMsg);
?>

