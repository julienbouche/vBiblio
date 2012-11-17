<?php
require_once("Livre.php");


class Utilisateur{
  private $pseudo;
  private $identifiant;
  private $fullname;
  private $nom;
  private $prenom;
  private $email;
  private $publicpageActive;
  private $notificationActive;
  private $website;
  private $idprefbook;
  private $preferredStyle; 
  private $sexe;  
  private $pronom;  
  
	/*
			Constructeurs et initialisation
	*/

  public function initializeByID($tableuserid){
      $this->identifiant = $tableuserid;
    
    //rÃ©cupÃ©rer les infos de l'utilisateur
    $sql = "SELECT userid, fullname, email, nom, prenom, notification_active, active_public_page, website, sexe FROM vBiblio_user WHERE tableuserid='".$this->identifiant."'";

    $result = mysql_query($sql);

    if($result && mysql_num_rows($result)){
		$row = mysql_fetch_assoc($result);
		$this->pseudo = $row['userid'];
		$this->fullname= $row['fullname'];
		$this->nom = $row['nom'];
		$this->prenom = $row['prenom'];
		$this->email = $row['email'];
		$this->website = $row['website'];
		$this->sexe = $row['sexe'];
		$this->pronom  = $this->sexe=="0"?"Il":"Elle";
		
		$this->notificationActive = ($row['notification_active']=='1');
		$this->publicpageActive = ($row['active_public_page']=='1');
    }
  }

  public function __construct($iduser){
    $this->pseudo = $iduser;
    
    //récupérer les infos de l'utilisateur
    $sql = "SELECT tableuserid, fullname, email, nom, prenom, notification_active, active_public_page, website, sexe FROM vBiblio_user WHERE userid='".$this->pseudo."'";
    
    $result = mysql_query($sql);

    if($result && mysql_num_rows($result)){
		$row = mysql_fetch_assoc($result);
		$this->identifiant = $row['tableuserid'];
		$this->fullname = $row['fullname'];
		$this->nom = $row['nom'];
		$this->prenom = $row['prenom'];
		$this->email = $row['email'];
		$this->website = $row['website'];
		$this->sexe = $row['sexe'];
		$this->pronom  = $this->sexe=="0"?"Il":"Elle";
		
		$this->notificationActive = ($row['notification_active']=='1');
		$this->publicpageActive = ($row['active_public_page']=='1');
    }
  }

	


  
  public function loadPersonalDatas(){
  }


	/*
		META Fonctions (affichage, donnÃ©es complexes, etc.)
	*/
  
  public function afficherDernieresDemandes(){
  	$sql="SELECT type, fullname FROM vBiblio_demande, vBiblio_user WHERE id_user_requested=".$this->identifiant." AND id_user=vBiblio_user.tableuserid LIMIT 0,10";

    $result = mysql_query($sql);

    if($result && mysql_num_rows($result)>0){
      echo "<ul>";
      while($row = mysql_fetch_assoc($result)){
        $usrName = $row['fullname'];
        if($row['type']=="FRIENDS_REQUEST"){
				  echo "<li><span><a href=\"friendsRequest.php\" class=\"vBibLink\"><b>$usrName</b> souhaite vous ajouter &agrave; ses amis</a></span></li>";
        }
        else if($row['type'] == "BOOK_REQUEST"){
				  echo "<li><span><a href=\"manageBooksRequest.php\" class=\"vBibLink\"><b>$usrName</b> souhaite vous emprunter un livre</a></span></li>";
        }
		  }
		  echo "</ul>";	
    }
    else echo "Aucune demande en attente";
  }
  
  public function afficherResumeSuggestions(){
  	$sql="SELECT fullname, titre 
		FROM vBiblio_suggest, vBiblio_book, vBiblio_user 
		WHERE id_from=tableuserid
		AND id_to=".$this->identifiant."
		AND vBiblio_suggest.id_book =vBiblio_book.id_book
		LIMIT 0,10";

    $result = mysql_query($sql);
    if($result && mysql_num_rows($result)>0){
      echo "<ul>";
  		while($row = mysql_fetch_assoc($result)){
  			$usrName = $row['fullname'];
  			$titre = $row['titre'];
  			echo "<li><span><a href=\"manageBooksSuggest.php\" class=\"vBibLink\"><b>$usrName</b> vous sugg&egrave;re de lire $titre</a></span></li>";
  		}
		  echo "</ul>";	
    }
    else echo "Aucune suggestion en ce moment";
  }
  
