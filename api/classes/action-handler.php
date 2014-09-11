<?php
  // Battle is broken out to its own file for sanity's sake, since it will be
  // getting quite girthy.
  require_once('battle-handler.php');

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
      if ($this->actionString == "login"){
        $this->gameData['prompt'] = "Thanks for logging in, you're now connected!";
        $this->gameData['session'] = "XXX";
        $this->gameData['choices'] = array();
      }
      
      if($this->actionString === 'test' || $this->actionString === 'ping') {
        $this->gameData['prompt'] = 'This is a test of the Rhizome Broadcasting
        System. It does absolutely nothing.';
        $this->gameData['choices'] = array();
      }

      if (isset($this->gameData['currentEvent'])){
        if($this->gameData['currentEvent'] === 'battle') {
          $battle = new BattleHandler($this->gameData, $string);
          $this->gameData = $battle->makeBattle();
        }
      }


      return $this->gameData;
    }
  }
?>
