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
	$tableID=$_GET['user'];
	$buddy = new Utilisateur("");
	$buddy->initializeByID($tableID);

}


?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Les livres que <?=$buddy->getFullname()?> souhaite lire</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
		<script type="text/javascript" src="js/filter2.js"></script>
<script language="javascript">
<!--
function createXHR(){
	var xhr;
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}

	//ie
	else if (window.ActiveXObject) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	} 
	return xhr;
}


function addToMyVBiblio(idbook, uid){
	var xhr = createXHR();

	if(xhr!=null) {
		xhr.open("GET","scripts/db/addToMyVBiblio.php?idbook="+idbook+"&id_user="+uid, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				//document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
				//penser à recharger la liste pour voir le changement immédiatement
				window.location.reload();
			}
		};
		xhr.send(null);
	}
}

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
	include('ssMenuPageAmi.php');
?>

<?

	$sql = "SELECT vBiblio_toReadList.id_book as id_book FROM vBiblio_author, vBiblio_book, vBiblio_toReadList, vBiblio_user WHERE vBiblio_toReadList.id_user = vBiblio_user.tableuserid AND vBiblio_user.tableuserid='".$buddy->getID()."' AND vBiblio_toReadList.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author ORDER BY vBiblio_author.nom ASC, id_cycle, numero_cycle ASC";
	
	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result)>0 ){
		$cpt=0;		
		echo "<input type=\"text\" name=\"filtreSaisie\" title=\"Filtrer...\" onkeyup=\"javascript:filter();\" style=\"float:left;\"/>";

		echo "<table class=\"vBiblioBooksTable\">";
		echo "<thead>";
		echo "<td onclick=\"javascript:sortTRLByTitle(".$buddy->getID().");\" style=\"width:75%\">Titre</td><td style=\"width:20%\" onclick=\"javascript:sortTRLByAuthor(".$buddy->getID().");\">Auteur</td>";
		
		//echo "<td style=\"width:5%\"></td>"; // bouton suppression

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
			
			$idbook = $row['id_book'];
			$bouquin = new Livre($idbook);
			
			
			echo "<tr class=\"$style infobulle\">";
			echo "<td class=\"vBibBookTitle\">";

			echo "<a href=\"ficheLivre.php?id=".$bouquin->getID()."\" class=\"vBibLink\" name=\"bookTitle\">";
			

			echo $bouquin->titreLong()."</a>";
			
			if(!$utilisateur->aDansUneListe($bouquin)){
			//ajout du menu contextuel (au survol)
			?>
			<span class="menuContextuel">
				<img class="ImgAction" onclick="javascript:addBookToMyVBiblio(this, <?=$bouquin->getID()?>, <?=$utilisateur->getID()?> );" src="images/AddToList.png" title="Ajouter &agrave; ma vBiblio" width="20px" height="20px" />
				<img class="ImgAction" onclick="javascript:addBookToMyTRL(this, <?=$bouquin->getID()?>, <?=$utilisateur->getID()?> );" src="images/AddToTRL.png" title="Ajouter &agrave; ma ToRead List" width="20px" height="20px" />
				
				<a target="_blank" href="emprunts.php?q=<?=$bouquin->titreLong()?>"><img class="ImgAction" onclick="" src="images/recherche.png" title="Rechercher qui peut vous pr&ecirc;ter ce livre" width="20px" height="19px" /></a>
			</span>
			
			<?
				
			}
			
			$auteur = $bouquin->retournerAuteur();
			echo "</td>";
			echo "<td><a href=\"ficheAuteur.php?id=".$auteur->getID()."\" class=\"vBibLink\" name=\"authorName\">".$auteur->fullname()."</a>";

			echo "</td>";
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";

	}else echo $buddy->getPronom()." n'a aucun livre dans sa liste de livres &agrave; lire.";
?>

</div>
<?
	include('footer.php');
?>

</div>
</body>
</html>
