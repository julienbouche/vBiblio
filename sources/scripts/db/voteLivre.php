<?php
include('db.php');



if(isset($_GET['note']) && isset($_GET['idbook']) ){
	dbConnect();

	$id_book = $_GET['idbook'];
	$note = intval($_GET['note']);
	
	$sql = "SELECT nb_votes, total_votes FROM vBiblio_book WHERE id_book=$id_book";
	$result = mysql_query($sql);

	if($result && mysql_num_rows($result)>0 ) {
		$nbvotes = intval(mysql_result($result,0,'nb_votes'));
		$total = intval(mysql_result($result,0,'total_votes'));
	
		$nbvotes= $nbvotes +1;
		$total = $total + $note;

		$sql = "UPDATE vBiblio_book SET nb_votes=$nbvotes, total_votes=$total WHERE id_book=$id_book";
		mysql_query($sql);

	}
}


?>
