<?php

//on regarde son propre profil
if(isset($_GET['user'])){
	$sql_myself = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
	$result_myself = mysql_query($sql_myself);
	$myself = mysql_result($result_myself, 0, 'tableuserid');

	$sql_ami = "SELECT id_user2 FROM vBiblio_amis WHERE id_user1=$myself";
	$result_ami = mysql_query($sql_ami);

	if($result_ami && mysql_num_rows($result_ami) ) {
		$onEstAmi= true;
?>
 <a href="userProfil.php?user=<?=$_GET['user']?>" class="vBibLink">Profil</a> | <a href="userBooks.php?user=<?=$_GET['user']?>" class="vBibLink">Consulter sa biblioth&egrave;que</a> | <a href="userTRL.php?user=<?=$_GET['user']?>" class="vBibLink">Consulter sa ToRead List</a><br/><br/>


<?
	}
	else $onEstAmi = false;
}

?>
