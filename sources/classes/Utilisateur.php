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
	private $exists;   

	/*
			Constructeurs et initialisation
	*/

	public function initializeByID($tableuserid){
		$this->identifiant = intval($tableuserid);
		$this->exists = false;
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
			$this->exists = true;
		}
	}

	public function __construct($iduser){
		$this->pseudo = mysql_real_escape_string($iduser);
		$this->exists = false;
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
			$this->exists = true;
		}
	}

	//TODO créer la fonction ci-dessous pour remplacer dans les différents constructeurs
	public function loadPersonalDatas(){
	}

	/*
	 *	META Fonctions (affichage, donnÃ©es complexes, etc.)
	 */

  	public function recupererListeResumeDernieresDemandes(){
		$sql="SELECT type, fullname 
			FROM vBiblio_demande, vBiblio_user 
			WHERE id_user_requested=".$this->identifiant." 
			AND id_user=vBiblio_user.tableuserid 
			LIMIT 0,10";

		$result = mysql_query($sql);

		if($result && mysql_num_rows($result)>0){
			$listeDemandes = array();
			$cptDemandes = 0;
			while($row = mysql_fetch_assoc($result)){
				$listeDemandes[$cptDemandes] = array();
				$listeDemandes[$cptDemandes][0] = $row['type'];
				$listeDemandes[$cptDemandes][1] = $row['fullname'];
				$cptDemandes++;
			}
		}
		return $listeDemandes;
	}

	//deprecated fonction remplacée par la fonction recupererListeResumeDernieresDemandes
	//public function afficherDernieresDemandes(){
	//}
	public function recupererListeResumeSuggestions(){
		$sql="SELECT tableuserid, vBiblio_book.id_book as idBook
			FROM vBiblio_suggest, vBiblio_book, vBiblio_user 
			WHERE id_from=tableuserid
			AND id_to=".$this->identifiant."
			AND vBiblio_suggest.id_book =vBiblio_book.id_book
			LIMIT 0,10";

		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$listeSuggestions = array() ;
			$cptSuggestions = 0;
			while($row = mysql_fetch_assoc($result)){
				$listeSuggestions[$cptSuggestions] = array();
				$listeSuggestions[$cptSuggestions][0] = new Utilisateur('');
				$listeSuggestions[$cptSuggestions][0]->initializeByID($row['tableuserid']);
				$listeSuggestions[$cptSuggestions][1] = new Livre($row['idBook']);
				$cptSuggestions++;
			}
		}
		return $listeSuggestions;
	}

	//deprecated remplacée par la fonction recupererListeResumeSuggestions
	//public function afficherResumeSuggestions(){
  	//}
  
	public function retournerListeDerniersAjouts(){
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
			$nbAjouts=0;
			$listeAjouts = array();
			while($row=mysql_fetch_assoc($result)){
				$listeAjouts[$nbAjouts++] = new Livre($row['id_book']);
			}
		}
		return $listeAjouts;
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
  
	//fonction deprecated remplacee par recupererListeDerniersMessages 
  /*public function afficherDerniersMessages(){
  }*/
  
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

	public function recupererListeDemandesContact(){
		$sql= "SELECT id_user, id_demande FROM vBiblio_demande WHERE id_user_requested ='".$this->identifiant."' AND type='FRIENDS_REQUEST'";

		$result = mysql_query($sql);

		if($result && mysql_num_rows($result) ){
			$nbDemandes=0;
			$listeDemandes = array();
			while($row = mysql_fetch_assoc($result)){
				$listeDemandes[$nbDemandes] = array();
				$listeDemandes[$nbDemandes][0] = new Utilisateur('');
				$listeDemandes[$nbDemandes][0]->initializeByID($row['id_user']);
				$listeDemandes[$nbDemandes][1] = $row['id_demande'];
				$nbDemandes++;
			}
		}
		return $listeDemandes;
	}

  //fonction deprecated remplacée par la fonction recupererListeDemandesContact
  /*public function afficherListeDemandesContact(){
  }*/

	public function retournerListeRechercheEmpruntsPossibles($searchTerms){
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

		
		$result = mysql_query($sql);
		
		if($result && mysql_num_rows($result)>0){
			$listeEmprunts = array();
			$nbEmprunts = 0;
			while($row= mysql_fetch_assoc($result) ) {
				$listeEmprunts[$nbEmprunts] = array();
				$listeEmprunts[$nbEmprunts][0] = new Utilisateur($row['userid']);
				$listeEmprunts[$nbEmprunts][1] = new Livre($row['id_book']);
				$nbEmprunts++;
			}
		}
		return $listeEmprunts;
	}
	
	//fonction deprecated remplacer par la fonction retournerListeRechercheEmpruntsPossibles
	/*public function afficherRechercheEmprunts($searchTerms){
	}*/
	
	public function recupererListeDemandesLivres(){
		$sql= "SELECT id_user, id_demande, id_requested FROM vBiblio_demande WHERE id_user_requested ='".$this->identifiant."' AND type='BOOK_REQUEST' ";

		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$nbDemandes = 0;
			$listeLivresDemandes = array();

			while($row = mysql_fetch_assoc($result)){
				$listeLivresDemandes[$nbDemandes] = array();
				$listeLivresDemandes[$nbDemandes][0] = new Utilisateur('');
				$listeLivresDemandes[$nbDemandes][0]->initializeByID($row['id_user']);
				$listeLivresDemandes[$nbDemandes][1] = new Livre($row['id_requested']);
				$listeLivresDemandes[$nbDemandes][2] = $row['id_demande'];
				$nbDemandes++;
			}
		}
		return $listeLivresDemandes;
	}

	//remplacé par une fonction en renvoyant qu'une liste pour traiter l'affichage dans la page manageBooksRequest.php
	/*public function afficherListeDemandesLivres(){
	}*/

	/*Cette fonction retourne la liste des prets en cours.
	La fonction retourne une liste de structure, comprenant le livre, l'identifiant de l'utilisateur et eventuellement son nom si l'utilisateur est externe à vBiblio
	*/
	public function retournerListePretsEnCours(){
		$sql = "SELECT nom_emprunteur, id_emprunteur, id_book FROM vBiblio_pret WHERE id_preteur='".$this->identifiant."'"; 
	
		$result = mysql_query($sql) ;
	
		if($result && mysql_num_rows($result)>0 ){
			$nbPrets=0;
			$listePrets = array();
			while($row=mysql_fetch_assoc($result)){
				$listePrets[$nbPrets] = array();
				$listePrets[$nbPrets][0] = new Livre($row['id_book']);
				$listePrets[$nbPrets][1] = $row['id_emprunteur'];
				$listePrets[$nbPrets][2] = $row['nom_emprunteur'];
				if($row['id_emprunteur']!="0"){
					$buddy=new Utilisateur("");
					$buddy->initializeByID($row['id_emprunteur']);
					$listePrets[$nbPrets][2] = $buddy->getFullname();
				}
				$nbPrets++;
			}
		}
		return $listePrets;
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
		$sql = "SELECT vBiblio_poss.id_book as id_book 
			FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user 
			WHERE vBiblio_poss.userid = vBiblio_user.tableuserid 
			AND vBiblio_user.userid='".$this->pseudo."' 
			AND vBiblio_poss.id_book = vBiblio_book.id_book 
			AND vBiblio_book.id_author=vBiblio_author.id_author 
			AND vBiblio_poss.pret=0
			AND vBiblio_poss.id_book NOT IN (SELECT id_book 
							 FROM vBiblio_pret 
							 WHERE id_emprunteur=".$this->identifiant.") 
			ORDER BY vBiblio_author.nom ASC";

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

	//TODO réfléchir à une gestion de la pagination...comment faire si des livres ont été sélectionnés dans une page lorsque l'on fait suivant/précédent
	public function rechercherLivresAAjouter($searchText){		
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
		$listeLivres = array();
		$nbLivres = 0;
		if($result && mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)){
				$listeLivres[$nbLivres++] = new Livre($row['id_book']);
				
			}
		}
		//echo "$sql";
		return $listeLivres;
	}
	


	public function recupererListeCompleteSuggestions(){
		$sql= "SELECT userid, vBiblio_book.id_book as id_book, id_suggest 
			FROM vBiblio_suggest, vBiblio_book, vBiblio_user 
			WHERE vBiblio_suggest.id_from=vBiblio_user.tableuserid 
			AND id_to ='".$this->identifiant."' 
			AND vBiblio_suggest.id_book=vBiblio_book.id_book 
			ORDER BY date_suggest ASC";

		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$listeSuggestions = array();
			$cptSuggestions=0;
			while($row = mysql_fetch_assoc($result)){
				$listeSuggestions[$cptSuggestions] = array();
				$listeSuggestions[$cptSuggestions][0] = new Utilisateur($row['userid']);
				$listeSuggestions[$cptSuggestions][1] = new Livre($row['id_book']);
				$listeSuggestions[$cptSuggestions][2] = $row['id_suggest']; 
			}
		}
		return $listeSuggestions;
	}

	//remplacé par un retour d'une liste de Suggestion(Utilisateur, Livre, idTransaction)
	//et gérer l'affichage dans la page manageBooksSuggest.php 
	/*public function afficherSuggestions(){
	}*/
	
	
	public function possede($livre){
		$sqlposs = "SELECT id_book FROM vBiblio_poss WHERE id_book=".$livre->getID()." AND userid=".$this->identifiant;
		$resPoss= mysql_query($sqlposs);
		
		return ($resPoss && mysql_num_rows($resPoss)>0) ;
	}

	//vérifie si l'utilisateur a marque le livre comme "je l'ai"
	public function possedeVraiment($livre){
		$sqlposs = "SELECT id_book FROM vBiblio_poss WHERE id_book=".$livre->getID()." AND userid=".$this->identifiant." AND possede=1";
		$resPoss= mysql_query($sqlposs);
		
		return ( ($resPoss && mysql_num_rows($resPoss)>0)  ) ;
	}
	
	public function aDansUneListe($livre){
	$sqlposs = "SELECT id_book FROM vBiblio_poss WHERE id_book=".$livre->getID()." AND userid=".$this->identifiant;
		$resPoss= mysql_query($sqlposs);
		$sqlposs = "SELECT id_book FROM vBiblio_toReadList WHERE id_book=".$livre->getID()." AND id_user=".$this->identifiant;
		$resPoss2 = mysql_query($sqlposs);
		return ( ($resPoss && mysql_num_rows($resPoss)) || ($resPoss2 && mysql_num_rows($resPoss2)) );
	}
	
	public function aLu($Livre){	
		$sql = "SELECT * FROM vBiblio_poss 
					WHERE userid=".$this->identifiant." 
					AND lu=1 
					AND id_book=".$Livre->getID();
		
		$res= mysql_query($sql);
		return ($res && mysql_num_rows($res)>0);
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

	//fonction qui retourne le nombre de demande de contact envoyée à notre utilisateur
	public function recupererNombreDemandesDeContactEnAttente(){
		$sql = "SELECT COUNT(*) as nb FROM vBiblio_demande WHERE type like '%FRIENDS_REQUEST%' AND id_user_requested ='".$this->identifiant."' ";

		$result = mysql_query($sql);

		if($result && mysql_num_rows($result)>0 ){
			$row = mysql_fetch_assoc($result);
			return $row['nb'];
		}
		return 0;
	}
	
	//fonction qui retourne le nombre de demande de pret de livre faite à notre utilisateur
	public function recupererNombreDemandesDePretEnAttente(){
		$sql = "SELECT COUNT(*) as nb FROM vBiblio_demande WHERE type='BOOK_REQUEST' AND id_user_requested ='".$this->identifiant."' ";

		$result = mysql_query($sql);

		if($result && mysql_num_rows($result)>0 ){
			$row = mysql_fetch_assoc($result);
			return $row['nb'];
		}
		return 0;
	}
	
	
	/** FONCTIONS POUR LES SIMILARITES **/
	public function calculerCompatibiliteAmi($buddy){
		$total_mes_livres = $this->NbBooksInLibrary();
		$total_ses_livres = $buddy->NbBooksInLibrary();
		
		$compat = 0;
		
		$sql = "SELECT COUNT(DISTINCT id_book) as nb
			FROM vBiblio_poss
			WHERE userid='".$this->identifiant."'
			AND id_book IN (SELECT DISTINCT id_book FROM vBiblio_poss WHERE userid='".$buddy->identifiant."')";
		
		$result = mysql_query($sql);
		$livres_communs = 0;
		
		if($result && mysql_num_rows($result)>0 ){
			$row = mysql_fetch_assoc($result);
			$livres_communs = $row['nb'];
		}
		
		//éviter la division par 0
		if(($total_mes_livres+$total_ses_livres-$livres_communs)!=0){
			$compat = ($livres_communs / ($total_mes_livres+$total_ses_livres-$livres_communs))*100;
		}
		return $compat;
	}
	
	
	public function getAllTagsFromBooks(){
		$sql = "SELECT id_tag, SUM(count) as SOMME
			FROM vBiblio_tag_book, vBiblio_poss
			WHERE userid='".$this->identifiant."'
			AND vBiblio_tag_book.id_book=vBiblio_poss.id_book
			GROUP BY vBiblio_tag_book.id_book
			ORDER BY SOMME";
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0 ){
			$listTags = array();
			$idxTags = 0;
			
			while($row = mysql_fetch_assoc($result)){
				$listTags[$idxTags] = new Tag($row['id_tag']);
				$idxTags++;
			}
			
		}
		return $listTags;
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
	public function exists(){
		return $this->exists;
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
