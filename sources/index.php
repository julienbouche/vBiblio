<?php
require_once('accesscontrol.php');
require_once('scripts/common.php');
require_once('scripts/dateFunctions.php');
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
	$user_from=$utilisateur->getID();
	$insertSQL = "INSERT INTO vBiblio_message (from_user, to_user, date, message) VALUES ( '$user_from', '$user_to_id', '$sysd' ,'$mess')";
	
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
	//notifyUser($tableID, "Vous avez reçu un nouveau message sur vBiblio", $mailMessage);

	$msgSendMsg = "<a style=\"color:red;\">Votre message a bien &eacute;t&eacute; envoy&eacute;.</a>";
	}
	else $msgSendMsg = "<a style=\"color:red;\">Une erreur est survenue lors de l'envoi de votre message.</a>";
}




?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Votre actualit&eacute;</title>  
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

	//Focus sur le textarea
	$eltId= "TAReponseMessage"+$id_mess;
	$elt = document.getElementById($eltId).focus();
}
-->
</script>

</head>
<body>
<div id="vBibContenu">
	<?php include('header.php'); ?>

	<div id="vBibDisplay">
	<p><a href="addNewBook.php" class="vBibLink" >Aidez-nous</a> &agrave; augmenter notre r&eacute;f&eacute;rentiel.</p>

	<h2>Les Derni&egrave;res Infos</h2>
	

	<div class="colonneGauche">
	<div class="vBibBoite">
		<div class="vBibBoiteTitre">Derni&egrave;res demandes</div>
		<div class="vBibBoiteContenu">
		<?php $listeDemandes = $utilisateur->recupererListeResumeDernieresDemandes(); ?>
		<?php if(count($listeDemandes)>0) : ?>
			<ul>
			<?php foreach($listeDemandes as $demande) : ?>
				<li>
				<?php if($demande[0]=="FRIENDS_REQUEST") : ?>
					<span><a href="friendsRequest.php" class="vBibLink"><b><?=$demande[1]?></b> souhaite vous ajouter &agrave; ses amis</a></span>
				<?php else : ?>
					<?php if ($demande[0]=="BOOK_REQUEST") : ?>
					<span><a href="manageBooksRequest.php" class="vBibLink"><b><?=$demande[1]?></b> souhaite vous emprunter un livre</a></span>
					<?php endif; ?>
				<?php endif; ?>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else : ?>
		Aucune demande en attente
		<?php endif; ?>
		</div>
	</div>

	<div class="vBibBoite">
		<div class="vBibBoiteTitre">Suggestions de vos amis</div>
		<div class="vBibBoiteContenu">
		<?php $listeSuggestions = $utilisateur->recupererListeResumeSuggestions(); ?>
		<?php if(count($listeSuggestions)>0) : ?>
			<ul>
			<?php foreach($listeSuggestions as $suggestion) : $buddy = $suggestion[0]; $book = $suggestion[1]?>
				<li>
					<span>
						<a href="manageBooksSuggest.php" class="vBibLink">
							<b><?=$buddy->getFullname()?></b> vous sugg&egrave;re de lire <?=$book->TitreCourt()?>
						</a>
					</span>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else : ?>
			Aucune suggestion actuellement
		<?php endif; ?>
		</div>
	</div>



	</div> <!-- fin de la colonne de gauche -->


	<div class="colonneDroite">

		<div class="vBibBoite">
			<div class="vBibBoiteTitre">Derniers ajouts dans votre <a href="myBooks.php" class="vBibLink" >biblioth&egrave;que</a></div>
			<div class="vBibBoiteContenu">
			
			<?php $listeAjoutsLivre = $utilisateur->retournerListeDerniersAjouts(); ?>

			<?php if(count($listeAjoutsLivre)>0) : ?>
				<ul>
				<?php foreach($listeAjoutsLivre as $livre) : $auteur=$livre->retournerAuteur() ?>
					<li>
						<span>
							<a href="<?=$livre->retournerURL()?>" class="vBibLink"><?=$livre->titreLong()?></a>
							de <a href="ficheAuteur.php?id=<?=$auteur->getID()?>" class="vBibLink"><?=$auteur->fullname()?></a>
						</span>
					</li>
				<?php endforeach; ?>
				</ul>
			<?php else : ?>
			<br/>
			Vous n'avez pas ajout&eacute; de livre dans votre <a href="myBooks.php" class="vBibLink">biblioth&egrave;que</a> r&eacute;cemment.
			<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div style="clear:both;"></div>
	<hr/>
	<div id="vBibActus">
		<h2>Derniers Messages</h2>
    <?php  $ListeMessages = $utilisateur->recupererListeDerniersMessages(); ?>
    <?php if(sizeof($ListeMessages)>0) : ?>
	
	<ul id="vBibMessages">
	<?php foreach($ListeMessages as $Message) : $Expediteur = $Message->getExpediteur() ?>
		<?php if($Expediteur->aUnAvatar()) : ?>
		<li class="vBibMessage" style="background: url(<?=$Expediteur->cheminFichierAvatar()?>) no-repeat 0 1.45em;min-height:70px;" >
	 	<?php else : ?>
		<li class="vBibMessage" style="" >
		<?php endif; ?>
			<div>
	   			<div class="vBibMessageAuthor">
				<a href="userProfil.php?user=<?=$Expediteur->getID()?>" title="Voir le profil" class="vBibLink"><b>
					<?=$Expediteur->getPrenom()?></b>
				</a>
	 			</div>&nbsp;a &eacute;crit:&nbsp;
				<span class="vBibMessageDate">le <?=dateh_lettres($Message->getDate())?></span>
			</div>
			<div></div>
			<div class="vBibMessageContent"  style="margin: 0 4em;">
				<?=nl2br(htmlentities($Message->getContent()))?>
				<br/>&nbsp;
				<input type="button" value="R&eacute;pondre" style="float:right;" onclick="AfficherFormReponse(this,<?=$Message->getID()?>);"/>
			</div>
			<div id="ReponseMessage<?=$Message->getID()?>" style="display:none;">
				<form name="formDirectMessage" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
					<input type="hidden" value="<?=$Message->getExpediteur()->getID()?>" name="user_to" /> 
					<textarea id="TAReponseMessage<?=$Message->getID()?>" wrap="soft" name="directMessage" rows="5" cols="60" ></textarea><br/>
					<input type="submit" value="Envoyer" />
				</form>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
<?php else : ?>
	<br/>Vous n'avez aucun message.
<?php endif; ?>
	</div>
	</div>
</div>
	<?php include('footer.php'); ?>
</body>
</html>
