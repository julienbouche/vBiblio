<?php
include('db.php');



if(isset($_GET['user1']) && isset($_GET['user2']) && isset($_GET['idRequest']) && isset($_GET['id_book'])){
	dbConnect();

	//mettre à jour la table.
	$user1 = intval($_GET['user1']);
	$user2 = intval($_GET['user2']);
	$idRequest = intval($_GET['idRequest']);
	$id_book = intval($_GET['id_book']);
	$sysdate = date('Y-m-d H:i:s');
	
	$sql= "SELECT fullname FROM vBiblio_user WHERE tableuserid='$user2'";
	$res = mysql_query($sql);
	if($res && mysql_num_rows($res)>0){
		$row = mysql_fetch_assoc($res);
		$nomEmprunteur = $row['fullname'];
	}
	
	$sql = "INSERT INTO vBiblio_pret (id_preteur, id_emprunteur, nom_emprunteur, id_book, date_pret) VALUES ('$user1', '$user2', '$nomEmprunteur', '$id_book', '$sysdate') ";

	mysql_query($sql) or die("Erreur : ".$sql);

	$sql = "DELETE FROM vBiblio_demande WHERE vBiblio_demande.id_demande = $idRequest";
	mysql_query($sql) or die("Erreur : ".$sql);


	//echo "user1 = $user1 &amp; user2 = $user2";
	echo "Le pr&ecirc;t a &eacute;t&eacute; valid&eacute;.";
}
else echo "ERREUR param&egrave;tre manquant.";


?>
