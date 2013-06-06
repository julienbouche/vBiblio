<?php
require_once('Auteur.php');
require_once('Tag.php');


class Livre{
	private $id;
	private $exist;
	private $titreCourt;
	private $titreLong;
	private $belongToCycle;
	private $tome;
	private $nomCycle;
	private $idCycle;
	private $idauteur;
	private $isbn;
	private $description;  
	private $exists;  
	private $nb_tomes_cycle;
	private $nbVotants;
	private $totalVotes;  
	private $nextBookID;
	private $previousBookID;
  
  function __construct($idBook){
    $this->id=$idBook;
    //récupérer les valeurs du livre
    $sql = "SELECT titre, id_author, id_cycle, numero_cycle, isbn, description, total_votes, nb_votes
            FROM vBiblio_book
            WHERE id_book=$idBook 
            ";
    $result = mysql_query($sql);

    if($result && mysql_num_rows($result)>0 ){
      $row = mysql_fetch_assoc($result);
      $this->titreCourt = $row['titre'];
      $this->idauteur = $row['id_author'];
      $this->idCycle = $row['id_cycle'];
      $this->tome = $row['numero_cycle'];
      $this->isbn = $row['isbn'];
	  $this->description = $row['description'];
	  $this->totalVotes = $row['total_votes'];
	  $this->nbVotants = $row['nb_votes'];
      $this->exists = true;
	  
	  
      $sql_cycle= "SELECT vBiblio_cycle.titre, nb_tomes FROM vBiblio_cycle, vBiblio_book WHERE vBiblio_book.id_cycle=vBiblio_cycle.id_cycle AND vBiblio_book.id_book=".$this->id;
      

	$cycles = mysql_query($sql_cycle);
	if($cycles && mysql_num_rows($cycles) > 0) {
		$this->belongToCycle = true;
		$this->nomCycle = mysql_result($cycles, 0, 'titre');
		$this->titreLong = $this->nomCycle.", Tome ".$this->tome." : ".$this->titreCourt;
		$this->nb_tomes_cycle = mysql_result($cycles, 0, 'nb_tomes');
	}
	else {
		$this->belongToCycle = false;
		$this->titreLong = $this->titreCourt;
	}
	//$this->nextBook = new Livre(0);
	
    }else $this->exists=false;
  }

	function TitreLong(){
		return $this->titreLong;
	}
	
	public function TitreLongAsShortNames(){
			if($this->belongToCycle){
				$str = (strlen($this->nomCycle)>20) ? substr($this->nomCycle, 0, 20)."..." : $this->nomCycle;
				$str .= " (T".$this->tome.") : ";
				$str .= (strlen($this->titreCourt)>20) ? substr($this->titreCourt, 0, 20)."..." : $this->titreCourt;
			}
			else 
				$str .= (strlen($this->titreCourt)>40) ? substr($this->titreCourt, 0, 40)."..." : $this->titreCourt;
			return $str;
	}
	public function TitreCourt(){
		return $this->titreCourt;
	}

	function getID(){
			return $this->id;
	}
  
	function retournerDescription(){
		return $this->description;
	}
  
  //deprecated ?
  function afficherDescription(){
    //récupérer la description
    $str = recupererDescription();  
    //afficher la description
    
  }


	public function retournerNbVotants(){
		return $this->nbVotants;  
	}
	
	public function retounerTotalVotes(){
		return $this->totalVotes;
	}
	
	public function retournerNote(){
		return round($this->totalVotes/$this->nbVotants, 1);
	}
  
	//TODO AmÃ©liorer la fonction pour trouver des livres diffÃ©rents, tous ceux de la meme sÃ©rie, etc.
	public function retournerAutresLivresMemeAuteur(){
		$sql = "SELECT distinct id_book FROM vBiblio_book WHERE vBiblio_book.id_author=".$this->idauteur." AND id_book<>".$this->id." LIMIT 0,5";

		$result = mysql_query($sql);


		if($result && mysql_num_rows($result) > 0){
			$livres = array();
			$i = 0;
			while($row = mysql_fetch_assoc($result)){
				$livres[$i] = new Livre($row['id_book']);
				$i++;
			}
		}
		return $livres;
	}
	
