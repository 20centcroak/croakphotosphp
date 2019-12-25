
function verifSignUp(f){
   var nameOk = verifName(f.inputName);
   var mailOk = verifMail(f.inputEmail);
   var passwordOk = verifPassword(f.repeatPassword);   

   if(nameOk && mailOk && passwordOk)
      return true;
   else{
      return false;
   }
}

function verifName(field){
	var error=true;
	if((field.value).length<=20 && (field.value).length>=2){
		var regex = /^[a-zA-Z0-9]+[a-zA-Z0-9_-]+$/;
		error = !regex.test(field.value);
	}
    highlight(field, error);
	displayError(document.getElementById("nameError"), error);
    return !error;
}

function verifMail(field){
   var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
   var error = !regex.test(field.value);
   highlight(field, error);
   return !error;
}

function verifPassword(field){
	var error=(field.value).length<6;		
	displayError(document.getElementById("passwordError"), error);
	highlight(field, error);	
    return !error;
}

function verifRepeatPassword(field){
	var p1 = field.value;
	var p2 = document.getElementById("inputPassword").value;
	var error=!(p1===p2);
	displayError(document.getElementById("passwordRepeatError"), error);
	highlight(field, error);	
    return !error;
}

function trigErrors(nameError, emailError, passwordError){
	var field = document.getElementById("inputName");
	highlight(field, nameError);
	displayError(document.getElementById("nameError"), nameError);
	field = document.getElementById("inputEmail");
	highlight(field, emailError);
	field = document.getElementById("inputPassword");
	highlight(field, passwordError);
	displayError(document.getElementById("passwordError"), passwordError);
}

function highlight(field, error){
	if(error){
      field.style.backgroundColor = "#fba";
	  field.autofocus = true;
	}
   else{
	field.style.backgroundColor = "";
	}
}

function displayError(field, error){
	if(error){
	  field.className = 'fail';
	}
   else{
	field.className = 'hidden';
	}
}
