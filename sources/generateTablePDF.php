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

$type= $_GET['type'];


$reqSQL = "SELECT vBiblio_book.titre As titre, vBiblio_author.nom as nom, vBiblio_author.prenom as prenom FROM vBiblio_author, vBiblio_book, vBiblio_poss, vBiblio_user WHERE vBiblio_poss.userid = vBiblio_user.tableuserid AND vBiblio_user.tableuserid='$mytableId' AND vBiblio_poss.id_book = vBiblio_book.id_book AND vBiblio_book.id_author=vBiblio_author.id_author order by vBiblio_author.id_author";


class PDF extends PDF_MySQL_Table
{
  function Header()
  {
    //Titre
    $this->SetFont('Arial','',18);
    $this->Cell(0,6,utf8_decode('vBiblio - ma bibliothèque'),0,1,'C');
    $this->Ln(10);
    //Imprime l'en-tête du tableau si nécessaire
    parent::Header();
  }
}


$pdf=new PDF();
$pdf->AddPage();
$pdf->AddCol('titre',120,'Titre','L');
$pdf->AddCol('nom',40,'Nom', 'R');
$pdf->AddCol('prenom',40,utf8_decode('Prénom'),'R');
$prop=array('HeaderColor'=>array(200,200,255),
            'color1'=>array(255,255,255),
            'color2'=>array(230,230,230),
            'padding'=>2);
//Premier tableau : imprime toutes les colonnes de la requête
$pdf->Table($reqSQL, $prop);

$pdf->Output();
}

?>
