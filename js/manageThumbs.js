function request(callback) {
    var xhr = getXMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			callback(xhr.responseText);
		}
	};
	xhr.open("POST", "php/handlingThumbs.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send();
}

function display(arg){
	var json = JSON.parse(arg);
	var colArrays = json.col;
	var nbCols= colArrays.length;
	var percentage = Math.round(100/nbCols);
	var addHtml = "";
	for(let i=0; i<nbCols; i++){
		let col = colArrays[i];
		addHtml+='<div class="w3-col" style="width:'+percentage+'%">';
		for (let j=0; j< col.length; j++){
			addHtml+=col[j];
		}
		addHtml+='</div>';
	}
	document.getElementById("pictures").innerHTML=addHtml;
}

// Open and close sidenav
function w3_open() {
    document.getElementById("mySidenav").style.width = "100%";
    document.getElementById("mySidenav").style.display = "block";
}

function w3_close() {
    document.getElementById("mySidenav").style.display = "none";
}



