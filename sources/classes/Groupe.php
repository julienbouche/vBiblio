<?php
require_once("Utilisateur.php");

class Groupe {
    #code   
    private $name;
    private $id;
    private $exist;
    
    public function __construct($name){
        $name = trim(mysql_real_escape_string($name));
        $sql = "SELECT id_role FROM vBiblio_acl_role WHERE role_name='$name'";
        $this->exist = false;
        $result = mysql_query($sql);
        
        if($result && mysql_num_rows($result)==1){   
            if($row = mysql_fetch_assoc($result)){
                $this->id = $row['id_role'];
                $this->name = $name;
                $this->exist = true;
                
            }
        }
        
    }
    
    public static function fromID($id){
        $id = intval($id);
        $sql = "SELECT role_name FROM vBiblio_acl_role WHERE id_role=$id";
        $result = mysql_query($sql);
        
        if($result && mysql_numrows($result)==1){
            if($row=mysql_fetch_assoc($result)){
                return new Groupe($row['role_name']);
            }
        }
        
        return null;
    }
    
    
    //fonction retournant tous les utilisateurs appartenant à ce groupe 
    public function getAllMembers(){
        //TODO
        $sql =" SELECT userid
		FROM vBiblio_user, vBiblio_acl_role_user
		WHERE tableuserid<>0
		AND tableuserid = id_user
		AND id_role=".$this->id.")";
		
        $results = mysql_query($sql);
        $users = array();
        
        if($results && mysql_num_rows($results)>0){
            $cpt = 0;
            while($row = mysql_fetch_assoc($results)){
                $users[$cpt] = new Utilisateur($row['userid']);
                $cpt++;
            }
        }
        
        return $users;
    }
    
    public function addUser($newUser){
        //TODO
    }
    
    public function removeUser($user){
        //TODO
    }
    
    public function getID(){
        return $this->id;
    }
    
    public function getName(){
        return $this->name;
    }
}


function getAllGroups(){
    $sql = "SELECT role_name FROM vBiblio_acl_role ORDER BY role_name ASC";
    $result = mysql_query($sql);
    
    if($result && mysql_numrows($result)>0){
        $groups = array();
        $cpt = 0;
        
        while($row=mysql_fetch_assoc($result)){
            $groups[$cpt] = new Groupe($row['role_name']);
            $cpt++;    
        }
        return $groups;
    }
    
    return null;
    
}

?>