<?php
include('accesscontrol.php');
require_once('classes/Utilisateur.php');

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
	<? include('header.php'); ?>

	<div id="vBibDisplay">

		<div class="BookmarkN1">
			<div class="BMCorner"></div>
			<div class="BMCornerLink"></div>
			<div class="BMMessage">Demandes en cours</div>
		</div>
		<br/><br/><br/><br/><br/>

		<? $LivresDemandes = $utilisateur->recupererListeDemandesLivres(); ?>
		<?php if(count($LivresDemandes)>0) : ?>
			<table>
			<?php foreach($LivresDemandes as $demande) : $buddy = $demande[0]; $bouquin = $demande[1]; $idDemande=$demande[2]?>
				<tr name="request<?=$idDemande?>">
					<td style="width:2%;"></td>
					<td style="width:50%;text-align:left;">
						<a href="userProfil.php?user=<?=$buddy->getID()?>" class="vBibLink">
							<b><?=$buddy->getFullname()?></b>
						</a> souhaite vous emprunter 
						<a href="ficheLivre.php?id=<?=$bouquin->getID()?>" class="vBibLink"><?=$bouquin->titreLong()?></a>
					</td>
					<td>
						<input type="button" value="Confirmer!" onclick="javascript:acceptRequest(<?=$idDemande?>, <?=$utilisateur->getID()?>, <?=$buddy->getID()?>, <?=$bouquin->getID()?>);" />&nbsp;
						<input type="button" class="alert" value="X" onclick="javascript:ignoreRequest(<?=$idDemande?>);"/>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
		<?php else : ?>
		Vous n'avez aucune demande actuellement.
		<?php endif; ?>
		</div>
		<? include('footer.php'); ?>
	</div>
</body>
</html>
