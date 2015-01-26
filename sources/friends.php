<?php
include('accesscontrol.php');
include('scripts/common.php');
include('classes/Utilisateur.php');

checkSecurity();


$uid = $_SESSION['uid'];

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
<?php
	include('header.php');
?>

	<div id="vBibDisplay">

	<h2>Mes Amis</h2>

	<div class="BuddyList">
<?php
	$friendsArray = $utilisateur->recupererListeAmis();
?>

	<?php foreach ($friendsArray as $friend) :?>
		<div class="BuddyBox">
			<a class="vBibLink" href="userProfil.php?user=<?=$friend->getID()?>"><img src="<?=$friend->cheminFichierAvatar()?>" /></a>
			<a class="vBibLink" href="userBooks.php?user=<?=$friend->getID()?>" style="position:absolute;margin:auto;padding-left:10px;"><?=$friend->getFullname()?></a>
		</div>
	<?php endforeach; ?>
	</div>
</div>
</div>
<?php
	include('footer.php');
?>
</body>
</html>
