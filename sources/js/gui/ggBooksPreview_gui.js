var isbn;

function processDynamicLinksResponse(booksInfo) {
	//test de nullite
	if(Object.keys(booksInfo).length === 0){
		processDynamicLinksError();
	}

	for (id in booksInfo) {
		isbn = id;
		if (booksInfo[id] && (booksInfo[id].preview == 'partial' || booksInfo[id].preview == 'fullview') ) {

		  google.load("books", "0");
		}
	}
	
	
}

function processDynamicLinksError(){
	//si erreur, on cache le bouton pour afficher le viewer
	toggleGGPreviewButton = document.getElementById('toggleGGPreviewButton');
	toggleGGPreviewButton.style.display="none";
}

function loadPreview() {
	try {
		var viewer = new google.books.DefaultViewer(document.getElementById('viewerCanvas'));
		viewer.load(isbn);
	} catch(e) {
		togglePreview(); //on cache l'affichage
		processDynamicLinksError(); //et on cache le bouton ... 
		alert('D\351sol\351, il semblerait finalement que Google Livres n\'ait pas de pr\351visualisation pour ce livre');
	}
	
}

function togglePreview() {
	var block = document.getElementById('fenetrePreviewGG');
	var canvas = document.getElementById('viewerCanvas');
	if (block.style.display != 'block') {
	  canvas.style.display = 'block';
	  block.style.display = 'block';
	  loadPreview();
	} else {
	  canvas.style.display = 'none';
	  block.style.display = 'none';
	}
}
