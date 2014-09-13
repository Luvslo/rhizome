var loginStep = 0;

var characterName = null;
var controlCode = null;
var session = null;
var connected = false;

var typeDelay = 20;

var inputStr = "";
var inputPrompt = "# ";
var inputEnabled = false;

var line = "-----";

var inputType = "text";

var terminal = "#terminal";

var apiEndpoint = "/api/action.php";

$( document ).ready(function(){
	//setting the options for the typer
    console.log( "ready!" );
    echo("Welcome to Rhizome! Press '1' to start", true);
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
        echo([reply.prompt], true);
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

function echo (text, wipe){
  if ($.isArray(text) === false){
    text = [text.toString()];
  }
  if (wipe == true){clear();}
  inputEnabled = false;
  //this is a multi-line echo
  while (text.length >= 1){
    append(text.shift() + "<br>");
  }
  append("<br>" + inputPrompt);
}

function append (text){
	$(terminal).append(text);
  inputEnabled = true;
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

function badCommand(){
  echo ("Sorry, I don't understand that command.  Please try again.", false, true);
}
// game specific code goes here

function echoPromptChoices(prompt, choices, wipe){
  if ($.isArray(prompt) === false){
    prompt = [prompt.toString()];
  }
  i = 0;
  prompt.push(line);
  while (i < choices.length){
    prompt.push((i+1) + ". " + choices[i]);
    i++;
  }
  prompt.push("Q. To quit");
  echo (prompt, wipe);
}

function handleLogin(input){
  switch (loginStep){
    case 0:
      if (input == "1"){
        echo ("What is your name, brave adventurer?", true);
        loginStep++;
      }else{
        badCommand();
      }
      break;
    case 1:
      characterName = input;
      echo ("OK " + characterName + ", what is your ship's control code?", true);
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
          echoPromptChoices(reply.prompt, reply.choices, true)
          loginStep++;
        }else{
          echo("I'm sorry, something went wrong, let's try this again.  Press '1' to get started.", false);
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
  battleArr = [];
  append("You are fighting a <u>Fierce Rabbit Monster</u><br>");
  append("-----<br>");
  append("HP <b>100/100</b> - MP <b>100/100</b>");
}