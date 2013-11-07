<?php


class SiteConfiguration {
    #code
    private $params;

    public function __construct(){
        //init things
        $this->reloadValuesFromDB();
        
    }
    
    /**
     * Fonction permettant de charger les valeurs actuellement en base
     */
    private function reloadValuesFromDB(){
        $sql = "SELECT id_param, param_name, param_value FROM vBiblio_config ";
        $result = mysql_query($sql);
        
        $this->params = array();
        
        if($result && mysql_num_rows($result)>0 ){
            while($row = mysql_fetch_assoc($result)){
                $this->params[$row['param_name']] = array();
                $this->params[$row['param_name']][0] = $row["id_param"];
                $this->params[$row['param_name']][1] = $row["param_value"];
            }
        }
    }
    
    /*
     * Fonction permettant de vérifier l'existence d'une clé dans la liste des paramètres du site
     */
    public function exists($key){
        //TODO à tester
        return isset($this->params[$key]);
    }
    
    
    /*
     * Fonction permettant de créer un paramètre (clé, valeur) en base
     */
    public function createParam($key, $value){
        $name = trim(mysql_real_escape_string($key));
        $value = trim(mysql_real_escape_string($value));
        if($name!=''){
            $sql= "INSERT INTO vBiblio_config(param_name, param_value) VALUES('$name', '$value')";
            mysql_query($sql);
            
            //on recharge les valeurs pour être sûr de la synchro DB/RAM: nécessaire?
            $this->reloadValuesFromDB();
        }
    }
    
    /*
     * fonction permettant de mettre à jour la valeur d'un paramètre dans la base de données
     */
    public function update($id, $value){
        $id=intval($id);
        $value=trim(mysql_real_escape_string($value));
        
        
        if($value !=''){
            $sql="UPDATE vBiblio_config SET param_value='$value' WHERE id_param=$id";
            mysql_query($sql);
            
            $this->reloadValuesFromDB();
        }
        
    }
    
    public function delete($param_id){
        $param_id = trim(mysql_real_escape_string($param_id));
        $sql= "DELETE FROM vBiblio_config where id_param=$param_id";
        mysql_query($sql);
        
        $this->reloadValuesFromDB();
    }
    
    /*
     * Fonction permettant de récupérer la liste de tous les paramètres du site 
     */
    public function getParams(){
        return $this->params;
    }
    
    /*
     * Fonction permettant de récupérer la valeur du paramètre passé en paramètre
     */
    public function getParameter($parameter_name){
        if($this->exists($parameter_name)){
            return $this->params[$parameter_name][1];
        }
    }
    
}



?>