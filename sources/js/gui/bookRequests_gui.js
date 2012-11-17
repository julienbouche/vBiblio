// JavaScript Document

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

function acceptRequest(idRequest, my_id, id_user, id_book){
	var xhr = createXHR();

	if(xhr!=null) {
		var url = 	"scripts/db/confirmBookRequest.php?idRequest="+idRequest+"&user1="+my_id+"&user2="+id_user+"&id_book="+id_book;
		xhr.open("GET",url, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				//document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
				//alert("'"+url+"'");
				document.getElementsByName("request"+idRequest)[0].innerHTML = "<td></td><td>"+xhr.responseText+"</td>";
			}
		};
		xhr.send(null);
	}	
}