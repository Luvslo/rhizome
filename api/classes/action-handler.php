<?php
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
      if($this->actionString === 'test') {
        $this->gameData['prompt'] = 'This is a test of the Rhizome Broadcasting
        System. It does absolutely nothing.';
        $this->gameData['choices'] = array();
      }

      return $this->gameData;
    }
  }
?>
