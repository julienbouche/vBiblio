//Javascript Document


function ignoreRequest(idRequest){
	var xhr = createXHR();

	if(xhr!=null) {
		xhr.open("GET","scripts/db/ignoreRequest.php?idRequest="+idRequest, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
			}
		};
		xhr.send(null);
	}
	
}

function acceptRequest(idRequest, my_id, id_user, executeCallback){
	var xhr = createXHR();

	if(xhr!=null) {
		xhr.open("GET","scripts/db/confirmRequest.php?idRequest="+idRequest+"&user1="+my_id+"&user2="+id_user, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				//document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
				if(executeCallback)document.getElementsByName("request"+idRequest)[0].innerHTML = "<td></td><td>"+xhr.responseText+"</td>";
				else document.getElementById("Req"+idRequest).style.visibility = "hidden";
			}
		};
		xhr.send(null);
	}	
}

function sendBuddyRequest(bouton, me, tableuserid){
	bouton.style.visibility= "hidden";
	var xhr = createXHR();
	

	if(xhr!=null) {
		xhr.open("GET","scripts/db/reqAddBuddy.php?user1="+me+"&user2="+tableuserid, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				balise = document.getElementsByName("feedback"+tableuserid)[0];
				balise.innerHTML = xhr.responseText;
				//balise.innerHTML = "a &eacute;t&eacute; ajout&eacute; &agrave; vos amis.";
				//xhr.responseText;

			}
		};
		xhr.send(null);
	}
}