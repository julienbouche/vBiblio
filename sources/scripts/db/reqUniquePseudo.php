<?php
include('../../scripts/db/db.php');

if(isset($_GET['q']) && trim($_GET['q'])!=''){
    dbConnect();
    $pseudo = trim(mysql_real_escape_string($_GET['q']));
    
    $sql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$pseudo'";
    $res = mysql_query($sql) or die(mysql_error());
    
    if($res && mysql_num_rows($res)==0) echo "true";
    else echo "false";
}
else echo "false";

?>