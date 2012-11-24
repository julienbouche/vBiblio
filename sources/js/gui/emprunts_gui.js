// JavaScript Document


function sendBookRequest(bouton, me, tableuserid, idBookRequested){
	bouton.style.visibility= "hidden";
	var xhr = createXHR();
	

	if(xhr!=null) {
		xhr.open("GET","scripts/db/reqAddBookRequest.php?user1="+me+"&user2="+tableuserid+"&id_req="+idBookRequested, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				balise = document.getElementsByName("feedbackU"+tableuserid+"B"+idBookRequested)[0];
				balise.innerHTML = xhr.responseText;
			}
		};
		xhr.send(null);
	}
}

function retourEmpruntExterne(objet, uid, nomEmprunteur, id_book){
	var xhr = createXHR();
	//alert('Preteur:'+uid+' nomEmprunteur:'+nomEmprunteur+' et livre :'+id_book)	
	if(confirm('Etes-vous certain de vouloir supprimer cet emprunt?')){
		if(xhr!=null) {
			xhr.open("GET","scripts/db/retourEmpruntExterne.php?uid="+uid+"&id_book="+id_book+"&nomEmprunteur="+nomEmprunteur, true);
			xhr.send(null);		
		}
		objet.parentNode.style.visibility = "hidden";
	}
}
