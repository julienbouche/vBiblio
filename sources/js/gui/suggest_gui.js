//Javascript Document

function ignoreRequest(idRequest){
	var xhr = createXHR();

	if(xhr!=null) {
		xhr.open("GET","scripts/db/ignoreRequest.php?idSuggest="+idRequest, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
			}
		};
		xhr.send(null);
	}
	
}

function addToMyTRL(idRequest, my_id, id_book){
	var xhr = createXHR();

	if(xhr!=null) {
		xhr.open("GET","scripts/db/addToMyTRL.php?idSuggest="+idRequest+"&id_user="+my_id+"&idbook="+id_book, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				//document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
				document.getElementsByName("request"+idRequest)[0].innerHTML = "<td></td><td>"+xhr.responseText+"</td>";
			}
		};
		xhr.send(null);
	}
}

function addToMyVBiblio(idRequest, my_id, id_book){
	var xhr = createXHR();
	
	if(xhr!=null) {
		xhr.open("GET","scripts/db/addToMyVBiblio.php?idSuggest="+idRequest+"&id_user="+my_id+"&idbook="+id_book, true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				// j'affiche dans la DIV un retour pour l'utilisateur
				//document.getElementsByName("request"+idRequest)[0].style.visibility = "hidden";
				document.getElementsByName("request"+idRequest)[0].innerHTML = "<td></td><td>"+xhr.responseText+"</td>";
			}
		};
		xhr.send(null);
	}
}

function getPos(el) {
    // yay readability
    var lx=0, ly=0;
    
    while (el != null){
	lx += el.offsetLeft;
	ly += el.offsetTop;
	el = el.offsetParent;
    }
    
    return {x: lx,y: ly};
}

window.onload = (function(){

		var $gal   = document.getElementById("booklistbanner"),
			galW   = $gal.offsetWidth,
			galSW  = $gal.scrollWidth,
			wDiff  = (galSW/galW)-1,  // widths difference ratio
			mPadd  = 200,  // Mousemove Padding
			damp   = 30,  // Mousemove response softness
			mX     = 0,   // Real mouse position
			mX2    = 0,   // Modified mouse position
			elementXOffset = 0, //element positioning in document ref
			posX   = 0,
			mmAA   = galW-(mPadd*2), // The mousemove available area
			mmAAr  = (galW/mmAA);    // get available mousemove fidderence ratio
			
		elementXOffset = getPos(document.getElementById("booklistbanner")).x;
			
		$gal.onmousemove=function(e) {
			
			mX = e.pageX - elementXOffset ;
			mX2 = Math.min( Math.max(0, mX-mPadd), mmAA ) * mmAAr;
		};

		setInterval(function(){
			posX += (mX2 - posX) / damp; // zeno's paradox equation "catching delay"	
			document.getElementById("booklistbanner").scrollLeft = (posX*wDiff);
		}, 20);
	
	});
