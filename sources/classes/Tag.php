<?php
require_once("Livre.php");

class Tag{
	private $identifiant;
	private $name;
	private $exist;
	/*
		CONSTRUCTOR
	*/
	public function __construct($id_tag){
		$this->identifiant = intval($id_tag);
		$this->exist = false;

		$sql = "SELECT value FROM vBiblio_tag WHERE id_tag=".$id_tag;
		$resT = mysql_query($sql);
		
		if($resT && mysql_num_rows($resT) > 0){
			$row = mysql_fetch_assoc($resT);
			$this->name = $row['value'];
			$this->exist = true;
		}
	}

	public function recupererListeLivreTaggueNonDansUneListeUtilisateur($utilisateur){
		$sql ="SELECT DISTINCT vBiblio_book.id_book as id_book
			FROM vBiblio_book, vBiblio_author, vBiblio_tag_book
			WHERE vBiblio_book.id_author = vBiblio_author.id_author
			AND vBiblio_book.id_book = vBiblio_tag_book.id_book
			AND id_tag=".$this->identifiant."
			AND vBiblio_book.id_book NOT IN (SELECT vBiblio_poss.id_book FROM vBiblio_poss WHERE vBiblio_poss.userid=".$utilisateur->getID()." )  
			AND vBiblio_book.id_book NOT IN (SELECT vBiblio_toReadList.id_book FROM vBiblio_toReadList WHERE vBiblio_toReadList.id_user=".$utilisateur->getID().")";
		$ListeLivres = array();
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result) > 0){
			$cpt=0;
			while($row=mysql_fetch_assoc($result)){
				$ListeLivres[$cpt] = new Livre($row['id_book']);
				$cpt++;
			}
		}
		return $ListeLivres;
	}

	/*
		GETTER
	*/
	public function getID(){
		return $this->identifiant;
	}

	public function getName(){
		return $this->name;
	}
	
	public function exists(){
		return $this->exist;
	}
}

?>
