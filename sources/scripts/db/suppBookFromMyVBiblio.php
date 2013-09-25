<?php
include('db.php');



if(isset($_GET['idbook']) && isset($_GET['id_user']) ){
	dbConnect();

	//mettre à jour la table.
	$idbook = intval($_GET['idbook']);
	$id_user = intval($_GET['id_user']);

	$sql = "DELETE FROM vBiblio_poss WHERE id_book = $idbook AND userid=$id_user";
	mysql_query($sql);

	mysql_close();

}
else echo "ERROR";


?>
