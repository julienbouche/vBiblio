<?php
require_once('Auteur.php');


class Livre{
  private $id;
  private $exist;
  private $titreCourt;
  private $titreLong;
  private $belongToCycle;
  //private $titre;
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
  
  function __construct($idBook){
    $this->id=$idBook;
    //r�cup�rer les valeurs du livre
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
  
  function afficherDescription(){
    //r�cup�rer la description
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
  
	//TODO Améliorer la fonction pour trouver des livres différents, tous ceux de la meme série, etc.
	public function retournerAutresLivresMemeAuteur(){
		$sql = "SELECT  distinct id_book FROM vBiblio_book WHERE vBiblio_book.id_author=".$this->idauteur." AND id_book<>".$this->id." LIMIT 0,5";

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
	
  //classe Auteur ?
  function retournerAuteur(){
    return new Auteur($this->idauteur);
  }
  
  function retournerLivresSimilaires(){
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
  

}

?>
