function validate_pseudo(elt) {
    //code
    
    if (elt.value.length>3) {
        //code
        pseudoIsUniqueCall(elt.value, elt);
    }
    else{
        //unvalidate
        show_validation_result(false, elt);
    }
}

function show_validation_result(boolean_value, elt) {
    //code
    if (boolean_value) {
        //code
        elt.style.backgroundColor = "#00FF00";
    }
    else{
        elt.style.backgroundColor = "#FF0000";
    }
}

function pseudoIsUniqueCall(pseudo, elt) {
    xhr = createXHR();
    if(xhr!=null) {
            xhr.open("GET","scripts/db/reqUniquePseudo.php?q="+pseudo, true);
            xhr.onreadystatechange = function(){
                    if ( xhr.readyState == 4 ){
                            unique = eval(xhr.responseText);
                            show_validation_result(unique, elt);
                    }
            };
            xhr.send(null);
    }
}