<?php
include('db.php');



if(isset($_GET['uid']) && isset($_GET['id_book']) && isset($_GET['idEmprunteur']) ){
	dbConnect();
	$uid=intval($_GET['uid']);
	$id_book = intval($_GET['id_book']);
	$idEmprunteur = intval($_GET['idEmprunteur']);
	
	//mis à jour des infos du livre
	$sql="UPDATE vBiblio_poss SET pret=0 WHERE id_book=$id_book AND userid=$uid";
	$result = mysql_query($sql) or die("Erreur lors de la mise a jour.");

	//suppression du pret.
	$sql = "DELETE FROM vBiblio_pret WHERE id_preteur=$uid AND id_book=$id_book AND id_emprunteur=$idEmprunteur";
	$result = mysql_query($sql) or die("Erreur lors de la mise a jour.");
}

?>