  public function afficherDerniersAjouts(){
    $sql = "SELECT vBiblio_poss.id_book as id_book
            FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user
            WHERE vBiblio_user.userid='".$this->pseudo."'
            AND vBiblio_book.id_book=vBiblio_poss.id_book
            AND vBiblio_book.id_author = vBiblio_author.id_author
            AND vBiblio_poss.userid=vBiblio_user.tableuserid 
            AND TO_DAYS(NOW()) - TO_DAYS(date_ajout) <= 15 
            ORDER BY date_ajout DESC";	
	
  	$result = mysql_query($sql);
  	
  	if($result && mysql_num_rows($result)>0 ){
  		echo "<ul>";
  		while($row=mysql_fetch_assoc($result)){
  		  $idbook = $row['id_book'];
		  $bookInst = new Livre($idbook);
  		  
		  $auteurInst = $bookInst->retournerAuteur();
  		  
  		  echo "<li>\n<span>\n<a href=\"".$bookInst->retournerURL()."\" class=\"vBibLink\">";
  		  echo $bookInst->titreLong()." </a>";
  		  
  		  echo " de <a href=\"ficheAuteur.php?id=".$auteurInst->getID()."\" class=vBibLink>".$auteurInst->fullname()." </a></span>";
  		  echo "</li>";
  		}
  		echo "</ul>";
  	}	
  	else {
  	  //on teste que l'utilisateur possède des livres...
  	  $sqlPoss = "SELECT id_book FROM vBiblio_poss WHERE vBiblio_poss.userid=".$this->identifiant;
  	  
  	  $resultPoss = mysql_query($sqlPoss);
  	  
  	  if($resultPoss && mysql_num_rows($resultPoss)>0 ){
        echo "<br/>Vous n'avez pas ajout&eacute; de livre dans votre <a href=\"myBooks.php\" class=\"vBibLink\">biblioth&egrave;que</a> r&eacute;cemment.";
      }
  		else echo "<br/>Vous n'avez ajout&eacute; encore aucun livre dans votre <a href=\"myBooks.php\" class=\"vBibLink\">biblioth&egrave;que</a>";
  	}  
  }

public function recupererListeTousMessagesEnvoyes(){
  	$sql = "SELECT id_message
            FROM vBiblio_message 
            WHERE from_user=".$this->identifiant."             
            ORDER BY date DESC;";
	
	$MessageList = array();
    
    
	$result = mysql_query($sql);

	if($result && mysql_num_rows($result)>0){
		$cpt = 0;
		while($row=mysql_fetch_assoc($result)){
      			$MessageList[$cpt]= new Message($row['id_message']);
			$cpt++;
		}		
	}
	
	return $MessageList;
}

public function recupererListeTousMessagesRecus(){
  	$sql = "SELECT id_message
            FROM vBiblio_message 
            WHERE to_user=".$this->identifiant."             
            ORDER BY date DESC;";
	
	$MessageList = array();
    
    
	$result = mysql_query($sql);

	if($result && mysql_num_rows($result)>0){
		$cpt = 0;
		while($row=mysql_fetch_assoc($result)){
      			$MessageList[$cpt]= new Message($row['id_message']);
			$cpt++;
		}		
	}
	
	return $MessageList;

}

public function recupererListeDerniersMessages(){
	/* MODIFICATION JBO */

  	$sql = "SELECT id_message
            FROM vBiblio_message 
            WHERE to_user=".$this->identifiant."             
            ORDER BY date DESC LIMIT 5;";
	
	$MessageList = array();
    
    
	$result = mysql_query($sql);

	if($result && mysql_num_rows($result)>0){
		$cpt = 0;
		while($row=mysql_fetch_assoc($result)){
      			$MessageList[$cpt]= new Message($row['id_message']);
			$cpt++;
		}		
	}
	
	return $MessageList;
}
  
