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
                <input name="param_name" value="" placeholder="NOM" />
                <input name="param_value" value="" placeholder="VALEUR" />
                <input type="submit" value="+" class="vert"/>
            </form>
            <?php
                //si une valeur a été postée, on l'insert
                if(isset($_POST['param_name']) && isset($_POST['param_value'])){
                    $name = trim(mysql_real_escape_string($_POST['param_name']));
                    $value = mysql_real_escape_string($_POST['param_value']);
                    
                    if($name!=''){
                        $sql= "INSERT INTO vBiblio_config(param_name, param_value) VALUES('$name', '$value')";
                        mysql_query($sql);
                    }
                }
            ?>
            
            
            <?php
                $sql = "SELECT id_param, param_name, param_value FROM vBiblio_config ORDER BY param_name ASC";
                $result = mysql_query($sql);
                
                if($result && mysql_num_rows($result)>0){
                    ?>    
            <table style="width:100%;">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>NOM</td>
                        <td>VALEUR</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row=mysql_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?=$row['id_param']?></td>   
                        <td><input type="text" value="<?=$row['param_name']?>" /></td>
                        <td><input type="text" value="<?=$row['param_value']?>" /></td>
                        <td><input type="button" class="alert" value="X" onclick="javascript:deleteWithConfirmation(this);"/></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
                    <?
                }
            ?>
            <br/>
            

            <img src="../images/save.png" title="Sauvegarder" alt="Sauvegarder" style="border:1px solid gray;padding:2px;float:right;"/>
	</div>	
        <? include('../footer.php'); ?>
</div>
</body>
</html>
