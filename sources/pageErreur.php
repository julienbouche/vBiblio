<?php
include('accesscontrol.php');
//include('scripts/db/db.php');
require_once('classes/Utilisateur.php');

//connexion à la bd
//dbConnect();


$uid= $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio : Information non disponible</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">
	
	L'information que vous recherchez n'existe plus ou alors vous avez mal &eacute;t&eacute; redirig&eacute; vers cette page.
	<!-- A Developper... -->
	
	
	
	</div>
</div>
</body>
</html>
