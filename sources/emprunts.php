<?php
include('accesscontrol.php');
//include('scripts/db/db.php');

include('classes/Utilisateur.php');

//dbConnect();
checkSecurity();

$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Les livres que vous avez emprunt&eacute;s</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	
  <script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
  <script type="text/javascript" src="js/gui/emprunts_gui.js"></script>

</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">

		<div align="center">
			<div class="MessagerieMenuItem"><a href="addBooks.php?q=<?=$_GET['q']?>" class="vBibLink" ><input value="Livres" type="button" /></a></div>
			<div class="MessagerieMenuItem"><a href="emprunts.php?q=<?=$_GET['q']?>" class="vBibLink" ><input class="vert" value="Emprunts" type="button" /></a></div>
			<div class="MessagerieMenuItem"><a href="addFriends.php?q=<?=$_GET['q']?>&attr=fullname" class="vBibLink" ><input value="Utilisateurs" type="button" /></a></div>
		</div>

	<div class="BookmarkN1">
		<div class="BMCorner"></div>
		<div class="BMCornerLink"></div>
		<div class="BMMessage">Rechercher un livre disponible dans les biblioth&egrave;ques de vos amis</div>
	</div>
	<br/><br/><br/><br/><br/>


<?
	if(isset($_POST['searchText']) ){
		$searchText = $_POST['searchText'];
	}
	else if(isset($_GET['q'])) $searchText = $_GET['q'];
?>

	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
		<fieldset>
			<input type="text" max-length="100" size="100" name="searchText" value="<? echo str_replace("\\", "", $searchText);?>"/> <input type="submit" value="Rechercher" style="right:0px"/>
		</fieldset>
	</form>
	<br/>

<?

	if(isset($searchText) ){
		
		$searchTerms = str_replace (" ", ",", $searchText );
		
		$utilisateur->afficherRechercheEmprunts($searchTerms);

	}
?>


	<div class="BookmarkN1">
		<div class="BMCorner"></div>
		<div class="BMCornerLink"></div>
		<div class="BMMessage">Marquer l'un de vos livres comme un emprunt &agrave; l'un de vos amis</div>
	</div>
	<br/><br/><br/><br/><br/>


	<!-- **************************************FORMULAIRE SECONDAIRE****************************************-->
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<fieldset>
	<table>
	<tr>
	<td>Le livre</td>
	<td><select name="id_book">
<?

	$bouquins = $utilisateur->retournerListeLivresDispos();
	
	foreach($bouquins as $bouquin){
		$concatStr =$bouquin->TitreLongAsShortNames()." de ".$bouquin->retournerAuteur()->getShortName();
		echo "<option value=\"".$bouquin->getID()."\" >$concatStr</option>";
	}


?>
	
	</select></td>
	</tr>
		<tr>
		<td>Nom:</td>
		<td style="text-align:left;"><input name="vUsername" type="text" size=25 ></td>

	</tr>
	<tr>
		<td></td>
		<td style="text-align:right;">
		<input type="submit" value="Confirmer" />
		</td>
	</tr>
</table>
	</fieldset>
	</form>		<!-- **************************************FIN FORMULAIRE SECONDAIRE****************************************-->
<?
	//Si l'utilisateur veut ajouter un emprunt d'une persone externe
	if(isset($_POST['vUsername']) and isset($_POST['id_book'])){
		$nomEmprunteur = $_POST['vUsername'];
		$id_book_Emp = $_POST['id_book'];
		$sysdate = date('Y-m-d H:i:s');
		
		$sql = "INSERT INTO vBiblio_pret (id_preteur, id_emprunteur, nom_emprunteur, id_book, date_pret) VALUES ('0', '".$utilisateur->getID()."', '$nomEmprunteur', '$id_book_Emp', '$sysdate') ";

		mysql_query($sql) or die("Erreur : ".$sql);
	}
?>
	<br/>
	<div class="BookmarkN1">
		<div class="BMCorner"></div>
		<div class="BMCornerLink"></div>
		<div class="BMMessage">Les livres qu'on vous a pr&ecirc;t&eacute;s</div>
	</div>
	<br/><br/><br/><br/><br/>


<?
	
	$sql = "SELECT vBiblio_pret.nom_emprunteur as fullname, titre, vBiblio_pret.id_preteur, vBiblio_pret.id_book as id_book FROM vBiblio_pret, vBiblio_book WHERE vBiblio_pret.id_emprunteur='".$utilisateur->getID()."' AND vBiblio_pret.id_book=vBiblio_book.id_book order by date_pret ASC";

	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result)>0 ){
		$cpt=0;

		echo "<a href=\"generateTableEmpruntsPDF.php\" target=\"_blank\" style=\"float:right;\" ><img src=\"images/adobe-pdf-logo.png\" width=\"32\" height=\"32\" title=\"T&eacute;l&eacute;charger la liste\"/></a><br/><br/>";

		echo "<div class=\"vBibList\">";
		echo "<ul>";
		while($row=mysql_fetch_assoc($result)){
			$preteur = $row['fullname'];
			$IDPreteur = $row['id_preteur'];
			if($IDPreteur != "0"){ //alors le user est dans le système
				$preteurUser = new Utilisateur("");
				$preteurUser->initializeByID($IDPreteur);
				$preteur = $preteurUser->getFullname();
			}
			$bouquin = new Livre($row['id_book']);


			echo "<li>";
			echo "<span class=\"vBibBookTitle\">";

			if($IDPreteur !="0")echo "<a class=\"vBibLink\" href=\"userProfil.php?user=$IDPreteur\"><b>$preteur</b></a>";
			else echo "<b>$preteur</b>";
			echo " vous a pr&ecirc;t&eacute; <a href=\"ficheLivre.php?id=".$bouquin->getID()."\" class=\"vBibLink\">";
			
			

			echo $bouquin->titreLong()."</a></span>";
			if($IDPreteur =="0"){//l'utilisateur est externe au systeme, il faut proposer le moyen de supprimer l'emprunt
				echo "<input type=\"button\" class=\"alert\" value=\"X\" onclick=\"javascript:retourEmpruntExterne(this,".$utilisateur->getID().", '$preteur', ".$bouquin->getID().");\"/>";
			}
			echo "</li>";
		}
		echo "</ul>";
		echo "</div>";

	}
	else{
		?>
		Vous n'avez emprunt&eacute; aucun livre, en ce moment.
		<?
	}

?>

	
</div>
<?
	include('footer.php');
?>

</div>

</body>
</html>
