<? 
function dateh_lettres($chaine)
{
	$jour = substr($chaine,8,2) ;
	
	if ($jour=='00') $jour="" ;
	else $jour.=" " ;
	
	$mois = substr($chaine,5,2) ;
	
	switch($mois)
	{
		case('00') :	$moisl = "" ;
						break ;
		case('01') :	$moisl = "janvier " ;
						break ;
		case('02') :	$moisl = "f&eacute;vrier " ;
						break ;
		case('03') :	$moisl = "mars " ;
						break ;
		case('04') :	$moisl = "avril " ;
						break ;
		case('05') :	$moisl = "mai " ;
						break ;
		case('06') :	$moisl = "juin " ;
						break ;
		case('07') :	$moisl = "juillet " ;
						break ;
		case('08') :	$moisl = "ao&ucirc;t " ;
						break ;
		case('09') :	$moisl = "septembre " ;
						break ;
		case('10') :	$moisl = "octobre " ;
						break ;
		case('11') :	$moisl = "novembre " ;
						break ;
		case('12') :	$moisl = "d&eacute;cembre " ;
						break ;
	}
						
	
	return $jour.$moisl.substr($chaine,0,4)." &agrave; ".substr($chaine,11,2)."h".substr($chaine,14,2) ;
}

function convertForJavascript($chaine){
	return str_replace('\'','\\\'', $chaine);
}

/*
Function permettant d'obtenir le format DD/MM/YYYY
*/
function displayForm($chaine){
	$jour = substr($chaine,8,2) ;
	$mois = substr($chaine,5,2) ;
	$annee = substr($chaine,0,4);

	return $jour."/".$mois."/".$annee;
}

function displayInfo($chaine){
	$jour = substr($chaine,8,2) ;

	$mois = substr($chaine,5,2) ;
	
	switch($mois)
	{
		case('00') :	$moisl = "" ;
						break ;
		case('01') :	$moisl = "janvier " ;
						break ;
		case('02') :	$moisl = "f&eacute;vrier " ;
						break ;
		case('03') :	$moisl = "mars " ;
						break ;
		case('04') :	$moisl = "avril " ;
						break ;
		case('05') :	$moisl = "mai " ;
						break ;
		case('06') :	$moisl = "juin " ;
						break ;
		case('07') :	$moisl = "juillet " ;
						break ;
		case('08') :	$moisl = "ao&ucirc;t " ;
						break ;
		case('09') :	$moisl = "septembre " ;
						break ;
		case('10') :	$moisl = "octobre " ;
						break ;
		case('11') :	$moisl = "novembre " ;
						break ;
		case('12') :	$moisl = "d&eacute;cembre " ;
						break ;
	}
						
	
	return $jour." ".$moisl.substr($chaine,0,4);
}

?>
