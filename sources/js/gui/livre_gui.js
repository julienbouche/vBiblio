// JavaScript Document
function DisplayFenetreTag(){
	popinside_show('fenetreTags');
	tagsInput = document.getElementsByName("tags")[0];
	tagsInput.focus();
}

function hideFenetreTags(){
	popinside_close('fenetreTags')
	tagsInput = document.getElementsByName("tags")[0];
	tagsInput.value="";
}

function sendTags(idbook){
	obj = document.getElementById("fenetreTags");
	var getstr = "";
	
	for (i=0; i<obj.getElementsByTagName("input").length; i++) {
        	if (obj.getElementsByTagName("input")[i].type == "text") {
           		getstr += obj.getElementsByTagName("input")[i].name + "=" + 
                   	obj.getElementsByTagName("input")[i].value + "&";
        	}
        	if (obj.getElementsByTagName("input")[i].type == "checkbox") {
        		if (obj.getElementsByTagName("input")[i].checked) {
              			getstr += obj.getElementsByTagName("input")[i].name + "=" + 
                   		obj.getElementsByTagName("input")[i].value + "&";
           		} else {
              			getstr += obj.getElementsByTagName("input")[i].name + "=&";
           		}
        	}
        	if (obj.getElementsByTagName("input")[i].type == "radio") {
           		if (obj.getElementsByTagName("input")[i].checked) {
              			getstr += obj.getElementsByTagName("input")[i].name + "=" + 
                   		obj.getElementsByTagName("input")[i].value + "&";
           		}
     		}  
     		if (obj.getElementsByTagName("input")[i].tagName == "SELECT") {
        		var sel = obj.getElementsByTagName("input")[i];
        		getstr += sel.name + "=" + sel.options[sel.selectedIndex].value + "&";
     		}
   	}
	getstr+="idbook="+idbook;
	//alert(getstr);

	//envoyer la requete 
	doPOSTRequest("scripts/db/updateTags.php", getstr, true);

	//cacher la fen�tre
	popinside_close('fenetreTags');
	
	//on rafrachit la page...
	//window.location.reload();
}



$().ready(function() {
	var tags;
	xhr = createXHR();
	if(xhr!=null){
		xhr.open("GET","scripts/db/getAllTags.php", true);
		xhr.onreadystatechange = function(){
			if ( xhr.readyState == 4 ){
				tags = eval(xhr.responseText);
				$("#tags").autocomplete(tags, {
					multiple: true,
					mustMatch: false,
					autoFill: true
				});
			}
		};
		xhr.send(null);
	}
});

var avote = 0;


function submitForm(obj, idbook, id_user){
	//Récupérer les paramètres du formulaire
      var getstr = "";
      for (i=0; i<obj.getElementsByTagName("input").length; i++) {
        if (obj.getElementsByTagName("input")[i].type == "text") {
           getstr += obj.getElementsByTagName("input")[i].name + "=" + 
                   obj.getElementsByTagName("input")[i].value + "&";
        }
        if (obj.getElementsByTagName("input")[i].type == "checkbox") {
           if (obj.getElementsByTagName("input")[i].checked) {
              getstr += obj.getElementsByTagName("input")[i].name + "=" + 
                   obj.getElementsByTagName("input")[i].value + "&";
           } else {
              getstr += obj.getElementsByTagName("input")[i].name + "=&";
           }
        }
        if (obj.getElementsByTagName("input")[i].type == "radio") {
           if (obj.getElementsByTagName("input")[i].checked) {
              getstr += obj.getElementsByTagName("input")[i].name + "=" + 
                   obj.getElementsByTagName("input")[i].value + "&";
           }
     }  
     if (obj.getElementsByTagName("input")[i].tagName == "SELECT") {
        var sel = obj.getElementsByTagName("input")[i];
        getstr += sel.name + "=" + sel.options[sel.selectedIndex].value + "&";
     }
     
   }
	getstr+="idbook="+idbook+"&id_from="+id_user;
	
	//envoyer la requete 
	doPOSTRequest("scripts/db/adviseFriends.php", getstr, false);
	
	//cacher la fenêtre
	popinside_close('fenetreConseilAmi');
	
}
var http_request;

function doPOSTRequest(url, parameters, reload){
	http_request = createXHR();
	if(reload){
		http_request.onreadystatechange = alertContentsWithReload;
	}
	else http_request.onreadystatechange = alertContents;
	
	http_request.open('POST', url, true);
	http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_request.setRequestHeader("Content-length", parameters.length);
	http_request.setRequestHeader("Connection", "close");	
	http_request.send(parameters);
}

function alertContentsWithReload(){
	if (http_request.readyState == 4) {
         if (http_request.status == 200 || http_request.status == 0) { // l'operation s'est correctement deroulée
		window.location.reload();
         } else {
            alert('Une erreur (code:'+http_request.status+') est survenue pendant le traitement de votre demande.');
         }
	}
}

function alertContents(){
	if (http_request.readyState == 4) {
         if (http_request.status == 200 || http_request.status == 0) { // l'operation s'est correctement deroulée
            //alert(''+http_request.status+''+http_request.responseText);
            //result = http_request.responseText;
            //document.getElementById('myspan').innerHTML = result;            
         } else {
            alert('Une erreur (code:'+http_request.status+') est survenue pendant le traitement de votre demande.');
         }
      }
}

function vote(idbook, note){
	if(avote==0){
		var xhr = createXHR();
	
		if(xhr!=null) {
			xhr.open("GET","scripts/db/voteLivre.php?idbook="+idbook+"&note="+note, true);
			xhr.send(null);		
		}
		avote = 1;
	}
} 
