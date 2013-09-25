<?php
include('accesscontrol.php');
include('classes/Utilisateur.php');

checkSecurity();

$uid = $_SESSION['uid'];
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

<?
	$listeDemandes = $utilisateur->recupererListeDemandesContact();
?>

	<?php if(count($listeDemandes)) : ?>
		<table>
		<?php foreach($listeDemandes as $demande) : $friend = $demande[0]; $idRequest=$demande[1]?>
			<tr name="request<?=$idRequest?>">
				<td style="width:10%;">
					<img src="<?=$friend->cheminFichierAvatar()?>"/>
				</td>
				<td style="width:50%;text-align:left;">
					<a href="userProfil.php?user=<?=$friend->getID()?>" class="vBibLink"><?=$friend->getFullname()?></a>
					 souhaite vous ajouter &agrave; sa liste d'amis
				</td>
				<td>
					<input type="button" value="Confirmer!" onclick="javascript:acceptRequest(<?=$idRequest?>, <?=$utilisateur->getID()?>, <?=$friend->getID()?>, true);" />&nbsp;
					<input type="button" class="alert" value="X" onclick="javascript:ignoreRequest(<?=$idRequest?>);"/>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php else : ?>
	Vous n'avez aucune demande actuellement.
	<?php endif; ?>

</div>
<?
	include('footer.php');
?>

</div>
</body>
</html>
