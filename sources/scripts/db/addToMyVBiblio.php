<?php  
include('db.php');



if(isset($_GET['idbook']) && isset($_GET['id_user']) ){
	dbConnect();

	//mettre à jour la table.
	$idbook = $_GET['idbook'];
	$id_user = $_GET['id_user'];
	$sysd = date('Y-m-d H:i:s');

	//on test si le livre est présent dans la TRL de l'utilisateur...
	$sql= "SELECT id_book FROM vBiblio_toReadList WHERE id_book=$idbook AND id_user=$id_user ";
	$res = mysql_query($sql);
	if($res && mysql_num_rows($res)>0){
		//si oui, on le supprime de la TRL
		$sql = "DELETE FROM vBiblio_toReadList WHERE id_book=$idbook AND id_user=$id_user ";
		$res = mysql_query($sql);
	}
	
	//maintenant on ajoute le livre !
	$sql = "INSERT INTO vBiblio_poss (id_book, userid, possede, lu, pret, date_ajout) VALUES ('$idbook', '$id_user', '1', '0', '0', '$sysd')";
	mysql_query($sql);
	
	//si l'utilisateur ajoute le livre depuis la page de suggestion, on supprime la suggestion
	if(isset($_GET['idSuggest']) ){
		$idSuggest= $_GET['idSuggest'];
		//alors on supprime la suggestion...
		$sql = "DELETE FROM vBiblio_suggest WHERE id_suggest=$idSuggest";
		mysql_query($sql);
		echo "le livre a &eacute;t&eacute; ajout&eacute;";
	}

	mysql_close();
}
else echo "Une erreur est survenue";


?>
