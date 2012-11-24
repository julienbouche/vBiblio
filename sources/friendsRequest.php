<?php
include('accesscontrol.php');
//include('scripts/db/db.php');
include('classes/Utilisateur.php');

//dbConnect();
checkSecurity();

$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Demandes d'amis</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />

	<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
	<script type="text/javascript" src="js/gui/friendsRequest_gui.js"></script>
	
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">

<div class="BookmarkN1">
	<div class="BMCorner"></div>
	<div class="BMCornerLink"></div>
	<div class="BMMessage">Demandes en cours</div>
</div>

<br/><br/><br/><br/><br/>

	<!--b>Demandes en cours</b-->

<?
	
	$utilisateur->afficherListeDemandesContact();

?>

</div>
<?
	include('footer.php');
?>

</div>
</body>
</html>
