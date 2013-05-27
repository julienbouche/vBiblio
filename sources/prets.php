<?php
include('accesscontrol.php');
require_once('classes/Utilisateur.php');

checkSecurity();


$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

//ajouter un pret
if(isset($_POST['id_book']) && isset($_POST['id_user']) ){
	$today = date('Y-m-d H:i:s');
	$idNewBookPret = $_POST['id_book'];
	$idNewEmprunteur = $_POST['id_user'];
	$sql = "SELECT fullname FROM vBiblio_user WHERE tableuserid=$idNewEmprunteur";
	$result = mysql_query($sql);
	if($result && mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$nom_emprunteur = $row['fullname'];
		$sql = "INSERT INTO vBiblio_pret (id_preteur, id_emprunteur, id_book, date_pret, nom_emprunteur) VALUES ('".$utilisateur->getID()."', '$idNewEmprunteur', '$idNewBookPret', '$today', '$nom_emprunteur')";
	
		mysql_query($sql);

	$sql = "UPDATE vBiblio_poss SET pret=1 WHERE id_book=$idNewBookPret AND userid=".$utilisateur->getID()."";

	mysql_query($sql);
	}
}

//enregistrement d'un pret fait à un utilisateur qui n'est pas dans le système
if( isset($_POST['id_book']) && isset($_POST['outsideUser']) ){
	$nom_emprunteur = $_POST['vUsername'];
	$today = date('Y-m-d H:i:s');
	$idNewBookPret = $_POST['id_book'];
	$sql = "INSERT INTO vBiblio_pret (id_preteur, id_emprunteur, id_book, date_pret, nom_emprunteur) VALUES ('".$utilisateur->getID()."', '0', '$idNewBookPret', '$today', '$nom_emprunteur')";

	mysql_query($sql);

	$sql = "UPDATE vBiblio_poss SET pret=1 WHERE id_book=$idNewBookPret AND userid=".$utilisateur->getID()."";

	mysql_query($sql);

}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Les livres que vous avez pr&ecirc;t&eacute;s</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	
	<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
	<script type="text/javascript" src="js/gui/pret_gui.js"></script>

</head>
<body>
<div id="vBibContenu">
	<? include('header.php'); ?>

	<div id="vBibDisplay">	

	<div class="BookmarkN1">
		<div class="BMCorner"></div>
		<div class="BMCornerLink"></div>
		<div class="BMMessage">Ajouter un pr&ecirc;t </div>
	</div>

	<br/><br/><br/><br/><br/>	
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<fieldset>
	<table>
	<tr>
		<td>Le livre</td>
		<td>
			<select name="id_book">
<?
	$bouquins = $utilisateur->retournerListeLivresDispos();
?>
	
	<?php foreach($bouquins as $bouquin) : ?>
			<option value="<?=$bouquin->getID()?>" title="<?=$bouquin->titreLong()?>"><?=$bouquin->TitreLongAsShortNames()?> de <?=$bouquin->retournerAuteur()->getShortName()?></option>
	<?php endforeach; ?>

			</select>
		</td>
	</tr>
	<tr>
		<td>&agrave;</td>
		<td>
			<select name="id_user">
<?
	$friends = $utilisateur->recupererListeAmis();
?>
	<?php foreach($friends as $friend) : ?>
				<option value="<?=$friend->getID()?>"><?=$friend->getFullname()?></option>
	<?php endforeach; ?>
	
?>
			</select>
		</td>
	</tr>
	<tr>
		<td></td>
		<td style="text-align:left;">Autre: <input name="outsideUser" type="checkbox" onchange="javascript:changeUserChoice(this);"/> <input name="vUsername" type="text" size=25 disabled/></td>

	</tr>
	<tr>
		<td></td>
		<td style="text-align:right;">
		<input type="submit" value="Confirmer" />
		</td>
	</tr>
</table>
	</fieldset>
	</form>

	<div class="BookmarkN1">
		<div class="BMCorner"></div>
		<div class="BMCornerLink"></div>
		<div class="BMMessage">Vos pr&ecirc;ts </div>
	</div>
	<br/><br/><br/>
<?
	$prets = $utilisateur->retournerListePretsEnCours();

?>
	<?php if(count($prets)>0) : ?>
	<a href="generateTablePretsPDF.php?type=2" target="_blank" style="float:right;" ><img src="images/adobe-pdf-logo.png" width="32" height="32" title="T&eacute;l&eacute;charger la liste"/></a>
		<br/><br/>
		<table class="vBibTablePret">
		<?php foreach($prets as $pret) : $bouquin = $pret[0]; $nomEmprunteur = $pret[2]; $idEmprunteur = $pret[1]; ?>
			<tr>
				<td></td>
				<td><a href="ficheLivre.php?id=<?=$bouquin->getID()?>" class="vBibLink"><?=$bouquin->titreLong()?></a> a &eacute;t&eacute; pr&ecirc;t&eacute; &agrave; 

				<?php if($idEmprunteur!="0") : ?>
					<a class="vBibLink" href="userProfil.php?user=<?=$idEmprunteur?>">
				<?php else : ?> 
					<a class="vBibLink" style="color:black">
				<?php endif; ?>
						<b><?=$nomEmprunteur?></b>
					</a>
				</td>
				<td>
					<input type="button" value="Ok, il me l'a rendu!" onclick="javascript:retourPret(this,<?=$utilisateur->getID()?>, <?=$idEmprunteur?>, <?=$bouquin->getID()?>);"/>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	<?php else : ?>
	Vous n'avez pr&ecirc;t&eacute; aucun livre, en ce moment.
	<?php endif; ?>
	</div>
	<? include('footer.php'); ?>
</div>
</body>
</html>
