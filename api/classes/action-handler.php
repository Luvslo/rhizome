<?php
  // Battle is broken out to its own file for sanity's sake, since it will be
  // getting quite girthy.
  require_once('battle-handler.php');
  require_once('loot-handler.php');

  /**
   * This class contains functions required to properly handle actions, and
   * references the needed functions and classes to handle said actions.
   *
   * @author Mike Mclaren
   */
  class ActionHandler {
    public function __construct($gameData = array(), $string = '') {
      $this->gameData     = $gameData;
      $this->actionString = strtolower($string);
    }

    public function handleAction() {
      // A 'ping' that exists for whatever reason we need it for, I guess.


      if($this->actionString === 'test' || $this->actionString === 'ping') {
        $this->gameData['prompt'] = 'This is a test of the Rhizome Broadcasting
        System. It does absolutely nothing.';
        $this->gameData['choices'] = array();
      }
      
      if($this->actionString === "Q"){
        session_unset();
        return;
      }
      //event related stuff goes here
      if ($this->actionString == "login"){
        error_log("event is login");
        $this->gameData['prompt'] = "Thanks for logging in, you're now connected!";
        $this->gameData['session'] = "XXX";
        $this->gameData['choices'] = array();
        //do location stuff here
        array_push($this->gameData['choices'], "To adventure into the great beyond");
        $this->gameData['current-event'] = "none";
        error_log("current event should be set here: " . $this->gameData['current-event']);
      }else if (isset($this->gameData['current-event']) && $this->gameData['current-event'] != null){
        error_log("event is set to something");
        //event roller
        if($this->gameData['current-event'] == "none" && $this->actionString == "1"){
          if (rand(1,10) <= 11 ){
            $this->gameData['current-event'] = 'loot';
          }else{
            $this->gameData['current-event'] = 'battle';
          }
        }

        if($this->gameData['current-event'] === 'loot') {
          $loot = new LootHandler($this->gameData, $this->actionString);
          $this->gameData = $loot->makeLoot();
          $this->gameData['current-event'] = "none";
        }

        if($this->gameData['current-event'] === 'battle') {
          $battle = new BattleHandler($this->gameData, $this->actionString);
          $this->gameData = $battle->battle();
        }
      }else{
        error_log("event is else :(");
        //error_log("else: current event should be set here: " . $this->gameData['currentEvent']);
        //this shouldn't happen, but if it does, it might as well be amusing
        $this->gameData['prompt'] = "You walk into a very dark caverous structure with one flickering blue light in the far corner.  As you slowly approach, unsure of what to make of the situation, the smell of Cheetos and Mountain Dew strikes your nostrils sharply.  Before you can process what is happening, a horrific creature abruptly turns around and snarls at you 'What are you doing here human?!'.  You've found an angry developer and this doesn't bode well for you.  Tough break champ.";
      }
      return $this->gameData;
    }
  }
?>
