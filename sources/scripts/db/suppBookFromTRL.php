<?php
include('db.php');



if(isset($_GET['idbook']) && isset($_GET['id_user']) ){
	dbConnect();

	//mettre à jour la table.
	$idbook = $_GET['idbook'];
	$id_user = $_GET['id_user'];

	$sql = "DELETE FROM vBiblio_toReadList WHERE id_book = $idbook AND id_user=$id_user";
	mysql_query($sql);

	mysql_close();

}
else echo "ERROR";


?>
