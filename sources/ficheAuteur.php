<?php
include('accesscontrol.php');
include('scripts/dateFunctions.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');
require_once('classes/Auteur.php');

checkSecurity();


$uid= $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

$edit_mode_available = false;

if($utilisateur->belongToGroup("POWER_USERS")){
	$edit_mode_available = true;
}

$edit_mode = false;
if(isset($_GET['edit']) && $_GET['edit']==1){
	$edit_mode=true;
}

if(isset($_GET['id'])){
	if($edit_mode_available && isset($_POST['nomAuteur']) && isset($_POST['prenomAuteur']) && isset($_POST['desc']) ){
		
		$auteur = new Auteur($_GET['id']);
		$auteur->update($_POST['nomAuteur'], $_POST['prenomAuteur'], $_POST['desc']);
	}
}

?>

<?php if(isset($_GET['id']) ) : $auteur=new Auteur($_GET['id']); ?>
	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title><?php if($auteur->exists()) : ?> <?=$auteur->fullname()?> sur <?php endif; ?>vBiblio</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
</head>
<body>
<div id="vBibContenu">
<?php
	include('header.php');
?>

	<div id="vBibDisplay">

<?php if($auteur->exists()) : ?>
	<div class="vBibBoite" style="float:right;max-width:200px;">
			<div class="vBibBoiteTitre">Plus d'informations sur l'auteur :</div>
			<div class="vBibBoiteContenu" style="padding-left:20px;">
		<li><a class="vBibLink" href="http://fr.wikipedia.org/wiki/Special:Search?search=<?php echo str_replace(' ','+',$auteur->fullname());?>">Wikip&eacute;dia</a></li>
		<li><a class="vBibLink" href='http://www.google.fr/#hl=fr&q="<?php echo "".str_replace(' ','+',$auteur->fullname());?>"'>Google</a></li>
		<li><a class="vBibLink" href="http://recherche.fnac.com/r/<?=$auteur->fullname()?>?SCat=2!1">Fnac.com</a></li>
		</div>
	</div>

	<form action="ficheAuteur.php?id=<?=$_GET['id']?>" method="POST" >
	<table border="0" cellpadding="0" style="font-size:inherit;border-spacing: 20px 5px;width:500px;">  
	   <tr>
		<td rowspan="5" style="width:200px"><img src="images/avatars/no_avatar.png" width="160px" height="160px"/></td>
		<td class="tdTitleProfil" colspan="2">Informations g&eacute;n&eacute;rales:
		<?php if($edit_mode_available) : ?>
			<a href="ficheAuteur.php?id=<?=$_GET['id']?>&edit=1" class="vBibLink" style="float:right;padding-right:5px;"><img src="images/edit.png" width="16px" height="16px"></a>
		<?php endif; ?>

		</td>
	   </tr>
	   <tr>  
	       <td align="left">  
		   <p>Nom:</p>  
	       </td>  
	       <td align="left">
		<?php if($edit_mode_available && $edit_mode) :  ?>
			<input type="text" name="nomAuteur" value="<?=$auteur->retournerNom()?>" />	
		<?php else: ?>
			<?=$auteur->retournerNom()?>
		<?php endif; ?>
	       </td>  
	   </tr>  
	   <tr>  
	       <td align="left">  
		   <p>Pr&eacute;nom:</p>  
	       </td>  
	       <td>
		<?php if($edit_mode_available && $edit_mode) :  ?>
			<input type="text" name="prenomAuteur" value="<?=$auteur->retournerPrenom()?>" />	
		<?php else: ?>
			<?=$auteur->retournerPrenom()?>
		<?php endif; ?>
		
	       </td>  
	   </tr>
	 <tr>  
	       <td align="left">  
		   <p></p>  
	       </td>  
	       <td align="left">
	       </td>  
	   </tr>  
	<?php
	//}
	?>
	   <tr>  
	       <td align="left">  
		   <p>Date de naissance:</p>  
	       </td>  
	       <td>  
		   
	       </td>  
	   </tr>
	<tr>
		<td colspan="3">
			<div style="display:inline;">
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
				(lang:'fr')
				</script>
				&nbsp;<g:plusone size="small" ></g:plusone>
			</div>
		</td>
	</tr>

	<!-- En dessous de l'avatar-->

		<tr>
			<td class="tdTitleProfil" colspan="3">Description:</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: justify;">
	<?php if($edit_mode_available && $edit_mode) : ?>
		<textarea name="desc" rows=10 style="width:100%;"><?=$auteur->retournerDescription()?></textarea>
		<input type="submit" value="Enregistrer" style="float:right;" class="vert"/>
		<input type="button" value="Annuler" onclick="window.location='ficheAuteur.php?id=<?=$_GET['id']?>'" />
	<?php else : ?>
		<?php if($auteur->retournerDescription() == "") : ?>
			Nous n'avons pas encore de description pour cet auteur.
		<?php else : ?>
			<?=$auteur->retournerDescription()?>
		<?php endif; ?>
	<?php endif; ?>
			</td>

		</tr>

		<tr>
			<td class="tdTitleProfil" colspan="3">Les livres de l'auteur:</td>
		</tr>
		<tr>
			<td colspan="3">
	<?php
		//afficher la liste des livres de l'auteur
		$livres = $auteur->retournerListeLivres();
	?>
	<?php if(count($livres) > 0) : ?>
			<div class="vBibList">
				<ul>
				<?php foreach($livres as $livre) : ?>
				<li>
					<a href="ficheLivre.php?id=<?=$livre->getID()?>" class="vBibLink"><?=$livre->titreLong()?></a>
				</li>
				<?php endforeach; ?>
				</ul>
			</div>
	<?php endif;?>
			</td>
		</tr>
	</table>
	</form>
<br/>
<br/>

	<?php else : ?>
	<center>L'auteur que vous recherchez n'est pas disponible dans la base. <br/>
	Il a peut-&ecirc;tre &eacute;t&eacute; supprim&eacute; ou alors vous aurez &eacute;t&eacute; mal redirig&eacute;s.
	<br/>
	<br/>
	Vous pouvez retourner &agrave; l'<a href="index.php" class="vBibLink">accueil</a>.
	</center>
	<?php endif; ?>


</div>
</div>
<?php
	include('footer.php');
?>
</body>
</html>
<?php endif; ?>
