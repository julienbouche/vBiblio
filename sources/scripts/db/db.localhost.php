<?php 

$serveur = "localhost";
//$login = "root" ;
$login = "root";
$mdp = "shifty12";

function dbConnect(){
		
	global $serveur, $login, $mdp;
 
	$bdname = "jubouche";

	//connexion  au serveur sql
	$db = mysql_connect($serveur, $login, $mdp)
		or die("Une erreur de connexion au serveur de base de donn&eacute;es est survenue.<br/>Merci de r&eacute;essayer plus tard.");

	mysql_select_db($bdname, $db);
}


function bookRequestExist($owntableuserid, $tableuserid, $id_book){
	$sql = "SELECT * FROM vBiblio_demande WHERE id_user=$owntableuserid AND id_user_requested=$tableuserid AND id_requested=$id_book";

	$result = mysql_query($sql);

	if($result)
		return mysql_num_rows($result)>0;
	else return false;

}

function ftrim($str){
	$tempStr="";
	$i=0;
	while($i<strlen($str) ){
		if(substr($str,$i,1)!= ' ')
			$tempStr = $tempStr.substr($str,$i,1);
		$i++;
	}
	return $tempStr;
}


?>
