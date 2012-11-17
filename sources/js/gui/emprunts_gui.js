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