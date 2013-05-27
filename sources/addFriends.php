<?php
include('accesscontrol.php');
include('scripts/friendsTools.php');
require_once('classes/Utilisateur.php');

checkSecurity();


$uid = $_SESSION['uid'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Rechercher de nouveaux contacts</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="js/core/vbiblio_ajax.js" ></script>
	<script type="text/javascript" src="js/gui/friendsRequest_gui.js" ></script>
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');

if(isset($_POST['searchText'])){
	$searchText=$_POST['searchText'];
}
else{
	if(isset($_GET['q'])){
		$searchText=$_GET['q'];
	}
}

?>

	<div id="vBibDisplay">
		<div align="center">
			<div class="MessagerieMenuItem"><a href="addBooks.php?q=<?=$searchText?>" class="vBibLink" ><input value="Livres" type="button" /></a></div>
			<div class="MessagerieMenuItem"><a href="emprunts.php?q=<?=$searchText?>" class="vBibLink" ><input value="Emprunts" type="button" /></a></div>
			<div class="MessagerieMenuItem"><a href="addFriends.php?q=<?=$searchText?>&attribut=fullname" class="vBibLink" ><input class="vert" value="Utilisateurs" type="button" /></a></div>
		</div>

	<div class="BookmarkN1">
		<div class="BMCorner"></div>
		<div class="BMCornerLink"></div>
		<div class="BMMessage">Retrouver vos amis:</div>
	</div>

	
	<br/>
	<br/><br/><br/><br/>
	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
	<fieldset style="width:430px;">
	<table style="font-size:inherit">
	<tr>


  <td>Par son <select name="attribut">
		<option value="fullname" <?if(isset($_POST['attribut']) and $_POST['attribut']=="fullname") echo "selected";?>>Nom</option>
		<option value="userid" <?if(isset($_POST['attribut']) and $_POST['attribut']=="userid") echo "selected";?>>Pseudo</option>
		<option value="email" <?if(isset($_POST['attribut']) and $_POST['attribut']=="email") echo "selected";?>>Email</option>
	</select> : <input type="text" max-length="100" size="25" name="searchText" value="<?=$searchText?>"/></td><td></td>
  </tr>
  <tr>
    <td>Afficher <select name="listSize"><option value="10">10</option><option value="20">20</option><option value="40">40</option><option value="100fr">100</option></select> r&eacute;sultats au max.</td><td></td>
  </tr>
  <tr>
	<td></td><td><input type="submit" value="Rechercher" /></td></tr>
	</table>
	</fieldset>
	</form>

<?
	if( (isset($_POST['attribut']) ||isset($_GET['attr']) ||isset($_GET['attribut']) ) && (isset($_POST['searchText']) || isset($_GET['q'])) ) {
		
		if( isset($_POST['attribut'] ) ) {
			$searchAttr = $_POST['attribut'];
		}
		else{ 
			if(isset($_GET['attr']))$searchAttr = $_GET['attr'];
			else $searchAttr=$_GET['attribut'];
		}

		if( isset($_POST['searchText']) ){
			$squery = $_POST['searchText'];
		}
		else $squery = $_GET['q'];

		if(isset($_GET['start']) ){
			$start = intval($_GET['start']);
		}
		else $start = 0;
		if(isset($_POST['listSize']) ) {
			$delta = $_POST['listSize'];
		}
		else {
			if(isset($_GET['s']))$delta = $_GET['s'];
			else $delta=10;
		}
		$end = intval($delta);
		

		$sql = "SELECT vBiblio_user.userid FROM vBiblio_user WHERE ".$searchAttr." like '%".$squery."%' AND userid<>'".$utilisateur->getPseudo()."' AND tableuserid NOT IN (SELECT id_user2 FROM vBiblio_amis, vBiblio_user WHERE id_user1=vBiblio_user.tableuserid AND vBiblio_user.userid='".$utilisateur->getPseudo()."') and tableuserid<>0 LIMIT $start,$end";
		
	
		$result = mysql_query($sql);
?>


<?php if($result && mysql_num_rows($result)>0) : ?>
	
	<div class="BuddyList">
	<?php while($row=mysql_fetch_assoc($result)) : $buddy = new Utilisateur($row['userid']); ?>		
		<?php if(friendRequestExist($utilisateur->getID(), $buddy->getID())) : ?>
		<div class="BuddyBox" style="">
			<a class="vBibLink" href="userProfil.php?user=<?=$buddy->getID()?>"><img src="<?=$buddy->cheminFichierAvatar()?>" /></a>
			<a class="vBibLink" href="userProfil.php?user=<?=$buddy->getID()?>" style="position:absolute;margin:auto;padding-left:10px;"><?=$buddy->getFullname()?></a>
			<img class="ImgAction" id="Req<?=$idReq?>" src="images/sablier.png" title="Une demande a d&eacute;j&agrave; &eacute;t&eacute; envoy&eacute;e" width="18px" height="18px" style="border:1px solid gray;padding:2px;float:right;"/>
		</div>
		<?php else : ?>
			<?php if(friendRequestExist($buddy->getID(), $utilisateur->getID())) : $idReq = retrieveIDRequest($buddy->getID(), $utilisateur->getID()); ?>
		<div class="BuddyBox" style="background: -moz-linear-gradient(to bottom, #8F8, #DDF);">
			<a class="vBibLink" href="userProfil.php?user=<?=$buddy->getID()?>"><img src="<?=$buddy->cheminFichierAvatar()?>" /></a>
			<a class="vBibLink" href="userProfil.php?user=<?=$buddy->getID()?>" style="position:absolute;margin:auto;padding-left:10px;"><?=$buddy->getFullname()?></a>
			<img class="ImgAction" id="Req<?=$idReq?>" src="images/checkmark.png" title="Accepter la demande" width="18px" height="18px" style="border:1px solid gray;padding:2px;float:right;" onclick="javascript:acceptRequest(<?=$idReq?>, <?=$buddy->getID()?>, <?=$utilisateur->getID()?>, false);return false;"/>
			<a name="request<?=$idReq?>"></a>
		</div>
				<?php else : ?>
		<div class="BuddyBox" style="">
			<a class="vBibLink" href="userProfil.php?user=<?=$buddy->getID()?>"><img src="<?=$buddy->cheminFichierAvatar()?>" /></a>
			<a class="vBibLink" href="userProfil.php?user=<?=$buddy->getID()?>" style="position:absolute;margin:auto;padding-left:10px;"><?=$buddy->getFullname()?></a>
			<img class="ImgAction" id="Req<?=$idReq?>" src="images/buddyPlus.png" title="Envoyer une demande" width="18px" height="18px" style="border:1px solid gray;padding:2px;float:right;" onclick="javascript:sendBuddyRequest(this,<?=$utilisateur->getID()?>, <?=$buddy->getID()?>);return false;"/>
			<a name="feedback<?=$buddy->getID()?>"></a>
</div>
				<?php endif; ?>
			<?php endif; ?>
		
	<?php endwhile; ?>
	</div>

<?php else : ?>
Aucun utilisateur ne correspond &agrave; votre recherche
<?php endif; ?>
<?


	}

	$sql = "SELECT COUNT(*) as nb FROM vBiblio_user WHERE ".$searchAttr." like '%".$squery."%' AND userid<>'".$utilisateur->getPseudo()."' AND tableuserid NOT IN (SELECT id_user2 FROM vBiblio_amis, vBiblio_user WHERE id_user1=vBiblio_user.tableuserid AND vBiblio_user.userid='".$utilisateur->getPseudo()."') and tableuserid<>0";
	
	$result = mysql_query($sql);
?>

	<?php if($start > 0 ) : ?>
	<a href="<?=$_SERVER['PHP_SELF']?>?attr=<?=$searchAttr?>&q=<?=$squery?>&start=<?=($start - $end)?>&s=<?=$delta?>" style="float:left;" class="vBibLink" title="Pr&eacute;c&eacute;dent">&lt;&lt;</a>
	<?php endif; ?>


	<?
	
	if($result && mysql_num_rows($result)>0 ){
		$row = mysql_fetch_assoc($result);
		$nb = intval($row['nb']);

		if($start + $end < $nb){
		?>
	<a href="<?=$_SERVER['PHP_SELF']?>?attr=<?=$searchAttr?>&q=<?=$squery?>&start=<?=($start + $end)?>&s=<?=$delta?>" style="float:right;" class="vBibLink" title="Suivant">&gt;&gt;</a>";
		<?
		}
	}

?>

</div>

<?
	include('footer.php');
?>
</div>

</body>
</html>
