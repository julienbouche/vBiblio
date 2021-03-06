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
		xhr.open("GET","/scripts/db/getFriendBookList.php?u="+user+"&sort=Author&sortOrder=ASC", true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				bookList.innerHTML = xhr.responseText;
				//alert(xhr.responseText);
				filter();
			}
		};
		xhr.send(null);
	}
	else{
		//make an ajax request to get the result ORDER BY AuthorName DESC, AuthorForname DESC.
		AuthorAscending = 1;
		//populate bookList with the AJAX request results
		//bookList.innerHTML = xhr.responseText;
		xhr.open("GET","/scripts/db/getFriendBookList.php?u="+user+"&sort=Author&sortOrder=DESC", true);
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
*/
function sortByTitle(user){

  bookList = document.getElementsByName("vBiblioBookList")[0];
  xhr = createXHR();
  if (TitleAscending){
	//make an ajax request to get the result ORDER BY AuthorName ASC, AuthorForname ASC.
	TitleAscending = 0;
	xhr.open("GET","/scripts/db/getFriendBookList.php?u="+user+"&sort=Title&sortOrder=ASC", true);
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
    
	xhr.open("GET","/scripts/db/getFriendBookList.php?u="+user+"&sort=Title&sortOrder=DESC", true);
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
    elt.style.display = "";
}

/**
 * Fonction permettant le changement de classe d'une ligne à l'autre de la table
 * Ceci permet l'affichage strié (blanc/gris) des lignes de la table 
 */ 
function showAlternate(elt, n){
  if (n%2){
    elt.className = "vBiblioBookEven";
  }
  else{
    elt.className = "vBiblioBookOdd";
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
  
  for(cpt=0; cpt<titles.length;cpt++){
    str = titles[cpt].innerHTML;//.replace(/<[^>]+>/g,"");
    str2 = authors[cpt].innerHTML;
    //filtre.innerHTML += str;
    //filtre.innerHTML += "test "+titles[cpt].innerHTML+ " =? "+obj.value;
    if(str.toLowerCase().indexOf(obj.value.toLowerCase())>=0 || str2.toLowerCase().indexOf(obj.value.toLowerCase())>=0 ){
      showAlternate(titles[cpt].parentNode.parentNode, alternateCssClass);
      alternateCssClass++;
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
