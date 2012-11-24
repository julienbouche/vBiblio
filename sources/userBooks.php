<?php
include('accesscontrol.php');
//include('scripts/db/db.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');


//dbConnect();
checkSecurity();



$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

if(isset($_GET['user']) ){
	//$tableID=$_GET['user'];
	$buddy = new Utilisateur("");
	$buddy->initializeByID($_GET['user']);
	
}


?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">


<html>
<head>
	<title>vBiblio - Les livres de <?=$buddy->getFullname()?> </title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
	<script type="text/javascript" src="js/filter2.js"></script>
	<script type="text/javascript" src="js/gui/books_gui.js"></script>
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">
<?
	include('ssMenuPageAmi.php');
?>
	
	<div class="vBibList">
	
<?
	$sql = "SELECT  vBiblio_poss.lu, vBiblio_poss.possede, vBiblio_poss.pret, vBiblio_poss.id_book as id_book FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user WHERE vBiblio_poss.userid = vBiblio_user.tableuserid AND vBiblio_user.tableuserid='".$buddy->getID()."' AND vBiblio_poss.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author"; 
	
	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result)>0 ){
		$cpt=0;
		
		//echo "<ul>";
		echo "<input type=\"text\" name=\"filtreSaisie\" title=\"Rechercher...\" onkeyup=\"javascript:filter();\" />";
		echo "<table class=\"vBiblioBooksTable\">";
		echo "<thead>";
		echo "<td onclick=\"javascript:sortByTitle(".$buddy->getID().");\" style=\"width:60%\">Titre</td><td style=\"width:20%\" onclick=\"javascript:sortByAuthor(".$buddy->getID().");\">Auteur</td><td style=\"width:5%\">".$buddy->getPronom()." l'a</td><td style=\"width:5%\">lu</td><td style=\"width:5%\">pr&ecirc;t&eacute;</td>";
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

			
			$possede = $row['possede'];
			$lu = $row['lu'];
			$prete = $row['pret'];
			$idbook = $row['id_book'];
			
			$bouquin = new Livre($idbook);
			
			echo "<tr class=\"$style infobulle\">";
			echo "<td class=\"vBibBookTitle\">";
			echo "<a href=\"ficheLivre.php?id=$idbook\" class=\"vBibLink\" name=\"bookTitle\">";

			echo $bouquin->titreLong()."</a>";
			
			if(!$utilisateur->aDansUneListe($bouquin)){
			//ajout du menu contextuel (au survol)
			?>
			<span class="menuContextuel">
				<img class="ImgAction" onclick="javascript:addBookToMyVBiblio(this, <?=$bouquin->getID()?>, <?=$utilisateur->getID()?> );" src="images/AddToList.png" title="Ajouter &agrave; ma vBiblio" width="20px" height="20px" />
				<img class="ImgAction" onclick="javascript:addBookToMyTRL(this, <?=$bouquin->getID()?>, <?=$utilisateur->getID()?> );" src="images/AddToTRL.png" title="Ajouter &agrave; ma ToRead List" width="20px" height="20px" />
				<?
				if(!$bouquin->isRequested($utilisateur->getID(),$buddy->getID()) && !$buddy->aPrete($bouquin)){
				?>
				<img class="ImgAction" onclick="javascript:requestFriendBook(this, <?=$utilisateur->getID()?>, <?=$buddy->getID()?>, <?=$bouquin->getID()?> );" src="images/demande_pret.png" title="Demander en pr&ecirc;t &agrave; <?=$buddy->getPrenom()?>" width="20px" height="18px" />
			
			
			<?
				}
				?>
				<a target="_blank" href="emprunts.php?q=<?=$bouquin->titreLong()?>"><img class="ImgAction" onclick="" src="images/recherche.png" title="Rechercher qui peut vous pr&ecirc;ter ce livre" width="20px" height="19px" /></a>
				</span>
				<?
			}
			
			echo "</td>";
			$auteur = $bouquin->retournerAuteur();
			echo "<td><a href=\"ficheAuteur.php?id=".$auteur->getID()."\" class=\"vBibLink\" name=\"authorName\">".$auteur->fullname()."</a></td>";
			echo "<td style=\"text-align:center;\"><input name=\"".$idbook."Possede\" type=\"checkbox\" title=\"".$buddy->getPronom()." l'a\" onclick=\"return false\"  ";
			if($possede=="1") echo "checked";
			echo " /></td>\n";
			
			echo "<td style=\"text-align:center;\"><input name=\"".$idbook."Lu\" type=\"checkbox\" title=\"".$buddy->getPronom()." l'a lu\" onclick=\"return false\"  ";
			if($lu=="1") echo "checked";
			echo " /></td>\n";
			echo "<td style=\"text-align:center;\"><input name=\"".$idbook."Pret\" type=\"checkbox\" title=\"".$buddy->getPronom()." l'a d&eacute;j&agrave; pr&ecirc;t&eacute;\" onclick=\"return false\" ";
			if($prete=="1") echo "checked";
			echo " /></td>\n";
			echo "</tr>";

		}
		echo "</tbody>";
		echo "</table>";


	}else echo "Cet utilisateur n'a pas encore ajout&eacute; de livres &agrave; sa biblioth&egrave;que virtuelle.";
?>

	</div>
</div>

<?
	include('footer.php');
?>
</div>
</body>
</html>
