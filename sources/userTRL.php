<?php
require_once('accesscontrol.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');

checkSecurity();

$uid = $_SESSION['uid'];

if(isset($_GET['user']) ){
	$tableID=$_GET['user'];
	$buddy = new Utilisateur("");
	$buddy->initializeByID($tableID);

	//verifier que les utilisateurs sont amis ou que le profil consulté a demandé la personne en ami sinon redirection ou message d'erreur !!!
  	$verifSecuSQL = "SELECT id_user1 FROM vBiblio_user, vBiblio_amis WHERE userid='$uid' AND id_user2=tableuserid AND id_user1=".$buddy->getID(); 
  	$resultSecu = mysql_query($verifSecuSQL);
  	if(mysql_num_rows($resultSecu)>0){
    		$SecuriteOK = true;
  	}
  	else{
		$SecuriteOK = false;
	}
}


?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Les livres que <?=$buddy->getFullname()?> souhaite lire</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
		<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
		<script type="text/javascript" src="js/filter2.js"></script>
		<script type="text/javascript" src="js/gui/books_gui.js"></script>
</head>
<body>
<div id="vBibContenu">
	<?php include('header.php'); ?>
	<div id="vBibDisplay">
	<?php if($SecuriteOK) : include('ssMenuPageAmi.php'); ?>

<?php
	$sql = "SELECT vBiblio_toReadList.id_book as id_book FROM vBiblio_author, vBiblio_book, vBiblio_toReadList, vBiblio_user WHERE vBiblio_toReadList.id_user = vBiblio_user.tableuserid AND vBiblio_user.tableuserid='".$buddy->getID()."' AND vBiblio_toReadList.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author ORDER BY vBiblio_author.nom ASC, id_cycle, numero_cycle ASC";
	
	$result = mysql_query($sql);
?>

<?php if($result && mysql_num_rows($result)>0 ) : $cpt=0 ?>
	<input type="text" name="filtreSaisie" title="Filtrer..." onkeyup="javascript:filter();" style="moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;" placeholder="Filtrer..."/>
	<table class="vBiblioBooksTable">
	<thead>
		<tr>
			<td onclick="javascript:sortTRLByTitle(<?=$buddy->getID()?>);" style="width:75%">Titre</td><td style="width:20%" onclick="javascript:sortTRLByAuthor(<?=$buddy->getID()?>);">Auteur</td>
		</tr>
	</thead>
	<tbody name="vBiblioBookList">

	<?php while($row=mysql_fetch_assoc($result)) : $style=($cpt%2==0)?"vBiblioBookEven":"vBiblioBookOdd";$cpt++;$bouquin= new Livre($row['id_book']) ?>
		<tr class="<?=$style?> infobulle">
			<td class="vBibBookTitle">
				<a href="ficheLivre.php?id=<?=$bouquin->getID()?>" class="vBibLink" name="bookTitle"><?=$bouquin->titreLong()?></a>
				<?php if(!$utilisateur->aDansUneListe($bouquin)) : ?>
				<span class="menuContextuel">
					<img class="ImgAction" onclick="javascript:addBookToMyVBiblio(this, <?=$bouquin->getID()?>, <?=$utilisateur->getID()?> );" src="images/addToList2.png" title="Ajouter &agrave; ma vBiblio" width="20px" height="20px" />
					<img class="ImgAction" onclick="javascript:addBookToMyTRL(this, <?=$bouquin->getID()?>, <?=$utilisateur->getID()?> );" src="images/AddToTRL2.png" title="Ajouter &agrave; ma ToRead List" width="20px" height="20px" />
					<a target="_blank" href="emprunts.php?q=<?=$bouquin->titreLong()?>"><img class="ImgAction" onclick="" src="images/recherche.png" title="Rechercher qui peut vous pr&ecirc;ter ce livre" width="20px" height="20px" /></a>
				</span>
				<?php endif; ?>
			</td>
			<td>
				<a href="ficheAuteur.php?id=<?=$bouquin->retournerAuteur()->getID()?>" class="vBibLink" name="authorName"><?=$bouquin->retournerAuteur()->fullname()?></a>
			</td>
		</tr>
	<?php endwhile; ?>
	</tbody>
	</table>

	<?php else : ?>
		<?=$buddy->getPronom()?> n'a aucun livre dans sa liste de livres &agrave; lire.
	<?php endif; ?>

	<?php else : ?>
		Vous n'&ecirc;tes pas en contact avec cette personne. Ces informations ne sont pas accessibles.
	<?php endif; ?>	

	</div>
<?php include('footer.php'); ?>
</div>
</body>
</html>
