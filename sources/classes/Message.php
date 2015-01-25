<?php
require_once('Utilisateur.php');

class Message{

	private $Expediteur;
	private $Destinataire;
	
	private $id_message;
	private $date_message;
	private $contenu_message;

	
	/* AJOUTER GESTION DES REPONSES */
	private $MessagePrecedent;
	private $MessageSuivant;


	public function __construct($idMessage){

		$this->id_message = $idMessage;

		$sql = "SELECT message, date, from_user, to_user 
		    FROM vBiblio_message 
		    WHERE id_message=".	$this->id_message;

		$result = mysql_query($sql);

		if($result && mysql_num_rows($result)>0){
			while($row=mysql_fetch_assoc($result)){			
				$this->date_message = $row['date'];
				$this->contenu_message = utf8_encode($row['message']);

				$this->Destinataire = new Utilisateur("");
				$this->Destinataire->initializeByID($row['to_user']);
			
				$this->Expediteur = new Utilisateur("");
				$this->Expediteur->initializeByID($row['from_user']);
			}
		}
	}

	public function getExpediteur(){
		return $this->Expediteur;
	}
	public function getDestinataire(){
		return $this->Destinataire;
	}

	public function getDate(){
		return $this->date_message;
	}

	public function getContent(){
		return $this->contenu_message;
	}

	public function getID(){
		return $this->id_message;
	}
}
