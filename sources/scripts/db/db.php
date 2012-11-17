<?php 

$serveur = "localhost";
$login = "user";
$mdp = "mot_de_passe";

function dbConnect(){
		
	global $serveur, $login, $mdp;
 
	$bdname = "nom_de_la_base";

	//connexion  au serveur sql
	$db = mysql_connect($serveur, $login, $mdp)
		or die("Une erreur de connexion au serveur de base de donn&eacute;es est survenue.<br/>Merci de r&eacute;essayer plus tard.");

	mysql_select_db($bdname, $db);
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
