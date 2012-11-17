// JavaScript Document

function addToMyVBiblio(idbook, iduser){
	var xhr = createXHR();

	if(xhr!=null) {
		xhr.open("GET","scripts/db/addToMyVBiblio.php?idbook="+idbook+"&id_user="+iduser, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				window.location.reload();
			}
		};
		xhr.send(null);		
	}
}

function addToMyTRL(idbook, iduser){	
	var xhr = createXHR();
	
	if(xhr!=null) {
		xhr.open("GET","scripts/db/addToMyTRL.php?idbook="+idbook+"&id_user="+iduser, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				window.location.reload();
			}
		};
		xhr.send(null);		
	}	
}