  public function afficherDerniersMessages(){
  	$sql = "SELECT message, date, from_user 
            FROM vBiblio_message 
            WHERE to_user=".$this->identifiant."             
            ORDER BY date DESC LIMIT 5;";
    
    
	  $result = mysql_query($sql);

	  if($result && mysql_num_rows($result)>0){
?>
		<ul id="vBibMessages">
<?
		while($row=mysql_fetch_assoc($result)){
			
      			$datepost = $row['date'];
			$mess = $row['message'];
			$msg_user_id = $row['from_user'];
			$buddy = new Utilisateur("");
			$buddy->initializeByID($msg_user_id);
			
			$avatarPath = "images/avatars/avatar-".$msg_user_id.".png";
?>
<? //if(file_exists($avatarPath) ){
  if($buddy->aUnAvatar()){ 
	?>
			<li class="vBibMessage" style="background: url(<?=$buddy->cheminFichierAvatar()?>) no-repeat 0 1.45em;min-height:70px;" >
	 <?
   }
	 else{
	 ?>
	  <li class="vBibMessage" style="margin-right:52px;" >
	 <?
   }
   ?>
				<div>
					<div class="vBibMessageAuthor">
          <a href="userProfil.php?user=<?=$buddy->identifiant?>" title="Voir le profil" class="vBibLink"><b><?=$buddy->prenom?></b></a>
          </div>&nbsp;a &eacute;crit:&nbsp;<span class="vBibMessageDate">le <?=dateh_lettres($datepost)?></span>
				</div>
				<div></div>
				<div class="vBibMessageContent"  style="margin: 0 4em;"><?=nl2br(htmlentities($mess))?></div>
			</li>
<?
	}
?>
		</ul>
<?
    }
	  else echo "<br/>Vous n'avez aucun message.";
	  
  }
  
  public function recupererListeLivres(){
		$sql = "SELECT vBiblio_book.id_book 
					FROM vBiblio_poss, vBiblio_author, vBiblio_book 
					WHERE userid=".$this->identifiant."
					AND vBiblio_poss.id_book=vBiblio_book.id_book
					AND vBiblio_book.id_author=vBiblio_author.id_author
					ORDER BY vBiblio_author.nom ASC, id_cycle, numero_cycle ASC";
					
		$result = mysql_query($sql);
		
		if($result && mysql_num_rows($result)>0 ){
			$bookList = array();
			$cpt = 0;
			while($row = mysql_fetch_assoc($result)){
				$bookList[$cpt] = new Livre($row['id_book']);
				$cpt++;
			}
		}
  }
  
