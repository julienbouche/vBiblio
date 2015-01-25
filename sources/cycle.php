<?php
include('accesscontrol.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');
require_once('classes/Cycle.php');

//checkSecurity();


$uid= $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>Cycle sur vBiblio</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
</head>
<body>
<div id="vBibContenu">
<?php
	include('header.php');
?>

	<div id="vBibDisplay">
            <?php if(isset($_GET['id']) && $_GET['id']!='') : $cycle=new Cycle($_GET['id']);$Books=$cycle->getBooks();$auteur=$cycle->getAuthor(); ?>
		<h1 style="text-align: center"><?=$cycle->getTitle()?></h1>
		<p style="text-align: center;font-style: italic;">par <a href="ficheAuteur.php?id=<?=$auteur->getID()?>" class="vBibLink"><?=$auteur->fullname()?></a>,</p>
                <?php if(count($Books)>0) : $BookIdx=0?>
			<?php foreach($Books as $book) : $BookIdx++ ?>
			<?php
				if($BookIdx%2==1){
					$horizontalAlign = "right";
				}
				else $horizontalAlign = "left";
			?>
			<?php if($BookIdx != 1) : ?>
			
			<?php endif; ?>
			<h2 style="text-align:<?=$horizontalAlign?>;margin:5px;">Tome <?=$book->retournerNumeroTome()?> : <?=$book->TitreCourt()?></h2>
			<a href="<?=$book->retournerURL()?>" ><img src="<?=$book->getAvatarPath()?>" style="float:<?=$horizontalAlign?>; padding:5px 5px 5px 5px;" width="169px" height="225px" /></a>
			<p style="text-align: justify;margin:10px 10px 10px 10px;"><?=$book->retournerDescription()?></p>
			<br/>
			
			<?php endforeach; ?>
			
			<?php $calcTags = $cycle->getCalculatedTags(); ?>
			<?php if(count($calcTags)) : ?>
			<ul id="vBiblio_tagcloud" style="clear:both;">
				<?php foreach($calcTags as $tag) : ?>
				<li class="tag">
					<div style="display:inline-block;">
						<a href="searchByTag.php?idtag=<?=$tag->getID()?>" style="color:#FFF" title="Rechercher d'autres livres"><?=$tag->getName()?></a>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		<?php else : ?>
		Ce cycle ne contient pas encore de livre
		<?php endif; ?>
            <?php else : ?>
		Ce cycle n'existe pas.
            <?php endif; ?>
        </div>
	
	<div style="clear:both"></div>
</div>

<?php
	include('footer.php');
?>
</body>
</html>

