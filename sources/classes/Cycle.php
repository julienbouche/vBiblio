<?php
require_once('Livre.php');
require_once('Auteur.php');
require_once('Tag.php');

class Cycle{
    private $id;
    private $title;
    private $nbTomes;
    private $idauteur;
    private $exist;

    public function __construct($idCycle){
        $idCycle = intval($idCycle);
        
        $sql="SELECT titre, nb_tomes, id_author FROM vBiblio_cycle WHERE id_cycle=$idCycle";
        $result = mysql_query($sql);
        
        if($result && mysql_num_rows($result)>0){
            $this->id=$idCycle;
            $this->exist = true;
            $row = mysql_fetch_assoc($result);
            $this->idauteur = $row['id_author'];
            $this->nbTomes = $row['nb_tomes'];
            $this->title = $row['titre'];
        }
        else $this->exist = false;
    }
    
    
    public function getBooks(){
        $sql="SELECT id_book FROM vBiblio_book WHERE id_cycle=".$this->id." ORDER BY numero_cycle ASC";
        $result = mysql_query($sql);
        
        if($result && mysql_num_rows($result)>0){
            $listeLivres= array();
            $listIdx = 0;
            
            while($row=mysql_fetch_assoc($result)){
                $listeLivres[$listIdx] = new Livre($row['id_book']);
                $listIdx++;
            }
        }
        return $listeLivres;
    }
    
    public function getCalculatedTags(){
        
        $sql ="SELECT vBiblio_tag.id_tag as idtag, SUM(count) as total
			FROM vBiblio_tag, vBiblio_tag_book, vBiblio_book
			WHERE vBiblio_tag_book.id_tag = vBiblio_tag.id_tag 
			AND vBiblio_tag_book.id_book=vBiblio_book.id_book
                        AND id_cycle=".$this->id."
                        GROUP BY idtag
			ORDER BY total DESC";      
        
        $result = mysql_query($sql);

        if($result && mysql_num_rows($result)>0){
            $listeTags= array();
            $TagsIdx = 0;
            
            while($row=mysql_fetch_assoc($result)){
                $listeTags[$TagsIdx] = new Tag($row['idtag']);
                $TagsIdx++;
            }
        }
        return $listeTags;
    }
    
    public function getAuthor(){
        return new Auteur($this->idauteur);
    }
    
    public function getTitle(){
        return $this->title;
    }
    
    public function getID(){
        return $this->id;
    }
}

?>