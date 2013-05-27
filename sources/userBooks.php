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
	<title>vBiblio - Les livres de <?=$buddy->getFullname()?> </title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
	<script type="text/javascript" src="js/filter2.js"></script>
	<script type="text/javascript" src="js/gui/books_gui.js"></script>
</head>
<body>
<div id="vBibContenu">
<? include('header.php'); ?>

	<div id="vBibDisplay">
	<?php if($SecuriteOK) : include('ssMenuPageAmi.php'); ?>
	<div class="vBibList">
	
<?
	$sql = "SELECT  vBiblio_poss.lu, vBiblio_poss.possede, vBiblio_poss.pret, vBiblio_poss.id_book as id_book FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user WHERE vBiblio_poss.userid = vBiblio_user.tableuserid AND vBiblio_user.tableuserid='".$buddy->getID()."' AND vBiblio_poss.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author"; 
	
	$result = mysql_query($sql);
?>
	<?php if ($result && mysql_num_rows($result)>0 ) : $cpt=0; ?>
		<input type="text" name="filtreSaisie" title="Rechercher..." onkeyup="javascript:filter();" style="moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;" placeholder="Filtrer..."/>

		<table class="vBiblioBooksTable">
		<thead>
			<tr>
				<td onclick="javascript:sortByTitle(<?=$buddy->getID()?>);" style="width:60%">Titre</td>
				<td style="width:20%" onclick="javascript:sortByAuthor(<?=$buddy->getID()?>);">Auteur</td>
				<td style="width:5%"><?=$buddy->getPronom()?> l'a</td>
				<td style="width:5%">lu</td><td style="width:5%">pr&ecirc;t&eacute;</td>
			</tr>
		</thead>
		<tbody name="vBiblioBookList">
		<?php while($row=mysql_fetch_assoc($result)) : $style=($cpt%2==0)?"vBiblioBookEven":"vBiblioBookOdd"; $cpt++; $bouquin = new Livre($row['id_book']); $auteur=$bouquin->retournerAuteur(); ?>
			<tr class="<?=$style?> infobulle">
				<td class="vBibBookTitle">
					<a href="ficheLivre.php?id=<?=$bouquin->getID()?>" class="vBibLink" name="bookTitle"><?=$bouquin->titreLong()?></a>
					<?php if(!$utilisateur->aDansUneListe($bouquin)) : ?>
					<span class="menuContextuel">
						<img class="ImgAction" onclick="javascript:addBookToMyVBiblio(this, <?=$bouquin->getID()?>, <?=$utilisateur->getID()?> );" src="images/addToList2.png" title="Ajouter &agrave; ma vBiblio" width="20px" height="20px" style="border:1px solid gray;padding:2px;" />
						<img class="ImgAction" onclick="javascript:addBookToMyTRL(this, <?=$bouquin->getID()?>, <?=$utilisateur->getID()?> );" src="images/AddToTRL2.png" title="Ajouter &agrave; ma ToRead List" width="20px" height="20px" style="border:1px solid gray;padding:2px;"/>
						<?php if(!$bouquin->isRequested($utilisateur->getID(),$buddy->getID()) && !$buddy->aPrete($bouquin)) : ?>
						<img class="ImgAction" onclick="javascript:requestFriendBook(this, <?=$utilisateur->getID()?>, <?=$buddy->getID()?>, <?=$bouquin->getID()?> );" src="images/demande_pret.png" title="Demander en pr&ecirc;t &agrave; <?=$buddy->getPrenom()?>" width="20px" height="20px" style="border:1px solid gray;padding:2px;"/>
						<?php endif; ?>
						<a target="_blank" href="emprunts.php?q=<?=$bouquin->titreLong()?>"><img class="ImgAction" onclick="" src="images/recherche.png" title="Rechercher qui peut vous pr&ecirc;ter ce livre" width="20px" height="20px" style="border:1px solid gray;padding:2px;"/></a>
					</span>
					<?php endif; ?>
				</td>
				<td>
					<a href="ficheAuteur.php?id=<?=$auteur->getID()?>" class="vBibLink" name="authorName"><?=$auteur->fullname()?></a>
				</td>
				<td style="text-align:center;">
					<input name="<?=$bouquin->getID()?>Possede" type="checkbox" title="<?=$buddy->getPronom()?> l'a" onclick="return false" <?=$row['possede']==1?"checked":""?> />
				</td>
				<td style="text-align:center;">
					<input name="<?=$bouquin->getID()?>Lu" type="checkbox" title="<?=$buddy->getPronom()?> l'a lu" onclick="return false" <?=$row['lu']==1?"checked":""?> />
				</td>
				<td style="text-align:center;">
					<input name="<?=$bouquin->getID()?>Pret" type="checkbox" title="<?=$buddy->getPronom()?> l'a d&eacute;j&agrave; pr&ecirc;t&eacute;" onclick="return false" <?= $row['pret']==1?"checked":""?> />
			</tr>

		<?php endwhile; ?>
		</tbody>
		</table>
	<?php else : ?>
		Cet utilisateur n'a pas encore ajout&eacute; de livres &agrave; sa biblioth&egrave;que virtuelle.
	<?php endif; ?>

	<?php else : ?>
		Vous n'&ecirc;tes pas en contact avec cette personne. Ces informations ne sont pas accessibles.
	<?php endif; ?>	
	</div>
</div>
<? include('footer.php'); ?>
</div>
</body>
</html>
