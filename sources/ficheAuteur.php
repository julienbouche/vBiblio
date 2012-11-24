<?php
include('accesscontrol.php');
//include('scripts/db/db.php');
include('scripts/dateFunctions.php');
require_once('classes/Utilisateur.php');
require_once('classes/Livre.php');

//dbConnect();
checkSecurity();


$uid= $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

if(isset($_GET['id']) ){
	$IDAuthor=$_GET['id'];
	$auteur = new Auteur($IDAuthor);
	
	if($auteur->exists()){

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title><?=$auteur->fullname()?> sur vBiblio</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">

<div class="vBibBoite" style="float:right;max-width:200px;">
		<div class="vBibBoiteTitre">Plus d'informations sur l'auteur :</div>
		<div class="vBibBoiteContenu" style="padding-left:20px;">
	<li><a class="vBibLink" href="http://fr.wikipedia.org/wiki/Special:Search?search=<?echo str_replace(' ','+',$auteur->fullname());?>">Wikip&eacute;dia</a></li>
	<li><a class="vBibLink" href='http://www.google.fr/#hl=fr&q="<? echo "".str_replace(' ','+',$auteur->fullname());?>"'>Google</a></li>
	<li><a class="vBibLink" href="http://recherche.fnac.com/r/<?=$auteur->fullname()?>?SCat=2!1">Fnac.com</a></li>
	</div>
</div>


<table border="0" cellpadding="0" style="font-size:inherit;border-spacing: 20px 5px;max-width:600px;">  
   <tr>
	<td rowspan="13"><img src="images/avatars/no_avatar.png" width="160px" height="160px"/> </td><td class="tdTitleProfil" colspan="2">Informations g&eacute;n&eacute;rales:</td>
   </tr>
   <tr>  
       <td align="left">  
           <p>Nom:</p>  
       </td>  
       <td align="left">
	<?=$auteur->retournerNom()?>
       </td>  
   </tr>  
   <tr>  
       <td align="left">  
           <p>Pr&eacute;nom:</p>  
       </td>  
       <td>
	<?=$auteur->retournerPrenom()?>
       </td>  
   </tr>
<?
/*if(isset($authorNick) && $authorNicl != "" ) {
?>
 <tr>  
       <td align="left">  
           <p>Nom &agrave; la ville:</p>  
       </td>  
       <td align="left">   
	<?=$authorNick?>
       </td>  
   </tr>  
<?
}
else{*/
?>
 <tr>  
       <td align="left">  
           <p></p>  
       </td>  
       <td align="left">
       </td>  
   </tr>  
<?
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
       <td align="left"></td>  
       <td></td>  
   </tr>
   <tr>  
       <td align="left"></td>  
       <td></td>  
   </tr>   
   <tr>  
       <td align="left"></td>  
       <td></td>  
   </tr>

   <tr>  
       <td align="left"></td>  
       <td></td>  
   </tr>
   <tr>  
       <td align="left"></td>  
       <td></td>  
   </tr>

   <tr>  
       <td align="left"></td>  
       <td></td>  
   </tr>

   <tr>  
       <td align="left"></td>  
       <td></td>  
   </tr>

   <tr>  
       <td align="left"></td>  
       <td></td>  
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
<?
	$description = $auteur->retournerDescription();
	if($description==""){
		echo "Nous n'avons pas encore de description pour cet auteur.";
	}
	else echo $description;

?>

		</td>

	</tr>

	<tr>
		<td class="tdTitleProfil" colspan="3">Les livres de l'auteur:</td>
	</tr>
	<tr>
		<td colspan="3">
<?
//afficher la liste des livres de l'auteur
$livres = $auteur->retournerListeLivres();

if(count($livres) > 0){
	echo "<div class=\"vBibList\">";
	echo "<ul>";
	
	foreach($livres as $livre) {
		echo "<li>\n<a href=\"ficheLivre.php?id=".$livre->getID()."\" class=\"vBibLink\">";	
		echo $livre->titreLong()."</a></li>\n";
	}
	echo "</ul>";
	echo "</div>";
}
?>
		</td>
	</tr>
</table>

<br/>
<br/>


<?
	
	}
	else header("Location: pageErreur.php");
}
?>

</div>
<?
	include('footer.php');
?>

</div>

</body>
</html>
