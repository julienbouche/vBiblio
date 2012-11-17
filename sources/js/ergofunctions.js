function selectAllBooks(){
	list = document.getElementsByName("booksToAdd[]");
	if(list != null)
		for(var i=0;i<list.length;i++){
			list[i].checked = true;
		}
}

function unselectAllBooks(){
	list = document.getElementsByName("booksToAdd[]");
	if(list != null)
		for(var i=0;i<list.length;i++){
			list[i].checked = false;
		}
}

function selectAllBooksTRL(){
	list = document.getElementsByName("booksToAddTRL[]");
	if(list != null)
		for(var i=0;i<list.length;i++){
			list[i].checked = true;
		}
}

function unselectAllBooksTRL(){
	list = document.getElementsByName("booksToAddTRL[]");
	if(list != null)
		for(var i=0;i<list.length;i++){
			list[i].checked = false;
		}
}

