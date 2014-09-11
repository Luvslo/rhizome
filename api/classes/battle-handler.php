<?php
  class BattleHandler {
    public function __construct($gameData = array(), $string = '') {
      $this->gameData     = $gameData;
      $this->actionString = $string;
    }

    public function makeBattle() {
      $this->createBattleEntities();
      return $this->actionString();
    }

    public function createBattleEntities() {
      
    }
  }
?>
