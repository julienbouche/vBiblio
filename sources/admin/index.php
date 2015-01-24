<?php
require_once('../accesscontrol.php');
require_once('../scripts/dateFunctions.php');
require_once('../scripts/common.php');
require_once('../classes/Utilisateur.php');
require_once('../classes/SiteConfiguration.php');


$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

if(!$utilisateur->belongToGroup('SYS_ADMINS')){
	 header('Location:../index.php');
}

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
function deleteWithConfirmation(name, id){
    if(confirm('Etes vous sur de vouloir supprimer la variable '+name)){
        document.getElementsByName("deleteForm"+id)[0].submit();
    }
}

function save(id) {
    //code
    document.getElementsByName("saveForm"+id)[0].submit();
}
        //]]>
        </script>
</head>
<body>
<div id="vBibContenu">
	<?php include('../header.php'); ?>

	<div id="vBibDisplay">
            
            <?php include('menu.php'); ?>
            
            <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                <input name="param_name" value="" placeholder="NOM" />
                <input name="param_value" value="" placeholder="VALEUR" />
                <input type="submit" value="+" class="vert"/>
            </form>
            <?php
                //récupération des paramètres du site
                $config = new SiteConfiguration();
                
                //si on supprime une valeur
                if(isset($_POST['del_param_id']) && trim($_POST['del_param_id'])!="" ){
                    $config->delete($_POST['del_param_id']);
                }
                
                //si une valeur a été postée, on l'insert
                if(isset($_POST['param_name']) && isset($_POST['param_value'])){
                    $config->createParam($_POST['param_name'], $_POST['param_value']);
                }
                
                //modification d'une valeur
                if(isset($_POST["saveparamid"])){
                    $config->update($_POST["saveparamid"],$_POST["saveparamvalue"]);
                }
            ?>
            
            <?php
                $params = $config->getParams();
                
                if(count($params)>0){    
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
                    
                <?php foreach($params as $param_name=>$vals) : list($id_param,$param_value)=$vals; ?>
                    <tr>
                        <td><?=$id_param?></td>
                        <td>
                            <input type="text" value="<?=$param_name?>" />
                        </td>
                        <td>
                            <form name="saveForm<?=$id_param?>" method="POST" action="<?=$_SERVER['PHP_SELF']?>" >
                                <input type="text" name="saveparamvalue" value="<?=$param_value?>" />
                                <input type="hidden" name="saveparamid" value="<?=$id_param?>" />
                            </form>
                        </td>
                        <td>
                           
                            <form style="float:right" method="POST" action="<?=$_SERVER['PHP_SELF']?>" name="deleteForm<?=$id_param?>">
                                <input type="hidden" value="<?=$id_param?>" name="del_param_id">
                                <img class="ImgAction" onclick="javascript:deleteWithConfirmation('<?=$param_name?>', <?=$id_param?>);" src="../images/supp.png"  style="border:1px solid gray;padding:2px;float:right;margin-right:5px" alt="Supprimer" title="Supprimer" width="20px" height="20px"/>
                                <!--input type="submit" class="alert" value="X" title="Supprimer la valeur" onvalidate="javascript:deleteWithConfirmation('<?=$param_name?>');"/-->
                            </form>
                            <img class="ImgAction" onclick="javascript:save('<?=$id_param?>');" src="../images/checkmark.png"  style="border:1px solid gray;padding:2px;float:right;margin-right:5px" alt="Sauvegarder" title="Sauvegarder" width="20px" height="20px"/>
    
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
                    <?php
                }
            ?>
            <br/>
	</div>	
        <?php include('../footer.php'); ?>
</div>
</body>
</html>
