<?php
include('accesscontrol.php');
//include('scripts/db/db.php');
include('scripts/common.php');
include('scripts/dateFunctions.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');

//connexion à la bd
//dbConnect();

//ouverture des pages livres sans être connectés
//checkSecurity();

$uid= $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

//$userTUID = getTableUserId($uid);

if(isset($_GET['id']) ){
	$bouquin = new Livre($_GET['id']);
	$auteur = $bouquin->retournerAuteur();
	if(!$bouquin->exists()){
		header ('Location: pageErreur.php');
	}
	else{

?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title><?echo $bouquin->titreLong()?>: <? echo $auteur->fullname()?> sur vBiblio</title> 
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
	
	<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
	<link rel="stylesheet" type="text/css" href="js/jquery/lib/thickbox.css" />
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/popInside.css" media="screen" />	

	<link href='http://fonts.googleapis.com/css?family=Lancelot' rel='stylesheet' type='text/css'>
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">

<!-- FENETRE POP INSIDE -->
<div id="fenetreConseilAmi" class="insideWindow">
	<span class="insideWindowTitle">Conseiller &agrave; des amis</span><span class="insideWindowCloser" onclick="popinside_close('fenetreConseilAmi')">X</span>
	<div class="insideWindowContent" >
	<form >
	<input type="hidden" name="idbook" value="<?=$_GET['id']?>" />
<?
	$buddys = $utilisateur->recupererListeAmis();
	
	if(count($buddys)>0){
		echo "<table class=\"vBibTablePret\" id=\"formAdviseFriends\">\n";
		$i = 0;
		foreach($buddys as $buddy){
			//TODO eventuellement griser les utilisateurs ayant déjà "noté" qq part ce bouquin (TRL ou vBiblio).
			$utilisateurPossedeDeja = $buddy->aDansUneListe($bouquin);
			
			echo "<tr";
			
			if($utilisateurPossedeDeja) echo " title=\"Cet utilisateur connait d&eacute;j&agrave; ce livre\" ";
			
			echo "><td style=\"width:10%;\"><a class=\"vBibLink\" href=\"userProfil.php?user=".$buddy->getID()."\"><img src=\"".$buddy->cheminFichierAvatar()."\" /></a></td><td style=\"width:90%;text-align:left;\"><a class=\"vBibLink\" href=\"userProfil.php?user=".$buddy->getID()."\">".$buddy->getFullname()."</a></td>";
			echo "<td><input type=\"checkbox\" name=\"friend$i\" value=\"".$buddy->getID()."\" ";
			
			if($utilisateurPossedeDeja) echo "disabled";
			
			echo "/></td>";
			echo "</tr>";
			$i++;
		}
		echo "</table>\n";
	}

?>

	</form>
	</div>
	<input type="submit" class="vert" value="Valider" onclick="submitForm(document.getElementById('formAdviseFriends'), <?=$_GET['id']?>, <?=$utilisateur->getID()?>)" style="float:right;margin-right:5px"/>
	<input type="button" class="gris" value="Annuler" onclick="popinside_close('fenetreConseilAmi')" style="float:right;margin-right:5px"/>
</div> 

<!-- FIN FENETRE POP INSIDE -->



<?
if(isset($_GET['id']) ){
	
	//on a initialise la variable $bouquin plus haut !


?>

<div style="float:right;max-width:250px;">
	<div class="vBibBoite" style="left:-20px;width:100%">
			<div class="vBibBoiteTitre">Rechercher ce livre :</div>
			<div class="vBibBoiteContenu" style="padding-left:20px;">
	<li><a class="vBibLink" href="http://www.leboncoin.fr/livres/offres/ile_de_france/occasions/?f=a&th=1&q=<?echo str_replace(' ','+',$bouquin->TitreCourt())." ".$auteur->fullname();?>">Leboncoin.fr</a></li>
	<li><a class="vBibLink" href="http://fr.wikipedia.org/wiki/Special:Search?search=<?echo str_replace(' ','+',$bouquin->TitreCourt())." ".$auteur->fullname();?>">Wikip&eacute;dia</a></li>
	<li><a class="vBibLink" href="http://www.amazon.fr/s/ref=nb_sb_noss?url=search-alias%3Dstripbooks&field-keywords=<?echo "".str_replace(' ','+',$bouquin->TitreCourt());?>">Amazon.fr</a></li>
	<li><a class="vBibLink" href="http://www.priceminister.com/nav/Livres/kw/<?echo "".str_replace(' ','+',urlencode($bouquin->TitreCourt()));?>">Price Minister</a></li>
	<li><a class="vBibLink" href='http://www.google.fr/#hl=fr&q="<?echo "".str_replace(' ','+',$bouquin->TitreCourt())."\" ".$auteur->fullname();?>'>Google</a></li>
	<li><a class="vBibLink" href="http://recherche.fnac.com/r/<?=$bouquin->TitreCourt()?>?SCat=2!1">Fnac.com</a></li>
	</div>
	</div>

<?
	if( isUserKnown()){
		$buddyListWhoGotThisBook = $utilisateur->recupererListeAmisQuiPossedent($bouquin);
		if($buddyListWhoGotThisBook != null){
?>
	<div class="vBibBoite" style="left:-20px;width:100%">
			<div class="vBibBoiteTitre">Vos amis l'ont d&eacute;j&agrave;:</div>
			<div class="vBibBoiteContenu" style="padding-left:20px;">
			<?
			foreach($buddyListWhoGotThisBook as $buddyGTB){
			?>
				<a href="userBooks.php?user=<?=$buddyGTB->getID()?>" title="<?=$buddyGTB->getFullname()?>"><img src="<?=$buddyGTB->cheminFichierAvatar()?>"  /></a>
			<?
			}
			?>
			<br/>
	</div>
	</div>
<?
		}
	}
?>
</div>


<table border="0" cellpadding="0" style="font-size:inherit;border-spacing: 20px 5px;max-width:600px;">  
   <tr>
	<td rowspan="7"><img src="images/covers/no_cover2.jpg" width="169px" height="225px"/> </td><td class="tdTitleProfil" colspan="2">Informations :</td>
   </tr>
   <tr>  
       <td align="left">  
           <p>Titre:</p>  
       </td>  
       <td align="left">
	<?=$bouquin->TitreCourt()?>
       </td>  
   </tr>  
   <tr>  
       <td align="left">  
           <p>Auteur:</p>  
       </td>  
       <td>
	<? echo "<a href=\"ficheAuteur.php?id=".$auteur->getID()."\" class=\"vBibLink\">".$auteur->fullname()."</a>";?>
       </td>  
   </tr>
   <tr>  
       <td align="left">  
           <!--p>Date de sortie:</p-->  
       </td>  
       <td>      
       </td>  
   </tr>

<?
	if($bouquin->dansUnCycle()){
?>
   <tr>  
       <td align="left">
	<p>Cycle: </p>
	</td>
	<td><?=$bouquin->retournerNomCycle()?></td>
   </tr>
   <tr>  
       <td align="left"><p>Tome:</p></td>  
       <td>
<?
	if($bouquin->hasPrevious()){
		$nextBook = $bouquin->getPrevious();
		echo "<a href=\"".$nextBook->retournerURL()."\" class=\"vBibLink\"><img src=\"images/arrow2.png\" style=\"-moz-transform:scale(0.5);\" title=\"".$nextBook->TitreCourt()."\"/></a>\n";
	}
?>
<?=$bouquin->retournerNumeroTome()?>/<?=$bouquin->retournerMaxTomesCycle()?>
<?
	if($bouquin->hasNext()){
		$nextBook = $bouquin->getNext();
		echo "<a href=\"ficheLivre.php?id=".$nextBook->getID()."\" class=\"vBibLink\"><img src=\"images/arrow1.png\" style=\"-moz-transform:scale(0.5);\" title=\"".$nextBook->TitreCourt()."\"/></a>\n";
	}
?>
	</td>  
   </tr>
<?

	}
	else{

?>	  
      
   <tr>  
       <td align="left"><p>&nbsp;</p></td>  
       <td>&nbsp;</td>  
   </tr>
   <tr>  
       <td align="left"><p>&nbsp;</p></td>  
       <td>&nbsp;</td>  
   </tr>
<?

	}
	
?>
   <tr>  
       <td align="left"><p>ISBN : </p></td>  
       <td><?=$bouquin->retournerISBN();?></td>  
   </tr>
<tr>
<td colspan="3">
<?
	//rendant la page publique, on affiche les interactions avec les autres utilisateurs que si l'utilisateur est connecté
	if( isUserKnown()){
	?>
	<a href="" onclick="javascript:popinside_show('fenetreConseilAmi');return false;" class="vBibLink">Conseiller &agrave; un ami</a>
	&nbsp;&nbsp;&nbsp;<a href="" onclick="javascript:DisplayFenetreTag();return false;" class="vBibLink">Taguer</a> 
	<?
	}
?>
</td>
</tr>
<!-- En dessous de l'avatar-->
<tr>
<?
//rendant la page publique, on affiche les interactions avec les autres utilisateurs que si l'utilisateur est connecté
	//est-ce que l'utilisateur possède déjà ce livre 
	if( isUserKnown()){
		if($utilisateur->aDansUneListe($bouquin)){

			?>
				<td colspan="3"><img class="ImgAction" src="images/inList.png" title="Ce Livre est d&eacute;j&agrave; dans votre vBiblio" width="20px" height="20px"/>
			<?
				}
				else{

			?>
			<td colspan="3"><img class="ImgAction" onclick="addToMyVBiblio(<?=$bouquin->getID()?>, <?=$utilisateur->getID()?>);" src="images/AddToList.png" title="Ajouter &agrave; ma vBiblio" width="20px" height="20px"/>&nbsp;<img class="ImgAction" onclick="addToMyTRL(<?=$bouquin->getID()?>, <?=$utilisateur->getID()?>);" src="images/AddToTRL.png" title="Ajouter &agrave; ma ToRead List" width="20px" height="20px"/>

	<?
		}
	}
	?>
		<div style="display:inline;">
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
			(lang:'fr')
			</script>
			&nbsp;<g:plusone size="small" ></g:plusone>
		</div>
	</td>
</tr>
<tr>
	<td class="tdTitleProfil" colspan="3">Description:</td>
</tr>
<tr>
	<td colspan="3" style="text-align:justify;font-family: 'Lancelot', cursive;font-size:18px;">
<?
	$description = $bouquin->retournerDescription();
	if($description==""){
		echo "Nous n'avons pas encore de r&eacute;sum&eacute; pour ce livre.";
	}
	else echo nl2br(htmlentities($description));

?>

</td>

</tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3"></td></tr>
<tr><td colspan="3"></td></tr>
<tr>
	<td class="tdTitleProfil" colspan="3">Note des utilisateurs: <?
	$nb_votes = $bouquin->retournerNbVotants();
if (isset($nb_votes) && $nb_votes!=0)
	echo "".$bouquin->retournerNote()."/10 ";
else echo "";
if(intval($nb_votes)==0) echo "(aucun vote)";
else if(intval($nb_votes==1) ) echo "(1 vote)";
else echo "($nb_votes votes)";
?></td>
</tr>
<tr><td colspan="3">
<?
if( isUserKnown()){
?>
		Votez : 
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 0);" title="Nul !" />
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 1);" title="Tr&egrave;s mauvais" />
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 2);" title="Mauvais" />
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 3);" title="Pas terrible" />
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 4);" title="Bof..." />
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 5);" title="Moyen" />
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 6);" title="Pas mal" />
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 7);" title="Bon" />
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 8);" title="Tr&egrave;s bon" />
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 9);" title="Excellent!" />
			<img src="images/star1.png" class="vote" height=20 width=20 onclick="javascript:vote(<?=$bouquin->getID()?>, 10);" title="G&eacute;nialissime!" />	
	<?
	}else {
		?>
		Pour pouvoir voter, vous devez &ecirc;tre connect&eacute;. <a href="formLogin.php" class="vBibLink">Se connecter</a>
		<?
	}
	?>
