<?php
require_once('accesscontrol.php');
require_once('scripts/common.php');
require_once('scripts/dateFunctions.php');

checkSecurity();

$uid= $_SESSION['uid'];

$tuid = getTableUserId($uid);

if(isset($_GET['user']) ){
	$tableID=$_GET['user'];
	$tuid = $tableID;
	$sql = "SELECT email, date_naiss, fullname, userid, sexe, id_pref_book, website, prefBookStyle FROM vBiblio_user WHERE tableuserid = '$tableID'";
  	//verifier que les utilisateurs sont amis ou que le profil consulté a demandé la personne en ami sinon redirection ou message d'erreur !!!
  	$verifSecuSQL = "SELECT id_user1 FROM vBiblio_user, vBiblio_amis WHERE userid='$uid' AND id_user2=tableuserid AND id_user1=$tableID"; 
  	$resultSecu = mysql_query($verifSecuSQL);
  	if(mysql_num_rows($resultSecu)>0){
    		$SecuriteOK = true;
		$limitedAccess = false;
  	}
  	else{
		$verifSecuSQL = "SELECT id_user, id_demande FROM vBiblio_demande WHERE id_user_requested ='".getTableUserId($uid)."' AND id_user='$tableID' AND type='FRIENDS_REQUEST'";
		echo $verifSecuSQL;
  		$resultSecu = mysql_query($verifSecuSQL);
		if(mysql_num_rows($resultSecu)>0){
			$SecuriteOK = true;
			$limitedAccess = true;
		}else $SecuriteOK = false;
	}
}
else {
	$sql = "SELECT email, date_naiss, fullname, userid, sexe, id_pref_book, website, prefBookStyle FROM vBiblio_user WHERE userid = '$uid'";
	$SecuriteOK = true;
} 
	
if ( isset($_POST['directMessage']) and $_POST['directMessage']!=''){
	$isql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
	$iresult = mysql_query($isql);
	if($iresult && mysql_num_rows($iresult)){
		$irow = mysql_fetch_assoc($iresult);
		$mytableId = $irow['tableuserid'];
	$mess = $_POST['directMessage'];
	$sysd = date("Y-m-d H:i:s");
	$insertSQL = "INSERT INTO vBiblio_message (from_user, to_user, date, message) VALUES ( '$mytableId', '$tableID', '$sysd' ,'$mess')";
	
	$rs = mysql_query($insertSQL);

	//si l'utilisateur a activé les notifications, on lui envoie un mail.
	$sqlNotif = "SELECT notification_active FROM vBiblio_user WHERE tableuserid='$tableID'";
	$resNotif = mysql_query($sqlNotif);
	if($resNotif && mysql_num_rows($resNotif)){
		if(mysql_result($resNotif, 0, 'notification_active')=="1"){
			$mailMessage=$_SESSION['fullname'].utf8_decode(" vous a envoyé le message suivant : \n\n\n").$mess;
			$mailMessage.=utf8_decode("\n\n\nSi vous ne souhaitez plus recevoir de notifications, vous pouvez les désactiver sur votre page de profil: http://vbiblio.free.fr/profil.php");
			notifyUser($tableID, "[vBiblio] Notification de nouveau message", $mailMessage);
		}
	}
	notifyUser($tableID, "Vous avez reçu un nouveau message sur vBiblio", $mailMessage);


  

	$msgSendMsg = "<a style=\"color:red;\">Votre message a bien &eacute;t&eacute; envoy&eacute;.</a>";
	}
	else $msgSendMsg = "<a style=\"color:red;\">Une erreur est survenue lors de l'envoi de votre message.</a>";
}

$result = mysql_query($sql);

if (mysql_num_rows($result) == 0) {
	unset($_SESSION['uid']);    
	unset($_SESSION['pwd']);
	header('Location:LoginError.php');    
}
else{
	$userEmail = mysql_result($result,0,'email');
	$dateNaissance = mysql_result($result,0,'date_naiss');
	$userName = mysql_result($result,0,'fullname');
	$pseudo = mysql_result($result,0,'userid');
	$prefBookStyle = mysql_result($result, 0,'prefBookStyle');
	$sitePerso = mysql_result($result, 0,'website');
	$prefBook = mysql_result($result, 0,'id_pref_book');
	
	if(mysql_result($result, 0, 'sexe')=="0" ) $sexe = "Homme";
	else $sexe = "Femme";
	
	
	if ( file_exists("images/avatars/avatar-160-".$tuid.".png") ){
		$avatarPath = "images/avatars/avatar-160-".$tuid.".png";
	}
	else {
		$avatarPath = "images/avatars/no_avatar.png\"  width=\"160px\" height=\"160px";
	}
}

