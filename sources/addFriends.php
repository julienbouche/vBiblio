<?php
include('accesscontrol.php');
include('scripts/db/db.php');
include('scripts/friendsTools.php');

require_once('classes/Utilisateur.php');

dbConnect();
checkSecurity();


$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Rechercher de nouveaux contacts</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<script type="text/javascript" src="js/core/vbiblio_ajax.js" ></script>
	<script type="text/javascript" src="js/gui/friendsRequest_gui.js" ></script>
</head>
<body>
<div id="vBibContenu">
<?
	include('header.php');
?>

	<div id="vBibDisplay">

	<div class="BookmarkN1">
		<div class="BMCorner"></div>
		<div class="BMCornerLink"></div>
		<div class="BMMessage">Retrouver vos amis:</div>
	</div>

	
	<br/>
	<br/><br/><br/><br/>
	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
	<fieldset style="width:430px;">
	<table style="font-size:inherit">
	<tr>
  <td>Par son <select name="attribut">
		<option value="fullname" <?if(isset($_POST['attribut']) and $_POST['attribut']=="fullname") echo "selected";?>>Nom</option>
		<option value="userid" <?if(isset($_POST['attribut']) and $_POST['attribut']=="userid") echo "selected";?>>Pseudo</option>
		<option value="email" <?if(isset($_POST['attribut']) and $_POST['attribut']=="email") echo "selected";?>>Email</option>
	</select> : <input type="text" max-length="100" size="25" name="searchText" value="<?=$_POST['searchText']?>"/></td><td></td>
  </tr>
  <tr>
    <td>Afficher <select name="listSize"><option value="10">10</option><option value="20">20</option><option value="40">40</option><option value="100fr">100</option></select> r&eacute;sultats au max.</td><td></td>
  </tr>
  <tr>
	<td></td><td><input type="submit" value="Rechercher" /></td></tr>
	</table>
	</fieldset>
	</form>

<?
	if( (isset($_POST['attribut']) && isset($_POST['searchText']) )|| (isset($_GET['attr'])) ) {
		
		if( isset($_POST['attribut'] ) ) {
			$searchAttr = $_POST['attribut'];
		}
		else $searchAttr = $_GET['attr'];

		if( isset($_POST['searchText']) ){
			$squery = $_POST['searchText'];
		}
		else $squery = $_GET['q'];

		if(isset($_GET['start']) ){
			$start = intval($_GET['start']);
		}
		else $start = 0;
		if(isset($_POST['listSize']) ) {
			$delta = $_POST['listSize'];
		}
		else {
			$delta = $_GET['s'];
		}
		$end = intval($delta);
		

		$sql = "SELECT vBiblio_user.userid FROM vBiblio_user WHERE ".$searchAttr." like '%".$squery."%' AND userid<>'".$utilisateur->getPseudo()."' AND tableuserid NOT IN (SELECT id_user2 FROM vBiblio_amis, vBiblio_user WHERE id_user1=vBiblio_user.tableuserid AND vBiblio_user.userid='".$utilisateur->getPseudo()."') and tableuserid<>0 LIMIT $start,$end";
		
	
		$result = mysql_query($sql);
		
		if($result && mysql_num_rows($result)>0){
			echo "<table style=\"width:100%;font-size:inherit;\">";
			while($row = mysql_fetch_assoc($result)){
				//$searchFullname = $row['fullname'];
				$buddy = new Utilisateur($row['userid']);
				//$tableuserid = $row['tableuserid'];
				echo "<tr><td style=\"width:10%;\"><img src=\"images/buddy.png\"</td><td style=\"width:30%;text-align:left;\">".$buddy->getFullname()."</td><td style=\"width:60%;text-align:left;\"><span name=\"feedback".$buddy->getID()."\"></span>";
				if(friendRequestExist($utilisateur->getID(), $buddy->getID()))		
					echo "Une demande a &eacute;t&eacute; envoy&eacute;e.";
				else{ 
					if(friendRequestExist($buddy->getID(), $utilisateur->getID())){
						$idReq = retrieveIDRequest($buddy->getID(), $utilisateur->getID());
						echo "<input id=\"Req$idReq\" type=\"button\" class=\"vert\" value=\"Accepter la demande\" onclick=\"javascript:acceptRequest($idReq, ".$buddy->getID().", ".$utilisateur->getID().", false);return false;\" style=\"\"/>";
					}
					else echo "<input type=\"button\" value=\"Ajouter comme ami\" onclick=\"javascript:sendBuddyRequest(this,".$utilisateur->getID().", ".$buddy->getID().");return false;\"/>";
				}
				echo "</td></tr>";
			}
			echo "</table>";

		}
		else echo "Aucun utilisateur ne correspond &agrave; votre recherche";

	}

	$sql = "SELECT COUNT(*) as nb FROM vBiblio_user WHERE ".$searchAttr." like '%".$squery."%' AND userid<>'".$utilisateur->getPseudo()."' AND tableuserid NOT IN (SELECT id_user2 FROM vBiblio_amis, vBiblio_user WHERE id_user1=vBiblio_user.tableuserid AND vBiblio_user.userid='".$utilisateur->getPseudo()."') and tableuserid<>0";
	
	$result = mysql_query($sql);
	if($start > 0 ) echo "<a href=\"".$_SERVER['PHP_SELF']."?attr=$searchAttr&q=$squery&start=".($start - $end)."&s=$delta\" style=\"float:left;\"class=\"vBibLink\" title=\"Pr&eacute;c&eacute;dent\"><<</a>";
	
	
	if($result && mysql_num_rows($result)>0 ){
		$row = mysql_fetch_assoc($result);
		$nb = intval($row['nb']);

		if($start + $end < $nb) echo "<a href=\"".$_SERVER['PHP_SELF']."?attr=$searchAttr&q=$squery&start=".($start + $end)."&s=$delta\" style=\"float:right;\" class=\"vBibLink\" title=\"Suivant\">>></a>";
	}

?>

</div>

<?
	include('footer.php');
?>
</div>

</body>
</html>
