<?php


class SiteConfiguration {
    #code   

    public function __construct(){
        //TODO init things
    }
    
    public function getParameter($parameter_name){
        $parameter_name = mysql_real_escape_string($parameter_name);
        $sql = "SELECT param_value FROM vBiblio_config WHERE param_name='$parameter_name'";
        
        $result = mysql_query($sql);
        
        if( $result && mysql_num_rows($result)==1 ){
            $row = mysql_fetch_assoc($result);
            return $row['param_value'];
        }
        else die("Parametre $parameter_name n'existe pas!");
    }
}



?>