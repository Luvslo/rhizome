var loginStep = 0;

var characterName = null;
var controlCode = null;
var session = null;
var connected = false;

var typeDelay = 20;

var inputStr = "";
var inputPrompt = "# ";
var inputEnabled = false;
var inputQueue = [];

var inputType = "text";

var terminal = "#terminal";

var apiEndpoint = "/api/action.php";

$( document ).ready(function(){
	//setting the options for the typer
    console.log( "ready!" );
    echo("Welcome to Rhizome! Press '1' to start", true, true);
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
  if (inputStr.length == 0){
    return;
  }else{
    append("<br>");
    if (connected == false){
      handleLogin(text);
    }else{
      $.ajax({
        url: apiEndpoint,
        data: "string=" + text
      })
      .done(function(reply) {
        echo(reply.prompt, true, true);
      });
    }
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

function echo (text, wipe, prompt){
  inputQueue.push(text);
  console.log("echo: iq.length: " + inputQueue.length);
  if (wipe == true){clear();}
	inputEnabled = false;
  if (inputQueue.length <= 1){
    type(inputQueue[0], wipe, prompt);
  }
  $(document).on("type.finished", function(e){
    console.log("triggered: " + inputQueue.length);
    inputQueue.pop();
    if (inputQueue.length >= 1){
      console.log("sending next in queue");
      type(inputQueue[0], wipe, prompt);
    }else{
      if (prompt == true){
        append("<br>" + inputPrompt);
      }
      $(document).off("type.finished");
    }
  });
}

function append (text){
	$(terminal).append(text);
}

function input(text){
	inputStr += text;
  //for password entry
  if (inputType == "text"){
    append(text);
  }else{
    append("*");
  }
}

function type(text){
	totalRounds = text.split('').length - 1;
	$.each(text.split(''), function(i, letter){
	
		//we add 100*i ms delay to each letter 
		setTimeout(function(){
		
		    //we add the letter to the container
		    $(terminal).html($(terminal).html() + letter);
		    if (i >= totalRounds){
          append("<br>");
          inputEnabled = true;
          console.log("letter: " + letter);
          $(document).trigger("type.finished");
		    }
		
		}, typeDelay*i);
	});
}

function badCommand(){
  echo ("Sorry, I don't understand that command.  Please try again.", false, true);
}
// game specific code goes here

function handleLogin(input){
  switch (loginStep){
    case 0:
      if (input == "1"){
        echo ("What is your name, brave adventurer?", true, true);
        loginStep++;
      }else{
        badCommand();
      }
      break;
    case 1:
      characterName = input;
      echo ("OK " + characterName + ", what is your ship's control code?", false, true);
      inputType = "password";
      loginStep++;
      break;
    case 2:
      controlCode = input;
      
      $.ajax({
        url: apiEndpoint,
        data: "string=login"
      })
      .done(function(reply) {
        if (reply.session){
          inputType = "text";
          session = reply.session;
          connected = true;
          echo(reply.prompt, true, true);
          
          i = 0;
          while (i < reply.choices.length){
            echo((i+1) + ". " + reply.choices[i]);
            i++;
          }
          loginStep++;
        }else{
          echo("I'm sorry, something went wrong, let's try this again.  Press '1' to get started.", false, true);
          loginStep = 0;
        }
      });
      
      //perform connecting code here
      
      //"connect" for now
      
      break;
  }
}

function drawBattle(json){
  clear();
  append("You are fighting a <u>Fierce Rabbit Monster</u><br>");
  append("-----<br>");
  append("HP <b>100/100</b> - MP <b>100/100</b>");
}