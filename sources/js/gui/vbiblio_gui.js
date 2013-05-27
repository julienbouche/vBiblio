// JavaScript Document

function updateLu(uid, idbook){
	var value;

	if(document.getElementsByName(""+idbook+"Lu")[0].checked)value=1;
	else value=0;
	
	var xhr = createXHR();

	if(xhr!=null) {
		xhr.open("GET","scripts/db/updateBookInfo.php?uid="+uid+"&id_book="+idbook+"&Prop=lu&Val="+value, true);
		xhr.send(null);
	}
}

function updatePossede(uid, idbook){
	var value;

	if(document.getElementsByName(""+idbook+"Possede")[0].checked)value=1;
	else value=0;
	
	var xhr = createXHR();

	if(xhr!=null) {
		xhr.open("GET","scripts/db/updateBookInfo.php?uid="+uid+"&id_book="+idbook+"&Prop=possede&Val="+value, true);
		xhr.send(null);
	}
}


function suppBookFromList(idbook, uid){
	if(confirm('')){
		var xhr = createXHR();

		if(xhr!=null) {
			xhr.open("GET","scripts/db/suppBookFromMyVBiblio.php?idbook="+idbook+"&id_user="+uid, true);
			xhr.onreadystatechange = function(){
				if ( xhr.readyState == 4 ){
					// j'affiche dans la DIV un retour pour l'utilisateur
					//document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
					//penser à recharger la liste pour voir le changement immédiatement
					window.location.reload();
				}
			};
			xhr.send(null);
		}
	}
}
