<?php
include('accesscontrol.php');
include('classes/Utilisateur.php');

checkSecurity();

$uid = $_SESSION['uid'];

//priorité est donnée au champ texte venant de la recherche d'emprunts puisque nous sommes sur la recherche d'emprunts
if(isset($_POST['searchText']) ){
	$searchText = $_POST['searchText'];
}
else if(isset($_GET['q'])) $searchText = $_GET['q'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Les livres que vous avez emprunt&eacute;s</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	

				<!-- JQUERY CSS & JS --> 
	<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
	<link rel="stylesheet" type="text/css" href="js/jquery/lib/thickbox.css" />
	<script type="text/javascript" src="js/jquery/lib/jquery.js"></script>
	<script type='text/javascript' src='js/jquery/lib/jquery.bgiframe.min.js'></script>
	<script type='text/javascript' src='js/jquery/lib/jquery.ajaxQueue.js'></script>
	<script type='text/javascript' src='js/jquery/lib/thickbox-compressed.js'></script>
	<script type='text/javascript' src='js/jquery/jquery.autocomplete.js' ></script>
	
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	
	<script type="text/javascript" src="js/core/vbiblio_ajax.js"></script>
	<script type="text/javascript" src="js/gui/emprunts_gui.js"></script>
<script language="javascript" >
<!-- 
$().ready(function() {
	var tags;
	xhr = createXHR();
	if(xhr!=null){
		
		xhr.open("GET","scripts/db/getFriendsList.php?uid=<?=$uid?>", true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				tags = eval(xhr.responseText);
				$("#vUsername").autocomplete(tags, {
					multiple: false,
					mustMatch: false,
					autoFill: true
				});
			}
		};
		xhr.send(null);
	}
});
-->
</script>
</head>
<body>
<div id="vBibContenu">
	<?php include('header.php'); ?>

	<div id="vBibDisplay">
		<div align="center">
			<div class="MessagerieMenuItem">
				<a href="addBooks.php?q=<?=$searchText?>" class="vBibLink" ><input value="Livres" type="button" /></a>
			</div>
			<div class="MessagerieMenuItem">
				<a href="emprunts.php?q=<?=$searchText?>" class="vBibLink" ><input class="vert" value="Emprunts" type="button" /></a>
			</div>
			<div class="MessagerieMenuItem">
				<a href="addFriends.php?q=<?=$searchText?>&attr=fullname" class="vBibLink" ><input value="Utilisateurs" type="button" /></a>
			</div>
		</div>

	<div class="BookmarkN1">
		<div class="BMCorner"></div>
		<div class="BMCornerLink"></div>
		<div class="BMMessage">Rechercher un livre disponible dans les biblioth&egrave;ques de vos amis</div>
	</div>
	<br/><br/><br/><br/><br/>

	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
		<fieldset style="width:90%">
			<input type="text" max-length="100" size="100" name="searchText" value="<?=str_replace("\\", "", $searchText)?>"/> <input type="submit" value="Rechercher" style="right:0px"/>
		</fieldset>
	</form>
	<br/>

	<?php if(isset($searchText) && trim($searchText)!="") : ?>
<?php
	$searchTerms = str_replace (" ", ",", $searchText );
	$listeEmpruntsPossibles=  $utilisateur->retournerListeRechercheEmpruntsPossibles($searchTerms);
?>
	<?php if(count($listeEmpruntsPossibles)) : ?>
	<table class="vBibTablePret">
		<?php foreach($listeEmpruntsPossibles as $emprunt) : $buddy = $emprunt[0]; $bouquin = $emprunt[1]; $auteurBouquin = $bouquin->retournerAuteur(); ?>
		<tr>
			<td>
			<a href="userProfil.php?user=<?=$buddy->getID()?>" class="vBibLink" ><b><?=$buddy->getFullname()?></b></a> peut vous pr&ecirc;ter <a href="ficheLivre.php?id=<?=$bouquin->getID()?>" class="vBibLink"><?=$bouquin->TitreLong()?></a> de <a href="ficheAuteur.php?id=<?=$auteurBouquin->getID()?>" class="vBibLink" ><?=$auteurBouquin->fullname()?></a>
			</td>
			<td style="width:200px;" name="feedbackU<?=$buddy->getID()?>B<?=$bouquin->getID()?>">
			<?php if(!$bouquin->isRequested($utilisateur->getID(),$buddy->getID()) ) : ?>
				<input type="button" value="Envoyer une demande" onclick="sendBookRequest(this, <?=$utilisateur->getID()?>,<?=$buddy->getID()?>,<?=$bouquin->getID()?>);return false;"/>
			<?php else : ?>
				Une demande a &eacute;t&eacute; envoy&eacute;e.
			<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</table>
		<br/>
	<?php else : ?>
		Aucun r&eacute;sultat ne correspond &agrave; votre recherche.<br/>
	<?php endif; ?>
	<?php endif; ?>
		
	
