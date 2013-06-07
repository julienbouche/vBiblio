<?php
include('db.php');



if(isset($_GET['user1']) && isset($_GET['user2']) && isset($_GET['idRequest']) ){
	dbConnect();

	//mettre à jour la table.
	$user1 = intval($_GET['user1']);
	$user2 = intval($_GET['user2']);
	$idRequest = intval($_GET['idRequest']);
	
	$sql = "INSERT INTO vBiblio_amis (id_user1, id_user2) VALUES ( '$user1', '$user2') ";

	mysql_query($sql);

	$sql = "INSERT INTO vBiblio_amis (id_user1, id_user2) VALUES ( '$user2', '$user1') ";

	mysql_query($sql);

	$sql = "DELETE FROM vBiblio_demande WHERE vBiblio_demande.id_demande = $idRequest";
	mysql_query($sql);


	//echo "user1 = $user1 &amp; user2 = $user2";
	echo "L'utilisateur a &eacute;t&eacute; ajout&eacute;(e) &agrave; vos amis.";
}
else echo "ERROR";


?>
