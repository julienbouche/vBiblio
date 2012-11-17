<?php

function writeTags($idBook){
	echo "<div style=\"padding-left:20px;\">";
	$sqlReq = "SELECT value, vBiblio_tag.id_tag as idtag, count 
			FROM vBiblio_tag, vBiblio_tag_book 
			WHERE vBiblio_tag_book.id_tag = vBiblio_tag.id_tag 
			AND id_book=".$idBook."
			ORDER BY count DESC";
	//echo "$sqlReq";
	$results = mysql_query($sqlReq) or die("erreur");
	
	if($results && mysql_num_rows($results)>0 ) {
		?>
		
		<ul id="vBiblio_tagcloud">
		<?
		while($row = mysql_fetch_assoc($results)){
			?>
			<li class="tag"><div style="display:inline-block;"><a href="searchByTag.php?idtag=<?=$row['idtag']?>" class="vBibLink" title="Rechercher d'autres livres"><?=$row['value']?></a></div></li>
			<?
		}
		?>
		</ul>
		
		<?

	}
	else echo "Aucun tag associ&eacute; &agrave; ce livre";
 	echo "</div>";

?>

<?
}


?>
