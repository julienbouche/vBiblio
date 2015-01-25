<?php
	header( 'Location: /formLogin.php' ) ;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - Acc&egrave;s refus&eacute;</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="css/vBiblio.css" media="screen" />
 </head>    
 <body>    

<div id="vBibContenu">
<?php
	include('header.php');
?>
	<div id="vBibDisplay">
 <h1>Acc&egrave;s refus&eacute;</h1>    
 <p>Vous ne semblez pas &ecirc;tre connnect&eacute;. <br/>
Pour vous connecter, merci de cliquer    
    <a href="formLogin.php">ici</a>. Si vous ne poss&eacute;dez pas encore de compte, <a href="signup.php">inscrivez-vous</a>.</p>    


</div>
</div>
<?php
	include('footer.php');
?>
</body>    
</html>

