<?php
include('db.php');

$uid= $_GET['uid'];
$id_book = $_GET['id_book'];

$Prop = $_GET['Prop'];
$Value= $_GET['Val'];

//recuperer l'identifiant de l'utilisateur
dbConnect();

$sql = "SELECT tableuserid FROM vBiblio_user WHERE userid ='$uid'";

$result = mysql_query($sql);
$tableuserid ="";
if($result && mysql_num_rows($result)>0){
	$row = mysql_fetch_assoc($result);
	$tableuserid = $row['tableuserid'];

	//mettre à jour la table avec les valeurs transmises
	$sql="UPDATE vBiblio_poss SET $Prop = $Value WHERE id_book=$id_book AND userid=$tableuserid";

	mysql_query($sql);
	
}
?>


