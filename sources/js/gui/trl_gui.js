// JavaScript Document


function suppBookFromList(idbook, uid){
	if(confirm('Etes-vous sûr de vouloir supprimer ce livre?')){
		var xhr = createXHR();
	
		if(xhr!=null) {
			xhr.open("GET","scripts/db/suppBookFromTRL.php?idbook="+idbook+"&id_user="+uid, true);
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
