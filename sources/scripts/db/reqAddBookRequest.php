<?php
include('db.php');



if(isset($_GET['user1']) && isset($_GET['user2']) ){
	dbConnect();

	//mettre à jour la table.
	$user1 = $_GET['user1'];
	$user2 = $_GET['user2'];
	$id_book = $_GET['id_req'];

	$sql = "INSERT INTO vBiblio_demande (id_user, id_user_requested, type, id_requested) VALUES('$user1', '$user2', 'BOOK_REQUEST', $id_book)";
	mysql_query($sql) or die("ERROR");

	echo "Une demande a &eacute;t&eacute; envoy&eacute;e.";
}
else echo "ERROR";


?>
