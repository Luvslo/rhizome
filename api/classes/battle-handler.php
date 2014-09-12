<?php
  class BattleHandler {
    public function __construct($gameData = array(), $string = '') {
      $this->gameData     = $gameData;
      $this->actionString = $string;
    }

    public function battle() {
      $this->createBattleEntities();
      $this->

      return $this->actionString();
    }

    public function makeBattle() {
      $this->gameData['currentEvent'] = 'battle';
    }

    public function createBattleEntities() {
      $this->user  = new Entity($this->gameData['user-data']);
      $this->enemy = new Entity($this->gameData['enemy-data']);
    }
  }
?>
