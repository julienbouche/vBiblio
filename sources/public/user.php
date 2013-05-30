<?php
include('../accesscontrol.php');
require_once('../scripts/db/db.php');

dbConnect();

preg_match("/^\/([a-z0-9\-_]+)(?:(?:\/([a-z0-9\-_]+))(?:\/([a-z0-9\-_\.\(\)]+)+)?)?\/?$/i",$_SERVER['REQUEST_URI'],$url);
$user_login = $url[3];


if($user_login != "" ){
  
  $sql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$user_login'";
  $result = mysql_query($sql);
  
	if($result && mysql_num_rows($result)==1){
		$tableID  = mysql_result($result,0,'tableuserid');
		$userExist = true;
	  $sql = "SELECT fullname, active_public_page, sexe FROM vBiblio_user WHERE tableuserid = '$tableID'"; 
	  $result = mysql_query($sql);
	   
	  if($result && mysql_num_rows($result)>0){
		  $userProfilName  = mysql_result($result,0,'fullname');
		  $userSex = mysql_result($result,0,'sexe');
		  if($userSex=="0"){
		     $pronom_User = "Il";
		  }
		  else $pronom_User = "Elle";

		  $isPublicActivated = mysql_result($result,0,'active_public_page');
		  if($isPublicActivated== "1"){
		    $isPublicActivated = true;
			
      }
      else $isPublicActivated = false;
    }
	   
  }
  else $userExist = false;
  	
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">


<html>
<head>
  <?
  if($userExist){
  ?>
	<title>vBiblio - Les livres de <?=$user_login?> </title>
  <?
  }else{
  ?>
  <title>vBiblio - Page publique inexistante</title>
  <?
  }
  ?>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="/css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="/js/public_filter2.js"></script>
	<script type="text/javascript" src="/js/core/vbiblio_ajax.js"></script>
</head>
<body>
<div id="vBibContenu">
<?
	include('../header.php');
?>

	<div id="vBibDisplay">

<?

//si l'utilisateur n'est pas connu, insérer de la pub !
if( isUserKnown()==false ) {
?>
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


<?
}

if( $userExist && $isPublicActivated){
?>	

  
	<div class="vBibList">
	
<?
	$sql = "SELECT vBiblio_book.titre As titre, numero_cycle, vBiblio_author.nom as nom, vBiblio_author.prenom as prenom, vBiblio_poss.lu, vBiblio_poss.possede, vBiblio_poss.pret, vBiblio_poss.id_book as id_book, vBiblio_author.id_author FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user WHERE vBiblio_poss.userid = vBiblio_user.tableuserid AND vBiblio_user.tableuserid='".$tableID."' AND vBiblio_poss.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author"; 
	
	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result)>0 ){
		$cpt=0;
		
		//echo "<ul>";
		echo "<input type=\"text\" name=\"filtreSaisie\" title=\"Rechercher...\" onkeyup=\"javascript:filter();\" />";
		echo "<table class=\"vBiblioBooksTable\">";
		echo "<thead>";
		echo "<td onclick=\"javascript:sortByTitle($tableID);\" style=\"width:60%\">Titre</td><td style=\"width:20%\" onclick=\"javascript:sortByAuthor($tableID);\">Auteur</td><td style=\"width:5%\">$pronom_User l'a</td><td style=\"width:5%\">lu</td><td style=\"width:5%\">pr&ecirc;t&eacute;</td>";
		echo "</thead>";
		echo "<tbody name=\"vBiblioBookList\">";
		while($row=mysql_fetch_assoc($result)){
      if($cpt%2==0){
				//$style="vBibEven";
				$style = "vBiblioBookEven";
			}
			else{
				//$style="vBibOdd";
				$style="vBiblioBookOdd";
			}
			$cpt++;

			$titre = $row['titre'];
			$nom_auteur = $row['nom'];
			$prenom_auteur = $row['prenom'];
			$possede = $row['possede'];
			$lu = $row['lu'];
			$prete = $row['pret'];
			$idbook = $row['id_book'];
			$idAuthor = $row['id_author'];
			$num_in_cycle = $row['numero_cycle'];
			
			$sql_cycle= "SELECT vBiblio_cycle.titre, nb_tomes FROM vBiblio_cycle, vBiblio_book WHERE vBiblio_book.id_cycle=vBiblio_cycle.id_cycle AND vBiblio_book.id_book=$idbook";
			
			echo "<tr class=\"$style\">";
			echo "<td class=\"vBibBookTitle\">";
			echo "<a href=\"/ficheLivre.php?id=$idbook\" class=\"vBibLink\" name=\"bookTitle\">";
			$cycles = mysql_query($sql_cycle);
			if($cycles && mysql_num_rows($cycles) > 0) {
				$cycle = mysql_result($cycles, 0, 'titre');
				$nb_tomes= mysql_result($cycles, 0, 'nb_tomes');
				//echo "$cycle, Tome $num_in_cycle ($nb_tomes): ";
				echo "$cycle, Tome $num_in_cycle : ";
			}

			echo "$titre</a>";
			echo "</td>";
			echo "<td><a href=\"/ficheAuteur.php?id=$idAuthor\" class=\"vBibLink\" name=\"authorName\">$prenom_auteur $nom_auteur</a></td>";
			echo "<td style=\"text-align:center;\"><input name=\"".$idbook."Possede\" type=\"checkbox\" title=\"Il/Elle l'a\" onclick=\"return false\"  ";
			if($possede=="1") echo "checked";
			echo " /></td>\n";
			
			echo "<td style=\"text-align:center;\"><input name=\"".$idbook."Lu\" type=\"checkbox\" title=\"Il/Elle l'a lu\" onclick=\"return false\"  ";
			if($lu=="1") echo "checked";
			echo " /></td>\n";
			echo "<td style=\"text-align:center;\"><input name=\"".$idbook."Pret\" type=\"checkbox\" title=\"Il/Elle l'a d&eacute;j&agrave; pr&ecirc;t&eacute;\" onclick=\"return false\" ";
			if($prete=="1") echo "checked";
			echo " /></td>\n";

			echo "</tr>";

		}
		echo "</tbody>";
		echo "</table>";


	}else echo "Cet utilisateur ($userProfilName)n'a pas encore ajout&eacute; de livres &agrave; sa biblioth&egrave;que virtuelle.";
?>



	</div>
	
	<?
}else {
  if(!$userExist){
    echo "L'utilisateur ($user_login) que vous recherchez n'existe pas ou bien il/elle a supprimé son compte.";
	
  }
  else echo $userProfilName." n'a pas activé sa page publique.";

}
  
  ?>
</div>

<?
	include('../footer.php');
?>
</div>
</body>
</html>