</td></tr>
<tr><td colspan="3">

</td></tr>
<tr>
	<td class="tdTitleProfil" colspan="3">Autres livres du m&ecirc;me auteur:</td>
</tr>
<tr>
<td colspan="3">

<?

	$livres = $bouquin->retournerAutresLivresMemeAuteur();

	if(count($livres)){
		echo "	<div class=\"vBibList\">";	
		echo "<ul>";
		
		foreach($livres as $livre){
			echo "<li>\n<a href=\"ficheLivre.php?id=".$livre->getID()."\" class=\"vBibLink\">";
		
			echo $livre->titreLong()."</a></li>\n";
		}
		echo "</ul>";
		echo "</div>";
	}
?>
</td>
</tr>
<tr>
	<td class="tdTitleProfil" colspan="3">Tags associ&eacute;s &agrave; ce livre: </td>
</tr>
</table>




<?
include("scripts/libTags.php");
writeTags($bouquin->getID());
?>


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

	<input type="button" class="vert" value="Enregistrer" onclick="sendTags(<?=$bouquin->getID()?>);"; style="float:right;margin-right:5px;"/>
	<input type="button" value="Annuler" onclick="hideFenetreTags();" style="float:right;margin-right:5px;"/>
</div> 

<br/>
<br/>


<?

}
else echo "Ce livre ne semble pas exister dans notre r&eacute;f&eacute;rentiel ou a &eacute;t&eacute; supprim&eacute;.";
?>
	
<?
if(isUserKnown()==false){
?>
<div >
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

<?
}
?>	
	
</div>
<?
	include('footer.php');
?>

</div>

</body>
<?
  }
}
?>
