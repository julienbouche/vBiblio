<?php

  $isbn = isset($_GET['isbn']) ? $_GET['isbn'] : '';  
  // ou si vous préférez hardcodé  
  // $isbn = '0061234001';  
     
   $request = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . $isbn;  
   $response = file_get_contents($request);  
   $results = json_decode($response);  
   $nbTotalBooksFound = $results->totalItems;
   if( $nbTotalBooksFound > 0){
	$compteur = 0;
	while($compteur<$nbTotalBooksFound)  {

	//while($compteur<1)  {
      // avec de la chance, ce sera le 1er trouvé  
      $book = $results->items[0];  
     
      $infos['isbn'] = $book->volumeInfo->industryIdentifiers[0]->identifier;  
      $infos['titre'] = $book->volumeInfo->title;  
      $infos['auteur'] = $book->volumeInfo->authors[0];  
      $infos['langue'] = $book->volumeInfo->language;  
      $infos['publication'] = $book->volumeInfo->publishedDate; 
      $infos['description'] = $book->volumeInfo->description;
      $infos['pages'] = $book->volumeInfo->pageCount;  
     
      if( isset($book->volumeInfo->imageLinks) ){  
         $infos['image'] = str_replace('&edge=curl', '', $book->volumeInfo->imageLinks->thumbnail);  
     }  
    
     //print_r($infos);
	echo json_encode($infos); //TODO vérifier que le serveur supporte la fonction !!!!!  
	$compteur++;
	}
  }  
  else{  
     echo 'Livre introuvable';  
  }  
