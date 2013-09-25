<?php
include('accesscontrol.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');

checkSecurity();


$uid = $_SESSION['uid'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Suggestions de vos amis</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
	<script type="text/javascript" src="js/gui/suggest_gui.js"></script>
	
</head>
<body>
<div id="vBibContenu">
<? include('header.php'); ?>

	<div id="vBibDisplay">

	<div class="BookmarkN1">
		<div class="BMCorner"></div>
		<div class="BMCornerLink"></div>
		<div class="BMMessage">Suggestions de vos amis</div>
	</div>
	<br/><br/><br/><br/><br/>

	<? $listeSuggestions = $utilisateur->recupererListeCompleteSuggestions(); ?>

	<?php if(count($listeSuggestions) > 0) : ?>
		<table style="font-size:inherit;width:100%;border:0;">
		<?php foreach($listeSuggestions as $suggestion) : $bouquin =$suggestion[1]; $buddy=$suggestion[0]; $id_suggest =$suggestion[2]?>
			<tr name="request<?=$id_suggest?>">
				<td style="text-align:left;">
					<a href="userProfil.php?user=<?=$buddy->getID()?>" class="vBibLink">
						<b><?=$buddy->getFullname()?></b>
					</a> vous sugg&egrave;re de lire 
					<a href="ficheLivre.php?id=<?=$bouquin->getID()?>" class="vBibLink"><?=$bouquin->titreLong()?></a>
				</td>
		
				<td style="white-space:nowrap;">
					<input type="button" value="Ajouter &agrave; ma vBiblio" onclick="javascript:addToMyVBiblio(<?=$id_suggest?>, <?=$utilisateur->getID()?>, <?=$bouquin->getID()?>);" />&nbsp;
					<input type="button" class="vert" value="Ajouter &agrave; ma ToRead List" onclick="javascript:addToMyTRL(<?=$id_suggest?>, <?=$utilisateur->getID()?>, <?=$bouquin->getID()?>);"/>&nbsp;
					<input type="button" class="alert" value="X" onclick="javascript:ignoreRequest(<?=$id_suggest?>);"/>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php else : ?>
	Vous n'avez aucune suggestion actuellement.
	<?php endif; ?>


	</div>
	<? include('footer.php'); ?>

</div>

</body>
</html>
