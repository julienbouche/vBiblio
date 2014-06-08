<?php
include('db.php');

dbConnect();

$uid= mysql_real_escape_string($_GET['uid']);
$id_book = intval($_GET['id_book']);

$Prop = mysql_real_escape_string($_GET['Prop']);
$Value= mysql_real_escape_string($_GET['Val']);

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


