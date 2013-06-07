<?php
include('../../scripts/db/db.php');

if(isset($_POST['tags']) && isset($_POST['idbook']) && $_POST['idbook']!='' && $_POST['tags']!=''){
	dbConnect();

	$values = $_POST['tags'];
	$idBook_param = intval($_POST['idbook']);

	//split values to return each value
	$separated_values = my_split($values);

	foreach($separated_values as $val){
		$val = trim(mysql_real_escape_string($val));
		if(strlen($val)>1){
			//tester chaque valeur pour savoir si elle existe en base
			$val = utf8_decode($val);
			$existReqSQL = "SELECT id_tag FROM vBiblio_tag WHERE UCASE(value)=UCASE(\"".$val."\")";
			$resExist = mysql_query($existReqSQL);

			if($resExist && mysql_num_rows($resExist)>0){//le tag existe, y'a-t-il déjà une asso avec ce bouquin?
				if($row = mysql_fetch_assoc($resExist)){
					$idTag = $row['id_tag'];
			
					//on vérifie l'asso
					$assoTagBookExist ="SELECT count FROM vBiblio_tag_book WHERE id_tag=".$idTag." AND id_book=".$idBook_param; 	
					$resAssoTagBookExist = mysql_query($assoTagBookExist);
					if($resAssoTagBookExist && mysql_num_rows($resAssoTagBookExist)>0){
						incrementValAssoTagBook($idTag, $idBook_param);
					}
					else{
						createAssocTagBook($idTag, $idBook_param);
					}
				}
			}else{
				//le tag existe pas
				$createdID = createTag($val);
			
				//ajouter une asso dans la table d'asso
				createAssocTagBook($createdID, $idBook_param);
			}
		}
	}
}
//fonction permettant de créer une nouvelle asso entre un Tag et un livre existants!!!
function createAssocTagBook($idtag, $idbook){
	$sqlCreateTagBook= "INSERT INTO vBiblio_tag_book(`id_tag`,`id_book`,`count`) VALUES('$idtag','$idbook','1')";
	//echo "create : ".$sqlCreateTagBook;
	mysql_query($sqlCreateTagBook);
}

function incrementValAssoTagBook($idtag, $idbook){
	$sqlUpdateAsso = "UPDATE vBiblio_tag_book SET count=count+1 WHERE id_tag=$idtag AND id_book=$idbook";
	//echo "update : ".$sqlUpdateAsso;
	mysql_query($sqlUpdateAsso);
}

//fonction permettant de créer un tag !
function createTag($val){
	//echo "Create entirely";
	//peut etre essayer de faire du nettoyage du tag avant ? Majuscules, etc.
	$sqlCreateTag = "INSERT INTO vBiblio_tag(`id_tag`,`value`) VALUES('','$val')";
 
	mysql_query($sqlCreateTag);
	
	//quel id ?
	return mysql_insert_id();
}

//fonction permettant de séparer les différentes valeurs envoyés par le formulaire !
function my_split($concatVals){
	$tabVals = explode(",", $concatVals);
	return $tabVals;
}
?>
