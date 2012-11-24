<?php
include('accesscontrol.php');
//include('scripts/db/db.php');
include('scripts/common.php');
include('classes/Utilisateur.php');

//dbConnect();
checkSecurity();


$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Vos amis</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">

	<!--b>Mes amis</b-->
<div class="BookmarkN1">
	<div class="BMCorner"></div>
	<div class="BMCornerLink"></div>
	<div class="BMMessage">Mes Amis</div>
</div>

<br/><br/><br/><br/>

	<!--Afficher <select><option>10</option><option>20</option><option>40</option><option>100</option><option>TOUS</option></select> amis par page.-->

<?
	/*$friendsArray = $utilisateur->recupererListeAmis();
	echo "<table class=\"vBibTablePret\">\n";

	foreach ($friendsArray as $friend){
		echo "<tr><td style=\"width:10%;\"><a class=\"vBibLink\" href=\"userProfil.php?user=".$friend->getID()."\"><img src=\"".$friend->cheminFichierAvatar()."\" /></a></td><td style=\"width:90%;text-align:left;\"><a class=\"vBibLink\" href=\"userBooks.php?user=".$friend->getID()."\">".$friend->getFullname()."</a></td>";
		echo "</tr>";
	}
	echo "</table>\n";*/
	
?>


	<div class="BuddyList">
<?
	$friendsArray = $utilisateur->recupererListeAmis();

	foreach ($friendsArray as $friend){
?>
	<div class="BuddyBox">
		<a class="vBibLink" href="userProfil.php?user=<?=$friend->getID()?>"><img src="<?=$friend->cheminFichierAvatar()?>" /></a>
		<a class="vBibLink" href="userBooks.php?user=<?=$friend->getID()?>" ><?=$friend->getFullname()?></a>
	</div>
		
<?
	}
?>
	</div>
</div>
<?
	include('footer.php');
?>


</div>
</body>
</html>
