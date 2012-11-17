<?php

//on regarde son propre profil
//if(!isset($_GET['user'])){

?>

<img src="<?=$utilisateur->cheminFichierBigAvatar()?>" width="160" style="float:left;"/>
<a href="<?=$rootPath?>/userProfil.php" class="vBibLink">Voir mon profil</a> <br/><br/>
<a href="<?=$rootPath?>/profil.php" class="vBibLink">Modifier mon profil</a><br/><br/>
<a href="<?=$rootPath?>/messages_rec.php" class="vBibLink">Mes messages</a><br/>
<div style="clear:both"></div>
<hr/>
<a href="<?=$rootPath?>/disconnect.php" class="vBibLink" style="float:left;">D&eacute;connexion</a><br/>

<?

/*
e
/!\ RECOPIER LA PARTIE CI-DESSOUS DANS UNE AUTRE PAGE ET FAIRE LES INCLUDES NECESSAIRES

*/

//}
?>
