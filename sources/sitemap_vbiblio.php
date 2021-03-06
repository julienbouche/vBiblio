<?php
require_once('scripts/db/db.php');

header('Content-Type: text/xml');
echo "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">";

?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
<?php
	dbConnect();
	
	//lister toutes les pages publiques utilisateur activées
	$requete="SELECT user.userid, date_ajout
	FROM vBiblio_user as user, vBiblio_poss 
	WHERE active_public_page=1 
	AND tableuserid=vBiblio_poss.userid
	AND date_ajout =(SELECT MAX(date_ajout) FROM vBiblio_poss WHERE vBiblio_poss.userid = user.tableuserid)
	ORDER BY date_ajout desc;";
	$u=mysql_query($requete) or die(mysql_error('erreur'));
	
	while($data = mysql_fetch_array($u)){
		?>
	    <url>
			<loc>http://vbiblio.free.fr/public/user/<?=$data['userid']?></loc>
			<lastmod><?=substr($data['date_ajout'], 0, 10)?></lastmod>
			<changefreq>monthly</changefreq>
	    </url>
		<?php
	}


	//lister tous les livres
	$sql = "select id_book FROM vBiblio_book";
	$u=mysql_query($sql) or die(mysql_error('erreur'));

	while($data = mysql_fetch_assoc($u) ) {
	 ?>
	<url>
		<loc>http://vbiblio.free.fr/ficheLivre.php?id=<?=$data['id_book']?></loc>
		<changefreq>YEARLY</changefreq>
	</url>
	<?php
	}
	
	//lister tous les cycles
	$sql = "select id_cycle FROM vBiblio_cycle";
	$u=mysql_query($sql) or die(mysql_error('erreur'));

	while($data = mysql_fetch_assoc($u) ) {
	 ?>
	<url>
		<loc>http://vbiblio.free.fr/cycle.php?id=<?=$data['id_cycle']?></loc>
		<changefreq>YEARLY</changefreq>
	</url>
	<?php
	}

?>

</urlset>
