<?header('Content-Type: text/xml');
echo "<"."?xml version=\"1.0\" encoding=\"UTF-8\"?".">";

?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
<?
$serveur = "http://sql.free.fr";
$login = "vBiblio" ;
$mdp = "********";

$bdname = "vBiblio";

//connexion  au serveur sql
$db = mysql_connect($serveur, $login, $mdp);

mysql_select_db($bdname, $db);


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
	<?
}


	$sql = "select id_book FROM vBiblio_book";
	$u=mysql_query($sql) or die(mysql_error('erreur'));

	while($data = mysql_fetch_assoc($u) ) {
	 ?>
	<url>
	<loc>http://vbiblio.free.fr/ficheLivre.php?id=<?=$data['id_book']?></loc>
	<changefreq>YEARLY</changefreq>
	</url>
	<?
	}

?>

</urlset>
