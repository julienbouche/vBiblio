<?php //accesscontrol.php
require_once('scripts/db/db.php');

//permet de vérifier que l'utilisateur à le droit d'être sur cette page.
session_start();

dbConnect();
//permettre le login par le menu sur n'importe quelle page
if(!isset($_SESSION['uid']) ){
	   //recherche d'un éventuel cookie ... 
			if(isset($_POST['login']) && isset($_POST['pwd']) && $_POST['pwd']!='' && $_POST['login']!=''){
				$sqlSec = "SELECT fullname, userid FROM vBiblio_user WHERE (userid='".$_POST['login']."' OR email='".$_POST['login']."') AND password=PASSWORD('".$_POST['pwd']."')";
	      			$resSec = mysql_query($sqlSec);
				if($resSec && mysql_num_rows($resSec)>0 ){
					$_SESSION['fullname'] = mysql_result($resSec, 0, "fullname");
					$_SESSION['uid']=mysql_result($resSec, 0, "userid");
					//seulement dans ce cas, on considère l'utilisateur comme effectivement authentifié
				}else {
					unset($_SESSION['uid']);
				}
			}
}


/*
 *
 *
 */
function checkSecurity(){
  //est-ce que l'utilisateur est déjà identifié
	if(!isset($_SESSION['uid']) ){
	   //recherche d'un éventuel cookie ... 
		if(isset($_COOKIE["vbiblio"]) && isset($_COOKIE["vbiblio_check"]) ){
	      		// stocke les var...      
			$valeurrech = $_COOKIE["vbiblio"] ;
			$pass = "*".$_COOKIE["vbiblio_check"];
	      		$_SESSION['uid'] = $valeurrech ;
	      		$sqlSec = "SELECT fullname, userid FROM vBiblio_user WHERE (userid='$valeurrech' OR email='$valeurrech') AND password='$pass'";
	      		$resSec = mysql_query($sqlSec);
			if($resSec && mysql_num_rows($resSec)>0 ){
				$_SESSION['fullname'] = mysql_result($resSec, 0, "fullname");
				$_SESSION['uid']=mysql_result($resSec, 0, "userid");
				//seulement dans ce cas, on considère l'utilisateur comme effectivement authentifié
			}else {
				unset($_SESSION['uid']);
				header('Location:LoginError.php');
			}
		}
		else header('Location:LoginError.php');
	}
}

/*
 *
 *
 */
function checkSecurityHome(){

	if(!isset($_SESSION['fullname']) ){
		//si l'utilisateur n'a pas de session en cours, on recherche les cookies de connexion auto
		if( isset($_COOKIE["vbiblio"]) && isset($_COOKIE["vbiblio_check"]) ){
      		// stocke les var...      
	      		$valeurrech = $_COOKIE["vbiblio"] ;
			      $pass = $_COOKIE["vbiblio_check"];
	      		$_SESSION['uid'] = $valeurrech ;
	      		$sqlSec = "SELECT fullname, userid FROM vBiblio_user WHERE (userid='$valeurrech' OR email='$valeurrech') AND password='$pass' ";
	      		$resSec = mysql_query($sqlSec);
	      		if($resSec && mysql_num_rows($resSec)>0 ){
	        		$_SESSION['fullname'] = mysql_result($resSec, 0, "fullname");
				$_SESSION['uid']=mysql_result($resSec, 0, "userid");
	      		}
	      		else{
	        		unset($_SESSION['uid']);
	        		header('Location:formLogin.php');
	      		}
	  	}
	  	else header('Location:formLogin.php');
	}
}


/*
 *
 *
 */
function checkSecurityLoginPage(){
  //si l'utilisateur est déjà enregistré ou qu'il a un cookie valide... 
  //on redirige vers index.php
  
	if (isset($_SESSION['uid']) && isset($_SESSION['fullname']) ){
		header("Location:index.php");
	}
	else if( isset($_COOKIE["vbiblio"]) && isset($_COOKIE["vbiblio_check"]) ){
		//enregistrer les vars de sessions...
		$valeurrech = $_COOKIE["vbiblio"] ;
		$pass = "*".$_COOKIE["vbiblio_check"];
      		$_SESSION['uid'] = $valeurrech ;
      		$sqlSec = "SELECT fullname, userid FROM vBiblio_user WHERE (userid='$valeurrech' OR email='$valeurrech') AND password='$pass' ";
      		$resSec = mysql_query($sqlSec);
		if($resSec && mysql_num_rows($resSec)>0 ){
			$_SESSION['fullname'] = mysql_result($resSec, 0, "fullname");
			$_SESSION['uid']=mysql_result($resSec, 0, "userid");
			//seulement dans ce cas, on considère l'utilisateur comme effectivement authentifié
			header('Location:index.php');
		}
	}
}

//permet de tester si l'utilisateur est connu pour des tests simples
function isUserKnown(){
	if(!isset($_SESSION['uid']) ){
	   //recherche d'un éventuel cookie ... 
		if(isset($_COOKIE["vbiblio"]) && isset($_COOKIE["vbiblio_check"]) ){
			// stocke les var...      
			$valeurrech = $_COOKIE["vbiblio"] ;
			$pass = "*".$_COOKIE["vbiblio_check"];
			$_SESSION['uid'] = $valeurrech ;
			$sqlSec = "SELECT fullname FROM vBiblio_user WHERE (userid='$valeurrech' OR email='$valeurrech') AND password='$pass' ";
			$resSec = mysql_query($sqlSec);
			if($resSec && mysql_num_rows($resSec)>0 ){
			   $_SESSION['fullname'] = mysql_result($resSec, 0, "fullname");
			   //seulement dans ce cas, on considère l'utilisateur comme effectivement authentifié
				 return true;
			}
			else return false;
		}
		else return false;
	}
	else return true;
}

?>
