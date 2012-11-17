<?php

//normalement la connexion à la bdd a déjà été fait.
$sql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$row = mysql_fetch_assoc($result);
	$mytableId = $row['tableuserid'];
}

//récupérer le nombre de demandes d'amis en attente de traitement
$sql = "SELECT COUNT(*) as nb FROM vBiblio_demande WHERE type like '%FRIENDS_REQUEST%' AND id_user_requested ='$mytableId' ";

$result = mysql_query($sql);
$pendingRequest = "";

if($result && mysql_num_rows($result)>0 ){
	$row = mysql_fetch_assoc($result);
	if($row['nb']=="0"){
		$affBuddyRequestMenu = false;
		$pendingRequest = "";
	}
	else {
		$affBuddyRequestMenu = true;
		$pendingRequest = " (".$row['nb'].")";
	}
	
}

?>



<a href="<?=$rootPath?>/friends.php" class="vBibLink SubMenuItem">Consulter</a>
<a href="<?=$rootPath?>/addFriends.php" class="vBibLink SubMenuItem">Rechercher</a>

<?
	if ($affBuddyRequestMenu){
?>
	<a href="<?=$rootPath?>/friendsRequest.php" class="vBibLink SubMenuItem">Demandes d'ajout<?=$pendingRequest?></a>
<?
	}
?>
