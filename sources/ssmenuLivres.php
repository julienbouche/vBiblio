<?php

//normalement la connexion à la bdd a déjà été fait.
$sql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$row = mysql_fetch_assoc($result);
	$mytableId = $row['tableuserid'];
}

//récupérer le nombre de demandes de livres en attente de traitement
$sql = "SELECT COUNT(*) as nb FROM vBiblio_demande WHERE type='BOOK_REQUEST' AND id_user_requested ='$mytableId' ";

$result = mysql_query($sql);
$pendingRequest = "";

if($result && mysql_num_rows($result)>0 ){
	$row = mysql_fetch_assoc($result);
	if($row['nb']=="0") $pendingRequest = "";
	else $pendingRequest = " (".$row['nb'].")";
	
}


?>

<a href="<?=$rootPath?>/myBooks.php" class="vBibLink SubMenuItem">Ma vBiblio</a>
<a href="<?=$rootPath?>/myToReadList.php" class="vBibLink SubMenuItem">Ma ToRead List</a>
<a href="<?=$rootPath?>/addBooks.php" class="vBibLink SubMenuItem">Ajouter des livres</a>

<?
$sql = "SELECT COUNT(*) as nb FROM vBiblio_poss WHERE userid ='$mytableId' AND possede='1' ";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0 ){
?>
<a href="<?=$rootPath?>/prets.php" class="vBibLink SubMenuItem">Mes Pr&ecirc;ts</a>
<?
}

?>
  
<a href="<?=$rootPath?>/emprunts.php" class="vBibLink SubMenuItem">Mes Emprunts</a>
<?php if($pendingRequest != "") : ?>
<a href="<?=$rootPath?>/manageBooksRequest.php" class="vBibLink SubMenuItem">G&eacute;rer les demandes <?=$pendingRequest?></a>
<?php endif; ?>



