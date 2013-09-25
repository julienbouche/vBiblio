<?php
include('db.php');



if(isset($_GET['author'])){
	dbConnect();

	//mettre à jour la table.
	$auteur = intval($_GET['author']);
	
	$sql = "SELECT titre, id_cycle FROM vBiblio_author, vBiblio_cycle WHERE vBiblio_author.id_author=$auteur AND vBiblio_author.id_author=vBiblio_cycle.id_author";
	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result) ){
		while ( $row= mysql_fetch_assoc($result)){
			$titre = utf8_encode($row['titre']);
			$id = $row['id_cycle'];
			echo "<option value=\"$id\">$titre</option>";
		} 
	}
}
else echo "ERROR";


?>
