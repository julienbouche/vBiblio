<?php
require('mysql_table_pdf.php');
include('accesscontrol.php');



checkSecurity();


$uid = $_SESSION['uid'];
$sql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$row = mysql_fetch_assoc($result);
	$mytableId = $row['tableuserid'];

	$reqSQL = "SELECT vBiblio_author.nom as nom, vBiblio_author.prenom as prenom, vBiblio_book.titre As titre FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user WHERE vBiblio_poss.userid = vBiblio_user.tableuserid AND vBiblio_user.tableuserid='$mytableId' AND vBiblio_poss.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author order by vBiblio_author.id_author";

	$resQuery = mysql_query($reqSQL);

	header("Content-Type: application/vnd.ms-excel"); //works fine even on UNIX-like systems
	header("Content-disposition: filename=exportVBiblio.csv");

	if ($resQuery && mysql_num_rows($resQuery) != 0) {
		  // titre des colonnes
		$fields = mysql_num_fields($resQuery);
		$i = 0;
		echo "ID;";
		while ($i < $fields) {
			echo mysql_field_name($resQuery, $i).";";
			$i++;
		}
		echo "\n";
		$i = 0;
		// données de la table
		while ($arrSelect = mysql_fetch_array($resQuery, MYSQL_ASSOC)) {
			echo "$i;";
			foreach($arrSelect as $elem) {
				echo "$elem;";
			}
			echo "\n";
			$i++;
		}
	}
}
?>

