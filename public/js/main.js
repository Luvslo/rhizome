var typeDelay = 50;

var inputStr = "";
var inputPrompt = "# ";
var inputEnabled = false;

var terminal = "#terminal";

$( document ).ready(function(){
	//setting the options for the typer
    console.log( "ready!" );
    echo("Welcome to Rhizome, please wait while we connect to the server...");
    
    //the events start here
    $(document).on('keydown', function(e){
    	handleKeys(e.keyCode, e);
    });
});

function handleKeys (code, e){
	if (inputEnabled == false){
		return;
	}
	switch (code){
		case 8:
			//backspace
			e.preventDefault();
			erase(1);
			return;
			break;
		case 13:
			//enter
			e.preventDefault();
			sendInput(inputStr);
      return;
			break;
		default:
			//all other keys
			if (code >= 48 && code <= 90){
				e.preventDefault();
			}else if (code == 32){
				//space
				e.preventDefault();
			}else{
				return;
			}
	}
	input(String.fromCharCode(code));
}

function sendInput(text){
  console.log("inputStr.length: " + inputStr.length);
  if (inputStr.length == 0){
    return;
  }else{
    echo(text);
  }
	inputStr = '';
}

function erase (chars){
	chars = chars * -1;
	if (inputStr.length > 0){
		//there's text to be erased
		inputStr = inputStr.slice(0, chars);
		$(terminal).html($(terminal).html().slice(0, chars));
	}else{
		//maybe blink? who knows
	}
}

function clear (){
  $(terminal).html("");
}

function echo (text){
	inputEnabled = false;
	type(text);
}

function append (text){
	$(terminal).append(text);
}

function input(text){
	inputStr += text;
	append(text);
}

function type(text){
  clear();
	totalRounds = text.split('').length - 1;
	$.each(text.split(''), function(i, letter){
	
		//we add 100*i ms delay to each letter 
		setTimeout(function(){
		
		    //we add the letter to the container
		    $(terminal).html($(terminal).html() + letter);
		    if (i == totalRounds){
			    append("<br><br>" + inputPrompt);
				inputEnabled = true;	
		    }
		
		}, typeDelay*i);
	});
}

// game specific code goes here

function drawBattle(json){

}