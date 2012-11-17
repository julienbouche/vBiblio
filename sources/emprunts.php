<?php
include('accesscontrol.php');
include('scripts/db/db.php');

include('classes/Utilisateur.php');

dbConnect();
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

	<br/>
	<b>Les livres qu'on vous a pr&ecirc;t&eacute;s:</b><br/>

<?
	
	$sql = "SELECT vBiblio_user.fullname as fullname, titre, vBiblio_user.tableuserid, vBiblio_pret.id_book as id_book FROM vBiblio_user, vBiblio_pret, vBiblio_book WHERE vBiblio_pret.id_emprunteur='".$utilisateur->getID()."' AND vBiblio_pret.id_preteur=vBiblio_user.tableuserid AND vBiblio_pret.id_book=vBiblio_book.id_book order by fullname";

	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result)>0 ){
		$cpt=0;

		echo "<a href=\"generateTableEmpruntsPDF.php\" target=\"_blank\" style=\"float:right;\" ><img src=\"images/adobe-pdf-logo.png\" width=\"32\" height=\"32\" title=\"T&eacute;l&eacute;charger la liste\"/></a><br/><br/>";

		echo "<div class=\"vBibList\">";
		echo "<ul>";
		while($row=mysql_fetch_assoc($result)){
			$preteur = $row['fullname'];
			$IDPreteur = $row['tableuserid'];
			$bouquin = new Livre($row['id_book']);


			echo "<li>";
			echo "<span class=\"vBibBookTitle\"><a class=\"vBibLink\" href=\"userProfil.php?user=$IDPreteur\"><b>$preteur</b></a> vous a pr&ecirc;t&eacute; <a href=\"ficheLivre.php?id=".$bouquin->getID()."\" class=\"vBibLink\">";
			
			

			echo $bouquin->titreLong()."</a></span>";
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
