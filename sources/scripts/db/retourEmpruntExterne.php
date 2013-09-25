<?php
include('db.php');



if(isset($_GET['uid']) && isset($_GET['id_book']) && isset($_GET['nomEmprunteur']) ){
	dbConnect();
	$uid=intval($_GET['uid']);
	$id_book = intval($_GET['id_book']);
	$nomEmprunteur = mysql_real_escape_string($_GET['nomEmprunteur']);
	
	//suppression du pret.
	$sql = "DELETE FROM vBiblio_pret WHERE id_preteur=0 AND id_book=$id_book AND id_emprunteur=$uid AND nom_emprunteur='$nomEmprunteur'";
	$result = mysql_query($sql) or die("Erreur lors de la mise a jour.");
}

?>
