<?php
include('accesscontrol.php');
include('scripts/db/db.php');

checkSecurity();
dbConnect();

//ajout du bouquin si l'utilisateur a décidé d'ajouter un livre
if(isset($_POST['addBookTitle']) && $_POST['addBookTitle'] && isset($_POST['auteur']) && $_POST['auteur'] ){
	$title = $_POST['addBookTitle'];
	$id_auteur =  $_POST['auteur'];
	$desc = trim($_POST['desc']);
	
	if (isset($_POST['seriesEnabled']) and isset($_POST['idTome']) and isset($_POST['series']) and $_POST['idTome']!=''  ){
		$idtome = $_POST['idTome'];
		$serie = $_POST['series'];
		$sql = "INSERT INTO vBiblio_book (titre, id_author, id_cycle, numero_cycle, description) VALUES ('$title', '$id_auteur', '$serie', '$idtome', '$desc');";
	}
	else $sql = "INSERT INTO vBiblio_book (titre, id_author, description) VALUES ('$title', '$id_auteur', '$desc');";

	mysql_query($sql);
}
else {
	if (isset($_POST['addBookTitle']) ) {
		$error = "<a style=\"color:red;\">Vous n'avez pas correctement saisi l'un des champs.</a>";
	}
}


$uid = $_SESSION['uid'];
$sql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$row = mysql_fetch_assoc($result);
	$mytableId = $row['tableuserid'];
}



header('Access-Control-Allow-Origin: http://xisbn.worldcat.org/');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>

	<title>vBiblio - Vos Livres</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
<script language="javascript">
<!-- 
function createXHR(){
	var xhr;
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}

	//ie
	else if (window.ActiveXObject) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	} 
	return xhr;
}	


function enableSeries(){
	if(document.getElementsByName('series')[0].disabled){
		document.getElementsByName('series')[0].disabled = false;
		document.getElementsByName('idTome')[0].disabled = false;
		//charger la liste des series de l'auteur
		populateSeriesList(document.getElementsByName('auteur')[0]);		
	}else{ //on désactive le choix de la série
		document.getElementsByName('series')[0].disabled = true;
		document.getElementsByName('idTome')[0].disabled = true;
		//on met un élément vide
		document.getElementsByName('series')[0].innerHTML="<option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
	}
}

function reloadBookTitles(object){
	populateSeriesList(object);
}

function populateSeriesList(authorChoice){
	idAuteur = authorChoice.options[authorChoice.selectedIndex].value;
	

	xhr = createXHR();
	if(xhr!=null) {
		xhr.open("GET","http://localhost/vBiblio/scripts/db/reqAuthorSeries.php?author="+idAuteur, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans les series de l'auteur
				if(document.getElementsByName('series')[0].disabled!=true){
					seriesList = document.getElementById('seriesList');
					seriesList.innerHTML = xhr.responseText;			
				}
			}
		};
		xhr.send(null);
	}	

}


function validateFORM(){
	

	obj = document.getElementsByName('isbn')[0];
	chaine= obj.value;	
	xhr = createXHR();
	if(xhr !=null ) {
		alert("http://xisbn.worldcat.org/webservices/xid/isbn/"+chaine+"/metadata.js");
		xhr.open("GET","http://xisbn.worldcat.org/webservices/xid/isbn/"+chaine+"/metadata.js", true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans les series de l'auteur
				alert(xhr.responseText);
				/*if(document.getElementsByName('series')[0].disabled!=true){
					seriesList = document.getElementById('seriesList');
					seriesList.innerHTML = xhr.responseText;			
				}*/
			}
		};
		xhr.send(null);
	}	
	
	return false;
	
}

function validateNum(chaine){
	retour = false;
	if(chaine !=''){
		reg = /^[0-9]+$/;
		if(reg.test(chaine))retour = true;
		else alert("Vous ne devez entrer que des chiffres pour le numero du tome");
	}
	else{
		alert('Vous devez renseigner toutes les valeurs.');
		retour = false;
	}
	return retour;
}


-->
</script>
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">
<?
	include('ssmenuHelpUs.php');
?>
<!--
Vous avez la possibilit&eacute; d'ajouter un livre directement si celui-ci n'est pas d&eacute;j&agrave; pr&eacute;sent dans notre r&eacute;f&eacute;rentiel:
	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="return validateFORM();">
		<fieldset>
			<table style="font-size:inherit;">
			<tr><td>Auteur :</td><td> <select name="auteur" onchange="javascript:reloadBookTitles(this);">

<?
	$sql = "SELECT nom, prenom, id_author FROM vBiblio_author ORDER BY nom ASC";
	$result = mysql_query($sql);
	if($result && mysql_num_rows($result)>0){
		while($row = mysql_fetch_assoc($result)){
			$nom = $row['nom'];
			$prenom = $row['prenom'];
			$idAut = $row['id_author'];
			echo "<option value=\"$idAut\" >$prenom $nom</option>";
		}

	}

?>
			</select></td></tr>
			<tr><td>ISBN :</td><td><input type="text" size="25" name="isbn"/></td></tr>
			<tr><td>Titre :</td><td> <input type="text" max-length="100" size="25" name="addBookTitle" /></td></tr>
			<tr><td valign="top">Description :</td><td> <textarea  cols="50" rows="10" name="desc" ></textarea></tr>
			<tr><td>S&eacute;rie:</td><td> <input type="checkbox" onchange="javascript:enableSeries()" name="seriesEnabled"/>
			Titre : <select id="seriesList" name="series" disabled><option></option><option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option></select> 
			Num&eacute;ro du tome: <input type="text" name="idTome" disabled/></td></tr>
			<tr><td></td><td><input type="submit" value="Ajouter"/></td></tr>
			</table>
		</fieldset>
	</form>
-->
Prochainement disponible...
<?
	

	mysql_close();
?>
	
</div>
<?
	include('footer.php');
?>

</div>

</body>
</html>

