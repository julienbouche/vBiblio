<?php
require_once("classes/Utilisateur.php");
require_once("classes/SiteConfiguration.php");

$config = new SiteConfiguration();
$rootPath = $config->getParameter("VBIBLIO_ROOT_PATH");

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
				<div class="vBibProfilDisplay"><?php include('ssmenuProfil.php');?></div>
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
<?php
	if(isset($_SESSION['fullname'])){
		//$utilisateur = new Utilisateur($_SESSION['uid']);
		$mytableId = $utilisateur->getID();

		//récupérer le nombre de demandes de prets de livre en attente de traitement
		$sql = "SELECT COUNT(*) as nb FROM vBiblio_demande WHERE type='BOOK_REQUEST' AND id_user_requested ='$mytableId' ";

		$result = mysql_query($sql);

		if($result && mysql_num_rows($result)>0 ){
			$row = mysql_fetch_assoc($result);
			if($row['nb']=="0") $affMesLivres = "Mes Livres";
			else $affMesLivres = "<b>Mes Livres</b>";
			
		}
		
?>
			<li class="MenuContainer" >
				<a class="MenuItem" href="<?=$rootPath?>/myBooks.php">
				<?php if($utilisateur->recupererNombreDemandesDePretEnAttente()>0) : ?>
					<img src="<?=$rootPath?>/images/new_book_icon.png" title="Mes Livres" alt="Mes Livres"/>
				<?php else : ?>
					<img src="<?=$rootPath?>/images/new_book_icon.png" title="Mes Livres" alt="Mes Livres"/>
				<?php endif; ?>
				</a>
				<div class="SubMenuItem">
					<div class="SubMenuItemContainer">
					<?php include('ssmenuLivres.php'); ?>
					</div>
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
					<div class="SubMenuItemContainer">
					<?php  include('ssMenuAmis.php'); ?>
					</div>
				</div>
			</li>
			
			<?php if($utilisateur->belongToGroup("SYS_ADMINS")) : ?>
			<li class="MenuContainer">
				<a class="MenuItem" href="<?=$rootPath?>/admin/index.php">
					<img src="<?=$rootPath?>/images/editerprofil.png" title="Administration" alt="Administration" />
				</a>
				<div class="SubMenuItem">
					<div class="SubMenuItemContainer">	
						<a href="<?=$rootPath?>/admin/index.php" class="vBibLink SubMenuItem">Variables</a>
						<a href="<?=$rootPath?>/admin/manage_roles.php" class="vBibLink SubMenuItem">Groupes</a>
						<a href="<?=$rootPath?>/admin/users.php" class="vBibLink SubMenuItem">Utilisateurs</a>
					</div>
				</div>
			</li>
			<?php endif; ?>
			
			<form method="POST" action="<?=$rootPath?>/addBooks.php" >
				<input type="text" name="searchText" placeholder="Recherche..." class="awesomeBar" />
			</form>		
<?php
	}
?>	
		</ul>
	</div>
</div>
