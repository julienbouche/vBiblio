<?php
include('db.php');



if(isset($_GET['user1']) && isset($_GET['user2']) ){
	dbConnect();

	//mettre à jour la table.
	$user1 = $_GET['user1'];
	$user2 = $_GET['user2'];

	$sql = "INSERT INTO vBiblio_demande (id_user, id_user_requested, type) VALUES('$user1', '$user2', 'FRIENDS_REQUEST')";
	mysql_query($sql);

	echo "Une demande a &eacute;t&eacute; envoy&eacute;e.";
}
else echo "ERROR";


?>
