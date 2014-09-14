var gameData = null;

var loginStep = 0;

var characterName = null;
var controlCode = null;
var session = null;
var connected = false;

var typeDelay = 20;

var inputStr = "";
var inputPrompt = "<span id='cursor' class='cursor'>#</span> ";
var inputEnabled = true;

var line = "-----";

var inputType = "text";

var terminal = "#terminal";

var apiEndpoint = "/api/action.php";

$( document ).ready(function(){
	//setting the options for the typer
    console.log( "ready!" );
    echo("Welcome to [i][c1]Rhizome[/c1][/i]!\n Press [q]1[/q] to start", true);
    //the events start here
    $(document).on('keydown', function(e){
    	handleKeys(e.keyCode, e);
    });
    $(document).on('rhizome.inputDisabled', function(e){
      inputEnabled = false;
    });
    $(document).on('rhizome.inputEnabled', function(e){
      inputEnabled = true;
    });
    $(document).on('rhizome.noInput', function(e){
      console.log("noinput triggered");
      $("#cursor").addClass("cursor");
    });
    $(document).on('rhizome.input', function(e){
      $("#cursor").removeClass("cursor");
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
        getReply(reply);
      });
    }
  }
	inputStr = '';
  $(document).trigger("rhizome.noInput");
}

function getReply (reply){
  gameData = reply;
  echoPromptChoices(reply.prompt, reply.choices, true);
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
  if (inputStr.length <= 0){$(document).trigger("rhizome.noInput");}
}

function clear (){
  $(terminal).html("");
}

function echo (text, wipe){
  if (wipe == true){clear();}
  if ($.isArray(text) === false){
    text = [text.toString()];
  }
  if (connected == true){
    hud = drawHud();
    text = hud.concat(text);
  }
  $(document).trigger("rhizome.inputDisabled");
  //this is a multi-line echo
  while (text.length >= 1){
    addText = text.shift().split('\n').join('<br>');
    append(addText + "<br>");
  }
  append("<br>" + inputPrompt);
}

function append (text){
  text = parseChars(text);
	$(terminal).append(text);
  $(document).trigger("rhizome.inputEnabled");
}

function input(text){
	inputStr += text;
  $(document).trigger("rhizome.input");
  //for password entry
  if (inputType == "text"){
    append(text);
  }else{
    append("*");
  }
}

function parseChars(text){
  //check for b, i, u
  text = text.replace(/\[([biuq])\](.+?)\[\/\1\]/g, '<$1>$2</$1>');
  //do color replacing
  text = text.replace(/\[([c][\d])\](.+?)\[\/\1\]/g, '<span class=$1>$2</span>');
  return text;
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
          getReply(reply);
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

function drawHud(){
  hud = [];
  hud.push(line);
  hud.push("You are currently on turn " + gameData['turnCount']);
  hud.push(line)
  hud.push(" ");
  hud = hud.concat(drawHealthBar(100,100));
  return hud;
}

function drawHealthBar(currentHP, maxHP){
  barLength = 20;
  bar = [];
  healthPerc = currentHP / maxHP;
  playerBar = Math.floor(barLength * healthPerc);
  if (healthPerc >= .75){
    //green (c2)
    color = "c2";
  }else if (healthPerc >= .50){
    //yellow (c3)
    color = "c3";
  }else if (healthPerc >= .25){
    //orange (c8)
    color = "c8";
  }else{
    //red (c1)
    color = "c1";
  }
  bar.push("Player Health: " + currentHP + "/" + maxHP);
  bar.push("[" + color + "]" + Array(barLength + 1).join("-") + "[/" + color + "]");
  bar.push(Array((barLength - playerBar) + 1).join("&nbsp;") + "[" + color + "]" + Array(playerBar + 1).join("|") + "[/" + color + "]");
  bar.push("[" + color + "]" + Array(barLength + 1).join("-") + "[/" + color + "]");
  bar.push(" ");
  return bar;
}
