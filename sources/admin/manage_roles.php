<?php
require_once('../accesscontrol.php');
require_once('../scripts/dateFunctions.php');
require_once('../scripts/common.php');
require_once('../classes/Utilisateur.php');
require_once("../classes/Groupe.php");



$uid = $_SESSION['uid'];
$utilisateur = new Utilisateur($uid);

if(!$utilisateur->belongToGroup('SYS_ADMINS')){
	 header('Location:../index.php');
}

//gestion des différents formulaires
if(isset($_POST['toberemoveduserid'])){
    $userid= intval($_POST['toberemoveduserid']);
    $group = intval($_POST['role_selected']);
    
    $sql="DELETE FROM vBiblio_acl_role_user WHERE id_role=$group AND id_user=$userid";
    mysql_query($sql);
}

//utilisateur à ajouter au groupe
if(isset($_POST['role_selected']) && isset($_POST['users'])){
    if(is_array($_POST['users'])){
        $group_id = intval($_POST['role_selected']);
        $users = $_POST['users'];
        foreach($users as $userToAdd){
            $sql = "INSERT INTO vBiblio_acl_role_user(id_role,id_user) VALUES($group_id, $userToAdd)";
            mysql_query($sql);
        }
    }
}

?>
<!DOCTYPE XHTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vBiblio - ADMINISTRATION</title>  
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
            
	<link rel="stylesheet" type="text/css" href="../css/vBiblio.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="../css/vBiblio_admin.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="../css/popInside.css" media="screen" />
        
        <script type='text/javascript' src='../js/gui/insidepopup.js'></script>
        <script type="text/javascript">
        //<![CDATA[
function deleteSelectedUserFromSelectedGroup(group_id){
    deleteUserForm = document.getElementsByName("deleteUserForm")[0];
    
    //récupérer l'utilisateur sélectionné
    userlist = document.getElementsByName("userlist")[0];
    selected_user_id = userlist.options[userlist.selectedIndex].value;
    
    //mettre à jour la valeur dans le form de suppression
    document.getElementsByName("toberemoveduserid")[0].value = selected_user_id;
    
    //demander confirmation
    if(confirm('Etes-vous sur de vouloir enlever l\'utilisateur '+userlist.options[userlist.selectedIndex].text+' du groupe?')){
        //soumettre formulaire
        deleteUserForm.submit();
    }
}

function addUsersToGroup(group_id) {
    //TODO
    
    popinside_show('fenetreConseilAmi');    
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
            <form method="POST" action="<?=$_SERVER['PHP_SELF']?>" style="display:inline;">
                <select size=10 multiple onchange="this.form.submit();" name="role_selected">
                <?php $groups = getAllGroups(); ?>
		
		<?php foreach($groups as $group) : ?>
		    <?php if($_POST['role_selected']==$group->getID()) : ?>
			<option selected value="<?=$group->getID()?>"><?=$group->getName()?></option>
		    <?php else: ?>
			<option value="<?=$group->getID()?>"><?=$group->getName()?></option>
		    <?php endif; ?>
		<?php endforeach; ?>
                </select>
            </form>
            
            <select size=10 name="userlist">
            <?php
                $sql = "SELECT vBiblio_user.userid as pseudo, tableuserid as userid FROM vBiblio_acl_role_user, vBiblio_user WHERE vBiblio_acl_role_user.id_user=vBiblio_user.tableuserid AND id_role=".$_POST['role_selected']." ORDER BY userid ASC";
                $result = mysql_query($sql);
                
                if($result && mysql_num_rows($result)>0){
                    ?>    
            
                <?php while ($row=mysql_fetch_assoc($result)) : ?>
                    <option value="<?=$row['userid']?>"><?=$row['pseudo']?></option> 
                <?php endwhile; ?>
            
                    <?php
                }
                
            ?>
            </select>
            <img class="ImgAction" onclick="javascript:addUsersToGroup(<?=$_POST['role_selected']?>)" src="../images/addToList2.png"  style="border:1px solid gray;padding:2px;float:top;margin-right:5px" alt="Ajouter" title="Ajouter" width="20px" height="20px"/><br>
            <img class="ImgAction" onclick="javascript:deleteSelectedUserFromSelectedGroup();" src="../images/supp.png"  style="float:top;border:1px solid gray;padding:2px;margin-right:5px" alt="Enlever l'utilisateur du groupe" title="Enlever l'utilisateur du groupe" width="20px" height="20px"/>
            <form method="POST" action="<?=$_SERVER['PHP_SELF']?>" name="deleteUserForm">
                <input type="hidden" name="role_selected" value="<?=$_POST['role_selected']?>" />
                <input type="hidden" name="toberemoveduserid" value=""/>
            </form>
            <br/>
            

            <img src="../images/save.png" title="Sauvegarder" alt="Sauvegarder" style="border:1px solid gray;padding:2px;float:right;"/>
	</div>
        
        <div id="fenetreConseilAmi" class="insideWindow">
	<span class="insideWindowTitle">Utilisateurs</span><span class="insideWindowCloser" onclick="popinside_close('fenetreConseilAmi')">X</span>
	<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
            <div class="insideWindowContent" >
                <input type="hidden" name="role_selected" value="<?=$_POST['role_selected']?>" />
        <?php
                $users = getAllUsersNotBelongingToTheGroup($_POST['role_selected']);
        ?>
                <?php if (count($users)>0) : ?>
                        <table class="vBibTablePret" id="formAdviseFriends">
                        <?php foreach ($users as $buddy) : ?>
                                <tr>
                                        <td style="width:90%;text-align:left;">
                                                <?=$buddy->getPseudo()?>
                                        </td>	
        
                                        <td>
                                                <input type="checkbox" name="users[]" value="<?=$buddy->getID()?>" />
                                        </td>
                                </tr>
                        <?php endforeach; ?>
                        </table>
                <?php endif; ?>
    
            
            </div>
            <input type="submit" class="vert" value="Valider" onclick="submitForm(document.getElementById('formAdviseFriends'), <?=$_GET['id']?>, <?=$utilisateur->getID()?>)" style="float:right;margin-right:5px"/>
            <input type="button" class="gris" value="Annuler" onclick="popinside_close('fenetreConseilAmi')" style="float:right;margin-right:5px"/>
        </form>
    </div> 
        
        
        <?php include('../footer.php'); ?>
</div>
</body>
</html>
