<?php
require('mysql_table_pdf.php');
include('accesscontrol.php');
include('scripts/db/db.php');

checkSecurity();

dbConnect();
$uid = $_SESSION['uid'];
$sql = "SELECT tableuserid FROM vBiblio_user WHERE userid='$uid'";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$row = mysql_fetch_assoc($result);
	$mytableId = $row['tableuserid'];

$reqSQL = "SELECT vBiblio_user.fullname, titre FROM vBiblio_user, vBiblio_pret, vBiblio_book WHERE vBiblio_pret.id_emprunteur='$mytableId' AND vBiblio_pret.id_preteur=vBiblio_user.tableuserid AND vBiblio_pret.id_book=vBiblio_book.id_book order by vBiblio_book.id_author";



class PDF extends PDF_MySQL_Table
{
function Header()
{
    //Titre
    $this->SetFont('Arial','',18);
    $this->Cell(0,6,'vBiblio - les livres que je dois rendre...',0,1,'C');
    $this->Ln(10);
    //Imprime l'en-tête du tableau si nécessaire
    parent::Header();
}
}

$pdf=new PDF();
$pdf->AddPage();
$pdf->AddCol('titre',120,'Titre','L');
$pdf->AddCol('fullname',40,'Preteur', 'R');

$prop=array('HeaderColor'=>array(200,200,255),
            'color1'=>array(255,255,255),
            'color2'=>array(230,230,230),
            'padding'=>2);
//Premier tableau : imprime toutes les colonnes de la requête
$pdf->Table($reqSQL, $prop);

$pdf->Output();
}

?>
