<?php
include('../../scripts/db/db.php');

dbConnect();
	$connectedUserId = $_GET['uid'];
	$sqlReq = "SELECT user2.fullname as value 
			FROM vBiblio_user As user1, vBiblio_user as user2, vBiblio_amis 
			WHERE user1.userid='".$connectedUserId."' AND vBiblio_amis.id_user1=user1.tableuserid 
			AND user2.tableuserid=vBiblio_amis.id_user2 ORDER BY user2.fullname";
	$results = mysql_query($sqlReq);
	if($results && mysql_num_rows($results) ) {
		while($row = mysql_fetch_assoc($results)){
			$str = "\"".utf8_encode($row['value'])."\",".$str;
		}
		$str = substr($str,0,strlen($str)-1);
	}
	echo "tags = [".$str."];";
	//echo $_SESSION['uid'];
?>
