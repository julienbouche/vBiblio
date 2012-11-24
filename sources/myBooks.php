<?php
include('accesscontrol.php');
include('scripts/common.php');
//include('scripts/db/db.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');

//dbConnect();
checkSecurity();

$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);
//$myTUID = getTableUserId($uid);

?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Vos Livres</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="js/filter.js"></script>
	
	<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
	<script type="text/javascript" src="js/gui/vbiblio_gui.js"></script>
		
<script language="javascript">
<!--


//-->
</script>

</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">

<?

	$sql = "SELECT vBiblio_book.titre As titre, numero_cycle, vBiblio_author.nom as nom, vBiblio_author.prenom as prenom, vBiblio_poss.lu, vBiblio_poss.possede, vBiblio_poss.pret, vBiblio_poss.id_book as id_book, vBiblio_author.id_author FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user WHERE vBiblio_poss.userid = vBiblio_user.tableuserid AND vBiblio_user.tableuserid='".$utilisateur->getID()."' AND vBiblio_poss.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author ORDER BY vBiblio_author.nom ASC, id_cycle, numero_cycle ASC"; 
	
	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result)>0 ){
		$cpt=0;

		echo "<a href=\"export2csv.php\" target=\"_blank\" style=\"float:right;\" ><img src=\"images/excel-icon.png\" width=\"32\" height=\"32\" title=\"Export au format CSV\"/><a href=\"generateTablePDF.php\" target=\"_blank\" style=\"float:right;\" ><img src=\"images/adobe-pdf-logo.png\" width=\"32\" height=\"32\" title=\"Export au format PDF\"/></a><br/><br/>";
		
		
		echo "<input type=\"text\" name=\"filtreSaisie\" title=\"Filtrer...\" placeholder=\"Filtrer...\" style=\"-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;\" onkeyup=\"javascript:filter();\" style=\"float:left;\"/>";
		$str_nbBooksInLib = $utilisateur->NbBooksInLibrary();
		if($str_nbBooksInLib== "1"){
			echo "Vous poss&eacute;dez actuellement $str_nbBooksInLib seul livre dans votre vBiblio.<br/>";
		}
		else echo "<span style=\"float:right;margin:0px;padding:0;text-align:right;\">Vous poss&eacute;dez actuellement $str_nbBooksInLib livres dans votre vBiblio.</span><br/>";
		echo "<table class=\"vBiblioBooksTable\">";
		echo "<thead>";
		echo "<td onclick=\"javascript:sortByTitle();\" style=\"width:60%\">Titre</td><td style=\"width:20%\" onclick=\"javascript:sortByAuthor();\">Auteur</td><td style=\"width:5%\">Je l'ai</td><td style=\"width:5%\">lu</td><td style=\"width:5%\">pr&ecirc;t&eacute;</td>";
		echo "</thead>";
		echo "<tbody name=\"vBiblioBookList\">";

		while($row=mysql_fetch_assoc($result)){
			if($cpt%2==0){
				//$style="vBibEven";
				$style = "vBiblioBookEven";
			}
			else{
				//$style="vBibOdd";
				$style="vBiblioBookOdd";
			}
			$cpt++;
		
			$bouquin = new Livre($row['id_book']);

			$possede = $row['possede'];
			$lu = $row['lu'];
			$prete = $row['pret'];
			
			
			echo "<tr class=\"$style infobulle\">";
			echo "<td class=\"vBibBookTitle\">";
			echo "<a href=\"ficheLivre.php?id=".$bouquin->getID()."\" class=\"vBibLink\" name=\"bookTitle\">";
			echo $bouquin->titreLong()."</a>";

			//menu INFOBULLE
			?>
			<span class="menuContextuel">
			<?
			echo "<img class=\"ImgAction\" onclick=\"suppBookFromList(".$bouquin->getID().", ".$utilisateur->getID().");\" src=\"images/supp.png\" title=\"Enlever de ma liste de livres\" width=\"20px\" height=\"20px\"/></span>";
			$auteur = $bouquin->retournerAuteur();

			echo "</td>";
			echo "<td><a href=\"ficheAuteur.php?id=".$auteur->getID()."\" class=\"vBibLink\" name=\"authorName\">".$auteur->fullname()."</a></td>";
			echo "<td style=\"text-align:center;\"><input name=\"".$bouquin->getID()."Possede\" type=\"checkbox\" title=\"je l'ai\" onchange=\"javascript:updatePossede('".$utilisateur->getPseudo()."', ".$bouquin->getID()." )\" ";
			if($possede=="1") echo "checked";
			echo " /></td>\n";
			
			echo "<td style=\"text-align:center;\"><input name=\"".$bouquin->getID()."Lu\" type=\"checkbox\" title=\"je l'ai lu\" onchange=\"javascript:updateLu('".$utilisateur->getPseudo()."', ".$bouquin->getID()." )\" ";
			if($lu=="1") echo "checked";
			echo " /></td>\n";
			echo "<td style=\"text-align:center;\"><input name=\"".$bouquin->getID()."Pret\" type=\"checkbox\" title=\"je l'ai d&eacute;j&agrave; pr&ecirc;t&eacute;\" onclick=\"return false\" ";
			if($prete=="1") echo "checked";
			echo " /></td>\n";

			echo "</tr>";
		}
		
		echo "</tbody>";
		echo "</table>";

	}else echo "Vous n'avez pas encore ajout&eacute; de livres &agrave; votre biblioth&egrave;que virtuelle.";
?>

</div>
<?
	include('footer.php');
?>

</div>
</body>
</html>
