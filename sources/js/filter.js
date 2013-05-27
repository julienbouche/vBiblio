// JavaScript Document
var AuthorAscending = 1;
var TitleAscending = 1;

/*fonction permettant le tri des résultats par Titre...
*/
function sortByAuthor(user){
  bookList = document.getElementsByName("vBiblioBookList")[0];
  xhr = createXHR();
  if(xhr!=null){
	if (AuthorAscending){
		//make an ajax request to get the result ORDER BY AuthorName ASC, AuthorForname ASC.
		AuthorAscending = 0;
		//alert("scripts/db/getBookList.php?u="+user+"&sort=Title&sortOrder=DESC");
		xhr.open("GET","scripts/db/getBookList.php?sort=Author&sortOrder=ASC", true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				bookList.innerHTML = xhr.responseText;
				filter();
			}
		};
		xhr.send(null);

		//populate bookList with the AJAX request results

		//bookList.innerHTML="";
	}
	else{
		//make an ajax request to get the result ORDER BY AuthorName DESC, AuthorForname DESC.
		AuthorAscending = 1;
		//populate bookList with the AJAX request results
		//bookList.innerHTML = xhr.responseText;
		xhr.open("GET","scripts/db/getBookList.php?sort=Author&sortOrder=DESC", true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				bookList.innerHTML = xhr.responseText;
				filter();
			}
		};
		xhr.send(null);

	}
  }
}

/*fonction permettant le tri des résultats par Titre...
  /\
 /!\ Penser à gérer le cas où l'utilisateur a déjà filtrer la liste...
----
*/
function sortByTitle(user){

  bookList = document.getElementsByName("vBiblioBookList")[0];
  xhr = createXHR();
  if (TitleAscending){
	//make an ajax request to get the result ORDER BY AuthorName ASC, AuthorForname ASC.
	TitleAscending = 0;
	xhr.open("GET","scripts/db/getBookList.php?sort=Title&sortOrder=ASC", true);
	xhr.onreadystatechange = function(){
		if ( xhr.readyState == 4 ){
			bookList.innerHTML = xhr.responseText;
			filter();
		}
	};
	xhr.send(null);	
  }
  else{
    //make an ajax request to get the result ORDERED BY cycleTitle DESC, bookTitle DESC.
    	TitleAscending = 1;
    
	xhr.open("GET","scripts/db/getBookList.php?sort=Title&sortOrder=DESC", true);
	xhr.onreadystatechange = function(){
		if ( xhr.readyState == 4 ){
			bookList.innerHTML = xhr.responseText;
			filter();
		}
	};
	xhr.send(null);

  }
  filter();
}

/**
 * Fonction permettant de cacher un élément transmis en paramètre
 */ 
function hide(elt){
    elt.style.display = "none";
}

/**
 * Fonction permettant l'affichage d'un élément transmis en paramètre
 *
 */  
function show(elt){
    elt.style.display = "table-row";
}

/**
 * Fonction permettant le changement de classe d'une ligne à l'autre de la table
 * Ceci permet l'affichage strié (blanc/gris) des lignes de la table 
 */ 
function showAlternate(elt, n){
  if (n%2){
    elt.className = "vBiblioBookEven infobulle";
  }
  else{
    elt.className = "vBiblioBookOdd infobulle";
  }
  show(elt);
}

/**
 * Fonction permettant de filter la table à l'aide d'un champ texte.
 * La fonction compare la valeur saisie à celle des différentes colonnes (titre et auteur)
 * Si une occurence est trouvée alors la ligne est affichée, sinon la ligne est cachée  
 */ 
