var tags =retrieveAllTags();

function retrieveAllTags(){
	xhr = createXHR();	
	if(xhr!=null){
		//make an ajax request to get the result ORDER BY AuthorName ASC, AuthorForname ASC.
		AuthorAscending = 0;
		//alert("scripts/db/getBookList.php?u="+user+"&sort=Title&sortOrder=DESC");
		xhr.open("GET","scripts/db/getAllTags.php", true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				tags = xhr.responseText;
				alert(tags);
			}
		};
		xhr.send(null);

		//populate bookList with the AJAX request results

		//bookList.innerHTML="";
	}

}

/*var tags = [
	"Science-Fiction", "Heroic Fantasy", "Fantasy", "M�di�val Fantastique", "Aventure",
	"Roman", "Po�mes", "Roman Policier", "Nouvelles", "Horreur", "Philosophie", "Psychologie",
	"Polar", "Po�sie", "Religion", "Esot�risme", "Vie pratique", "High Fantasy", "Science Fantasy",
	"Space Fantasy", "Romantic Fantasy", "Fantasy mythique", "Fantasy historique", "Fantasy Arthurienne", 
	"Manner Fantasy", "Low Fantasy", "Uchronie", "Space Opera", "Cyberpunk", "Ethnic SF", "Post-apocalyptique", 
	"Vampires", "Fant�mes", "Humour", "Manga", "Roman sentimental", "Roman �rotique","Erotisme", "Roman historique", 
	"Roman d'aventures", "R�cits de voyage", "Roman de guerre", "Roman de terroir", "Roman d'enfance", "Roman d'amour", 
	"Contes", "Th��tre", "Biographie", "Essai", "Roman policier", "Thriller", "Polar", "Sciences Humaines",
	"Histoire", "Politique", "Actualit�", "Spiritualit�", "Paranormal", "Voyages", "Informatique"
];*/
