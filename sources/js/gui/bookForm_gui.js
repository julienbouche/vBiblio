var ISBNok = false;

function switchSeriesState(){
	if(document.getElementsByName('series')[0].disabled){
		enableSeriesState(document.getElementsByName('series')[0], document.getElementsByName('idTome')[0], document.getElementsByName('auteur')[0]);
	}
	else{
		disableSeriesState(document.getElementsByName('series')[0], document.getElementsByName('idTome')[0]);
	}
}

function enableSeriesState(dom_series_list, dom_tome, dom_auteur_selected){
	dom_series_list.disabled = false;
	dom_tome.disabled = false;
	//charger la liste des series de l'auteur
	populateSeriesList(dom_auteur_selected);
}

function disableSeriesState(dom_series_list, dom_tome){
	dom_series_list.disabled = true;
	dom_tome.disabled = true;
	//on met un élément vide
	dom_series_list.innerHTML="<option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
}

function reloadBookTitles(object){
	populateSeriesList(object);
}


function populateSeriesList(authorChoice){
	idAuteur = authorChoice.options[authorChoice.selectedIndex].value;
	

	xhr = createXHR();
	if(xhr!=null) {
		xhr.open("GET","scripts/db/reqAuthorSeries.php?author="+idAuteur, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans les series de l'auteur
				if(document.getElementsByName('series')[0].disabled!=true){
					seriesList = document.getElementById('seriesList');
					seriesList.innerHTML = xhr.responseText;			
				}
			}
		};
		xhr.send(null);
	}	

}


/* fonction permettant de valider un numero isbn*/
function validateISBN(dom_isbn){
	isbn = dom_isbn.value;
	if(isbn.match(/[^0-9xX\.\-\s]/)) {
		ISBNok = false;
	}
	else{
  		isbn = isbn.replace(/[^0-9xX]/g,'');
 	 	if(isbn.length != 10 && isbn.length != 13) {
  			ISBNok = false;
  		}
  		else{	
    			checkDigit = 0;
    			if(isbn.length == 10) {
    			checkDigit = 11 - ( (
    				10 * isbn.charAt(0) +
    				9  * isbn.charAt(1) +
    				8  * isbn.charAt(2) +
    				7  * isbn.charAt(3) +
    				6  * isbn.charAt(4) +
    				5  * isbn.charAt(5) +
    				4  * isbn.charAt(6) +
    				3  * isbn.charAt(7) +
    				2  * isbn.charAt(8)
    				) % 11);
    						 
    				if(checkDigit == 10) {
    					ISBNok = (isbn.charAt(9) == 'x' || isbn.charAt(9) == 'X') ? true : false;
    				} else {
    					ISBNok = (isbn.charAt(9) == checkDigit ? true : false);
    				}
    			} else {
    				checkDigit = 10 -  ((
    				 1 * isbn.charAt(0) +
    				 3 * isbn.charAt(1) +
    				 1 * isbn.charAt(2) +
    				 3 * isbn.charAt(3) +
    				 1 * isbn.charAt(4) +
    				 3 * isbn.charAt(5) +
    				 1 * isbn.charAt(6) +
    				 3 * isbn.charAt(7) +
    				 1 * isbn.charAt(8) +
    				 3 * isbn.charAt(9) +
    				 1 * isbn.charAt(10) +
    				 3 * isbn.charAt(11)
    				) % 10);
    						 
    				if(checkDigit == 10) {
    					ISBNok = (isbn.charAt(12) == 0 ? true : false) ;
    				} else {
    					ISBNok = (isbn.charAt(12) == checkDigit ? true : false);
    				}
    			}
  		}
	}
	showISBNValidationResult(dom_isbn);

	//on met à jour la valeur affichée, nettoyée
	if (ISBNok) {
		dom_isbn.value = isbn;
	}
	return ISBNok;
}

/*
Pour la page d'insertion par l'isbn on vérifie la validité de l'isbn.
Si ok, alors on charge les informations du livre à partir de ce dernier
*/
function validateISBNandPopulateBookInformations(dom_isbn){
	isbn = dom_isbn.value;
	validateISBN(dom_isbn);

	//faire une requete vers scripts/ISBNSearch.php?isbn=... si l'isbn est ok
	if(showISBNValidationResult(dom_isbn)){	
		xhr = createXHR();
		
		if(xhr!=null) {
			xhr.open("GET","https://www.googleapis.com/books/v1/volumes?q=isbn:"+isbn, true);
			xhr.onreadystatechange = function(){
				if ( xhr.readyState == 4 ){
					//indiquer qu'une requete est faite sur le serveur (petite icone de chargement ?)
					text = xhr.responseText;
					//alert(text);
					book = JSON.parse(text);
					//alert(book.items[0].volumeInfo.title);
					//TODO : trouver comment renseigner les champs tome & nom du cycle : non dispo sur gg books ?
					try {
						if (book.totalItems>0) {
							displayBookInformation(isbn,book.items[0].volumeInfo.title,
										book.items[0].volumeInfo.description,'','',book.items[0].volumeInfo.authors[0]);						
						}
						
					} catch(e) {
						alert(e);
					}
					
				}
			};
			xhr.send(null);
		}
	}
}

