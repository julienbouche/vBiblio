<?php
include('db.php');


dbConnect();

if(isset($_GET['idRequest']) ){
	//mettre à jour la table.
	$idRequest = intval($_GET['idRequest']);

	$sql = "DELETE FROM vBiblio_demande WHERE vBiblio_demande.id_demande = $idRequest";
	mysql_query($sql);

	mysql_close();

}
else if(isset($_GET['idSuggest']) ){
	//mettre à jour la table.
	$idSuggest = intval($_GET['idSuggest']);

	$sql = "DELETE FROM vBiblio_suggest WHERE id_suggest = $idSuggest";
	mysql_query($sql);

	mysql_close();

}
else echo "ERROR";


?>
