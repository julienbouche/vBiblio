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
	"Science-Fiction", "Heroic Fantasy", "Fantasy", "Médiéval Fantastique", "Aventure",
	"Roman", "Poèmes", "Roman Policier", "Nouvelles", "Horreur", "Philosophie", "Psychologie",
	"Polar", "Poésie", "Religion", "Esotérisme", "Vie pratique", "High Fantasy", "Science Fantasy",
	"Space Fantasy", "Romantic Fantasy", "Fantasy mythique", "Fantasy historique", "Fantasy Arthurienne", 
	"Manner Fantasy", "Low Fantasy", "Uchronie", "Space Opera", "Cyberpunk", "Ethnic SF", "Post-apocalyptique", 
	"Vampires", "Fantômes", "Humour", "Manga", "Roman sentimental", "Roman érotique","Erotisme", "Roman historique", 
	"Roman d'aventures", "Récits de voyage", "Roman de guerre", "Roman de terroir", "Roman d'enfance", "Roman d'amour", 
	"Contes", "Théâtre", "Biographie", "Essai", "Roman policier", "Thriller", "Polar", "Sciences Humaines",
	"Histoire", "Politique", "Actualité", "Spiritualité", "Paranormal", "Voyages", "Informatique"
];*/