  /*
  Retourne une liste d'Utilisateur
  */
  public function recupererListeAmis(){
	$sql = "SELECT user2.userid as pseudo 
		FROM vBiblio_user As user1, vBiblio_user as user2, vBiblio_amis 
		WHERE user1.userid='".$this->pseudo."' AND vBiblio_amis.id_user1=user1.tableuserid 
		AND user2.tableuserid=vBiblio_amis.id_user2 ORDER BY user2.fullname";
	
	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result)>0 ){
		$friends = array();
		$cpt =0;
		while($row=mysql_fetch_assoc($result)){
			$friends[$cpt] = new Utilisateur($row['pseudo']);
			$cpt++;
		}
	}
	return $friends;
  }
  
  public function afficherListeDemandesContact(){
	$sql= "SELECT id_user, id_demande FROM vBiblio_demande WHERE id_user_requested ='".$this->identifiant."' AND type='FRIENDS_REQUEST'";

	$result = mysql_query($sql);

	if($result && mysql_num_rows($result) ){
		echo "<table>";
		while($row = mysql_fetch_assoc($result)){
			$friend = new Utilisateur('');	
			$friend->initializeByID($row['id_user']);
			$idRequest = $row['id_demande'];

			echo "<tr name=\"request$idRequest\">";
			echo "<td style=\"width:10%;\"><img src=\"".$friend->cheminFichierAvatar()."\"</td><td style=\"width:50%;text-align:left;\">".$friend->getFullname()." souhaite vous ajouter &agrave; sa liste d'amis</td>";
			echo "<td><input type=\"button\" value=\"Confirmer!\"onclick=\"javascript:acceptRequest($idRequest, ".$this->identifiant.", ".$friend->getID().", true);\" />&nbsp;<input type=\"button\" class=\"alert\" value=\"X\" onclick=\"javascript:ignoreRequest($idRequest);\"/></td>";
			echo "</tr>";
		}
		echo "</table>";
	}
  }
	
	public function afficherRechercheEmprunts($searchTerms){
		$sql = "
    SELECT vBiblio_book.id_book, vBiblio_user.userid
    FROM vBiblio_book, vBiblio_author, vBiblio_poss, vBiblio_user
    WHERE vBiblio_book.id_book=vBiblio_poss.id_book 
	AND vBiblio_book.id_author=vBiblio_author.id_author 
	AND vBiblio_user.tableuserid = vBiblio_poss.userid
    AND (
		MATCH(vBiblio_author.nom,vBiblio_author.prenom) AGAINST('$searchTerms') 
		OR MATCH(vBiblio_book.titre) AGAINST('$searchTerms')  
		OR vBiblio_book.id_book IN ( SELECT id_book 
											FROM vBiblio_book, vBiblio_cycle 
											WHERE vBiblio_book.id_cycle=vBiblio_cycle.id_cycle
											AND MATCH(vBiblio_cycle.titre) AGAINST ('$searchTerms')
										)
			)
    AND vBiblio_poss.userid IN (SELECT DISTINCT vBiblio_amis.id_user2 
												FROM vBiblio_amis 
												WHERE vBiblio_amis.id_user1='".$this->identifiant."')
    AND vBiblio_book.id_book NOT IN (SELECT id_book FROM vBiblio_poss WHERE userid=".$this->identifiant.")";

	//echo $sql;
	$result = mysql_query($sql);
		
		if($result && mysql_num_rows($result)>0){
			echo "<table class=\"vBibTablePret\">";
			while($row= mysql_fetch_assoc($result) ) {
				$buddy = new Utilisateur($row['userid']);
				$bouquin = new Livre($row['id_book']);
				
				$chaineDemandePret = "<a href=\"userProfil.php?user=".$buddy->getID()."\" class=\"vBibLink\" ><b>".$buddy->getFullname()."</b></a> peut vous pr&ecirc;ter <a href=\"ficheLivre.php?id=".$bouquin->getID()."\" class=\"vBibLink\">";
				
        $chaineDemandePret.= $bouquin->TitreLong();
		$auteurBouquin = $bouquin->retournerAuteur();
		
        $chaineDemandePret.= "</a> de <a href=\"ficheAuteur.php?id=".$auteurBouquin->getID()."\" class=\"vBibLink\" >".$auteurBouquin->fullname()."</a>";
				echo "<tr><td>$chaineDemandePret</td><td style=\"width:200px;\" name=\"feedbackU".$buddy->getID()."B".$bouquin->getID()."\">";
				if(!$bouquin->isRequested($this->identifiant,$buddy->getID()) ){
					echo "<input type=\"button\" value=\"Envoyer une demande\" onclick=\"sendBookRequest(this, ".$this->identifiant.",".$buddy->getID().",".$bouquin->getID().");return false;\"/>";
				}
				else echo "Une demande a &eacute;t&eacute; envoy&eacute;e.";
				
				echo "</td></tr>";
			}
			echo "</table><br/> <hr>";
		}else echo "Aucun r&eacute;sultat ne correspond &agrave; votre recherche.<br/>";
	}

	public function afficherListeDemandesLivres(){
		//TODO Ã  simplifier !!!
		$sql= "SELECT id_user, id_demande, id_requested FROM vBiblio_demande WHERE id_user_requested ='".$this->identifiant."' AND type='BOOK_REQUEST' ";
	
		$result = mysql_query($sql);

		if($result && mysql_num_rows($result) ){
			echo "<table style=\"font-size:inherit;\">";
			while($row = mysql_fetch_assoc($result)){
				$bouquin = new Livre($row['id_requested']);
				$buddy = new Utilisateur("");
				$buddy->initializeByID($row['id_user']);
				$idRequest = $row['id_demande'];
				
				$titre = "<a href=\"userProfil.php?user=".$buddy->getID()."\" class=\"vBibLink\"><b>".$buddy->getFullname()."</b></a> souhaite vous emprunter <a href=\"ficheLivre.php?id=".$bouquin->getID()."\" class=\"vBibLink\">".$bouquin->titreLong()."</a>";
				
				
				echo "<tr name=\"request$idRequest\">";
				echo "<td style=\"width:10%;\"><img src=\"/vBiblio/images/buddy.png\"</td><td style=\"width:50%;text-align:left;\">".$titre."</td>";
				echo "<td style=\"\"><input type=\"button\" value=\"Confirmer!\"onclick=\"javascript:acceptRequest($idRequest, ".$this->identifiant.", ".$buddy->getID().", ".$bouquin->getID().");\" />&nbsp;<input type=\"button\" class=\"alert\" value=\"X\" onclick=\"javascript:ignoreRequest($idRequest);\"/></td>";
				echo "</tr>";
			}
			echo "</table>";
		}else echo "<br/>Aucune demande en cours.";
	}


	public function afficherListePretsEncours(){
	$sql = "SELECT nom_emprunteur, id_emprunteur, id_book FROM vBiblio_pret WHERE id_preteur='".$this->identifiant."'"; 
	
	$result = mysql_query($sql) ;
	
	if($result && mysql_num_rows($result)>0 ){
		$cpt=0;
		echo "<a href=\"generateTablePretsPDF.php?type=2\" target=\"_blank\" style=\"float:right;\" ><img src=\"images/adobe-pdf-logo.png\" width=\"32\" height=\"32\" title=\"T&eacute;l&eacute;charger la liste\"/></a><br/><br/>";
		echo "<table class=\"vBibTablePret\">\n";
		while($row=mysql_fetch_assoc($result)){
			
			$bouquin = new Livre($row['id_book']);
			
			$nomEmprunteur = $row['nom_emprunteur'];
			$idEmprunteur = $row['id_emprunteur'];
			
			echo "<tr>";
			echo "<td></td><td><a href=\"ficheLivre.php?id=".$bouquin->getID()."\" class=\"vBibLink\">";
			echo $bouquin->titreLong();
			echo "</a> a &eacute;t&eacute; pr&ecirc;t&eacute; &agrave; ";

			//echo "<tr>";
			//echo "<td></td><td>$titre a &eacute;t&eacute; pr&ecirc;t&eacute; &agrave; ";
			if($idEmprunteur!="0")
				echo "<a class=\"vBibLink\" href=\"userProfil.php?user=$idEmprunteur\">";
			else 
				echo "<a class=\"vBibLink\" style=\"color:black\">";
			echo "<b>$nomEmprunteur</b></a></td><td><input type=\"button\" value=\"Ok, il me l'a rendu!\" onclick=\"javascript:retourPret(this,".$this->identifiant.", $idEmprunteur, ".$bouquin->getID().");\"/></td>";
			echo "</tr>";
		}
		echo "</table>\n";
	}
	else{
		?>
		Vous n'avez pr&ecirc;t&eacute; aucun livre, en ce moment.
		<?
	}	
	}
	
	public function NbBooksInLibrary(){
		$sql = "SELECT COUNT(*) as nbTotalLivresUtilisateur FROM vBiblio_poss WHERE userid='".$this->identifiant."'";

	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$str_nbBooksInLib = $row['nbTotalLivresUtilisateur'];
	}else $str_nbBooksInLib = "0";
	
	return $str_nbBooksInLib;
	}
	public function retournerListeLivresDispos(){
			$sql = "SELECT vBiblio_poss.id_book as id_book FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user WHERE vBiblio_poss.userid = vBiblio_user.tableuserid AND vBiblio_user.userid='".$this->pseudo."' AND vBiblio_poss.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author AND vBiblio_poss.pret=0 ORDER BY vBiblio_author.nom ASC";

		//echo "$sql";
		$result = mysql_query($sql) or die(mysql_error());

		if($result && mysql_num_rows($result)>0 ){
			$bouquins = array();
			$cpt = 0;
			
			while($row = mysql_fetch_assoc($result)){
				$bouquins[$cpt] = new Livre($row['id_book']);
				$cpt++;
			}
			
		}
		return $bouquins;
	}

	public function afficherRechercheLivresAAjouter($searchText){
		
		$searchTerms = str_replace(" ", ",", $searchText);
		
		$sql = "SELECT  distinct id_book, titre, nom, prenom, vBiblio_author.id_author, numero_cycle, MATCH(nom,prenom) AGAINST('$searchTerms') as pertinence
      FROM vBiblio_book, vBiblio_author WHERE vBiblio_book.id_author=vBiblio_author.id_author 
      AND ( 
				MATCH(nom,prenom) AGAINST('$searchTerms') 
				OR MATCH(vBiblio_book.titre) AGAINST('$searchTerms')
				OR id_book IN (
										SELECT id_book 
										FROM vBiblio_book, vBiblio_cycle
										WHERE vBiblio_book.id_cycle=vBiblio_cycle.id_cycle
										AND MATCH(vBiblio_cycle.titre) AGAINST('$searchTerms'))
				)
      AND id_book NOT IN (SELECT id_book FROM vBiblio_poss WHERE vBiblio_poss.userid=".$this->identifiant." ) 
      ORDER BY pertinence DESC
      ";


		$result = mysql_query($sql) ;

		if($result && mysql_num_rows($result) > 0){
			echo "<form name=\"addingBookList\" method=\"POST\" action=\"".$_SERVER['PHP_SELF']."\">";
			echo "<table style=\"font-size:inherit;\">";
			echo "<tr>";
			echo "<td></td><td></td><td style=\"text-align:center;\">Dans votre vBiblio<br/><a href=\"#\" class=\"vBibLink\" onclick=\"javascript:selectAllBooks();\">Tous</a> / <a href=\"#\" class=\"vBibLink\" onclick=\"javascript:unselectAllBooks();\">Aucun</a></td>";
			echo "<td style=\"text-align:center;\">Dans votre ToRead List<br/><a href=\"#\" class=\"vBibLink\" onclick=\"javascript:selectAllBooksTRL();\">Tous</a> / <a href=\"#\" class=\"vBibLink\" onclick=\"javascript:unselectAllBooksTRL();\">Aucun</a></td>";
			echo "</tr>";

			while($row = mysql_fetch_assoc($result)){
				
				$bouquin = new Livre($row['id_book']);

			
				$chaine = "<a href=\"ficheLivre.php?id=".$bouquin->getID()."\" class=\"vBibLink\">".$bouquin->titreLong()."</a> ";
				$auteur = $bouquin->retournerAuteur();
				$chaine .= "de <a href=\"ficheAuteur.php?id=".$auteur->getID()."\" class=\"vBibLink\" >".$auteur->fullname();
				
				echo "<tr>";
				echo "<td></td><td>$chaine</td>";
				//check box ajouter a la vBiblio
				echo "<td style=\"text-align:center;\"><input type=\"checkbox\" name=\"booksToAdd[]\" value=\"".$bouquin->getID()."\"/></td>";
				//checkbox ajouter Ã  la TRL
				echo "<td style=\"text-align:center;\"><input type=\"checkbox\" name=\"booksToAddTRL[]\" value=\"".$bouquin->getID()."\"/></td>";
				echo "</tr>";
			}
			echo "<td></td><td></td><td></td><td></td><td style=\"text-align:center;\"><input type=\"submit\" value=\"Ajouter\" /></td>";
			echo "</table>";
			echo "</form>";
		}
    else echo "Aucun r&eacute;sultat ne correspond &agrave; votre recherche.";
	}
	
	public function afficherSuggestions(){
		
		$sql= "SELECT userid, vBiblio_book.id_book, id_suggest FROM vBiblio_suggest, vBiblio_book, vBiblio_user WHERE vBiblio_suggest.id_from=vBiblio_user.tableuserid AND id_to ='".$this->identifiant."' AND vBiblio_suggest.id_book=vBiblio_book.id_book ORDER BY date_suggest ASC";

		$result = mysql_query($sql);

		if($result && mysql_num_rows($result) ){
			echo "<table style=\"font-size:inherit;width:100%;border:0;\">";
			while($row = mysql_fetch_assoc($result)){
				$buddy = new Utilisateur($row['userid']);
				$bouquin = new Livre($row['id_book']);
				
				$titre = "<a href=\"userProfil.php?user=".$buddy->getID()."\" class=\"vBibLink\"><b>".$buddy->getFullname()."</b></a> vous sugg&egrave;re de lire <a href=\"ficheLivre.php?id=".$bouquin->getID()."\" class=\"vBibLink\">".$bouquin->titreLong()."</a>";
				
				$idRequest = $row['id_suggest'];
				
				echo "<tr name=\"request$idRequest\">";
				echo "<td style=\"text-align:left;\">$titre</td>";
				echo "<td style=\"white-space:nowrap;\"><input type=\"button\" value=\"Ajouter &agrave; ma vBiblio\" onclick=\"javascript:addToMyVBiblio($idRequest, ".$this->identifiant.", ".$bouquin->getID().");\" />&nbsp;<input type=\"button\" class=\"vert\" value=\"Ajouter &agrave; ma ToRead List\" onclick=\"javascript:addToMyTRL($idRequest, ".$this->identifiant.", ".$bouquin->getID().");\"/>&nbsp;<input type=\"button\" class=\"alert\" value=\"X\" onclick=\"javascript:ignoreRequest($idRequest);\"/></td>";
				echo "</tr>";
			}
			echo "</table>";
		}else echo "<br/>Aucune demande en cours.";
	}
	
	
	public function possede($livre){
		$sqlposs = "SELECT id_book FROM vBiblio_poss WHERE id_book=".$livre->getID()." AND userid=".$this->identifiant;
		$resPoss= mysql_query($sqlposs);
		
		return ( ($resPoss && mysql_num_rows($resPoss))  ) ;
	}
	
	public function aDansUneListe($livre){
	$sqlposs = "SELECT id_book FROM vBiblio_poss WHERE id_book=".$livre->getID()." AND userid=".$this->identifiant;
		$resPoss= mysql_query($sqlposs);
		$sqlposs = "SELECT id_book FROM vBiblio_toReadList WHERE id_book=".$livre->getID()." AND id_user=".$this->identifiant;
		$resPoss2 = mysql_query($sqlposs);
		return ( ($resPoss && mysql_num_rows($resPoss)) || ($resPoss2 && mysql_num_rows($resPoss2)) );
	}
	
	
	public function aPrete($Livre){
		$sql = "SELECT * FROM vBiblio_poss 
					WHERE userid=".$this->identifiant." 
					AND pret=1 
					AND id_book=".$Livre->getID();
		
		$res= mysql_query($sql);
		
		if($res) return mysql_num_rows($res)>0;
		else return false;	
	}
	
	/*
	Cette fonction renvoit les identifiants des amis possedant le livre passé en paramètre
	*/
	public function recupererListeAmisQuiPossedent($bouquin){
		$idBookToSearch = $bouquin->getID();
		$sql = "SELECT user2.userid as pseudo 
			FROM vBiblio_user As user1, vBiblio_user as user2, vBiblio_amis
			WHERE user1.userid='".$this->pseudo."' 
			AND vBiblio_amis.id_user1=user1.tableuserid 
			AND user2.tableuserid=vBiblio_amis.id_user2
			AND user2.tableuserid IN (
				SELECT DISTINCT vBiblio_poss.userid FROM vBiblio_poss
				WHERE vBiblio_poss.id_book=$idBookToSearch
				AND vBiblio_poss.possede=1)
			 ORDER BY user2.fullname";
	
		$result = mysql_query($sql);
		$friends = null;

		if($result && mysql_num_rows($result)>0 ){
			$friends = array();
			$cpt =0;
			while($row=mysql_fetch_assoc($result)){
				$friends[$cpt] = new Utilisateur($row['pseudo']);
				$cpt++;
			}
		}
		return $friends;
	}
	
	

	/* 
		GETTER 
	*/
	
	public function getID(){
		return $this->identifiant;
	}
	
	public function getFullname(){
		return $this->fullname;
	}
	public function getPseudo(){
		return $this->pseudo;
	}
	public function getPronom(){
		return $this->pronom;
	}
	public function getPrenom(){
		return $this->prenom;
	}
	
	public function aUnAvatar(){
		return file_exists("images/avatars/avatar-".$this->identifiant.".png");
	}

	public function cheminFichierAvatar(){
		if( $this->aUnAvatar() ){
			$chemin =  "images/avatars/avatar-".$this->identifiant.".png";
		}
		else $chemin = "images/buddy.png";
		return $chemin;
	}
	
	public function cheminFichierBigAvatar(){
		if( $this->aUnAvatar() ){
			$chemin =  "images/avatars/avatar-160-".$this->identifiant.".png";
		}
		else $chemin = "images/avatars/no_avatar.png";
		return $chemin;
	}

}

?>
