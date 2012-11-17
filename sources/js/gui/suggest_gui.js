//Javascript Document

function ignoreRequest(idRequest){
	var xhr = createXHR();

	if(xhr!=null) {
		xhr.open("GET","scripts/db/ignoreRequest.php?idSuggest="+idRequest, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
			}
		};
		xhr.send(null);
	}
	
}

function addToMyTRL(idRequest, my_id, id_book){
	var xhr = createXHR();

	if(xhr!=null) {
		xhr.open("GET","scripts/db/addToMyTRL.php?idSuggest="+idRequest+"&id_user="+my_id+"&idbook="+id_book, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				//document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
				document.getElementsByName("request"+idRequest)[0].innerHTML = "<td></td><td>"+xhr.responseText+"</td>";
			}
		};
		xhr.send(null);
	}
}

function addToMyVBiblio(idRequest, my_id, id_book){
	var xhr = createXHR();
	
	if(xhr!=null) {
		xhr.open("GET","scripts/db/addToMyVBiblio.php?idSuggest="+idRequest+"&id_user="+my_id+"&idbook="+id_book, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				//document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
				document.getElementsByName("request"+idRequest)[0].innerHTML = "<td></td><td>"+xhr.responseText+"</td>";
			}
		};
		xhr.send(null);
	}
}