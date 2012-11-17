<?php
require_once("classes/Utilisateur.php");
//$rootPath = "/vBiblio";

?>
	<div id="header">
		<div id="vBibHeader">
<?
	if(isset($_SESSION['fullname'])){
		$utilisateur = new Utilisateur($_SESSION['uid']);
?>		
			<div class="vBibDeconn">
				<div class="vBibProfilDisplayMenu">
					<?=$_SESSION['fullname']?>
					<a href="<?=$rootPath?>/disconnect.php" class="vBibDeconn" title="me d&eacute;connecter (<?=$_SESSION['fullname']?>)">
						<img src="<?=$rootPath?>/images/logout.png" heigth="32px" width="32px" />
					</a>
				</div>
				<div class="vBibProfilDisplay"><? include('ssmenuProfil.php');?></div>
			</div>

			<ul id="vBibMenu">
			<li><div id="enseigne"><a href="<?=$rootPath?>/index.php">vBiblio</a></div></li>
<?
	}else{
?>
			<div style="display:inline;float:right; margin-top:10px;">
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
				(lang:'fr')
				</script>
				<g:plusone size="medium" ></g:plusone>
			</div>

			<ul id="vBibMenu">
			<li><div id="enseigne"><a href="<?=$rootPath?>/formLogin.php">vBiblio</a></div></li>
<?
	}
	if(isset($_SESSION['fullname'])){
		//$utilisateur = new Utilisateur($_SESSION['uid']);
		$mytableId = $utilisateur->getID();

		//récupérer le nombre de demandes d'amis en attente de traitement
		$sql = "SELECT COUNT(*) as nb FROM vBiblio_demande WHERE type like '%FRIENDS_REQUEST%' AND id_user_requested ='$mytableId' ";

		$result = mysql_query($sql);
		$pendingRequest = "";

		if($result && mysql_num_rows($result)>0 ){
			$row = mysql_fetch_assoc($result);
			if($row['nb']=="0") $affMesAmis = "Mes Amis";
			else $affMesAmis = "<b>Mes Amis</b>";
		}

		//récupérer le nombre de demandes d'amis en attente de traitement
		$sql = "SELECT COUNT(*) as nb FROM vBiblio_demande WHERE type='BOOK_REQUEST' AND id_user_requested ='$mytableId' ";

		$result = mysql_query($sql);
		$pendingRequest = "";

		if($result && mysql_num_rows($result)>0 ){
			$row = mysql_fetch_assoc($result);
			if($row['nb']=="0") $affMesLivres = "Mes Livres";
			else $affMesLivres = "<b>Mes Livres</b>";
			
		}
?>


			<li><a class="MenuItem" href="<?=$rootPath?>/index.php">Accueil</a></li>
			<!--li><a class="MenuItem" href="<?=$rootPath?>/userProfil.php">Profil</a></li-->
			<li class="MenuContainer">
				<a class="MenuItem" href="<?=$rootPath?>/myBooks.php"><?=$affMesLivres?></a>
				<div class="SubMenuItem">
					<?
					include('ssmenuLivres.php');
					?>
				</div>
			</li>
			<li class="MenuContainer"> 
				<a class="MenuItem" href="<?=$rootPath?>/friends.php"><?=$affMesAmis?></a>
				<div class="SubMenuItem">
					<?
					include('ssMenuAmis.php');
					?>
				</div>
			</li>

<?
	}
?>
		</ul>
	</div>
</div>
