<?php
include('accesscontrol.php');
//include('scripts/db/db.php');

//dbConnect();

checkSecurity();

$uid = $_SESSION['uid'];


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Demandes de pr&ecirc;ts</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	
	<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
	<script type="text/javascript" src="js/gui/bookRequests_gui.js"></script>
	
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

<?
	
	$utilisateur->afficherListeDemandesLivres();

?>

</div>
<?
	include('footer.php');
?>

</div>

</body>
</html>
