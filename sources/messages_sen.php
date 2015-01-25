<?php
require_once('accesscontrol.php');
require_once('scripts/dateFunctions.php');
require_once('scripts/common.php');
require_once('classes/Utilisateur.php');
require_once('classes/Message.php');

checkSecurityHome();

$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);


//Cas où on répond à un message 

if ( isset($_POST['directMessage']) and $_POST['directMessage']!='' and isset($_POST['user_to'])){
	$isql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
	$iresult = mysql_query($isql);
	if($iresult && mysql_num_rows($iresult)){
		$irow = mysql_fetch_assoc($iresult);
		$mytableId = $irow['tableuserid'];

	$mess = $_POST['directMessage'];
	$sysd = date("Y-m-d H:i:s");
	$user_to_id= $_POST['user_to'];
	$insertSQL = "INSERT INTO vBiblio_message (from_user, to_user, date, message) VALUES ( '$utilisateur->getID()', '$user_to_id', '$sysd' ,'$mess')";
	
	$rs = mysql_query($insertSQL);

	//si l'utilisateur a activé les notifications, on lui envoie un mail.
	$sqlNotif = "SELECT notification_active FROM vBiblio_user WHERE tableuserid='$user_to_id'";
	$resNotif = mysql_query($sqlNotif);
	if($resNotif && mysql_num_rows($resNotif)){
		if(mysql_result($resNotif, 0, 'notification_active')=="1"){
			$mailMessage=$_SESSION['fullname'].utf8_decode(" vous a envoyé le message suivant : \n\n\n").$mess;
			$mailMessage.=utf8_decode("\n\n\nSi vous ne souhaitez plus recevoir de notifications, vous pouvez les désactiver sur votre page de profil: http://vbiblio.free.fr/profil.php");
			notifyUser($tableID, "[vBiblio] Notification de nouveau message", $mailMessage);
		}
	}

	$msgSendMsg = "<a style=\"color:red;\">Votre message a bien &eacute;t&eacute; envoy&eacute;.</a>";
	}
	else $msgSendMsg = "<a style=\"color:red;\">Une erreur est survenue lors de l'envoi de votre message.</a>";
}




?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Vos messages re&ccedil;us</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type='text/javascript' src='js/core/vbiblio_ajax.js'></script>
<script language="javascript">
<!-- 
function AfficherFormReponse($caller, $id_mess){
	//apparition du formulaire
	$eltId= "ReponseMessage"+$id_mess;
	$elt = document.getElementById($eltId);
	$elt.style.display='inline-block';
	
	//disparition du bouton 'Répondre'
	$caller.style.display='none';
}
-->
</script>

</head>
<body>
<div id="vBibContenu">
<?php
	include('header.php');
?>

	<div id="vBibDisplay">
		<div align="center">
			<div class="MessagerieMenuItem"><a href="messages_rec.php" class="vBibLink" ><input value="Messages re&ccedil;us" type="button"></a></div>
			<div class="MessagerieMenuItem"><a href="messages_sen.php" class="vBibLink" ><input class="vert" value="Messages envoy&eacute;s" type="button"></a></div>
		</div>

    <?php
    //$utilisateur->afficherDerniersMessages();
    $ListeMessages = $utilisateur->recupererListeTousMessagesEnvoyes();
    ?>
	<?php if(sizeof($ListeMessages)>0) : ?>
	
	<ul id="vBibMessages">
	<?php foreach($ListeMessages as $Message) : $Destinataire = $Message->getDestinataire() ?>
		<?php if($Destinataire->exists()) : ?>
			<?php if($Destinataire->aUnAvatar()) : ?>
		<li class="vBibMessage" style="background: url(<?=$Destinataire->cheminFichierAvatar()?>) no-repeat 0 1.45em;min-height:70px;" >
	 		<?php else : ?>
		<li class="vBibMessage" style="" >
			<?php endif; ?>
			<div>
			Envoy&eacute; &agrave; <a href="userProfil.php?user=<?=$Destinataire->getID()?>" title="Voir le profil" class="vBibLink"><b><?=$Destinataire->getPrenom()?></b></a>:&nbsp;<span class="vBibMessageDate">le <?=dateh_lettres($Message->getDate())?></span>
			</div>
			<div></div>
			<div class="vBibMessageContent"  style="margin-left: 4em;border-radius:10px 10px 0px 10px;">
				<?=nl2br(htmlentities($Message->getContent()))?>
				<br/>&nbsp;
			</div>
		</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
<?php else : ?>
	<br/>Vous n'avez envoy&eacute; aucun message.
<?php endif; ?>
	</div>	
</div>
<?php include('footer.php'); ?>
</body>
</html>
