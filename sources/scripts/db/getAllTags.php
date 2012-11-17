<?php
include('../../scripts/db/db.php');

dbConnect();

	$sqlReq = "SELECT value FROM vBiblio_tag";
	$results = mysql_query($sqlReq);
	if($results && mysql_num_rows($results) ) {
		while($row = mysql_fetch_assoc($results)){
			$str = "\"".utf8_encode($row['value'])."\",".$str;
		}
		$str = substr($str,0,strlen($str)-1);
	}
	echo "tags = [".$str."];";

?>