<?php
	//Si l'utilisateur veut ajouter un emprunt d'une persone externe
	//détecter le cas où la personne fait partie des amis de l'utilisateur
	if(isset($_POST['vUsername']) and isset($_POST['id_book'])){
		$nomEmprunteur = trim($_POST['vUsername']);
		//recherche si ce nom appartient à l'un de nos amis
		$sql= "SELECT tableuserid 
			FROM vBiblio_user u, vBiblio_amis a 
			WHERE a.id_user1=".$utilisateur->getID()."
			AND a.id_user2=u.tableuserid
			AND u.fullname='".$nomEmprunteur."'";

		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){ //l'utilisateur fait partie du systeme
			//on recup le premier resultat ... mais on fait quoi si plusieurs utilisateur ?
			//et que faire si l'utilisateur n'a pas le livre dans sa vbiblio ??? ça risque de foutre la grouille dans d'autres scripts...
			$row = mysql_fetch_assoc($result);			
			$id_emprunteur = $row['tableuserid'];
			$id_book_Emp = $_POST['id_book'];
			$sysdate = date('Y-m-d H:i:s');
			
			$sql = "INSERT INTO vBiblio_pret (id_preteur, id_emprunteur, nom_emprunteur, id_book, date_pret) VALUES ('".$id_emprunteur."', '".$utilisateur->getID()."', '$nomEmprunteur', '$id_book_Emp', '$sysdate') ";
			
			$erreur = 0;
			mysql_query($sql);

		}else{ //l'utilisateur ne semble pas être dans la base
			$id_book_Emp = $_POST['id_book'];
			$sysdate = date('Y-m-d H:i:s');
		
			$sql = "INSERT INTO vBiblio_pret (id_preteur, id_emprunteur, nom_emprunteur, id_book, date_pret) VALUES ('0', '".$utilisateur->getID()."', '$nomEmprunteur', '$id_book_Emp', '$sysdate') ";
			
			$erreur = 0;
			mysql_query($sql);
		}
	}
?>
	<br/>
	<div class="BookmarkN1">
		<div class="BMCorner"></div>
		<div class="BMCornerLink"></div>
		<div class="BMMessage">Les livres qu'on vous a pr&ecirc;t&eacute;s</div>
	</div>
	<br/><br/><br/><br/><br/>


<?php
	
	$sql = "SELECT vBiblio_pret.nom_emprunteur as fullname, titre, vBiblio_pret.id_preteur, vBiblio_pret.id_book as id_book FROM vBiblio_pret, vBiblio_book WHERE vBiblio_pret.id_emprunteur='".$utilisateur->getID()."' AND vBiblio_pret.id_book=vBiblio_book.id_book order by date_pret ASC";
	
	$result = mysql_query($sql) ;
?>

<?php if($result && mysql_num_rows($result)>0 ) : $cpt=0; ?>
	<a href="generateTableEmpruntsPDF.php" target="_blank" style="float:right;" >
		<img src="images/adobe-pdf-logo.png" width="32" height="32" title="T&eacute;l&eacute;charger la liste"/>
	</a>
	
	<div class="vBibList">
		<ul>
	<?php while($row=mysql_fetch_assoc($result)) : $bouquin = new Livre($row['id_book']);  ?>

<?php
		//while($row=mysql_fetch_assoc($result)){
			$preteur = $row['fullname'];
			$IDPreteur = $row['id_preteur'];
			if($IDPreteur != "0"){ //alors le user est dans le système
				$preteurUser = new Utilisateur("");
				$preteurUser->initializeByID($IDPreteur);
				$preteur = $preteurUser->getFullname();
			}
			//$bouquin = new Livre($row['id_book']);
?>
			<li style="margin-top:10px">
				<span class="vBibBookTitle">
				<?php if($IDPreteur !="0") : ?>
					<a class="vBibLink" href="userProfil.php?user=<?=$IDPreteur?>"><b><?=$preteur?></b></a>
				<?php else : ?>
					<b><?=$preteur?></b>
				<?php endif; ?>
				vous a pr&ecirc;t&eacute; <a href="ficheLivre.php?id=<?=$bouquin->getID()?>" class="vBibLink"><?=$bouquin->titreLong()?></a>
				</span>
				<?php if($IDPreteur =="0") : ?>
&nbsp;<input type="button" class="alert" value="X" onclick="javascript:retourEmpruntExterne(this,<?=$utilisateur->getID()?>, '<?=$preteur?>', <?=$bouquin->getID()?>);"/>
				<?php endif;?>
			</li>
	<?php endwhile; ?>
		</ul>
	</div>
<?php else :?>
		Vous n'avez emprunt&eacute; aucun livre, en ce moment.
<?php endif; ?>
</div>
<?php
	include('footer.php');
?>

</div>

</body>
</html>
