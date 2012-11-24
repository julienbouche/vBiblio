<?php
include('accesscontrol.php');
//include('scripts/db/db.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');

//dbConnect();
checkSecurity();


$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

/*$sql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$row = mysql_fetch_assoc($result);
	$mytableId = $row['tableuserid'];
}*/

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
<?
	include('header.php');
?>

	<div id="vBibDisplay">
<?

	if(isset($_GET['idtag']) ){
		$id_tag = $_GET['idtag'];
		$sql = "SELECT value FROM vBiblio_tag WHERE id_tag=$id_tag";
		$resT = mysql_query($sql);
		if($resT && mysql_num_rows($resT) > 0){
			$row = mysql_fetch_assoc($resT);
			$TagDispl = $row['value'];
		
?>

	Rechercher parmi notre r&eacute;f&eacute;rentiel un livre correspondant au tag "<?=$TagDispl?>" :
<?
		}
	}

	if(isset($_GET['idtag']) && isset($_GET['idtag']) ){
		$sql ="SELECT DISTINCT vBiblio_book.id_book as id_book
			FROM vBiblio_book, vBiblio_author, vBiblio_tag_book
			WHERE vBiblio_book.id_author = vBiblio_author.id_author
			AND vBiblio_book.id_book = vBiblio_tag_book.id_book
			AND id_tag=".$id_tag."
			AND vBiblio_book.id_book NOT IN (SELECT vBiblio_poss.id_book FROM vBiblio_poss WHERE vBiblio_poss.userid=".$utilisateur->getID()." )  
			AND vBiblio_book.id_book NOT IN (SELECT vBiblio_toReadList.id_book FROM vBiblio_toReadList WHERE vBiblio_toReadList.id_user=".$utilisateur->getID().")";

		$result = mysql_query($sql);

		if($result && mysql_num_rows($result) > 0){
			?>
			<br>
			<br>
			<?
			echo "<form name=\"addingBookList\" method=\"POST\" action=\"".$_SERVER['PHP_SELF']."\">";
			echo "<table style=\"font-size:inherit;\">";
			echo "<tr>";
			echo "<td></td><td></td><td style=\"text-align:center;\">Dans votre vBiblio<br/><a href=\"#\" class=\"vBibLink\" onclick=\"javascript:selectAllBooks();\">Tous</a> / <a href=\"#\" class=\"vBibLink\" onclick=\"javascript:unselectAllBooks();\">Aucun</a></td><td style=\"text-align:center;\">Dans votre ToRead List<br/><a href=\"#\" class=\"vBibLink\" onclick=\"javascript:selectAllBooksTRL();\">Tous</a> / <a href=\"#\" class=\"vBibLink\" onclick=\"javascript:unselectAllBooksTRL();\">Aucun</a></td>";
			echo "</tr>";

			while($row = mysql_fetch_assoc($result)){
				//Rechercher si le titre fait partie d'une série, d'un cycle
				
				$id_book = $row['id_book'];
				$bouquin = new Livre($id_book);

				$chaine = "<a href=\"ficheLivre.php?id=$id_book\" class=\"vBibLink\">".$bouquin->titreLong()." </a>";
				$auteur = $bouquin->retournerAuteur();
				$chaine .= "de <a href=\"ficheAuteur.php?id=".$auteur->getID()."\" class=\"vBibLink\" >".$auteur->fullname();
				
				echo "<tr>";
				echo "<td></td><td>$chaine</td><td style=\"text-align:center;\"><input type=\"checkbox\" name=\"booksToAdd[]\" value=\"$id_book\"/></td><td style=\"text-align:center;\"><input type=\"checkbox\" name=\"booksToAddTRL[]\" value=\"$id_book\"/></td>";
				echo "</tr>";
			}
			echo "<td></td><td></td><td></td><td></td><td style=\"text-align:center;\"><input type=\"submit\" value=\"Ajouter\" /></td>";
			echo "</table>";
			echo "</form>";
		}else echo "<br/>Aucun livre n'a &eacute;t&eacute; trouv&eacute; qui ne soit d&eacute;j&agrave; dans votre biblioth&egrave;que."; 
	}

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