	public function isRequested($fromUser, $toUser){
		$sql = "SELECT * FROM vBiblio_demande WHERE id_user=".$fromUser." AND id_user_requested=".$toUser." AND id_requested=".$this->id;
		
		$result = mysql_query($sql);

		if($result) return mysql_num_rows($result)>0;
		else return false;
	}
	
	function retournerAuteur(){
		return new Auteur($this->idauteur);
	}
	
	/**
	*TODO trouver un algo pour déterminer la similarité entre deux livres
	* basé sur les tags ? tous les tags ? regle des 80/20? sur le fait que les utilisateurs possèdent les deux ? 
	*/
	function retournerListeLivresSimilaires(){
	}
  
	function retournerURL(){
		return "ficheLivre.php?id=".$this->id;
	}
  
	function dansUnCycle(){
		return $this->belongToCycle;
	}

	public function retournerNomCycle(){
		return $this->nomCycle;
	}
	
	public function retournerMaxTomesCycle(){
		return $this->nb_tomes_cycle;
	}
	
	public function retournerNumeroTome(){
		return $this->tome;
	}
	
	public function retournerISBN(){
		return $this->isbn;
	}

	public function exists(){
		return $this->exists;
	}

	public function getTagsOrdered(){
		$sqlReq = "SELECT vBiblio_tag.id_tag as idtag, count 
			FROM vBiblio_tag, vBiblio_tag_book 
			WHERE vBiblio_tag_book.id_tag = vBiblio_tag.id_tag 
			AND id_book=".$this->id."
			ORDER BY count DESC";
		
		$results = mysql_query($sqlReq) or die("erreur".mysql_error());
		$listTags = array();
	
		if($results && mysql_num_rows($results)>0 ) {
			$cpt = 0;
			while($row = mysql_fetch_assoc($results)){
				$listTags[$cpt] = new Tag($row['idtag']);
				$cpt++;
			}
		}
		return $listTags;
	}
  
	public function hasNext(){
		if($this->dansUnCycle() && $this->retournerNumeroTome() < $this->retournerMaxTomesCycle() ){
			$sql= "SELECT vBiblio_book.id_book 
				FROM vBiblio_cycle, vBiblio_book 
				WHERE vBiblio_book.id_cycle=vBiblio_cycle.id_cycle 
				AND vBiblio_cycle.id_cycle =".$this->idCycle."
				AND vBiblio_book.numero_cycle=".($this->tome+1);
			$result = mysql_query($sql);

			if($result && mysql_num_rows($result)>0 ){
				$row = mysql_fetch_assoc($result);
				$this->nextBookID = $row['id_book'];

				return true;
			}
			else return false;
		}
		else return false;
	}
	public function getNext(){
		return new Livre($this->nextBookID);
	}

	public function hasPrevious(){
		if($this->dansUnCycle() && $this->retournerNumeroTome()>1){
			$sql= "SELECT vBiblio_book.id_book 
				FROM vBiblio_cycle, vBiblio_book 
				WHERE vBiblio_book.id_cycle=vBiblio_cycle.id_cycle 
				AND vBiblio_cycle.id_cycle =".$this->idCycle."
				AND vBiblio_book.numero_cycle=".($this->tome-1);
			$result = mysql_query($sql);

			if($result && mysql_num_rows($result)>0 ){
				$row = mysql_fetch_assoc($result);
				$this->previousBookID = $row['id_book'];

				return true;
			}
			else return false;
		}else return false;
	}
	public function getPrevious(){
		return new Livre($this->previousBookID);
	}

	public function getAvatarPath(){
		$strImgPath = "images/covers/book-".$this->id.".png";
		if(file_exists($strImgPath)){
			return $strImgPath;
		}
		
		return "images/covers/no_cover2.jpg";
	}
	
	public function getIDCycle(){
		return $this->idCycle;
	}
}

?>
