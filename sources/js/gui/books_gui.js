function addBookToMyVBiblio(elt, idbook, iduser){
	var xhr = createXHR();
	
	if(xhr!=null) {
		xhr.open("GET","scripts/db/addToMyVBiblio.php?idbook="+idbook+"&id_user="+iduser, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				HideItem(elt.parentNode);
			}
		};
		xhr.send(null);
	}
}

function addBookToMyTRL(elt, idbook, iduser){
	var xhr = createXHR();
	
	if(xhr!=null) {
		xhr.open("GET","scripts/db/addToMyTRL.php?idbook="+idbook+"&id_user="+iduser, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				HideItem(elt.parentNode);
			}
		};
		xhr.send(null);		
	}	
	
	
}

function requestFriendBook(elt, me, tableuserid, idbook){
	var xhr = createXHR();
	
	if(xhr!=null) {
		xhr.open("GET","scripts/db/reqAddBookRequest.php?user1="+me+"&user2="+tableuserid+"&id_req="+idbook, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				HideItem(elt);
			}
		};
		xhr.send(null);
	}
}

function HideItem(element){
	element.style.visibility="hidden";
}