/*
fonction permettant de populer les champs à partir des informations renvoyées par le ws isbn
*/
function displayBookInformation(isbn,titre,description, tome, cycle, auteur){
	var dom_isbn = document.getElementsByName('addBookISBN')[0];
	//TODO effectuer un nettoyage de l'isbn (ne conserver que les chiffres par ex)
	

	//affichage du titre
	var dom_titre = document.getElementsByName('addBookTitle')[0];
	dom_titre.value = titre;

	//affichage de la description
	var dom_titre = document.getElementsByName('desc')[0];
	if(description) {
		dom_titre.value = description;
	}
	else dom_titre.value = "Aucune description disponible.";


	//sélectionner automatiquement l'auteur 
	var dom_auteur = document.getElementsByName('auteur')[0];
	var auteurTrouve = false;
	var current_min_dist_lev=5; //distance maximum acceptable
	var idx_chaine_plus_proche = -1;
	
	//recherche de l'auteur
	for (var increment=0;increment<dom_auteur.options.length;increment++){
		//on trouve exactement la même chaine de caractère (peu probable)
		if(dom_auteur.options[increment].text==auteur){
			dom_auteur.options[increment].selected ="selected";
			auteurTrouve = true;

			//permet de recharger la liste des séries de l'auteur récemment sélectionné par script si la case série est active
			reloadBookTitles(dom_auteur);
		}
		else{
			//on stocke le nom d'auteur se rapprochant le plus de celui renvoyé par le ws
			//afin de le proposer à l'utilisateur
			var test_dist = distance_levenshtein(dom_auteur.options[increment].text, auteur);
			if (current_min_dist_lev>test_dist) {
				idx_chaine_plus_proche = increment;
				current_min_dist_lev = test_dist;
			}
		}
	}
	
	if(!auteurTrouve){
		if (idx_chaine_plus_proche!=-1) {
			dom_auteur.options[idx_chaine_plus_proche].selected = "selected";
			reloadBookTitles(dom_auteur);
			
			alert('Nous ne sommes pas certains d\'avoir identifier le bon auteur. Merci de v\351rifier. ');
		}
		else{
			alert('L\'auteur '+auteur+' n\'a pas \351t\351 identifi\351 dans nos auteurs connus. Merci de l\'ajouter, s\'il n\'est pas pr\351sent ou de le s\351lectionner manuellement si vous pensez l\'identifier');
		}
	}
	
	//que faire s'il n'existe pas ???
	//doit on remplir le numéro du tome ?
	//de toutes façons on ne trouvera pas la série du bouquin s'il appartient à une serie

	//si le livre appartient à une série
	//sélectionner le titre de la série et 
}


/*
Fonction permettant de colorier le fond du champs ISBN en vert si ISBNok=true et en rouge si ISBNok=false
*/
function showISBNValidationResult(obj){
	if(ISBNok){
		obj.style.backgroundColor = "#00FF00";
	}
	else{
		obj.style.backgroundColor = "#FF0000";
	}
	return ISBNok;
}


/*
Fonction permettant de vérifier que si le livre doit être ajouter dans un cycle, le numéro du tome est correct
Si le tome n'est pas correct, l'utilisateur reçoit une notification au sein de la fonction validateNum
*/
function validateTomes(){
	serie = document.getElementsByName('seriesEnabled')[0];
	if(serie.checked){
		tome = document.getElementsByName('idTome')[0];
		chaine= tome.value;
		return validateNum(chaine);
	} else return true;
}

/*
Fonction permettant de valider qu'il n'y a que des chiffres dans un champs. Et de prévenir l'utilisateur si problème il y'a.
*/
function validateNum(chaine){
	retour = false;
	if(chaine !=''){
		reg = /^[0-9]+$/;
		if(reg.test(chaine))retour = true;
		else alert("Vous ne devez entrer que des chiffres pour le numero du tome");
	}
	else{
		alert("Vous n\'avez pas saisi le numero du tome");
		retour = false;
	}
	return retour;
}


/*
Validation du formulaire : ISBN OK et Tome OK
*/
function validateForm(){
	return showISBNValidationResult(document.getElementsByName('addBookISBN')[0]) && validateTomes(); 

	/*if(showISBNValidationResult(document.getElementsByName('addBookISBN')[0])){
		//si la check est sélectionnée
		return validateTomes();
	}
	else{
		return false;
	}
	return false;*/
}

function resetISBNBookForm(){
	var dom_isbn = document.getElementsByName('addBookISBN')[0];

	//affichage du titre
	var dom_titre = document.getElementsByName('addBookTitle')[0];
	dom_titre.value = '';

	//affichage de la description	
	var dom_titre = document.getElementsByName('desc')[0];
	dom_titre.value = '';


	//décocher le fait que le livre appartienne à une série 
	//serie = document.getElementsByName('seriesEnabled')[0];
	//serie.checked=false; //fait tout bugger

	numero_tome = document.getElementsByName('idTome')[0];
	numero_tome.value = '';

	dom_isbn = dom_isbn = document.getElementsByName('addBookISBN')[0];
	dom_isbn.value = '';
	dom_isbn.style.backgroundColor = "#FFFFFF";
}






