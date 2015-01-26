<?php
require_once('accesscontrol.php');
require_once('scripts/common.php');

session_start();
checkSecurityLoginPage();

if( !isset($_POST['uid']) ){

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">


<html>  
<head>  
	<title>vBiblio - Accueil Connexion</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="keywords" content="gerer votre bibliotheque, site web, gestion, gerer vos livres, livres, bibliotheque virtuelle, prêts" />  
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/github_ribbon.css" media="screen" />
</head>  
<body>

<div id="vBibContenu">
<?php include('header.php'); ?>
	<div id="vBibDisplay">
	<br/><br/><br/><br/>
	<div style="float:left;width:350px;padding-left:20px;text-align:justify">Ce site est destin&eacute; &agrave; vous aider &agrave; g&eacute;rer simplement votre biblioth&egrave;que.	<br/>
	Vous pourrez g&eacute;rer simplement les livres que vous poss&eacute;dez, ceux que vous avez lus, 
  garder une trace de ceux que vous avez pr&ecirc;t&eacute;s ou encore de ceux qu'on vous a pr&ecirc;t&eacute;s...<br/><br/>
	De m&ecirc;me, n'ayez plus d'h&eacute;sitations losque vous vous retrouverez
	chez votre libraire pr&eacute;f&eacute;r&eacute; pour acheter le tome suivant de la 
	s&eacute;rie que vous lisez actuellement... Est-ce le 3&egrave;me que vous avez ? Le quatri&egrave;me ?
  L'aviez-vous achet&eacute; mais pas encore lu ?...<br/><br/>
  Pour toutes ces raisons, vBiblio est la solution !
   <br/>
	Si vous ne poss&eacute;dez pas encore de compte: 
<a href="signup.php" class="btn">Inscrivez-vous maintenant !<br/><div style="margin:auto;text-align:left;width:100px;"><span style="font-size:x-small;">C'est gratuit!</span></div></a>

	 <!--Si vous ne poss&eacute;dez pas encore de compte, <a href="signup.php" class="vBibLink">inscrivez-vous</a>.-->
	 <br/><br/><br/>
  </div>
	
  <div name="loginBox" class="loginBox">
		<div class="title"><b>Connexion &agrave; vBiblio</b></div>
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>" style="display:table-cell;position:relative;padding-left:40px;text-align: right;"> 
		<p>Login/e-mail:
		<input name="uid" type="text" maxlength="100" size="25" /></p>
		<p>Mot de passe:
		<input name="pwd" type="password" maxlength="100" size="25" /></p>
		<p style="font-size:x-small;color:#999;">Rester connect&eacute; ? <input type="checkbox" name="creercookie" <?php if(isset($_COOKIE["vbiblio"]))echo "checked";?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="resetPwd.php" class="vBibLink" style="font-size:x-small;color:#999;padding-right:60px">Mot de passe oubli&eacute; ?</a></p>
		<input type="submit" name="submitok" class="blue" value="Se connecter" /> 
		<br/>
	</form>
  <div style="color:red"><?=$_SESSION['erreurLogin']?></div>  
	</div>
	

	<br/><br/><br/><br/>
	<br/><br/><br/><br/>
	<br/><br/><br/><br/>
	<br/><br/><br/><br/>
	<br/><br/>
	
</div>
</div>
<?php include('footer.php'); ?>


<!-- GITHUB FORK ME -->
    <div class="github-fork-ribbon-wrapper right-bottom">
        <div class="github-fork-ribbon">
            <a href="https://github.com/julienbouche/vBiblio" target="_blank">Fork Moi Sur GitHub</a>
        </div>
    </div>

</body>  
</html>
<?php

}
else{ //l'utilisateur tente une connexion
	if (isset($_POST['uid']) ){    
 		$uid = $_POST['uid'];    
	}
	else {    
 		$uid = $_SESSION['uid'];    
	}    
	if (isset($_POST['pwd']) and $_POST['pwd'] != "" ){    
		$pwd = $_POST['pwd'];    
	}
	else {    
		$pwd = $_SESSION['pwd'];    
	}

	dbConnect();

	//on enregistre les variables de session
	$_SESSION['uid'] = $uid; 	   
	$_SESSION['pwd'] = $pwd;
	
	$sql = "SELECT userid, password, fullname FROM vBiblio_user WHERE    
	       (userid = '$uid' OR email='$uid') AND password=PASSWORD('$pwd') AND tableuserid<>0";    
	$result = mysql_query($sql);    
	if (!$result) { //un problème de connexion à la bdd est survenu   
		error('A database error occurred while checking your '.    
		       'login details.\\nIfhis error persists, please '.    
		       'contact webmaster@vBiblio.com.');    
	}
	if (mysql_num_rows($result) == 0) {
 		unset($_SESSION['uid']);    
 		unset($_SESSION['pwd']);
 		$_SESSION['erreurLogin']="Utilisateur ou mot de passe inconnu";
		header('Location:formLogin.php');    
 	}
	else{
		//la connexion a réussie
		if(isset($_SESSION['erreurLogin']))unset($_SESSION['erreurLogin']);
		$username = mysql_result($result,0,'fullname');
		$_SESSION['uid'] = mysql_result($result,0,'userid');
		$encryptedPass = mysql_result($result,0,'password');
		$_SESSION['fullname'] = $username;

	    	//si il souhaite enregistrer ses params de connexion
		if (isset($_POST['creercookie'])){
			setCookie("vbiblio","$uid",time()+3600*24*30);
			setCookie("vbiblio_check", substr($encryptedPass,1, strlen($encryptedPass)-1),time()+3600*24*30);
		}
		//la connexion a réussie
		header('Location: index.php');

	}
}

?>
