<?php
require_once('../accesscontrol.php');
require_once('../scripts/dateFunctions.php');
require_once('../scripts/common.php');
require_once('../classes/Utilisateur.php');


$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);


?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - ADMINISTRATION</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="../css/vBiblio.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="../css/vBiblio_admin.css" media="screen" />
        <script type="text/javascript">
        //<![CDATA[
function deleteWithConfirmation(element){
    
    if(confirm('Etes vous sur de vouloir supprimer la variable')){
        
    }
}
        //]]>
        </script>
</head>
<body>
<div id="vBibContenu">
	<? include('../header.php'); ?>

	<div id="vBibDisplay">
            
            <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                <input name="param_value" value="" placeholder="Nom du r&ocirc;le" />
                <input type="submit" value="+" class="vert"/>
            </form>
            <?php
                //si une valeur a été postée, on l'insert
                if(isset($_POST['param_value'])){
                    $value = mysql_real_escape_string($_POST['param_value']);
                    
                    if($name!=''){
                        $sql= "INSERT INTO vBiblio_acl_role(role_name) VALUES('$value')";
                        mysql_query($sql);
                    }
                }
            ?>
            
            <select multiple size=10  onselect="alert('item selected');">
            <?php
                $sql = "SELECT role_name, id_role FROM vBiblio_acl_role ORDER BY role_name ASC";
                $result = mysql_query($sql);
                
                if($result && mysql_num_rows($result)>0){
                    ?>    
            
                <?php while ($row=mysql_fetch_assoc($result)) : ?>
                    <option value="<?=$row['id_role']?>"><?=$row['role_name']?></option> 
                <?php endwhile; ?>
            
                    <?
                }
                
            ?>
            </select>
            
            
            
            <br/>
            

            <img src="../images/save.png" title="Sauvegarder" alt="Sauvegarder" style="border:1px solid gray;padding:2px;float:right;"/>
	</div>	
        <? include('../footer.php'); ?>
</div>
</body>
</html>