$message="";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Profil utilisateur</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
</head>
<body>
<div id="vBibContenu">
	<?php include('header.php'); ?>

	<div id="vBibDisplay">
	<?php if($SecuriteOK) :  ?>
		<?php if(!$limitedAccess) : include('ssMenuPageAmi.php') ?>
		<?php endif; ?>
		
		<?php if(isset($_GET['user'])) : ?>
	<div style="float:right;width:200px;">
		<?php
		$buddy = new Utilisateur("");
		$buddy->initializeByID($tableID);
		$compatibility = $utilisateur->calculerCompatibiliteAmi($buddy);
		?>
		
		<div class="vBibBoite" style="left:-20px;width:100%">
			<div class="vBibBoiteTitre">Styles de lecture:</div>
			<div class="vBibBoiteContenu" style="padding:0px;">
				<?php $tags = $buddy->getAllTagsFromBooks(); ?>
				<?php if(count($tags)>0) : ?>
				<ul id="vBiblio_tagcloud">	
					<?php foreach($tags as $tag) : ?>
					<li class="tag">
						<div style="padding-bottom: 5px;font-size:10px;">
							<a href="searchByTag.php?idtag=<?=$tag->getID()?>" style="color:#FFF" title="Rechercher des livres dans ce style"><?=$tag->getName()?></a>
						</div>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
		</div>
		
		<br/>
		<meter value="<?=$compatibility?>" min="0" max="100" title="Votre compatibilit&eacute; est <?php if($compatibility<20){echo "faible"; }elseif($compatibility<60){ echo "moyenne"; }else {echo "&eacute;lev&eacute;e";}?>"><?=$compatibility?>%</meter>
		<br/>
	</div>
		<?php endif; ?>
		
	<table border="0" cellpadding="0" style="font-size:inherit;border-spacing: 20px 5px;">  
		<tr>
			<td class="tdTitleProfil" colspan="3" style="text-align:center;">Informations g&eacute;n&eacute;rales</td>
		</tr>
		<tr>
			<td rowspan="5"><img src="<?=$avatarPath?>" /></td>
		</tr>
		<tr>
			<td align="left">Pseudo:</td>  
			<td align="left"><?=$pseudo?></td>  
		</tr>  
		<tr>  
			<td align="left">Nom:</td>
			<td><?=$userName?></td>  
		</tr>
		<tr>  
			<td align="left">Date de naissance:</td>
			<td><?=displayInfo($dateNaissance)?></td>
		</tr>
	<tr>
		<td>Site internet:</td>
		<td><a href="<?=$sitePerso?>" class="vBibLink" target="_blank"><?=$sitePerso?></a></td>
	</tr>

	<!-- En dessous de l'avatar-->
	<tr>
		<td class="tdTitleProfil" colspan="3" style="text-align:center;">Informations sur vBiblio</td>
	</tr>
	<tr>
		<td>Style pr&eacute;f&eacute;r&eacute;:</td><td colspan="2"><?=$prefBookStyle?></td>
	</tr>
	<tr>
		<td>Livre pr&eacute;f&eacute;r&eacute;:</td><td colspan="2">
<?php
	$req = "SELECT titre, id_cycle, numero_cycle FROM vBiblio_book WHERE id_book=$prefBook";
	$res = mysql_query($req);
	
	if($res && mysql_num_rows($res)>0){
		$idCycle = mysql_result($res, 0, "id_cycle");
		$req2 ="SELECT titre FROM vBiblio_cycle WHERE id_cycle=$idCycle";
		$res2 = mysql_query($req2);
		$num_in_cycle = mysql_result($res, 0, "numero_cycle");
		if($res2 && mysql_num_rows($res2)>0)$beginTitre = mysql_result($res2, 0, "titre").", Tome ".$num_in_cycle.": ";
		echo "<a href=\"ficheLivre?id=$prefBook\" class=\"vBibLink\">$beginTitre".mysql_result($res, 0, "titre")."</a>";
	}
?>
		</td>
	</tr>

	</table>  
	<br/>
	<br/>
	<?php if(isset($_GET['user']) && !$limitedAccess) : ?>
		Envoyez un message &agrave; <?=$userName?>:
		<form name="formDirectMessage" method="POST" action="<?=$_SERVER['PHP_SELF']?>?user=<?=$_GET['user']?>">
		<fieldset>
			<table style="font-size:inherit">
				<tr>
					<td>Message:</td><td></td>
				</tr>
				<tr>
					<td><textarea wrap="soft" name="directMessage" rows="5" cols="60"></textarea></td><td></td>
				</tr>
				<tr>
					<td><?=$msgSendMsg?></td><td><input type="submit" value="Envoyer" /></td>
				</tr>
			</table>
		</fieldset>
		</form>
		<?php endif; ?>
	<?php else : ?>
	Vous n'&ecirc;tes pas dans la liste d'amis de cet utilisateur. Vous ne pouvez pas acc&eacute;der &agrave; ses informations.
	<?php endif; ?>
	</div>
	<?php include('footer.php'); ?>
</div>
</body>
</html>