function filter(){
  //filtre = document.getElementsByName("filtre")[0];
  obj = document.getElementsByName("filtreSaisie")[0];
  titles = document.getElementsByName("bookTitle");
  authors = document.getElementsByName("authorName");
  
  var alternateCssClass = 1;
  var searchTerms = obj.value.toLowerCase();
  
  //améliorer cette recherche pour qu'on trouve pas si y'a "lus" ou "lucien", etc.
  //à l'aide d'une regex  
  if(searchTerms.indexOf("nonlu")>=0 ){
	rechercheNonLus = true;
	//alert("Recherche Non Lus");
  } else {
	  rechercheNonLus = false;
	  if(searchTerms.indexOf("lu")>=0 ) {
		rechercheLus = true;
		//alert("Recherche Lus");
	  } else rechercheLus = false;  
  } 
  var re= /(nonlu)|(lu)/gi ;
  searchTerms = searchTerms.replace(re, "");
  //alert("Recherche: '"+searchTerms+"'");  
  
  for(cpt=0; cpt<titles.length;cpt++){
    str = titles[cpt].innerHTML;//.replace(/<[^>]+>/g,"");
    str2 = authors[cpt].innerHTML;
    //filtre.innerHTML += str;
    //filtre.innerHTML += "test "+titles[cpt].innerHTML+ " =? "+obj.value;
	
    if(str.toLowerCase().indexOf(searchTerms)>=0 || str2.toLowerCase().indexOf(searchTerms)>=0 ){
	   if(rechercheNonLus || rechercheLus){
		//alert(titles[cpt].parentNode.parentNode.childNodes[3].childNodes[0].getAttribute("name"));
		/*if( (luChecked && rechercheLus) || (!luChecked && rechercheNonLus) ){
			showAlternate(titles[cpt].parentNode.parentNode, alternateCssClass);
			alternateCssClass++;
		}
		else{
			hide(titles[cpt].parentNode.parentNode);
		}*/
	  }
      else {
		showAlternate(titles[cpt].parentNode.parentNode, alternateCssClass);
		alternateCssClass++;
	  }  
    }
    else {
      hide(titles[cpt].parentNode.parentNode);
    }
	
  }
//  filtre.innerHTML += "<br>";
}

/** TODO
 * Fonction permettant la mise à jour des données de la table (nécessaire si on fait les sortes sans recharger la table)
 */
function refreshData(){

}


/**
*
*
*
*
*/

function sortTRLByAuthor(user){
  bookList = document.getElementsByName("vBiblioBookList")[0];
  xhr = createXHR();
  if(xhr!=null){
	if (AuthorAscending){
		//make an ajax request to get the result ORDER BY AuthorName ASC, AuthorForname ASC.
		AuthorAscending = 0;
		//alert("scripts/db/getBookList.php?u="+user+"&sort=Title&sortOrder=DESC");
		xhr.open("GET","scripts/db/getTRLBookList.php?sort=Author&sortOrder=ASC", true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				bookList.innerHTML = xhr.responseText;
				filter();
			}
		};
		xhr.send(null);

		//populate bookList with the AJAX request results

		//bookList.innerHTML="";
	}
	else{
		//make an ajax request to get the result ORDER BY AuthorName DESC, AuthorForname DESC.
		AuthorAscending = 1;
		//populate bookList with the AJAX request results
		//bookList.innerHTML = xhr.responseText;
		xhr.open("GET","scripts/db/getTRLBookList.php?sort=Author&sortOrder=DESC", true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				bookList.innerHTML = xhr.responseText;
				filter();
			}
		};
		xhr.send(null);

	}
  }
}




function sortTRLByTitle(user){

  bookList = document.getElementsByName("vBiblioBookList")[0];
  xhr = createXHR();
  if (TitleAscending){
	//make an ajax request to get the result ORDER BY AuthorName ASC, AuthorForname ASC.
	TitleAscending = 0;
	xhr.open("GET","scripts/db/getTRLBookList.php?sort=Title&sortOrder=ASC", true);
	xhr.onreadystatechange = function(){
		if ( xhr.readyState == 4 ){
			bookList.innerHTML = xhr.responseText;
			filter();
		}
	};
	xhr.send(null);	
  }
  else{
    //make an ajax request to get the result ORDERED BY cycleTitle DESC, bookTitle DESC.
    	TitleAscending = 1;
    
	xhr.open("GET","scripts/db/getTRLBookList.php?sort=Title&sortOrder=DESC", true);
	xhr.onreadystatechange = function(){
		if ( xhr.readyState == 4 ){
			bookList.innerHTML = xhr.responseText;
			filter();
		}
	};
	xhr.send(null);

  }
  filter();
}

