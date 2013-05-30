<?php
require_once("classes/Utilisateur.php");
$rootPath = "";

?>
	<div id="header">
		<div id="vBibHeader">
<?php if(isset($_SESSION['fullname'])) : $utilisateur = new Utilisateur($_SESSION['uid']) ?>		
			<div class="vBibDeconn">
				<div class="vBibProfilDisplayMenu">
					<?=$_SESSION['fullname']?>
					<a href="<?=$rootPath?>/disconnect.php" class="vBibDeconn" title="me d&eacute;connecter (<?=$_SESSION['fullname']?>)">
						<img src="<?=$rootPath?>/images/logout.png" height="32px" width="32px" />
					</a>
				</div>
				<div class="vBibProfilDisplay"><? include('ssmenuProfil.php');?></div>
			</div>

			<ul id="vBibMenu">
			<li><div id="enseigne"><a href="<?=$rootPath?>/index.php">vBiblio</a></div></li>
<?php else : ?>
			<div style="display:inline;float:right; margin-top:10px;">
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
				(lang:'fr')
				</script>
				<g:plusone size="medium" ></g:plusone>
			</div>

			<ul id="vBibMenu">
			<li><div id="enseigne"><a href="<?=$rootPath?>/formLogin.php">vBiblio</a></div></li>
			<li style="float:right;">
				<form method="POST" action="<?=$_SERVER['REQUEST_URI']?>">
					<input type="text" name="login" placeholder="login/email..." size="20" style="-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;margin-left:60px"/>
					<input type="password" name="pwd" placeholder="Mot de passe" size="20" style="-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;"/>
					<input type="submit" name="submitok" class="darkblue" value="Connexion" /> 
				</form>
			</li>
<?php endif; ?>
<?
	if(isset($_SESSION['fullname'])){
		//$utilisateur = new Utilisateur($_SESSION['uid']);
		$mytableId = $utilisateur->getID();

		?>
		

<?

		//récupérer le nombre de demandes de prets de livre en attente de traitement
		$sql = "SELECT COUNT(*) as nb FROM vBiblio_demande WHERE type='BOOK_REQUEST' AND id_user_requested ='$mytableId' ";

		$result = mysql_query($sql);

		if($result && mysql_num_rows($result)>0 ){
			$row = mysql_fetch_assoc($result);
			if($row['nb']=="0") $affMesLivres = "Mes Livres";
			else $affMesLivres = "<b>Mes Livres</b>";
			
		}
?>


			<!--li><a class="MenuItem" href="<?=$rootPath?>/index.php">Accueil</a></li-->
			<li class="MenuContainer">
				<a class="MenuItem" href="<?=$rootPath?>/myBooks.php">
				<?php if($utilisateur->recupererNombreDemandesDePretEnAttente()>0) : ?>
					<img src="<?=$rootPath?>/images/new_book_icon.png" title="Mes Livres" alt="Mes Livres"/>
				<?php else : ?>
					<img src="<?=$rootPath?>/images/new_book_icon.png" title="Mes Livres" alt="Mes Livres"/>
				<?php endif; ?>
				</a>
				<div class="SubMenuItem">
					<?
					include('ssmenuLivres.php');
					?>
				</div>
			</li>
			<li class="MenuContainer"> 
				<a class="MenuItem" href="<?=$rootPath?>/friends.php">
				<?php if($utilisateur->recupererNombreDemandesDeContactEnAttente()>0) : ?>
					<img src="<?=$rootPath?>/images/new_friends_icon.png" title="Mes Amis" alt="Mes Amis"/>
				<?php else : ?>
					<img src="<?=$rootPath?>/images/new_friends_icon.png" title="Mes Amis" alt="Mes Amis"/>
				<?php endif; ?>
				</a>
				<div class="SubMenuItem">
					<?
					include('ssMenuAmis.php');
					?>
				</div>
			</li>
			<li>
				<form method="POST" action="addBooks.php" >
					<input type="text" name="searchText" placeholder="Recherche..." size="40" style="-moz-border-radius: 10px;-webkit-border-radius: 10px;border-radius: 10px;padding-left:5px;padding-right:5px;margin-left:30px; "/>
				</form>
			</li>

<?
	}
	
?>	
		</ul>
	</div>
</div>
