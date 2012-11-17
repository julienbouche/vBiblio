// JavaScript Document

function createXHR(){
	var xhr;
	if (window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();
	}

	//ie
	else if (window.ActiveXObject) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	} 
	return xhr;
}

