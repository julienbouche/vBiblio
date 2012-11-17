// JavaScript Document

function retourPret(objet, uid, idEmprunteur, id_book){
	var xhr = createXHR();
	
	if(xhr!=null) {
		xhr.open("GET","scripts/db/retourPret.php?uid="+uid+"&id_book="+id_book+"&idEmprunteur="+idEmprunteur, true);
		xhr.send(null);		
	}
	objet.parentNode.parentNode.style.visibility = "hidden";

}

function changeUserChoice(bouton){
	if(bouton.checked){ //activer pour utilisateur externe au systeme
		document.getElementsByName("vUsername")[0].disabled = false;
		document.getElementsByName("id_user")[0].disabled = true;
	}
	else{ //activer pour utilisateur interne au système
		document.getElementsByName("vUsername")[0].disabled = true;
		document.getElementsByName("id_user")[0].disabled = false;
	}	
}
