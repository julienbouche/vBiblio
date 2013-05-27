<?php

class Auteur{
	private $id;
	private $nom;
	private $Prenom;
	private $description;
	private $exists;
	
	

	public function __construct($idA){
		$this->id = $idA;

		$sql = "SELECT nom, prenom, description from vBiblio_author WHERE id_author=$idA";


		$result = mysql_query($sql);

		if($result && mysql_num_rows($result)>0){
			$this->prenom = mysql_result($result, 0, 'prenom');
			$this->nom = mysql_result($result, 0, 'nom');
			$this->description = mysql_result($result, 0, 'description');
			$this->exists = true;
		}else $this->exists = false;
	}


	public function exists(){
		return $this->exists;
	}
	public function getID(){
		return $this->id;
	}

	public function fullname(){
		return $this->prenom." ".$this->nom;
	}
	
	public function retournerPrenom(){
		return $this->prenom;
	}
	
	public function retournerNom(){
		return $this->nom;
	}
	
	public function retournerDescription(){
		return $this->description;
	}
	
	public function getShortName(){
		if(strlen($this->prenom)>=1) $tmp = substr($this->prenom, 0, 1).". ";
		$tmp = strtoupper($tmp);
		return $tmp.$this->nom;
	}

	public function retournerListeLivres(){
		$sql = "SELECT  distinct id_book, titre 
			FROM vBiblio_book 
			WHERE vBiblio_book.id_author=".$this->id." 
			ORDER BY id_cycle, numero_cycle";

		$result = mysql_query($sql);
		if($result && mysql_num_rows($result) > 0){
			$livres = array();
			$cpt = 0;
			
			while($row = mysql_fetch_assoc($result)){
				$livres[$cpt] = new Livre($row['id_book']);
				$cpt++;
			}
		}
		return $livres;
	}

	public function retournerListeAuteursSimilaires(){
		//TODO trouver une méthode pour la similarité... par les tags des livres ?
		return array();
	}
}
?>
