<?php

function friendRequestExist($owntableuserid, $tableuserid){
	$sql = "SELECT * FROM vBiblio_demande WHERE id_user=$owntableuserid AND id_user_requested=$tableuserid AND type='FRIENDS_REQUEST' ";

	$result = mysql_query($sql);

	if($result)
		return mysql_num_rows($result)>0;
	else return false;

}


function retrieveIDRequest($owntableuserid, $tableuserid){
	$sql = "SELECT id_demande FROM vBiblio_demande WHERE id_user=$owntableuserid AND id_user_requested=$tableuserid AND type='FRIENDS_REQUEST' ";

	$result = mysql_query($sql);

	if($result)
		return mysql_result($result, 0, "id_demande");
	else return -1;
}


?>