<?php
include('accesscontrol.php');
include('scripts/common.php');
include('scripts/dateFunctions.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');


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

if(isset($_GET['id']) ){
	$bouquin = new Livre($_GET['id']);
	
	//si en mode edition, on met à jour les valeurs.
	if($edit_mode_available && isset($_POST['titre']) && isset($_POST['isbn']) && isset($_POST['desc']) ){
		$cycle_enabled = false;
		$idtome;
		$serie;
		if(isset($_POST['seriesEnabled'])){
			$cycle_enabled = true;
			$idtome = $_POST['idTome'];
			$serie = $_POST['series'];
		}
		
		
		$bouquin->update($_POST['titre'], $_POST['isbn'], $_POST['desc'], $cycle_enabled, $idtome, $serie);
		$bouquin = new Livre($_GET['id']);
	}
	
	
	$auteur = $bouquin->retournerAuteur();
	if(!$bouquin->exists()){
		header ('Location: pageErreur.php');
	}
	else{

?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title><?=$bouquin->titreLong()?> : <?=$auteur->fullname()?> sur vBiblio</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script type="text/javascript" src="js/jquery/lib/jquery.js"></script>
	<script type='text/javascript' src='js/jquery/lib/jquery.bgiframe.min.js'></script>
	<script type='text/javascript' src='js/jquery/lib/jquery.ajaxQueue.js'></script>
	<script type='text/javascript' src='js/jquery/lib/thickbox-compressed.js'></script>
	<script type='text/javascript' src='js/jquery/jquery.autocomplete.js' ></script>
	
	<script type='text/javascript' src='js/core/vbiblio_ajax.js'></script>
	<script type='text/javascript' src='js/core/user_functions.js'></script>
	<script type='text/javascript' src='js/gui/insidepopup.js'></script>
	<script type='text/javascript' src='js/gui/livre_gui.js'></script>
	<script type="text/javascript" src="js/gui/bookForm_gui.js"></script>
	
	<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
	<link rel="stylesheet" type="text/css" href="js/jquery/lib/thickbox.css" />
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/popInside.css" media="screen" />	

	<!--link href='http://fonts.googleapis.com/css?family=Lancelot' rel='stylesheet' type='text/css'-->
	<link href='http://fonts.googleapis.com/css?family=Donegal+One&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

	<!-- google APIs-->
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript" src="js/gui/ggBooksPreview_gui.js"></script>

</head>
<body>
<div id="vBibContenu">
	<?php include('header.php'); ?>

	<div id="vBibDisplay">

<!-- FENETRE POP INSIDE -->
<div id="fenetreConseilAmi" class="insideWindow">
	<span class="insideWindowTitle">Conseiller &agrave; des amis</span><span class="insideWindowCloser" onclick="popinside_close('fenetreConseilAmi')">X</span>
	<div class="insideWindowContent" >
	<form >
	<input type="hidden" name="idbook" value="<?=$_GET['id']?>" />

<?php
	$buddys = $utilisateur->recupererListeAmis();
?>
	<?php if (count($buddys)>0) : $i=0 ?>
		<table class="vBibTablePret" id="formAdviseFriends">
		<?php foreach ($buddys as $buddy) : $utilisateurPossedeDeja = $buddy->aDansUneListe($bouquin);  ?>
			<?php if ($utilisateurPossedeDeja) : ?>
			<tr title="Cet utilisateur connait d&eacute;j&agrave; ce livre">
			<?php else : ?>
			<tr>
			<?php endif; ?>
				<td style="width:10%;">
					<a class="vBibLink" href="userProfil.php?user=<?=$buddy->getID()?>">
						<img src="<?=$buddy->cheminFichierAvatar()?>" />
					</a>
				</td>
				<td style="width:90%;text-align:left;">
					<a class="vBibLink" href="userProfil.php?user=<?=$buddy->getID()?>"><?=$buddy->getFullname()?></a>
				</td>	

				<td>
					<input type="checkbox" name="friend<?=$i?>" value="<?=$buddy->getID()?>" />
				</td>
			</tr>
		<?php $i++; endforeach; ?>
		</table>
	<?php endif; ?>

	</form>
	</div>
	<input type="submit" class="vert" value="Valider" onclick="submitForm(document.getElementById('formAdviseFriends'), <?=$_GET['id']?>, <?=$utilisateur->getID()?>)" style="float:right;margin-right:5px"/>
	<input type="button" class="gris" value="Annuler" onclick="popinside_close('fenetreConseilAmi')" style="float:right;margin-right:5px"/>
</div> 

<!-- FIN FENETRE POP INSIDE -->

<?php if(isset($_GET['id']) ) : ?>

<div style="float:right;width:200px;">
	<div class="vBibBoite" style="left:-20px;width:100%">
		<div class="vBibBoiteTitre">Rechercher ce livre :</div>
		<div class="vBibBoiteContenu" style="padding-left:20px;">
			<li><a class="vBibLink" href="http://www.leboncoin.fr/livres/offres/ile_de_france/occasions/?f=a&th=1&q=<?php echo str_replace(' ','+',$bouquin->TitreCourt())." ".$auteur->fullname();?>">Leboncoin.fr</a></li>
			<li><a class="vBibLink" href="http://fr.wikipedia.org/wiki/Special:Search?search=<?php echo str_replace(' ','+',$bouquin->TitreCourt())." ".$auteur->fullname();?>">Wikip&eacute;dia</a></li>
			<li><a class="vBibLink" href="http://www.amazon.fr/s/ref=nb_sb_noss?url=search-alias%3Dstripbooks&field-keywords=<?php echo "".str_replace(' ','+',$bouquin->TitreCourt());?>">Amazon.fr</a></li>
			<li><a class="vBibLink" href="http://www.priceminister.com/nav/Livres/kw/<?php echo "".str_replace(' ','+',urlencode($bouquin->TitreCourt()));?>">Price Minister</a></li>
			<li><a class="vBibLink" href="http://www.google.fr/#hl=fr&q=<?=str_replace(' ','+',urlencode($bouquin->TitreCourt().' '.$auteur->fullname()));?>">Google</a></li>
			<li><a class="vBibLink" href="http://recherche.fnac.com/r/<?=$bouquin->TitreCourt()?>?SCat=2!1">Fnac.com</a></li>
		</div>
	</div>

<?php if(isUserKnown()) : $buddyListWhoGotThisBook = $utilisateur->recupererListeAmisQuiPossedent($bouquin) ?>
	<?php if($buddyListWhoGotThisBook != null) : ?>
	<div class="vBibBoite" style="left:-20px;width:100%">
		<div class="vBibBoiteTitre">Vos amis l'ont d&eacute;j&agrave;:</div>
		<div class="vBibBoiteContenu" style="padding-left:20px;">
		<?php foreach($buddyListWhoGotThisBook as $buddyGTB) : ?>
			<a href="userBooks.php?user=<?=$buddyGTB->getID()?>" title="<?=$buddyGTB->getFullname()?>"><img src="<?=$buddyGTB->cheminFichierAvatar()?>"  /></a>
		<?php endforeach; ?>
			<br/>
		</div>
	</div>
	<?php endif; ?>
<?php endif; ?>
</div> <!-- FIN COLONNE DROITE -->

<form action="ficheLivre.php?id=<?=$_GET['id']?>" method="POST" >
<table border="0" cellpadding="0" style="font-size:inherit;border-spacing: 20px 5px;width:580px;">  
   <tr>
	<td rowspan="7" width="180px" align="center"><img src="<?=$bouquin->getAvatarPath()?>" width="169px" height="225px"/> </td>
	<td colspan="2">
	</td>
   </tr>
   <tr>  
       <td align="right" width="50px">  
           <p>Titre:</p>  
       </td>
       
       <td align="left">
	<?php if($edit_mode_available && $edit_mode) :  ?>
		<input type="text" name="titre" value="<?=$bouquin->TitreCourt() ?>" />	
	<?php else : ?>
		<?=$bouquin->TitreCourt()?>
	<?php endif; ?>
	<?php if($edit_mode_available) : ?>
		<a href="ficheLivre.php?id=<?=$_GET['id']?>&edit=1" class="vBibLink" style="float:right;padding-right:5px;"><img src="images/edit.png" width="20px" height="20px"></a>
	<?php endif; ?>
       </td>  
   </tr>  
   <tr>  
       <td align="right">  
           <p>Auteur:</p>  
       </td>  
       <td>
	<?php if($edit_mode_available && $edit_mode) : $auteurs = retournerListeAuteurs(); ?>
		
		<?php if(count($auteurs)) : ?>
		<select name="auteur" onchange="javascript:reloadBookTitles(this);">

		<?php foreach($auteurs as $itemAuteur) : ?>
			<?php if($itemAuteur->getID()==$auteur->getID() ) : ?>
			<option value="<?=$itemAuteur->getID()?>" selected><?=$itemAuteur->fullname()?></option>
			<?php else : ?>
			<option value="<?=$itemAuteur->getID()?>" ><?=$itemAuteur->fullname()?></option>
			<?php endif; ?>
		<?php endforeach; ?>
		</select>
		<?php endif; ?>
	<?php else : ?>
		<a href="ficheAuteur.php?id=<?=$auteur->getID()?>" class="vBibLink"><?=$auteur->fullname()?></a>
	<?php endif; ?>
		
       </td>  
   </tr>
   <tr>  
       <td align="right">  
           <!--p>Date de sortie:</p-->  
       </td>  
       <td>      
       </td>  
   </tr>
<?php if($edit_mode && edit_mode_available) : ?>
	<tr>  
		<td align="right">
			<p>Cycle: </p>
		</td>
			<td>
			<?php if($bouquin->dansUnCycle()) : $cycles=$auteur->retournerListeCycles() ?>
			<input type="checkbox" onchange="javascript:switchSeriesState()" name="seriesEnabled" checked/>
			<select id="seriesList" name="series" enabled>
				<?php if(count($cycles) > 0) : ?>
					<?php foreach($cycles as $cycleItem) : ?>
						<?php if($cycleItem->getID()== $bouquin->getIDCycle() ) : ?>
					<option value="<?=$cycleItem->getID()?>" selected><?=$cycleItem->getTitle()?></option>	
						<?php else : ?>
					<option value="<?=$cycleItem->getID()?>" ><?=$cycleItem->getTitle()?></option>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php else : ?>
				<option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
				<?php endif; ?>
			</select>
			<?php else : ?>
			<input type="checkbox" onchange="javascript:switchSeriesState()" name="seriesEnabled" />
			<select id="seriesList" name="series" disabled>
				<option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
			</select>
			<?php endif; ?>
			</td>
		</tr>
		<tr>  
			<td align="right"><p>Tome:</p></td>  
		<td>
		<?php if($bouquin->hasPrevious()) : $prevBook = $bouquin->getPrevious();?>
			<a href="<?=$prevBook->retournerURL()?>" class="vBibLink">
				<img src="images/arrow2.png" style="-moz-transform:scale(0.5);" title="<?=$prevBook->TitreCourt()?>"/>
			</a>
		<?php endif; ?>
		<input type="text" name="idTome" size="3" value="<?=$bouquin->retournerNumeroTome()?>" />/<?=$bouquin->retournerMaxTomesCycle()?>
			<?php if($bouquin->hasNext()) : $nextBook = $bouquin->getNext() ?>
			<a href="ficheLivre.php?id=<?=$nextBook->getID()?>" class="vBibLink">
				<img src="images/arrow1.png" style="-moz-transform:scale(0.5);" title="<?=$nextBook->TitreCourt()?>"/></a>
		<?php endif; ?>
		</td>  
	</tr>
<?php else : ?>
	<?php if($bouquin->dansUnCycle()) : ?>
	   <tr>  
	       <td align="right">
		<p>Cycle: </p>
		</td>
		<td><a href="cycle.php?id=<?=$bouquin->getIDCycle()?>" class="vBibLink" ><?=$bouquin->retournerNomCycle()?></a></td>
	   </tr>
	   <tr>  
	       <td align="right"><p>Tome:</p></td>  
	       <td>
		<?php if($bouquin->hasPrevious()) : $prevBook = $bouquin->getPrevious();?>
			<a href="<?=$prevBook->retournerURL()?>" class="vBibLink">
				<img src="images/arrow2.png" style="-moz-transform:scale(0.5);" title="<?=$prevBook->TitreCourt()?>"/>
			</a>
		<?php endif; ?>
		<?=$bouquin->retournerNumeroTome()?>/<?=$bouquin->retournerMaxTomesCycle()?>
			<?php if($bouquin->hasNext()) : $nextBook = $bouquin->getNext() ?>
			<a href="ficheLivre.php?id=<?=$nextBook->getID()?>" class="vBibLink">
				<img src="images/arrow1.png" style="-moz-transform:scale(0.5);" title="<?=$nextBook->TitreCourt()?>"/></a>
		<?php endif; ?>
		</td>  
	   </tr>
	<?php else: ?>	  
	   <tr>  
	       <td align="left"><p>&nbsp;</p></td>  
	       <td>&nbsp;</td>  
	   </tr>
	   <tr>  
	       <td align="left"><p>&nbsp;</p></td>  
	       <td>&nbsp;</td>  
	   </tr>
	<?php endif; ?>
   <?php endif; ?>
   <tr>  
       <td align="right"><p>ISBN : </p></td>  
       <td>
       <?php if($edit_mode_available && $edit_mode) : ?>
		<input type=text name="isbn" value="<?=$bouquin->retournerISBN();?>" oninput="validateISBN(this);"/>	
	<?php else : ?>
		<?=$bouquin->retournerISBN();?>
	<?php endif; ?>
       
       </td>  
   </tr>
<tr>
<td align="center">
	<?php if( isUserKnown()) : ?>
		<?php //rendant la page publique, on affiche les interactions avec les autres utilisateurs que si l'utilisateur est connecté ?>
		<a href="" onclick="javascript:popinside_show('fenetreConseilAmi');return false;" class="vBibLink"><img src="images/recommander.png" alt="Conseiller ce livre" title="Conseiller ce livre" style="border:1px solid gray;padding:2px;" width="18" height="18"/></a>
		<a href="" onclick="javascript:DisplayFenetreTag();return false;" class="vBibLink"><img src="images/addTag.png" alt="Taguer" title="Taguer" style="border:1px solid gray;padding:2px;" width="18" height="18"/></a>
		<?php if($bouquin->retournerISBN() != "") : ?>
        		<a href="javascript:togglePreview();" class="vBibLink"><img src="images/open-book.png" alt="Pr&eacute;visualiser" title="Pr&eacute;visualiser" style="border:1px solid gray;padding:2px;" id="toggleGGPreviewButton"/></a>
		<?php endif; ?>
		<?php if($utilisateur->aDansUneListe($bouquin)) : ?>
		<img src="images/simpleList.png" title="Ce Livre est d&eacute;j&agrave; dans votre vBiblio" width="18px" height="18px" style="border:1px solid gray;padding:2px;"/>
		<?php else : ?>
		<img class="ImgAction" onclick="addToMyVBiblio(<?=$bouquin->getID()?>, <?=$utilisateur->getID()?>);" src="images/addToList2.png" title="Ajouter &agrave; ma vBiblio" width="18px" height="18px" style="border:1px solid gray;padding:2px;"/>
		<img class="ImgAction" onclick="addToMyTRL(<?=$bouquin->getID()?>, <?=$utilisateur->getID()?>);" src="images/AddToTRL2.png" title="Ajouter &agrave; ma ToRead List" width="18px" height="18px" style="border:1px solid gray;padding:2px;"/>
		<?php endif; ?>
	<?php endif; ?>
</td>
<td colspan="2"></td>
</tr>
<!-- En dessous de l'avatar-->
</table>

<table border="0" cellpadding="0" >
<tr>
<?php if( isUserKnown()) : ?>
	<td colspan="3">
	
		<div style="display:inline;">
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
			(lang:'fr')
			</script>
			&nbsp;<g:plusone size="small" ></g:plusone>
		</div>
	</td>
<?php else : ?>
	<td colspan="3"></td>
<?php endif; ?>
</tr>
<tr>
	<td class="tdTitleProfil" colspan="3">Description:</td>
</tr>
<tr class="bordered">
	<td colspan="3" style="text-align:justify;font-family: 'Donegal One', cursive;font-size:small">
	
	<?php $description = $bouquin->retournerDescription(); ?>
	
<?php if($edit_mode && $edit_mode_available) : ?>
	<textarea name="desc" rows=10 style="width:100%;"><?=$description?></textarea>
	<input type="submit" value="Enregistrer" style="float:right;" class="vert"/>
	<input type="button" value="Annuler" onclick="window.location='ficheLivre.php?id=<?=$_GET['id']?>'" />
<?php else :  ?>
	<?php if(strlen($description) == 0 ) : ?>
		Nous n'avons pas encore de r&eacute;sum&eacute; pour ce livre.
	<?php else : ?>
		<?=nl2br($description)?>
	<?php endif; ?>
<?php endif; ?>
	</td>

</tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3"></td></tr>
<tr>
	<td class="tdTitleProfil" colspan="3">Note des utilisateurs: 
<?php
	$nb_votes = $bouquin->retournerNbVotants();
?>
	<?php if (isset($nb_votes) && $nb_votes!=0) : $actualRating = $bouquin->retournerNote() ?>
		<?=$actualRating?>/10 
	<?php else : $actualRating=-1 ?>
	<?php endif; ?>
	
	<?php if(intval($nb_votes)==0) : ?> 
	<a style="font-size:xx-small; font-weight:normal">aucun vote</a>
	<?php else : ?>
		<?php if(intval($nb_votes==1)) : ?>
		(1 seul vote)
		<?php else : ?>
		(<?=$nb_votes?> votes)
		<?php endif; ?>
	<?php endif; ?>
	
	</td>
</tr>
<tr class="bordered">
	<td colspan="3">
<?php if( isUserKnown()) : ?>
		Votez : <div onMouseOut="ratingOutGlobal(this)" style="display:inline">
	<?php for($cptRating=0; $cptRating<11; $cptRating++) : ?>
		<?php if($cptRating<=$actualRating) : ?>
			<img src="images/star-rating-full.png" id="rating<?=$cptRating?>" class="vote" height=20 width=20 onMouseOver="javascript:ratingOverFnct(this, <?=$cptRating?>)" onMouseOut="ratingOutFnct(this, 'images/star-rating-full.png')" onclick="javascript:vote(<?=$bouquin->getID()?>, <?=$cptRating?>);" title="<?=$cptRating?> / 10" />
		<?php else : ?>		
			<img src="images/star1.png" id="rating<?=$cptRating?>" class="vote" height=20 width=20 onmouseover="javascript:ratingOverFnct(this, <?=$cptRating?>)" onMouseOut="ratingOutFnct(this, 'images/star1.png')" onclick="javascript:vote(<?=$bouquin->getID()?>, <?=$cptRating?>);" title="<?=$cptRating?> / 10" />
		<?php endif; ?>
	<?php endfor; ?>
		</div>
<?php else : ?>
	Pour pouvoir voter, vous devez &ecirc;tre connect&eacute;. <a href="formLogin.php" class="vBibLink">Se connecter</a>
<?php endif; ?>
	</td>
</tr>
<tr>
	<td colspan="3">
	</td>
</tr>

<?php
	$livres = $bouquin->retournerAutresLivresMemeAuteur();
?>

<?php if(count($livres)>0) : ?>
	<tr>
		<td class="tdTitleProfil" colspan="3">Du m&ecirc;me auteur:</td>
	</tr>
	<tr class="bordered">
		<td colspan="3">

			<div class="vBibList">
				<ul>
				<?php foreach($livres as $livre) : ?>
					<li>
						<a href="ficheLivre.php?id=<?=$livre->getID()?>" class="vBibLink"><?=$livre->titreLong()?></a>
					</li>
				<?php endforeach; ?>
		</td>
	</tr>
<?php endif; ?>	
	
<tr>
	<td class="tdTitleProfil" colspan="3">Tags associ&eacute;s &agrave; ce livre: </td>
</tr>
</table>
</form>
<?php $listeTags = $bouquin->getTagsOrdered(); ?>

<div style="padding-left:20px;">
<?php if(count($listeTags)>0) : ?>
	<ul id="vBiblio_tagcloud">
	<?php foreach($listeTags as $tag) : ?>
		<li class="tag">
			<div style="display:inline-block;">
				<a href="searchByTag.php?idtag=<?=$tag->getID()?>" style="color:#FFF" title="Rechercher d'autres livres"><?=$tag->getName()?></a>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
<?php else : ?>
Aucun tag associ&eacute; &agrave; ce livre
</div>
<?php endif; ?>


<!-- Fin Affichage pour les étiquettes --> 
<div id="fenetreTags" class="insideWindow" >
	<span class="insideWindowTitle">Ajouter des tags</span><span class="insideWindowCloser" onclick="popinside_close('fenetreTags')">X</span>
	<div class="insideWindowContent" style="height:250px;overflow:hidden;padding:10px">
	<!-- Bla bla bla-->
	Livre : <?=$bouquin->TitreCourt()?><br/>
	Auteur : <?=$auteur->fullname()?> <br/>
	<br/>
	<input type="text" id="tags" name="tags" size="36"/><br/>
	<span style="color:gray">Tapez les tags ci-dessus en les s&eacute;parant par des virgules</span>
	<form>
	<input type="hidden" name="idbook" value="<?=$bouquin->getID()?>" />
	</form>
	</div>

	<input type="button" class="vert" value="Enregistrer" onclick="sendTags(<?=$bouquin->getID()?>);" style="float:right; margin-right:5px;"/>
	<input type="button" value="Annuler" onclick="hideFenetreTags();" style="float:right;margin-right:5px;"/>
</div> 

<br/>
<br/>

<!-- Fin Affichage pour les étiquettes --> 


<div id="fenetrePreviewGG" class="insideWindow" style="width:610px;height:555px;left:100px;top:50px;" >
	<span class="insideWindowTitle">Google Livres</span><span class="insideWindowCloser" onclick="togglePreview()">X</span>

	<div id="viewerCanvas" style="padding:5px; width: 600px; height: 500px; display:none"></div>
</div>

<?php else: ?>
	Ce livre ne semble pas exister dans notre r&eacute;f&eacute;rentiel ou a &eacute;t&eacute; supprim&eacute;
<?php endif; ?>
	
<?php if(isUserKnown()==false) : ?>
<div>
<div style="float:left;width:90%;padding-left:20px;text-align:justify">Ce site est destin&eacute; &agrave; vous aider &agrave; g&eacute;rer simplement votre biblioth&egrave;que.	<br/>
	Vous pourrez g&eacute;rer simplement les livres que vous poss&eacute;dez, ceux que vous avez lus, 
  garder une trace de ceux que vous avez pr&ecirc;t&eacute;s ou encore de ceux qu'on vous a pr&ecirc;t&eacute;s...<br/><br/>
	De m&ecirc;me, n'ayez plus d'h&eacute;sitations losque vous vous retrouverez
	chez votre libraire pr&eacute;f&eacute;r&eacute; pour acheter le tome suivant de la 
	s&eacute;rie que vous lisez actuellement... Est-ce le 3&egrave;me que vous avez ? Le quatri&egrave;me ?
  L'aviez-vous achet&eacute; mais pas encore lu ?...<br/><br/>
  Pour toutes ces raisons, vBiblio est la solution ! <br/>
  Si vous poss&eacute;dez d&eacute;j&agrave; un compte, vous pouvez vous <a href="/formLogin.php" class="vBibLink">connecter</a>.<br/>
  Alors, si vous ne poss&eacute;dez pas encore de compte:
<a href="/signup.php" class="btn" style="float:right">Inscrivez-vous maintenant !<br/>
<div style="margin:auto;text-align:left;width:100px;"><span style="font-size:x-small;">C'est gratuit!</span></div></a>
</div>
<div style="clear:both"></div>
</div>

<?php endif; ?>	
	
</div>
</div>
</div>
<?php
	include('footer.php');
?>
<script src="https://encrypted.google.com/books?jscmd=viewapi&bibkeys=ISBN:<?=$bouquin->retournerISBN()?>&callback=processDynamicLinksResponse"></script>
</body>
<?php
  }
}
?>
