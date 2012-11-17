<?php  
include('db.php');
dbConnect();




if(isset($_GET['idbook']) && isset($_GET['id_user']) ){

	//mettre à jour la table.
	$idbook = $_GET['idbook'];
	$id_user = $_GET['id_user'];
	$sysd = date('Y-m-d H:i:s');

	$sql = "INSERT INTO vBiblio_toReadList (id_book, id_user, date_ajout) VALUES ('$idbook', '$id_user', '$sysd') ";
	mysql_query($sql);
	
	if(isset($_GET['idSuggest']) ){
		$idSuggest= $_GET['idSuggest'];
		//alors on supprime la suggestion...
		$sql = "DELETE FROM vBiblio_suggest WHERE id_suggest=$idSuggest";
		mysql_query($sql);
	}
	
	mysql_close();
	echo "le livre a &eacute;t&eacute; ajout&eacute;";
}
else echo "Une erreur est survenue";


?>
